<?php
require_once '../../include/connect.php';

// Check if order_ID is passed
if (!isset($_GET['order_ID'])) {
    header('Location: home.php');
    exit;
}

// Get order details
$stmt = $conn->prepare("SELECT * FROM order_details WHERE order_ID = ?");
$stmt->bind_param("i", $_GET['order_ID']);
$stmt->execute();
$orderDetails = $stmt->get_result()->fetch_assoc();

if (!$orderDetails) {
    echo "Order not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $orderDetails['order_ID'] ?> Details</title>
</head>
<body>
    <h1>Order Details</h1>
    <a href="home.php">← Back to Home</a>
    <hr>

    <p><strong>Order ID:</strong> <?= $orderDetails['order_ID'] ?></p>
    <p><strong>Customer Name:</strong> <?= $orderDetails['customer_name'] ?? 'N/A' ?></p>
    <p><strong>Package ID:</strong> <?= $orderDetails['package_ID'] ?? 'N/A' ?></p>
    <p><strong>Status:</strong> <?= $orderDetails['status'] ?></p>
    <p><strong>Acceptance:</strong> <?= $orderDetails['acceptance'] ?? 'Pending' ?></p>
    <p><strong>Submitted On:</strong> <?= $orderDetails['submission_date'] ?? '—' ?></p>
</body>
</html>
