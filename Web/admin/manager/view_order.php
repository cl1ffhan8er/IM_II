<?php
require_once '../../include/connect.php';

if (!isset($_GET['order_ID'])) {
    header('Location: home.php');
    exit;
}

$orderID = $_GET['order_ID'];

// Get order info along with customer email and name
$stmt = $conn->prepare("SELECT od.*, p.name AS customer_name, p.email AS customer_email,  pi.package_ID, pi.package_name
                        FROM Order_Details od
                        JOIN Customer c ON od.customer_ID = c.customer_ID
                        JOIN Person p ON c.customer_ID = p.person_ID
                        LEFT JOIN Package_Itinerary pi ON od.itinerary_ID = pi.package_ID
                        WHERE od.order_ID = ?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$orderDetails = $stmt->get_result()->fetch_assoc();

if (!$orderDetails) {
    echo "Order not found.";
    exit;
}

// Handle Approve / Reject / Modify actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerEmail = $orderDetails['customer_email'];

    if (isset($_POST['approve'])) {
        $stmt = $conn->prepare("UPDATE Order_Details SET status = 'ACCEPTED' WHERE order_ID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

        $message = "✅ Order approved and email sent.";
    }

    if (isset($_POST['reject'])) {
        $stmt = $conn->prepare("UPDATE Order_Details SET status = 'REJECTED' WHERE order_ID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

        $message = "❌ Order rejected and email sent.";
    }

    if (isset($_POST['modification_message'])) {
        $msg = $_POST['modification_message'];

        $stmt = $conn->prepare("UPDATE Order_Details SET status = 'IN MODIFICATION' WHERE order_ID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

        $message = "📝 Modification request sent.";
    }

    // Refresh order data
    $stmt = $conn->prepare("SELECT od.*, p.name AS customer_name, p.email AS customer_email 
                            FROM Order_Details od
                            JOIN Customer c ON od.customer_ID = c.customer_ID
                            JOIN Person p ON c.customer_ID = p.person_ID
                            WHERE od.order_ID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $orderDetails = $stmt->get_result()->fetch_assoc();
}

$driverQuery = $conn->query("SELECT d.driver_ID, p.name FROM Driver d JOIN Person p ON d.driver_ID = p.person_ID WHERE d.Availability = TRUE");

if (isset($_POST['assign_driver'])) {
    $driverID = $_POST['driver_ID'];

    // Update the order with assigned driver
    $stmt = $conn->prepare("UPDATE Order_Details SET driver_ID = ? WHERE order_ID = ?");
    $stmt->bind_param("ii", $driverID, $orderID);
    $stmt->execute();

    // Mark driver as unavailable
    $stmt = $conn->prepare("UPDATE Driver SET Availability = 0 WHERE driver_ID = ?");
    $stmt->bind_param("i", $driverID);
    $stmt->execute();

    $message = "🚐 Driver assigned successfully.";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="view_order_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <title>Order #<?= $orderDetails['order_ID'] ?> Details</title>
</head>
<!-- Modal -->
<div id="modModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
    <div style="background:white; padding:20px; border-radius:10px; width:400px;">
        <h3>Send Modification Request</h3>
        <form method="POST">
            <textarea name="modification_message" rows="4" cols="50" placeholder="Type message to client..." required></textarea><br><br>
            <button type="submit">Send</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>
<body>
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

    <div class="main-content">
        <h1>ORDER DETAILS</h1>

        <a href="home.php" class="button-link">Back to Home</a>

        <div class="order-layout">
            <!-- Left side: Assign Driver Form -->
            <form method="POST" class="driver-form">
                <label for="driver_ID"><strong>Assign a Driver:</strong></label>
                <select name="driver_ID" required>
                    <option value="" disabled selected>Select Driver</option>
                    <?php while ($driver = $driverQuery->fetch_assoc()): ?>
                        <option value="<?= $driver['driver_ID'] ?>"><?= $driver['name'] ?> (ID: <?= $driver['driver_ID'] ?>)</option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="assign_driver">Assign</button>
            </form>

            <!-- Right side: Order Info -->
            <div class="order-info">
                <?php if ($message): ?>
                    <p style="color: green; font-weight: bold;"><?= $message ?></p>
                <?php endif; ?>

                <p><strong>Order ID:</strong> <?= $orderDetails['order_ID'] ?></p>
                <p><strong>Customer Name:</strong> <?= $orderDetails['customer_name'] ?? 'N/A' ?></p>
                <p><strong>Package ID:</strong> <?= $orderDetails['package_ID'] ?? 'N/A' ?></p>
                <p><strong>Package:</strong> <?= $orderDetails['package_name'] ?? 'N/A' ?></p>
                <p><strong>Status:</strong> <?= $orderDetails['status'] ?></p>

                <hr>

                <a href="emails/emailtest.php?order_ID=<?= $orderDetails['order_ID'] ?>" class="button-link-approval">SEND AN EMAIL</a>
            </div>
        </div>
    </div>
</body>
</html>
