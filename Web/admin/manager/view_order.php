<?php
require_once '../../include/connect.php';

if (!isset($_GET['order_ID'])) {
    header('Location: home.php');
    exit;
}

$orderID = $_GET['order_ID'];

// Get order info along with customer email and name
$stmt = $conn->prepare("SELECT od.*, p.name AS customer_name, p.email AS customer_email 
                        FROM order_details od
                        JOIN customer c ON od.customer_ID = c.customer_ID
                        JOIN person p ON c.customer_ID = p.person_ID
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
        $stmt = $conn->prepare("UPDATE order_details SET status = 'ACCEPTED' WHERE order_ID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

file_put_contents(
    '../../logs/email_log.txt',
    "TO: $customerEmail\nSUBJECT: Booking Approved\nBODY: Your booking (Order #$orderID) has been approved.\n\n",
    FILE_APPEND
);
        $message = "✅ Order approved and email sent.";
    }

    if (isset($_POST['reject'])) {
        $stmt = $conn->prepare("UPDATE order_details SET status = 'REJECTED' WHERE order_ID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

file_put_contents(
    '../../logs/email_log.txt',
    "TO: $customerEmail\nSUBJECT: Booking Approved\nBODY: Your booking (Order #$orderID) has been approved.\n\n",
    FILE_APPEND
);
        $message = "❌ Order rejected and email sent.";
    }

    if (isset($_POST['modification_message'])) {
        $msg = $_POST['modification_message'];

        $stmt = $conn->prepare("UPDATE order_details SET status = 'IN MODIFICATION' WHERE order_ID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

file_put_contents(
    '../logs/email_log.txt',
    "TO: $customerEmail\nSUBJECT: Booking Approved\nBODY: Youre Gay (Order #$orderID) has been approved.\n\n",
    FILE_APPEND
);
        $message = "📝 Modification request sent.";
    }

    // Refresh order data
    $stmt = $conn->prepare("SELECT od.*, p.name AS customer_name, p.email AS customer_email 
                            FROM order_details od
                            JOIN customer c ON od.customer_ID = c.customer_ID
                            JOIN person p ON c.customer_ID = p.person_ID
                            WHERE od.order_ID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $orderDetails = $stmt->get_result()->fetch_assoc();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    <h1>Order Details</h1>
    <a href="home.php">← Back to Home</a>
    <hr>

    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?= $message ?></p>
    <?php endif; ?>

    <p><strong>Order ID:</strong> <?= $orderDetails['order_ID'] ?></p>
    <p><strong>Customer Name:</strong> <?= $orderDetails['customer_name'] ?? 'N/A' ?></p>
    <p><strong>Package ID:</strong> <?= $orderDetails['package_ID'] ?? 'N/A' ?></p>
    <p><strong>Status:</strong> <?= $orderDetails['status'] ?></p>
    <p><strong>Acceptance:</strong> <?= $orderDetails['acceptance'] ?? 'Pending' ?></p>
    <p><strong>Submitted On:</strong> <?= $orderDetails['submission_date'] ?? '—' ?></p>

    <hr>

    <form method="POST">
        <button name="approve" style="background: green; color: white;">✅ Approve</button>
        <button name="reject" style="background: red; color: white;">❌ Reject</button>
        <button type="button" onclick="openModal()" style="background: orange; color: white;">✏️ Request Modifications</button>
    </form>

    <script>
        function openModal() {
            document.getElementById("modModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("modModal").style.display = "none";
        }
    </script>

</body>
</html>
