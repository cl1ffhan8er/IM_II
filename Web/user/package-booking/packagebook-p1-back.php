<?php
session_start();
include '../../include/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['package_id'])) {
    die("Error: No package selected. Please go back and choose a package.");
}

$package_id = $_POST['package_id'];

$package_stmt = $conn->prepare(
    "SELECT pi.package_name, pi.description, pi.package_picture, i.price
     FROM Package_Itinerary pi
     JOIN Itinerary i ON pi.package_id = i.itinerary_ID
     WHERE pi.package_id = ? AND i.type = 'PACKAGE' AND pi.is_available = TRUE"
);
$package_stmt->bind_param("i", $package_id);
$package_stmt->execute();
$package_result = $package_stmt->get_result();

if ($package_result->num_rows === 0) {
    die("Error: The selected package could not be found or is not available.");
}

$package = $package_result->fetch_assoc();
$package_stmt->close();
$conn->close();

// Save to session
$_SESSION['booking_type'] = 'PACKAGE';
$_SESSION['package_id'] = $package_id;
$_SESSION['package_name'] = $package['package_name'];
$_SESSION['package_price'] = $package['price'];
$_SESSION['package_description'] = $package['description'];
$_SESSION['package_picture'] = $package['package_picture'];

session_write_close();

// 🔁 Redirect to page that displays the booking form
header("Location: packagebook-p1.php");
exit;
