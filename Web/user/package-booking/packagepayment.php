<?php
session_start();
include '../../include/connect.php';

if (!isset($_SESSION['package_id'])) {
    if (isset($_GET['package_id'])) {
        $_SESSION['package_id'] = $_GET['package_id'];
    } else {
        die("Error: No package booking in progress. Please select a package first.");
    }
}

$isLoggedIn = isset($_SESSION['person_ID']);
$username = $_SESSION['username'] ?? '';
$package_id = $_SESSION['package_id'];
$package_stops = [];

$stops_stmt = $conn->prepare(
    "SELECT l.location_name
     FROM Itinerary_Stops its
     JOIN Locations l ON its.location_ID = l.location_ID
     WHERE its.custom_ID = ?
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

$price = number_format($_SESSION['package_price'] ?? 0, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/payment.css" />
    <link rel="stylesheet" href="../css/shared.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300..700&family=Spectral&display=swap" rel="stylesheet" />
    <script src="scripts/main.js"></script>
    <title>Booking Summary & Payment</title>
</head>
<body>
<nav class="navbar">
    <div class="navbar-inner">
        <div class="navbar-logo">
            <img src="../images/srvanlogo.png" alt="Logo" />
        </div>
        <div class="navbar-links">
            <a href="../index.php" class="nav-item">Home</a>
            
            <?php if ($isLoggedIn): ?>
                <a href="../packages.php" class="nav-item">Book Package</a>
            <?php else: ?>
                <a href="../login/login.php" class="nav-item">Book Package</a>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <a href="../itinerary-booking/custom-booking.php" class="nav-item">Book Itinerary</a>
            <?php else: ?>
                <a href="../login/login.php" class="nav-item">Book Itinerary</a>
            <?php endif; ?>

            <a href="../minor/help.php" class="nav-item">Help</a>
            <a href="../minor/about-us.php" class="nav-item">About Us</a>

            <?php if ($isLoggedIn): ?>
                <a href="../login/logout.php" class="nav-item">Log Out</a>
                <a href="../profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
            <?php else: ?>
                <a href="../login/login.php" class="nav-item">Log In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<h1>BOOKING SUMMARY</h1>

<div class="booking-wrapper">
    <div class="booking-summary">
        <div class="summary-inner">
            <div class="text-summary">
                <div class="field"><b>Full Name:</b> <?= htmlspecialchars($_SESSION['fname'] ?? '') . ' ' . htmlspecialchars($_SESSION['lname'] ?? '') ?></div>
                <div class="field"><b>Pickup Date:</b> <?= htmlspecialchars($_SESSION['date'] ?? '') ?></div>
                <div class="field"><b>Pickup Time:</b> <?= htmlspecialchars($_SESSION['pickuptime'] ?? '') ?></div>
                <div class="field"><b>Dropoff Time:</b> <?= htmlspecialchars($_SESSION['dropofftime'] ?? '') ?></div>
                <div class="field"><b>Number of Party Members:</b> <?= htmlspecialchars($_SESSION['pax'] ?? '') ?></div>
                <div class="field"><b>Number of Luggage:</b> <?= htmlspecialchars($_SESSION['luggage'] ?? '') ?></div>
                <?php if (!empty($_SESSION['comments'])): ?>
                    <div class="field"><b>Comments:</b><br><?= nl2br(htmlspecialchars($_SESSION['comments'])) ?></div>
                <?php endif; ?>

                <hr>
                <div class="price-display">
                    <p><strong>Total Price:</strong> <span id="price-display">₱<?= $price ?></span></p>
                </div>
            </div>

        <div class="package-wrapper">
            <h2 class="package-label">PACKAGE:</h2>
            <div class="location-summary">
                <?php if (!empty($_SESSION['package_name'])): ?>
                    <h2><?= htmlspecialchars($_SESSION['package_name']) ?></h2>
                <?php endif; ?>
                <hr>
                <?php if (!empty($_SESSION['package_route'])): ?>
                    <div class="summary-section">
                        <p><strong>Route:</strong></p>
                        <ul>
                            <?php 
                                $routes = preg_split("/\s*,\s*/", $_SESSION['package_route']);
                                foreach ($routes as $route):
                                    if (trim($route) !== ''):
                            ?>
                                <li><?= htmlspecialchars(trim($route)) ?></li>
                            <?php 
                                    endif;
                                endforeach;
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['package_inclusions'])): ?>
                    <div class="summary-section">
                        <p><strong>Inclusions:</strong></p>
                        <ul>
                            <?php 
                                $inclusions = preg_split("/\s*,\s*/", $_SESSION['package_inclusions']);
                                foreach ($inclusions as $inclusion):
                                    if (trim($inclusion) !== ''):
                            ?>
                                <li><?= htmlspecialchars(trim($inclusion)) ?></li>
                            <?php 
                                    endif;
                                endforeach;
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>

    <div class="payment">
        <h2>PAYMENT DETAILS</h2>
        <hr>
        <div class="qr-wrapper">
            <img src="../images/200x200.png" alt="GCash QR Code" class="qr-image" />
        </div>
        <form action="submit-data.php" method="post">
            <label for="payment_type">Payment Type:</label>
            <select name="payment_type" id="payment_type" required>
                <option value="gcash">GCash</option>
                <option value="cash">Pay on Pick-up</option>
            </select>
            <input type="submit" value="COMPLETE BOOKING" name="submit" />
            <button type="button" onclick="history.back()">EDIT BOOKING</button>
        </form>
    </div>
</div>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-text">SR Van Travels 2025 ©. All Rights Reserved</div>
        <div class="footer-icons">
            <a href="mailto:srvantravels@gmail.com" class="footer-icon-link" aria-label="Email">
                <img src="../svg-icons/email.svg" alt="Email Icon" class="footer-icon" />
            </a>
            <a href="https://www.facebook.com/profile.php?id=61569662235289" target="_blank" rel="noopener noreferrer" class="footer-icon-link" aria-label="Facebook">
                <img src="../svg-icons/facebook.svg" alt="Facebook Icon" class="footer-icon" />
            </a>
        </div>
    </div>
</footer>
</body>
</html>