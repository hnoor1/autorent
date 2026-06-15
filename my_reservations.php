<?php
session_start();
include('config.php');

if (empty($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

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

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Auto</th>
                <th>Algus</th>
                <th>Lõpp</th>
                <th>Hind</th>
                <th>Staatus</th>
            </tr>
        </thead>

        <tbody>
        <?php while($rida = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($rida['mark'] . ' ' . $rida['model']); ?>
                </td>
                <td><?php echo htmlspecialchars($rida['start_date']); ?></td>
                <td><?php echo htmlspecialchars($rida['end_date']); ?></td>
                <td><?php echo htmlspecialchars($rida['total_price']); ?> €</td>
                <td><?php echo htmlspecialchars($rida['status']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
