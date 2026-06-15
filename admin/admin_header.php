<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Autorent admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white border-bottom mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Autorent admin</a>

        <div class="navbar-nav me-auto">
            <a class="nav-link" href="index.php">Autod</a>
            <a class="nav-link" href="reservations.php">Reserveeringud</a>
            <a class="nav-link" href="../index.php">Avaleht</a>
        </div>

        <a href="logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
    </div>
</nav>
