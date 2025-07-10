<?php
session_start();
include '../include/connect.php';

// 1. Check if the user is logged in.
if (!isset($_SESSION['person_ID'])) {
    header("Location: login/login.php");
    exit();
}

$person_id = $_SESSION['person_ID'];

// 2. Fetch the user's personal details.
$user_details_stmt = $conn->prepare("SELECT name, email FROM Person WHERE person_ID = ?");
$user_details_stmt->bind_param("i", $person_id);
$user_details_stmt->execute();
$user_result = $user_details_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_details_stmt->close();

// 3. Fetch all bookings and their details.
// The query is updated to get the itinerary type and package name.
$bookings = [];
$itineraryIDs = [];

$bookings_stmt = $conn->prepare(
    "SELECT
        b.booking_ID,
        b.itinerary_ID,
        b.pickup_date,
        b.pickup_time,
        b.passenger_count,
        b.ID_Picture,
        p.payment_method,
        p.payment_status,
        i.type,
        pi.package_name
    FROM Booking b
    JOIN Payment p ON b.payment_ID = p.payment_ID
    LEFT JOIN Itinerary i ON b.itinerary_ID = i.itinerary_ID
    LEFT JOIN Package_Itinerary pi ON i.itinerary_ID = pi.package_id
    WHERE b.customer_ID = ?
    ORDER BY b.pickup_date DESC"
);
$bookings_stmt->bind_param("i", $person_id);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();

while ($booking = $bookings_result->fetch_assoc()) {
    $bookings[] = $booking;
    if (!in_array($booking['itinerary_ID'], $itineraryIDs)) {
        $itineraryIDs[] = $booking['itinerary_ID'];
    }
}
$bookings_stmt->close();

// 4. Fetch all stops for all the user's itineraries in a single query.
$stopsByItinerary = [];
if (!empty($itineraryIDs)) {
    $placeholders = implode(',', array_fill(0, count($itineraryIDs), '?'));
    
    $stops_stmt = $conn->prepare(
        "SELECT its.itinerary_ID, l.location_name, l.location_address
         FROM Itinerary_Stops its
         JOIN Locations l ON its.location_ID = l.location_ID
         WHERE its.itinerary_ID IN ($placeholders)
         ORDER BY its.stop_order ASC"
    );
    $stops_stmt->bind_param(str_repeat('i', count($itineraryIDs)), ...$itineraryIDs);
    $stops_stmt->execute();
    $stops_result = $stops_stmt->get_result();

    while ($stop = $stops_result->fetch_assoc()) {
        $stopsByItinerary[$stop['itinerary_ID']][] = $stop;
    }
    $stops_stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Profile</h1>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <ul class="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="login/logout.php">Log Out</a></li>
            </ul>
        </div>

        <div class="bookings-section">
            <h2>My Bookings</h2>
            <?php if (empty($bookings)): ?>
                <div class="no-bookings">
                    <p>You have not made any bookings yet.</p>
                    <a href="custom-booking.php">Book a Trip</a>
                </div>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card">

                        <?php if ($booking['type'] === 'PACKAGE'): ?>
                            <h3>Package: <?php echo htmlspecialchars($booking['package_name']); ?></h3>
                            <p class="booking-date">Travel Date: <?php echo date("F j, Y", strtotime($booking['pickup_date'])); ?></p>
                        <?php else: ?>
                            <h3>Custom Trip on <?php echo date("F j, Y", strtotime($booking['pickup_date'])); ?></h3>
                        <?php endif; ?>

                        <div class="booking-details">
                            <div class="detail-item"><span>Pickup Time:</span> <?php echo date("g:i A", strtotime($booking['pickup_time'])); ?></div>
                            <div class="detail-item"><span>Passengers:</span> <?php echo htmlspecialchars($booking['passenger_count']); ?></div>
                            <div class="detail-item"><span>Payment Method:</span> <?php echo htmlspecialchars(ucfirst($booking['payment_method'])); ?></div>
                            <div class="detail-item"><span>Payment Status:</span> <?php echo $booking['payment_status'] ? 'Paid' : 'Pending'; ?></div>
                        </div>

                        <?php if ($booking['type'] === 'CUSTOM' && !empty($booking['ID_Picture'])): ?>
                            <div class="custom-id-section">
                                <h4>Uploaded ID for Verification:</h4>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($booking['ID_Picture']); ?>" alt="User Uploaded ID" style="max-width: 200px; border: 1px solid #ccc; padding: 5px;">
                            </div>
                        <?php endif; ?>

                        <h4>Itinerary:</h4>
                        <ul class="itinerary-list">
                            <?php 
                            $current_stops = $stopsByItinerary[$booking['itinerary_ID']] ?? []; 
                            ?>
                            <?php if (empty($current_stops)): ?>
                                <li>No stops listed for this itinerary.</li>
                            <?php else: ?>
                                <?php foreach ($current_stops as $stop): ?>
                                    <li><strong><?php echo htmlspecialchars($stop['location_name']); ?></strong> - <?php echo htmlspecialchars($stop['location_address']); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>