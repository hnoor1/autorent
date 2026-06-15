<?php
session_start();
include('../config.php');

if (empty($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!empty($_GET['delid'])) {
    $id = (int)$_GET['delid'];

    $sql = "DELETE FROM cars WHERE id = ?";
    $stmt = mysqli_prepare($yhendus, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php?msg=deleted");
exit();
