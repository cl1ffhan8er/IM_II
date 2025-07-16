<!-- fixed profile !-->

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../include/connect.php';
$isLoggedIn = isset($_SESSION['person_ID']);

if (!$isLoggedIn) {
    header("Location: login/login.php");
    exit();
}

$person_id = $_SESSION['person_ID'];

// Fetch user info
$user_details_stmt = $conn->prepare("SELECT name, email FROM Person WHERE person_ID = ?");
$user_details_stmt->bind_param("i", $person_id);
$user_details_stmt->execute();
$user_result = $user_details_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_details_stmt->close();

// Fetch bookings
$bookings = [];
$itineraryIDs = [];

$sql = "
SELECT
    i.itinerary_ID,
    c.date_of_travel AS pickup_date,
    od.time_for_pickup,
    od.time_for_dropoff,
    c.number_of_PAX,
    c.ID_Picture,
    p.payment_method,
    p.payment_status,
    i.type,
    pi.package_name,
    od.status AS order_status
FROM Customer c
JOIN Payment p ON c.payment_ID = p.payment_ID
JOIN Order_Details od ON c.customer_ID = od.customer_ID AND c.payment_ID = od.payment_ID
JOIN Itinerary i ON od.itinerary_ID = i.itinerary_ID
LEFT JOIN Package_Itinerary pi ON i.itinerary_ID = pi.package_id
WHERE c.customer_ID = ?
ORDER BY c.date_of_travel DESC";

$bookings_stmt = $conn->prepare($sql);
if (!$bookings_stmt) {
    die("❌ SQL Prepare Error: " . $conn->error);
}
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

// Fetch itinerary stops for CUSTOM itineraries
$stopsByItinerary = [];
if (!empty($itineraryIDs)) {
    $placeholders = implode(',', array_fill(0, count($itineraryIDs), '?'));
    $types = str_repeat('i', count($itineraryIDs));

    $query = "
        SELECT its.custom_ID AS itinerary_ID, l.location_name, l.location_address
        FROM Itinerary_Stops its
        JOIN Locations l ON its.location_ID = l.location_ID
        WHERE its.custom_ID IN ($placeholders)
        ORDER BY its.stop_order ASC";

    $stops_stmt = $conn->prepare($query);
    $stops_stmt->bind_param($types, ...$itineraryIDs);
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
    <title>My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&family=Spectral&display=swap" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-inner">
        <div class="navbar-logo"><img src="images/srvanlogo.png" alt="Logo"></div>
        <div class="navbar-links">
            <a href="index.php" class="nav-item">Home</a>
            <a href="<?php echo $isLoggedIn ? 'packages.php' : 'login/login.php'; ?>" class="nav-item">Book</a>
            <a href="minor/help.php" class="nav-item">Help</a>
            <a href="minor/about-us.php" class="nav-item">About Us</a>
            <?php if ($isLoggedIn): ?>
                <a href="login/logout.php" class="nav-item">Log Out</a>
                <a href="profile.php" class="nav-item"><?php echo htmlspecialchars($user['name']); ?></a>
            <?php else: ?>
                <a href="login/login.php" class="nav-item">Log In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Main -->
<main class="container">
    <div class="profile-wrapper">

        <!-- Welcome -->
        <div class="welcome-box">
            <div class="header">
                <h1>Welcome</h1>
                <p><strong><?php echo htmlspecialchars($user['name']); ?></strong>!</p>
            </div>
        </div>

        <!-- Bookings -->
        <div class="bookings-box">
            <div class="bookings-section">
                <h2>My Bookings</h2>

                <?php if (empty($bookings)): ?>
                    <div class="no-bookings">
                        <p>You have not made any bookings yet.</p>
                        <a href="itinerary-booking/custom-booking.php">Book a Trip</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <div class="booking-card">
                            <?php if ($booking['type'] === 'PACKAGE'): ?>
                                <h3>Package: <?php echo htmlspecialchars($booking['package_name']); ?></h3>
                            <?php else: ?>
                                <h3>Custom Itinerary</h3>
                            <?php endif; ?>

                            <p class="booking-date">Travel Date: <?php echo date("F j, Y", strtotime($booking['pickup_date'])); ?></p>

                                <div class="booking-info-wrapper">
                                    <div class="booking-details">
                                        <div class="detail-item"><span>Pickup Time:</span> 
                                            <?php echo !empty($booking['time_for_pickup']) ? date("g:i A", strtotime($booking['time_for_pickup'])) : 'N/A'; ?>
                                        </div>
                                        <div class="detail-item"><span>Drop-off Time:</span> 
                                            <?php echo !empty($booking['time_for_dropoff']) ? date("g:i A", strtotime($booking['time_for_dropoff'])) : 'N/A'; ?>
                                        </div>
                                        <div class="detail-item"><span>Passengers:</span> <?php echo htmlspecialchars($booking['number_of_PAX']); ?></div>
                                        <div class="detail-item"><span>Payment Method:</span> <?php echo htmlspecialchars(ucfirst($booking['payment_method'])); ?></div>
                                        <div class="detail-item"><span>Payment Status:</span> <?php echo htmlspecialchars($booking['payment_status']); ?></div>
                                        <div class="detail-item"><span>Booking Status:</span> <?php echo htmlspecialchars(ucfirst(strtolower($booking['order_status']))); ?></div>
                                    </div>

                                    <?php if ($booking['type'] === 'CUSTOM' && !empty($booking['ID_Picture'])): ?>
                                        <div class="custom-id-section">
                                            <h4>Uploaded ID for Verification:</h4>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($booking['ID_Picture']); ?>" alt="User Uploaded ID">
                                        </div>
                                    <?php endif; ?>
                                </div>

                            <h4>Itinerary:</h4>
                            <ul class="itinerary-list">
                                <?php $current_stops = $stopsByItinerary[$booking['itinerary_ID']] ?? []; ?>
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
    </div>
</main>

<!-- Footer -->
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-text">SR Van Travels 2025 ©. All Rights Reserved</div>
        <div class="footer-icons">
            <a href="mailto:srvantravels@gmail.com" class="footer-icon-link" aria-label="Email">
                <img src="svg-icons/email.svg" alt="Email Icon" class="footer-icon">
            </a>
            <a href="https://www.facebook.com/profile.php?id=61569662235289" target="_blank" rel="noopener noreferrer" class="footer-icon-link" aria-label="Facebook">
                <img src="svg-icons/facebook.svg" alt="Facebook Icon" class="footer-icon">
            </a>
        </div>
    </div>
</footer>

</body>
</html>
