<?php
session_start();
include '../../include/connect.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php"); 
    exit();
}

$_SESSION['fname'] = htmlspecialchars($_POST['fname']);
$_SESSION['lname'] = htmlspecialchars($_POST['lname']);
$_SESSION['pax'] = htmlspecialchars($_POST['pax']);
$_SESSION['date'] = htmlspecialchars($_POST['date']);
$_SESSION['pickuptime'] = htmlspecialchars($_POST['pickuptime']);
$_SESSION['dropofftime'] = htmlspecialchars($_POST['dropofftime']);
$_SESSION['luggage'] = htmlspecialchars($_POST['luggage']);
$_SESSION['comments'] = htmlspecialchars($_POST['comments']);

if (isset($_FILES['id']) && $_FILES['id']['error'] == UPLOAD_ERR_OK) {
    $destination = 'uploads/' . uniqid('id_', true) . '.' . pathinfo($_FILES['id']['name'], PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES['id']['tmp_name'], $destination)) {
        $_SESSION['id_filepath'] = $destination;
        $_SESSION['id_filename'] = $_FILES['id']['name'];
    } else {
        die("Error: Could not move the uploaded file.");
    }
} else {
    die("Error: File upload failed.");
}

session_write_close();

header("Location: packagepayment.php");
exit();
?>