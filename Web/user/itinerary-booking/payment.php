<?php
    session_start();
    include '../../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);
    $username = $_SESSION['username'] ?? '';

    $booking_itinerary = $_SESSION['booking_itinerary'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "../css/payment.css">
    <link rel="stylesheet" href="../css/shared.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">


    <script src = "scripts/main.js"></script>
    <title>BOOKING SUMMARY & PAYMENT</title>
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
                    <a href="../profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Log In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <h1 style = "color: white">BOOKING SUMMARY</h1>

    <div class="booking-wrapper">
    <div class = "booking-summary">
        <div class="summary-inner">
        
        <div class="text-summary">
            <?php if (isset($_SESSION['fname']) && isset($_SESSION['lname'])): ?>
                <div class="field"><b>Full Name:</b> <?= htmlspecialchars($_SESSION['fname']) . ' ' . htmlspecialchars($_SESSION['lname']) ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['date'])): ?>
                <div class="field"><b>Pickup Date:</b> <?= htmlspecialchars($_SESSION['date']) ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['pickuptime'])): ?>
                <div class="field"><b>Pickup Time:</b> <?= htmlspecialchars($_SESSION['pickuptime']) ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['dropofftime'])): ?>
                <div class="field"><b>Dropoff Time:</b> <?= htmlspecialchars($_SESSION['dropofftime']) ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['pax'])): ?>
                <div class="field"><b>Number of Party Members:</b> <?= htmlspecialchars($_SESSION['pax']) ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['luggage'])): ?>
                <div class="field"><b>Number of Luggage:</b> <?= htmlspecialchars($_SESSION['luggage']) ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['comments'])): ?>
                <div class="field"><b>Comments:</b><br><?= nl2br(htmlspecialchars($_SESSION['comments'])) ?></div>
            <?php endif; ?>
        </div>

        <div class = "location-summary">
            <b>ITINERARY:</b>
            <div id = "selected-locations-part2"></div>
        </div>
        </div>

    </div>
    <div class = "payment">

        <h1>PAYMENT DETAILS</h1>
        <hr>
        <div class="qr-wrapper">
            <img src="../images/200x200.png" alt="GCash QR Code" class="qr-image">
        </div>

        <form action="submit-data.php" method="post">
            <label for = "payment_type">Payment Type:</label>
            <select name="payment_type" id="payment_type" required>
                <option value="gcash">GCash</option>
                <option value="cash">Pay on Pick-up</option>
            </select>
            <input type = "submit" value = "COMPLETE BOOKING" name = "submit">
            <button type="button" onclick="history.back()">EDIT BOOKING</button>
        </form>
    </div>
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

    <script>
        const selectedLocations = <?php echo json_encode($booking_itinerary); ?>;

        function updateDisplay() {
            const container = document.getElementById("selected-locations-part2");
            if (!container) return;
            container.innerHTML = '';

            if (!selectedLocations || selectedLocations.length === 0) {
                container.innerHTML = "<p>No locations were selected.</p>";
                return;
            }

            selectedLocations.forEach(loc => {
                const div = document.createElement("div");
                div.className = "location";
                const customIndicator = loc.isCustom ? ' <span style="color: #007bff; font-size: 0.9em;">(Custom)</span>' : '';
                div.innerHTML = `<p><b>${loc.name}</b>${customIndicator} - ${loc.address}</p>`;
                container.appendChild(div);
            });
        }
        document.addEventListener('DOMContentLoaded', updateDisplay);
    </script>
</body>
</html>
