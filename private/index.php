<?php
header("Content-type: text/html; charset=uft-8");
session_start();
if (!isset($_SESSION['name']))
    header("Location: ../");
?>

