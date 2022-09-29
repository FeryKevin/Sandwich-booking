<!-- déconnection : fermeture de la session -->

<?php

session_start();

$_SESSION['ifco'] = false;
$_SESSION['role'] = '';

header("Location: login.php");

?>