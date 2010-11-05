<?php
session_start();

if (!$_SESSION) {
    header('Location: login.php');
    exit;
}

$_SESSION = array();
session_destroy();

header('Location: login.php');

?>
