<?php
// Kui projekt töötab Dockeris, kasutatakse docker-compose.yml väärtuseid.
// Kui projekt töötab otse VM-is Apache all, kasutatakse vanu kohalikke väärtuseid.

$db_server = getenv('DB_HOST') ?: 'localhost';
$db_andmebaas = getenv('DB_NAME') ?: 'autorent';
$db_kasutaja = getenv('DB_USER') ?: 'liina';
$db_salasona = getenv('DB_PASS') ?: 'Passw0rd';

$yhendus = mysqli_connect($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);

if (!$yhendus) {
    die('Ei saa ühendust andmebaasiga: ' . mysqli_connect_error());
}

mysqli_set_charset($yhendus, 'utf8mb4');
?>
