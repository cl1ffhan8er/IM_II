<?php
    session_start();
    $isLoggedIn = isset($_SESSION['person_ID']);
    $username = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="scripts/main.js"></script>
    <title>Booking Successful</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/booking-success.css">
    <link rel="stylesheet" href="../css/shared.css">
</head>
<body>
<div class="page-wrapper"><!-- ✅ Page wrapper start -->

    <nav class="navbar">
        <div class="navbar-inner">
            <div class="navbar-logo">
                <img src="../images/srvanlogo.png" alt="Logo">
            </div>
            <div class="navbar-links">
                <a href="#" class="nav-item">Home</a>

                <?php if ($isLoggedIn): ?>
                    <a href="../packages.php" class="nav-item">Book Package</a>
                <?php else: ?>
                    <a href="../login/login.php" class="nav-item">Book Package</a>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <a href="custom-booking.php" class="nav-item">Book Itinerary</a>
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

    <main class="success-container">
        <h1>Booking Submitted Successfully!</h1>
        <p>Thank you for booking with SRVan Travels. A confirmation email has been sent to you.</p>
        <hr>
        <p class="help-tagline">If there are any complications and questions, contact us below:</p>

        <div class="contact-grid">
        <div class="contact-card">
            <h3>Facebook</h3>
            <p>SR Van Travels</p>
        </div>

        <div class="contact-card">
            <h3>Email</h3>
            <p>srvantravels@gmail.com</p>
        </div>

        <div class="contact-card">
            <h3>Phone Numbers</h3>
            <ul class="phone-grid">
            <li>09452866649</li>
            <li>09478196739</li>
            <li>09166240642</li>
            <li>09166629657</li>
            <li>0916660527</li>
            <li>09569430826</li>
            </ul>
        </div>

        <div class="contact-card">
            <h3>Location</h3>
            <p>Jugan, Consolacion, Cebu City, Philippines</p>
        </div>
        </div>
    </main>

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

</div><!-- ✅ Page wrapper end -->

<script>
    localStorage.removeItem('customItineraryLocations');
</script>
</body>
</html>