<?php
session_start();
include('../config.php');

if (
    empty($_SESSION['logged_in']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: login.php");
    exit();
}

/* Broneeringu kustutamine */
if (!empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $sql = "DELETE FROM reservations WHERE id = ?";
    $stmt = mysqli_prepare($yhendus, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: reservations.php");
    exit();
}

/* Staatuse muutmine */
if (!empty($_GET['id']) && !empty($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];

    if (in_array($status, ['pending', 'confirmed', 'cancelled'])) {
        $sql = "UPDATE reservations SET status=? WHERE id=?";
        $stmt = mysqli_prepare($yhendus, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: reservations.php");
    exit();
}

$sql = "
SELECT
    reservations.id,
    reservations.start_date,
    reservations.end_date,
    reservations.total_price,
    reservations.status,

    users.first_name,
    users.last_name,
    users.email,

    cars.mark,
    cars.model

FROM reservations
JOIN users ON reservations.user_id = users.id
JOIN cars ON reservations.car_id = cars.id
ORDER BY reservations.created_at DESC
";

$result = mysqli_query($yhendus, $sql);
?>

<!doctype html>
<html lang="et">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Broneeringud</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

<h2>Broneeringute haldus</h2>

<a href="index.php" class="btn btn-secondary mb-3">
    Tagasi administraatori avalehele
</a>

<table class="table table-striped align-middle">

<thead>
<tr>
    <th>ID</th>
    <th>Klient</th>
    <th>Auto</th>
    <th>Algus</th>
    <th>Lõpp</th>
    <th>Hind</th>
    <th>Staatus</th>
    <th>Tegevused</th>
</tr>
</thead>

<tbody>

<?php while($rida = mysqli_fetch_assoc($result)) { ?>

<tr>

<td><?= (int)$rida['id']; ?></td>

<td>
    <?= htmlspecialchars($rida['first_name']); ?>
    <?= htmlspecialchars($rida['last_name']); ?>
    <br>
    <small><?= htmlspecialchars($rida['email']); ?></small>
</td>

<td>
    <?= htmlspecialchars($rida['mark']); ?>
    <?= htmlspecialchars($rida['model']); ?>
</td>

<td><?= htmlspecialchars($rida['start_date']); ?></td>

<td><?= htmlspecialchars($rida['end_date']); ?></td>

<td><?= htmlspecialchars($rida['total_price']); ?> €</td>

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
    <a class="btn btn-success btn-sm mb-1"
       href="?id=<?= (int)$rida['id']; ?>&status=confirmed">
        ✓ Kinnita
    </a>

    <a class="btn btn-warning btn-sm mb-1"
       href="?id=<?= (int)$rida['id']; ?>&status=pending">
        ⏳ Ootele
    </a>

    <a class="btn btn-secondary btn-sm mb-1"
       href="?id=<?= (int)$rida['id']; ?>&status=cancelled">
        ✕ Tühista
    </a>

    <a class="btn btn-danger btn-sm mb-1"
       href="?delete=<?= (int)$rida['id']; ?>"
       onclick="return confirm('Kas oled kindel, et soovid selle broneeringu kustutada?');">
        🗑 Kustuta
    </a>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>
