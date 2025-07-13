<?php
session_start();
require_once '../../include/connect.php';

if (!isset($_SESSION['driver_ID'])) {
    header("Location: ../../user/login/login.php");
    exit();
}

$driverID = $_SESSION['driver_ID'];

// Handle availability toggle
if (isset($_POST['toggle_availability'])) {
    $currentStatus = $_POST['current_availability'];
    $newStatus = $currentStatus ? 0 : 1;

    $stmt = $conn->prepare("UPDATE Driver SET Availability = ? WHERE driver_ID = ?");
    $stmt->bind_param("ii", $newStatus, $driverID);
    $stmt->execute();
}

// Fetch current availability
$availabilityResult = $conn->query("SELECT Availability FROM Driver WHERE driver_ID = $driverID");
$availability = $availabilityResult->fetch_assoc()['Availability'];

// Fetch assigned orders
$sql = "SELECT od.*, c.name AS customer_name, i.type AS itinerary_type
        FROM Order_Details od
        JOIN Customer cust ON od.customer_ID = cust.customer_ID
        JOIN Person c ON cust.customer_ID = c.person_ID
        JOIN Itinerary i ON od.itinerary_ID = i.itinerary_ID
        WHERE od.driver_ID = $driverID
        ORDER BY od.date_of_travel ASC";

$orders = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Dashboard</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #888; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .status-available { color: green; font-weight: bold; }
        .status-unavailable { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Driver Dashboard</h1>
    <a href="../user/login/logout.php">Log Out</a>

    <form method="POST">
        <input type="hidden" name="current_availability" value="<?= $availability ?>">
        <button type="submit" name="toggle_availability">
            <?= $availability ? 'Set as Unavailable' : 'Set as Available' ?>
        </button>
        <p>Status: <span class="<?= $availability ? 'status-available' : 'status-unavailable' ?>">
            <?= $availability ? 'Available' : 'Unavailable' ?>
        </span></p>
    </form>

    <h2>Assigned Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Itinerary Type</th>
            <th>Number of Pax</th>
            <th>Date of Travel</th>
            <th>Pickup Time</th>
            <th>Dropoff Time</th>
            <th>Status</th>
        </tr>
        <?php if ($orders->num_rows > 0): ?>
            <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['order_ID'] ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= $row['itinerary_type'] ?></td>
                    <td><?= $row['number_of_PAX'] ?></td>
                    <td><?= $row['date_of_travel'] ?></td>
                    <td><?= $row['time_for_pickup'] ?></td>
                    <td><?= $row['time_for_dropoff'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No orders assigned.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
