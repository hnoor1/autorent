<?php
session_start();
include('../config.php');

$msg = "";

if (!empty($_POST)) {
    $uname = $_POST['user'];
    $password = $_POST['password'];

    $paring = "SELECT id, email, password_hash, role FROM users WHERE email = ?";
    $stmt = mysqli_prepare($yhendus, $paring);
    mysqli_stmt_bind_param($stmt, "s", $uname);
    mysqli_stmt_execute($stmt);
    $tulemus = mysqli_stmt_get_result($stmt);
    $rida = mysqli_fetch_assoc($tulemus);

    if (!empty($rida) && password_verify($password, $rida['password_hash']) && $rida['role'] === 'admin') {
        $_SESSION['tuvastamine'] = true;
        $_SESSION['user_id'] = $rida['id'];
        $_SESSION['email'] = $rida['email'];
        $_SESSION['role'] = $rida['role'];

        header("Location: index.php");
        exit();
    } else {
        $msg = "Kasutajat ei tuvastatud või puudub admini õigus!";
    }
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administraatori vaade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row pt-4 mt-4">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2>Administraatori vaade</h2>

            <form method="post" action="login.php" autocomplete="off">
                <div class="mb-3">
                    <label for="u" class="form-label">E-posti aadress</label>
                    <input name="user" type="email" class="form-control" id="u" required>
                </div>

                <div class="mb-3">
                    <label for="p" class="form-label">Parool</label>
                    <input name="password" type="password" class="form-control" id="p" required>
                </div>

                <button type="submit" class="btn btn-primary">Logi sisse</button>
            </form>

            <p class="text-danger mt-3"><?php echo $msg; ?></p>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>
</body>
</html>
