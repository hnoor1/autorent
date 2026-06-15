<?php
session_start();
include('config.php');

if (empty($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Kasutaja saab enda broneeringu tühistada */
if (!empty($_GET['cancel'])) {
    $reservation_id = (int)$_GET['cancel'];

    $sql = "
        UPDATE reservations
        SET status = 'cancelled'
        WHERE id = ?
        AND user_id = ?
        AND status IN ('pending', 'confirmed')
    ";

    $stmt = mysqli_prepare($yhendus, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $reservation_id, $user_id);
    mysqli_stmt_execute($stmt);

    header("Location: my_reservations.php");
    exit();
}

$sql = "
SELECT 
    reservations.id,
    reservations.start_date,
    reservations.end_date,
    reservations.total_price,
    reservations.status,
    cars.mark,
    cars.model
FROM reservations
JOIN cars ON reservations.car_id = cars.id
WHERE reservations.user_id = ?
ORDER BY reservations.created_at DESC
";

$stmt = mysqli_prepare($yhendus, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<?php include('header.php'); ?>

<div class="container">
    <h2>Minu broneeringud</h2>

    <table class="table table-striped table-hover align-middle mt-4">
        <thead>
            <tr>
                <th>Auto</th>
                <th>Algus</th>
                <th>Lõpp</th>
                <th>Hind</th>
                <th>Staatus</th>
                <th>Tegevus</th>
            </tr>
        </thead>

        <tbody>
        <?php while($rida = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td>
                    <?= htmlspecialchars($rida['mark'] . ' ' . $rida['model']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($rida['start_date']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($rida['end_date']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($rida['total_price']); ?> €
                </td>

                <td>
                    <?php
                    if ($rida['status'] == 'pending') {
                        echo '<span class="badge bg-warning text-dark">Ootel</span>';
                    }
                    elseif ($rida['status'] == 'confirmed') {
                        echo '<span class="badge bg-success">Kinnitatud</span>';
                    }
                    elseif ($rida['status'] == 'cancelled') {
                        echo '<span class="badge bg-danger">Tühistatud</span>';
                    }
                    else {
                        echo htmlspecialchars($rida['status']);
                    }
                    ?>
                </td>

                <td>
                    <?php if ($rida['status'] != 'cancelled') { ?>
                        <a href="my_reservations.php?cancel=<?= (int)$rida['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Kas oled kindel, et soovid broneeringu tühistada?');">
                            Tühista
                        </a>
                    <?php } else { ?>
                        <span class="text-muted">Tühistatud</span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
