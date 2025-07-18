<?php
session_start();
include '../../include/connect.php';

if (isset($_POST['package_id'])) {
    $_SESSION['package_id'] = $_POST['package_id'];
} else {
    die("Error: No package selected. Please go back and choose a package.");
}

$package_id = $_POST['package_id'];

$package_stmt = $conn->prepare(
    "SELECT pi.package_name, pi.description, pi.package_picture, i.price, pi.route, pi.inclusions
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

// Set session values
$_SESSION['booking_type'] = 'PACKAGE';
$_SESSION['package_name'] = $package['package_name'];
$_SESSION['package_price'] = $package['price'];
$_SESSION['package_description'] = $package['description'];
$_SESSION['package_picture'] = $package['package_picture'];
$_SESSION['package_route'] = $package['route'] ?? ''; // add this if route is present
$_SESSION['package_inclusions'] = $package['inclusions'] ?? ''; // add this if inclusions are present

session_write_close();

header("Location: packagebook-p1.php");
exit;