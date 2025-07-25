<?php
session_start();
require_once '../../include/connect.php';

if (!isset($_SESSION['manager_ID'])) {
    header("Location: ../../user/login/login.php");
    exit();
}

$limit = 5; // bookings per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalResult = $conn->query("
    SELECT COUNT(*) AS total 
    FROM order_details 
    WHERE status = 'pending' OR status = 'IN MODIFICATION'
");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);


// Get all pending requests
$stmt = $conn->prepare("
    SELECT od.order_ID, od.date_of_transaction, od.status, i.type 
    FROM Order_Details od
    JOIN Itinerary i ON od.itinerary_ID = i.itinerary_ID
    WHERE od.status = 'pending' OR od.status = 'IN MODIFICATION'
    LIMIT ? OFFSET ?
");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$orders = $stmt->get_result();


// If a specific order is clicked
$orderDetails = null;
if (isset($_GET['order_ID'])) {
    $stmt = $conn->prepare("SELECT * FROM Order_Details WHERE order_ID = ?");
    $stmt->bind_param("i", $_GET['order_ID']);
    $stmt->execute();
    $orderDetails = $stmt->get_result()->fetch_assoc();
}

// Get today’s date
$today = date("Y-m-d");

// Count pending bookings
$pendingCountResult = $conn->query("SELECT COUNT(*) AS count FROM Order_Details WHERE status = 'PENDING'");
$pendingCount = $pendingCountResult->fetch_assoc()['count'] ?? 0;

// Count available drivers
$driverCountResult = $conn->query("SELECT COUNT(*) AS count FROM Driver WHERE availability = TRUE");
$driverCount = $driverCountResult->fetch_assoc()['count'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href="home_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <title>Manager Home</title>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../user/images/srvanlogo.png" alt="Logo" class="sidebar-logo">
            <span class="admin-label">Admin Menu</span>
        </div>

        <div class="nav-top">
            <a href="home.php">BOOKINGS</a>
            <a href="add_package.php">PLANS</a>
            <a href="add_locations.php">LOCATIONS</a>
            <a href="monthly_summary.php">MONTHLY SUMMARY</a>
        </div>

        <div class="nav-bottom">
            <a href="../../user/login/logout.php" class="logout">Log Out</a>
        </div>
    </div>


    <!-- Main content area -->
    <div class="content">
        <h1>MANAGER DASHBOARD</h1>

        <h2>Pending Requests</h2>
        <div class="summary">
            <div class="date">
                <p>DATE TODAY</p>
                <?= $today ?>
            </div>
            <div class="pending">
                <p>PENDING BOOKINGS</p>
                <?= $pendingCount ?>
            </div>
            <div class="drivers">
                <p>DRIVERS AVAILABLE</p>
                <?= $driverCount ?>
            </div>
        </div>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Date of Transaction</th>
                <th>Type</th>
                <th>Status</th>
                <th>View</th>
            </tr>
            <?php while ($row = $orders->fetch_assoc()): ?>
            <tr>
                <td><?= $row['order_ID'] ?></td>
                <td><?= $row['date_of_transaction'] ?></td>
                <td><?= $row['type'] ?></td>
                <td>
                    <?php
                        if ($row['status'] === 'returned') echo "Returned";
                        elseif ($row['status'] === 'inquiry') echo "Inquiry";
                        else echo "Pending";
                    ?>
                </td>
                <td>
                    <a href="view_order.php?order_ID=<?= $row['order_ID'] ?>">
                        <img src="../../user/svg-icons/eye.svg" alt="View" class="eye-icon">
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
            <?php endif; ?>

            <span>Page <?= $page ?> of <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
