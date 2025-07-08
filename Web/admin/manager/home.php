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

// Get today’s date
$today = date("Y-m-d");

// Count pending bookings
$pendingCountResult = $conn->query("SELECT COUNT(*) AS count FROM order_details WHERE status = 'pending'");
$pendingCount = $pendingCountResult->fetch_assoc()['count'] ?? 0;

// Count available drivers
$driverCountResult = $conn->query("SELECT COUNT(*) AS count FROM driver WHERE availability = TRUE");
$driverCount = $driverCountResult->fetch_assoc()['count'] ?? 0;

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
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="nav-top">
            <h3>Admin Menu</h3>
            <a href="home.php">🏠 Bookings</a>
            <a href="add_package.php">📦 Plans</a>
        </div>

        <div class="nav-bottom">
            <a href="../../user/login/logout.php">🚪 Log Out</a>
        </div>
    </div>


    <!-- Main content area -->
    <div class="content">
        <h1>Manager Dashboard — Pending Submissions</h1>

        <h2>Pending Requests</h2>
        <div class="summary">
            <div class="date">
                <p>Date Today</p>
                <?= $today ?>
            </div>
            <div class="pending">
                <p>Pending Bookings</p>
                <?= $pendingCount ?>
            </div>
            <div class="drivers">
                <p>Drivers Available</p>
                <?= $driverCount ?>
            </div>
        </div>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Date of Transaction</th>
                <th>Status</th>
                <th>View</th>
            </tr>
            <?php while ($row = $orders->fetch_assoc()): ?>
            <tr>
                <td><?= $row['order_ID'] ?></td>
                <td><?= $row['date_of_transaction'] ?></td>
                <td>
                    <?php
                        if ($row['status'] === 'returned') echo "📨 Returned";
                        elseif ($row['status'] === 'inquiry') echo "❓ Inquiry";
                        else echo ""; 
                    ?>
                </td>
                <td><a href="view_order.php?order_ID=<?= $row['order_ID'] ?>">👁️</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
