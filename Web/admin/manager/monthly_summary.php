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
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
</head>
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

    <hr>

    <div class="content">
        <h1>MANAGE BOOKINGS</h1>

        <?php if (empty($bookings_by_month)): ?>
        <p>No bookings have been completed yet.</p>
        <?php else: ?>
            <?php foreach ($bookings_by_month as $month => $entries): ?>
                <div class="month-block">
                    <h2><?= $month ?></h2>
                    <?php foreach ($entries as $entry): ?>
                        <details class="entry">
                            <summary><?= $entry['day'] ?> | Completed</summary>
                            <div class="entry-content">
                                <table class="entry-table">
                                    <tr>
                                        <th>Customer</th>
                                        <td><?= htmlspecialchars($entry['customer_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Driver</th>
                                        <td><?= htmlspecialchars($entry['driver_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status</th>
                                        <td><?= htmlspecialchars($entry['payment_status']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Order ID</th>
                                        <td><?= $entry['order_ID'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>

