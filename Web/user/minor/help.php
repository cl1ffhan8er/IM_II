<?php
session_start();
$isLoggedIn = isset($_SESSION['person_ID']);
$username = $_SESSION['username'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>USER Help</title>
  <link rel="stylesheet" href="../css/shared.css">
  <link rel="stylesheet" href="../css/help.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
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
                    <a href="../packages.php" class="nav-item">Book Package</a>
                <?php else: ?>
                    <a href="../login/login.php" class="nav-item">Book Package</a>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <a href="../itinerary-booking/custom-booking.php" class="nav-item">Book Itinerary</a>
                <?php else: ?>
                    <a href="../login/login.php" class="nav-item">Book Itinerary</a>
                <?php endif; ?>

                <a href="help.php" class="nav-item">Help</a>
                <a href="about-us.php" class="nav-item">About Us</a>

                <?php if ($isLoggedIn): ?>
                    <a href="../login/logout.php" class="nav-item">Log Out</a>
                    <a href="../profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="../login/login.php" class="nav-item">Log In</a>
                <?php endif; ?>
            </div>
    </div>
</nav>

  <main class="help-content">
    <h1 class="help-title">
        <span class="main">HELP CENTER</span>
        <span class="sub">Welcome to the SR Van Travels Help Center!</span>
    </h1>

    <div class="faq-box">
    <h2>Frequently Asked Questions (FAQs)</h2>

    <div class="faq-grid">
        <div class="faq-card">
        <h3>1. How do I book a trip with SR Van Travels?</h3>
        <ul>
            <li>Visit the Home Page and click on the “Book Now” button.</li>
            <li>Choose a travel type: custom or regular packages.</li>
            <li>Fill out the Booking form, complete with the travel date, number of passengers, and other relevant information.</li>
            <li>Proceed to the Payment Page, then confirm your booking.</li>
            <li>You’ll receive a confirmation number via email or text once your payment is processed.</li>
        </ul>
        </div>

        <div class="faq-card">
        <h3>2. Can I customize my itinerary?</h3>
        <p>Yes! In the Home page, there is a designated button meant to direct you 
            to the custom itinerary page, where you can freely design and coordinate 
            your stops, destinations, and schedules. Making a custom itinerary comes 
            with a downpayment of <strong>500 PESOS</strong>, and the final payment will be calculated 
            based on gasoline consumption, driver’s fee, and maintenance fee for the van. 
            Lastly, our team will reach out to you to confirm the plan’s feasibility 
            and availability.</p>
        </div>

        <div class="faq-card">
        <h3>3. What payment options are available?</h3>
        <ul>
            <li><strong>GCash</strong></li>
            <li><strong>Physical Payments:</strong> requires an online down payment; remaining balance is payable in person</li>
        </ul>
        <p><i>Note: Credit card and direct bank transfers are not yet available.</i></p>
        </div>

        <div class="faq-card">
        <h3>4. How do I know if my booking was successful?</h3>
        <p>Once you complete the payment, a confirmation screen will appear and a Booking Reference Number will be sent to your email and phone number. If you don’t receive confirmation within 24 hours, please contact us.</p>
        </div>
    </div>
    </div>

    <h2>Tips for a Smooth Booking:</h2>
    <div class="tips-card">
        <ul>
        <li>Book at least 3–5 days before your intended travel date.</li>
        <li>Review your selected itinerary and trip details before confirming.</li>
        <li>Use a valid and active email and mobile number to ensure you receive updates.</li>
        <li>If traveling in a group, coordinate with members beforehand to avoid schedule conflicts.</li>
        <li>Use accurate names and contact numbers to avoid booking delays or confusion.</li>
        </ul>
    </div>

    <p class="help-tagline">
        <strong>
        Let us help you travel better.<br>
        Thank you for choosing SR Van Travels — <i>your reliable itinerary companion.</i>
        </strong>
    </p>

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
</body>
</html>