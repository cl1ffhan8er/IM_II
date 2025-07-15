<?php
require_once '../../include/connect.php';

// Get all completed bookings grouped by month and week
$query = "
    SELECT 
        od.order_ID,
        od.date_of_travel,
        od.status,
        p.name AS customer_name,
        d.driver_ID,
        d2.name AS driver_name,
        pay.payment_status
    FROM order_details od
    JOIN customer c ON od.customer_ID = c.customer_ID
    JOIN person p ON c.customer_ID = p.person_ID
    LEFT JOIN driver d ON od.driver_ID = d.driver_ID
    LEFT JOIN employee e ON d.driver_ID = e.employee_ID
    LEFT JOIN person d2 ON d.driver_ID = d2.person_ID
    JOIN payment pay ON c.payment_ID = pay.payment_ID
    WHERE od.status = 'ACCEPTED'
    ORDER BY od.date_of_travel DESC
";

$result = $conn->query($query);

$bookings_by_month = [];

while ($row = $result->fetch_assoc()) {
    $date = new DateTime($row['date_of_travel']);
    $month = $date->format('F Y');
    $day = $date->format('l, M j');

    $bookings_by_month[$month][] = [
        'day' => $day,
        'order_ID' => $row['order_ID'],
        'customer_name' => $row['customer_name'],
        'driver_name' => $row['driver_name'] ?? 'Unassigned',
        'payment_status' => $row['payment_status']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Booking Summary</title>
    <link rel="stylesheet" href="monthly_summary_styles.css">
</head>
<body>
    <div class="sidebar">
        <div class="nav-top">
            <h3>Admin Menu</h3>
            <a href="home.php">🏠 Bookings</a>
            <a href="add_package.php">📦 Plans</a>
            <a href="add_locations.php">📍 Locations</a>
            <a href="monthly_summary.php">📊 Monthly Summary</a>
        </div>

        <div class="nav-bottom">
            <a href="../../user/login/logout.php">🚪 Log Out</a>
        </div>
    </div>
    <hr>

    <div class="content">
        <h1>Manage Locations</h1>

        <?php if (empty($bookings_by_month)): ?>
        <p>No bookings have been completed yet.</p>
        <?php else: ?>
            <?php foreach ($bookings_by_month as $month => $entries): ?>
                <div class="month-block">
                    <h2><?= $month ?></h2>
                    <?php foreach ($entries as $entry): ?>
                        <details class="entry">
                            <summary>• <?= $entry['day'] ?> | Completed</summary>
                            <p>Customer: <?= htmlspecialchars($entry['customer_name']) ?></p>
                            <p>Driver Assigned: <?= htmlspecialchars($entry['driver_name']) ?></p>
                            <p>Payment Status: <?= htmlspecialchars($entry['payment_status']) ?></p>
                            <p>Order ID: <?= $entry['order_ID'] ?></p>
                        </details>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>

