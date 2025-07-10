<?php
session_start();
include '../../include/connect.php';

// Ensure this script is accessed via a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php"); // Redirect if accessed directly
    exit();
}

// --- Save form data to the session ---
$_SESSION['fname'] = htmlspecialchars($_POST['fname']);
$_SESSION['lname'] = htmlspecialchars($_POST['lname']);
$_SESSION['pax'] = htmlspecialchars($_POST['pax']);
$_SESSION['date'] = htmlspecialchars($_POST['date']);
$_SESSION['pickuptime'] = htmlspecialchars($_POST['pickuptime']);
$_SESSION['dropofftime'] = htmlspecialchars($_POST['dropofftime']);
$_SESSION['luggage'] = htmlspecialchars($_POST['luggage']);
$_SESSION['comments'] = htmlspecialchars($_POST['comments']);

// --- Handle the file upload ---
if (isset($_FILES['id']) && $_FILES['id']['error'] == UPLOAD_ERR_OK) {
    // (Your existing file upload logic goes here)
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

// --- Redirect to the final summary page ---

// Force the session data to save immediately
session_write_close();

// Redirect to the summary/payment page
header("Location: packagepayment.php");
exit();
?>