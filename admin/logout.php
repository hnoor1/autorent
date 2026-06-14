<?php
session_Start();

session_destroy ();
header('Location: login.php');

?>