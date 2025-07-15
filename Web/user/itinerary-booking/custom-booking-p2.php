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
    <link rel = "stylesheet" href = "../css/booking-styles.css">
    <link rel="stylesheet" href="../css/shared.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <script src = "scripts/main.js"></script>
    <title>CUSTOM BOOKING - STEP 2</title>
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

    <div class="booking-container">
    <div class="title">
        <h1>BOOKING DETAILS</h1>
    </div>
    
    <form id="bookingform2" action="customform-p2.php" method="post" enctype="multipart/form-data" class="booking-form">

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

        <label for="pax">Number of Party Members:</label>
        <input type="number" id="pax" name="pax" placeholder="Number of Party Members" required>

        <label for="pickup_date">Pick-up Date:</label>
        <input type="date" id="pickup_date" name="pickup_date" value="<?= htmlspecialchars($date) ?>" readonly required>

        <label for="pickup_time">Pick-up Time:</label>
        <input type="time" id="pickup_time" name="pickup_time" value="<?= htmlspecialchars($pickuptime) ?>" readonly required>

        <label for="pickup_address">Pick-up Address:</label>
        <input type="text" id="pickup_address" name="pickup_address" value="<?= htmlspecialchars($pickup) ?>" readonly required>

        <label for="luggage">Number of Luggage:</label>
        <input type="number" id="luggage" name="luggage" placeholder="Number of Luggage" required>

        <label for="comments">Comments/Others:</label>
        <textarea id="comments" name="comments" placeholder="Comments or additional requests"></textarea>
      </div>

      <div class="form-right">
        <h3>Chosen Pit Stops</h3>
        <div class="pit-stops-box">
          <?php foreach ($booking_itinerary as $stop): ?>
            <div class="pit-stop">
              <p><strong><?= htmlspecialchars($stop['name']) ?></strong></p>
              <p><?= htmlspecialchars($stop['address']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <label for="id">Attach Official ID (jpg / png):</label>
    <input type="file" name="id" accept=".jpg, .jpeg, .png" required>

    <div class="form-field">
    <label>
        <input type="checkbox" name="pay_on_pickup" id="pay_on_pickup">
        Pay on Pick-Up Date?
        <small class="note">By choosing this, a downpayment is required.</small>
    </label>
    </div>

    <div class="form-buttons">
    <button type="button" class="nav-button action-button cancel-button" onclick="history.back()">CANCEL</button>
    <button type="submit" class="nav-button action-button submit-button">SUBMIT</button>
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