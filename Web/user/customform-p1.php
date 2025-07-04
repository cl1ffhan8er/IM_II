<?php
session_start();

$_SESSION['date'] = $_POST['date'];
$_SESSION['vehicle'] = $_POST['vehicle'];
$_SESSION['pickup'] = $_POST['pickup'];
$_SESSION['destination'] = $_POST['destination'];

header("Location: custom-booking-p2.php");
exit;
?>
