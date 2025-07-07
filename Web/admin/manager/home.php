<?php
require_once '../../include/connect.php';

// Get all pending requests
$orders = $conn->query("SELECT order_ID, date_of_transaction, status FROM order_details WHERE status = 'pending'");

// If a specific order is clicked
$orderDetails = null;
if (isset($_GET['order_ID'])) {
    $stmt = $conn->prepare("SELECT * FROM order_details WHERE order_ID = ?");
    $stmt->bind_param("i", $_GET['order_ID']);
    $stmt->execute();
    $orderDetails = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href="home_styles.css">
    <title>Manager Home</title>
</head>
<body>
    <h1>Manager Dashboard — Pending Submissions</h1>

    <nav class="nav">
        <a href="home.php">Home</a> | 
        <a href="add_package.php">Add Packages</a> | 
        <a href="../../user/login/logout.php">Log Out</a>
    </nav>

    <hr>

    <h2>Pending Requests</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Date of Transaction</th>
            <th>Pending</th>
            <th>View</th>
        </tr>
        <?php while ($row = $orders->fetch_assoc()): ?>
        <tr>
            <td><?= $row['order_ID'] ?></td>
            <td><?= $row['date_of_transaction'] ?></td>
            <td><?= $row['status'] ?? '—' ?></td>
            <td><a href="home.php?order_ID=<?= $row['order_ID'] ?>">👁️</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php if ($orderDetails): ?>
        <hr>
        <h2>Details for Order #<?= $orderDetails['order_ID'] ?></h2>
        <p><strong>Customer Name:</strong> <?= $orderDetails['customer_name'] ?? 'N/A' ?></p>
        <p><strong>Package ID:</strong> <?= $orderDetails['package_ID'] ?? 'N/A' ?></p>
        <p><strong>Status:</strong> <?= $orderDetails['status'] ?></p>
        <p><strong>Acceptance:</strong> <?= $orderDetails['acceptance'] ?? 'Pending' ?></p>
        <p><strong>Submitted On:</strong> <?= $orderDetails['submission_date'] ?? '—' ?></p>
    <?php endif; ?>
</body>
</html>
