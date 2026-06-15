<?php
session_start();
include('config.php');

$msg = "";

if (!empty($_POST)) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if ($first_name == "" || $last_name == "" || $email == "" || $password == "") {
        $msg = "Täida kõik kohustuslikud väljad!";
    } else {
        $check = mysqli_prepare($yhendus, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {
            $msg = "Sellise emailiga kasutaja on juba olemas!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (role, first_name, last_name, email, phone, password_hash)
                    VALUES ('user', ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($yhendus, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $first_name, $last_name, $email, $phone, $password_hash);

            if (mysqli_stmt_execute($stmt)) {
                $msg = "Registreerimine õnnestus! Võid nüüd sisse logida.";
            } else {
                $msg = "Registreerimine ebaõnnestus.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Registreeri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('header.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">

            <h2>Registreeri kasutajaks</h2>

            <?php if ($msg != "") { ?>
                <div class="alert alert-info">
                    <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php } ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Eesnimi *</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Perenimi *</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Parool *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Registreeri</button>
                <a href="login.php" class="btn btn-link">Mul on juba konto</a>
            </form>

        </div>
    </div>
</div>

</body>
</html>
