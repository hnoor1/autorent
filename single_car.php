<?php
session_start();
include('config.php');

$msg = "";

if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = mysqli_prepare($yhendus, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rida = mysqli_fetch_assoc($result);

if (!$rida) {
    die("Autot ei leitud.");
}

if (!empty($_POST)) {
    if (empty($_SESSION['logged_in'])) {
        header("Location: login.php");
        exit();
    }

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $user_id = $_SESSION['user_id'];

    if ($start_date == "" || $end_date == "") {
        $msg = "Palun vali algus- ja lõppkuupäev.";
    } elseif ($end_date < $start_date) {
        $msg = "Lõppkuupäev ei saa olla enne alguskuupäeva.";
    } else {
        $check_sql = "
            SELECT id FROM reservations
            WHERE car_id = ?
            AND status IN ('pending', 'confirmed')
            AND start_date <= ?
            AND end_date >= ?
        ";

        $check = mysqli_prepare($yhendus, $check_sql);
        mysqli_stmt_bind_param($check, "iss", $id, $end_date, $start_date);
        mysqli_stmt_execute($check);
        $check_result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($check_result) > 0) {
            $msg = "See auto on valitud perioodil juba broneeritud.";
        } else {
            $days = (strtotime($end_date) - strtotime($start_date)) / 86400 + 1;
            $total_price = $days * $rida['price'];

            $insert_sql = "
                INSERT INTO reservations
                (user_id, car_id, start_date, end_date, total_price, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ";

            $insert = mysqli_prepare($yhendus, $insert_sql);
            mysqli_stmt_bind_param($insert, "iissd", $user_id, $id, $start_date, $end_date, $total_price);

            if (mysqli_stmt_execute($insert)) {
                $msg = "Broneering lisatud! Staatus: pending.";
            } else {
                $msg = "Broneeringu lisamine ebaõnnestus.";
            }
        }
    }
}
?>

<?php include('header.php'); ?>

<div class="container">

    <a href="index.php" class="btn btn-dark mb-4">Tagasi</a>

    <?php if ($msg != "") { ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-6">
            <h1><?php echo htmlspecialchars($rida["mark"] . " " . $rida["model"]); ?></h1>

            <p>Mootor: <?php echo htmlspecialchars($rida["engine"]); ?></p>
            <p>Kütus: <?php echo htmlspecialchars($rida["fuel"]); ?></p>
            <p>Aasta: <?php echo htmlspecialchars($rida["year"]); ?></p>
            <p>Staatus: <?php echo htmlspecialchars($rida["status"]); ?></p>
            <p>Käigukast: <?php echo htmlspecialchars($rida["transmission"]); ?></p>
            <p>Istmed: <?php echo htmlspecialchars($rida["seats"]); ?></p>

            <p class="fs-5">
                Hind: <?php echo htmlspecialchars($rida["price"]); ?> €/päev
            </p>

            <hr>

            <h3>Rendi auto</h3>

<?php if (empty($_SESSION['logged_in'])) { ?>

    <div class="alert alert-warning">
        Rentimiseks pead olema sisse logitud.
        <a href="login.php">Logi sisse</a> või
        <a href="register.php">registreeri</a>.
    </div>

<?php } elseif ($rida['status'] == 'hoolduses') { ?>

    <div class="alert alert-warning">
        Auto on hetkel hoolduses ja seda ei saa rentida.
    </div>

<?php } elseif ($rida['status'] == 'renditud') { ?>

    <div class="alert alert-danger">
        Auto on hetkel välja renditud.
    </div>

<?php } else { ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Alguskuupäev</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lõppkuupäev</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>

        <button class="btn btn-dark w-100">
            Kinnita broneering
        </button>
    </form>

<?php } ?>
        </div>

        <div class="col-md-6">
            <img
		src="https://loremflickr.com/500/300/<?= urlencode($rida['mark']); ?>"
    		class="img-fluid rounded shadow-sm"
    		style="width:100%;max-height:300px;object-fit:cover;"
    		alt="<?= htmlspecialchars($rida['mark']); ?>">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
