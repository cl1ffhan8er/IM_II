<?php
    session_start();
    include '../../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);

    // Check if a package booking is in progress.
    if (!isset($_SESSION['booking_type']) || $_SESSION['booking_type'] !== 'PACKAGE' || !isset($_SESSION['package_id'])) {
        die("Error: No package booking in progress. Please select a package first.");
    }

    $package_id = $_SESSION['package_id'];
    $package_stops = [];
    $stops_stmt = $conn->prepare(
        "SELECT l.location_name
         FROM Itinerary_Stops its
         JOIN Locations l ON its.location_ID = l.location_ID
         WHERE its.itinerary_ID = ?
         ORDER BY its.stop_order ASC"
    );
    $stops_stmt->bind_param("i", $package_id);
    $stops_stmt->execute();
    $stops_result = $stops_stmt->get_result();
    while ($stop = $stops_result->fetch_assoc()) {
        $package_stops[] = $stop;
    }
    $stops_stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "../css/booking-styles.css">
    <script src = "scripts/main.js"></script>
    <title>BOOKING SUMMARY & PAYMENT</title>
</head>
<body>
    <ul>
        </ul>
    <br><br>
    <hr>
    <h1>BOOKING SUMMARY</h1>
    <div class = "booking-summary">
        <div class = "text-summary">
            <b>Full Name:</b> <?php echo htmlspecialchars($_SESSION['fname'] ?? '') . ' ' . htmlspecialchars($_SESSION['lname'] ?? ''); ?><br>
            <b>Pickup Date:</b> <?php echo htmlspecialchars($_SESSION['date'] ?? ''); ?><br>
            <b>Pickup Time:</b> <?php echo htmlspecialchars($_SESSION['pickuptime'] ?? ''); ?><br>
            <b>Dropoff Time:</b> <?php echo htmlspecialchars($_SESSION['dropofftime'] ?? ''); ?><br>
            <b>Number of Party Members:</b> <?php echo htmlspecialchars($_SESSION['pax'] ?? ''); ?><br>
            <b>Number of Luggage:</b> <?php echo htmlspecialchars($_SESSION['luggage'] ?? ''); ?><br>
            <b>Comments:</b> <?php echo nl2br(htmlspecialchars($_SESSION['comments'] ?? '')); ?>
        </div>
        <div class = "location-summary">
            <b>PACKAGE:</b> <?php echo htmlspecialchars($_SESSION['package_name'] ?? 'N/A'); ?>
            
            <p><b>ITINERARY:</b></p>
            <ul>
                <?php if (empty($package_stops)): ?>
                    <li>No stops listed for this package.</li>
                <?php else: ?>
                    <?php foreach ($package_stops as $stop): ?>
                        <li><?php echo htmlspecialchars($stop['location_name']); ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class = "payment">
        <h1>PAYMENT DETAILS</h1>
        <p> gcash image here</p>
        <form action="submit-data.php" method="post">
            <label for = "payment_type">Payment Type:</label>
            <select name="payment_type" id="payment_type" required>
                <option value="gcash">GCash</option>
                <option value="cash">Cash</option>
            </select>
            <input type = "submit" value = "COMPLETE BOOKING" name = "submit">
            <button type="button" onclick="history.back()">EDIT BOOKING</button>
        </form>
    </div>

    </body>
</html>