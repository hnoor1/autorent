<?php
session_start();
include('../config.php');

if (empty($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$msg = "";

if (!empty($_POST['updateid'])) {
    $id = (int)$_POST['updateid'];

    $mark = trim($_POST['mark']);
    $model = trim($_POST['model']);
    $engine = trim($_POST['engine']);
    $fuel = trim($_POST['fuel']);
    $price = (int)$_POST['price'];
    $year = (int)$_POST['year'];
    $transmission = trim($_POST['transmission']);
    $seats = (int)$_POST['seats'];
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    $sql = "
        UPDATE cars 
        SET mark = ?, model = ?, engine = ?, fuel = ?, price = ?, year = ?, 
            transmission = ?, seats = ?, description = ?, status = ?
        WHERE id = ?
    ";

    $stmt = mysqli_prepare($yhendus, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssiisissi",
        $mark,
        $model,
        $engine,
        $fuel,
        $price,
        $year,
        $transmission,
        $seats,
        $description,
        $status,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?msg=uuendatud");
        exit();
    } else {
        $msg = "Auto muutmine ebaõnnestus.";
    }
}

if (empty($_GET['editid'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['editid'];

$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = mysqli_prepare($yhendus, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rida = mysqli_fetch_assoc($result);

if (!$rida) {
    die("Autot ei leitud.");
}
?>

<?php include('admin_header.php'); ?>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Muuda autot</h2>
        <a href="index.php" class="btn btn-outline-secondary">Tagasi</a>
    </div>

    <?php if ($msg != "") { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($msg); ?></div>
    <?php } ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="muuda.php?editid=<?= (int)$rida['id']; ?>" method="post">
                <input type="hidden" name="updateid" value="<?= (int)$rida['id']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mark" class="form-label">Mark</label>
                        <input type="text" class="form-control" id="mark" name="mark"
                               value="<?= htmlspecialchars($rida['mark']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="model" class="form-label">Mudel</label>
                        <input type="text" class="form-control" id="model" name="model"
                               value="<?= htmlspecialchars($rida['model']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="engine" class="form-label">Mootor</label>
                        <input type="text" class="form-control" id="engine" name="engine"
                               value="<?= htmlspecialchars($rida['engine']); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="fuel" class="form-label">Kütus</label>
                        <input type="text" class="form-control" id="fuel" name="fuel"
                               value="<?= htmlspecialchars($rida['fuel']); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Hind (€ / päev)</label>
                        <input type="number" class="form-control" id="price" name="price"
                               value="<?= htmlspecialchars($rida['price']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="year" class="form-label">Aasta</label>
                        <input type="number" class="form-control" id="year" name="year"
                               value="<?= htmlspecialchars($rida['year']); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="transmission" class="form-label">Käigukast</label>
                        <input type="text" class="form-control" id="transmission" name="transmission"
                               value="<?= htmlspecialchars($rida['transmission']); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="seats" class="form-label">Istmete arv</label>
                        <input type="number" class="form-control" id="seats" name="seats"
                               value="<?= htmlspecialchars($rida['seats']); ?>">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Kirjeldus</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($rida['description']); ?></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Olek</label>
                        <select class="form-select" id="status" name="status">
                            <option value="vaba" <?= $rida['status'] == 'vaba' ? 'selected' : ''; ?>>Vaba</option>
                            <option value="renditud" <?= $rida['status'] == 'renditud' ? 'selected' : ''; ?>>Renditud</option>
                            <option value="hoolduses" <?= $rida['status'] == 'hoolduses' ? 'selected' : ''; ?>>Hoolduses</option>
                        </select>
                    </div>
                </div>

                <hr>

                <button type="submit" class="btn btn-dark">Salvesta</button>
                <a href="index.php" class="btn btn-outline-secondary">Tühista</a>
            </form>

        </div>
    </div>

</div>

</body>
</html>
