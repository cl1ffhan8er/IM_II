<?php
    session_start();
    include '../../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);
    $username = $_SESSION['username'] ?? '';

    $booking_itinerary = $_SESSION['booking_itinerary'] ?? [];

    $date = $_SESSION['date'] ?? '';
    $pickuptime = $_SESSION['pickuptime'] ?? '';
    $dropofftime = $_SESSION['dropofftime'] ?? '';
    $pickup = $_SESSION['pickup'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/booking-styles.css">
    <link rel="stylesheet" href="../css/shared.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral&display=swap" rel="stylesheet">

    <script src="scripts/main.js"></script>
    <title>Step 2: Your Booking Details</title>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <div class="navbar-logo">
            <img src="../images/srvanlogo.png" alt="Logo">
        </div>
        <div class="navbar-links">
            <a href="../index.php" class="nav-item">Home</a>

            <?php if ($isLoggedIn): ?>
                <a href="../packages.php" class="nav-item">Book</a>
            <?php else: ?>
                <a href="login/login.php" class="nav-item">Book</a>
            <?php endif; ?>

            <a href="/IM_II-2/Web/user/minor/help.php" class="nav-item">Help</a>
            <a href="/IM_II-2/Web/user/minor/about-us.php" class="nav-item">About Us</a>

            <?php if ($isLoggedIn): ?>
                <a href="login/logout.php" class="nav-item">Log Out</a>
                <a href="../profile.php" class="nav-item"><?= htmlspecialchars($username) ?></a>
            <?php else: ?>
                <a href="login/login.php" class="nav-item">Log In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="booking-container">
    <div class="title">
        <h1>BOOKING DETAILS FOR: <?= htmlspecialchars($_SESSION['package_name']) ?></h1>
    </div>

    <form id = "bookingform" action="packageform-p2.php" method="POST" enctype="multipart/form-data" class="booking-form">
        
    <div class="form2">
        <div class="name-fields">
            <div>
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" placeholder="First Name" required>
            </div>
            <div>
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" placeholder="Last Name" required>
            </div>
        </div>

        <div class="form-flex">
            <div class="form-left">
                <label for="pax">Number of Passengers:</label>
                <input type="number" id="pax" name="pax" value="1" min="1" max="100" required>

                <label for="date">Pickup Date:</label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    min="<?php echo date('Y-m-d'); ?>"
                    value="<?php echo htmlspecialchars($_SESSION['date'] ?? ''); ?>"
                    required
                />
                <label for="pickuptime">Pickup Time:</label>
                <input type="time" id="pickuptime" name="pickuptime" value="<?php echo htmlspecialchars($_SESSION['pickuptime'] ?? ''); ?>" required/>

                <label for="pickup">Pickup Address:</label>
                <input type="text" id="pickup" name="pickup" value="<?= htmlspecialchars($pickup) ?>" required>

                <label for="luggage">Number of Luggage (optional):</label>
                <input type="number" id="luggage" name="luggage" value="0" min="0">
            </div>

<div class="form-right">
    <h3>Package Summary</h3>
    <hr>
    <div class="summary-line">
    <strong>Name:</strong>
    <span><?= htmlspecialchars($_SESSION['package_name']) ?></span>
    </div>
    <div class="summary-line">
    <strong>Description:</strong>
    <span><?= nl2br(htmlspecialchars($_SESSION['package_description'])) ?></span>
    </div>

    <div class="summary-columns">
        <?php if (!empty($_SESSION['package_route'])): ?>
            <div class="summary-column">
                <p><strong>Route:</strong></p>
                <ul>
                    <?php 
                        $routes = preg_split("/\s*,\s*|\s+-\s+/", $_SESSION['package_route']);
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
            <div class="summary-column">
                <p><strong>Inclusions:</strong></p>
                <ul>
                    <?php 
                        $inclusions = preg_split("/\s*,\s*|\s+-\s+/", $_SESSION['package_inclusions']);
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

    <div class="price-row">
        <strong>Price per PAX: </strong>
        <span>₱<?= number_format($_SESSION['package_price'], 2) ?></span>
    </div>
</div>

        </div>

            <label for="id">Attach Official ID (jpg / png):</label>
            <input type="file" name="id" accept=".jpg, .jpeg, .png" required>

            <div class="form-buttons">
                <button type="button" class="nav-button action-button cancel-button" onclick="window.location.href='../index.php'">BACK TO PACKAGES</button>
                <button type="submit" class="nav-button action-button submit-button">PROCEED TO SUMMARY</button>
            </div>
        </div>
    </form>
</div>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-text">SR Van Travels 2025 ©. All Rights Reserved</div>
        <div class="footer-icons">
            <a href="mailto:srvantravels@gmail.com" class="footer-icon-link" aria-label="Email">
                <img src="../svg-icons/email.svg" alt="Email Icon" class="footer-icon">
            </a>
            <a href="https://www.facebook.com/profile.php?id=61569662235289" target="_blank" rel="noopener noreferrer" class="footer-icon-link" aria-label="Facebook">
                <img src="../svg-icons/facebook.svg" alt="Facebook Icon" class="footer-icon">
            </a>
        </div>
    </div>
</footer>

</body>
</html>
