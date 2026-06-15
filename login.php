<?php
session_start();
include('config.php');

$msg = '';

if (!empty($_POST)) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($yhendus, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password_hash'])) {

        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit();

    } else {
        $msg = "Vale email või parool";
    }
}
?>

<!doctype html>
<html lang="et">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Logi sisse</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include('header.php'); ?>

<div class="container mt-5">

<div class="row">
<div class="col-md-6 mx-auto">

<h2>Logi sisse</h2>

<?php if($msg!=""){ ?>
<div class="alert alert-danger">
<?php echo $msg; ?>
</div>
<?php } ?>

<form method="post">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control">
</div>

<div class="mb-3">
<label>Parool</label>
<input type="password" name="password" class="form-control">
</div>

<button class="btn btn-primary">
Logi sisse
</button>

</form>

</div>
</div>

</div>

</body>
</html>
