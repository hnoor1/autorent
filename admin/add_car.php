<?php
session_start();
include('../config.php');

if (empty($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$msg = "";

if (!empty($_POST)) {
    $mark = trim($_POST['mark']);
    $model = trim($_POST['model']);
    $engine = trim($_POST['engine']);
    $fuel = trim($_POST['fuel']);
    $price = (int)$_POST['price'];

    $year = date('Y');
    $transmission = 'manual';
    $seats = 5;
    $description = "Lisatud admini poolt.";
    $status = 'vaba';
    $image = "uploads/cars/default.jpg";

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "../uploads/cars/";
        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = "uploads/cars/" . $file_name;
        }
    }

    $sql = "
        INSERT INTO cars
        (mark, model, engine, fuel, price, image, year, transmission, seats, description, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($yhendus, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssisisiss",
        $mark,
        $model,
        $engine,
        $fuel,
        $price,
        $image,
        $year,
        $transmission,
        $seats,
        $description,
        $status
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit();
    } else {
        $msg = "Auto lisamine ebaõnnestus.";
    }
}
?>

<?php include('admin_header.php'); ?>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lisa auto</h2>
        <a href="index.php" class="btn btn-outline-secondary">Tagasi</a>
    </div>

    <?php if ($msg != "") { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($msg); ?></div>
    <?php } ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="post" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mark</label>
                        <input type="text" name="mark" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mudel</label>
                        <input type="text" name="model" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mootor</label>
                        <input type="text" name="engine" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kütus</label>
                        <select name="fuel" class="form-select" required>
                            <option value="">Vali</option>
                            <option value="bensiin">Bensiin</option>
                            <option value="diisel">Diisel</option>
                            <option value="electric">Elektriline</option>
                            <option value="hybrid">Hübriid</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hind (€ / päev)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Auto pilt</label>
                        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-muted">Lubatud formaadid: JPG, PNG, WEBP.</small>
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
