<?php
session_start();
$isLoggedIn = isset($_SESSION['person_ID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>USER About Us</title>

  <!-- CSS Styles -->
  <link rel="stylesheet" href="../css/shared.css">
  <link rel="stylesheet" href="../css/about-us.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <div class="top-bar">
      
    <img src="../img/logo.png" alt="SR Van Logo" class="logo">
      <nav class="navbar">
        <div class="navbar-inner">
            <div class="navbar-logo">
                <img src="https://placehold.co/109x107" alt="Logo">
            </div>
            <div class="navbar-links">
                <a href="#" class="nav-item">Home</a>

                <?php if ($isLoggedIn): ?>
                    <a href="packages.php" class="nav-item">Book</a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Book</a>
                <?php endif; ?>

                <a href="minor/help.php" class="nav-item">Help</a>
                <a href="#" class="nav-item">About Us</a>

                <?php if ($isLoggedIn): ?>
                    <a href="login/logout.php" class="nav-item">Log Out</a>
                    <a href="profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Log In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

      <div class="user-greeting">
        <?php if ($isLoggedIn): ?>
          <span>Hey, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>!</span>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <section class="hero">
    <img src="../img/cebu-banner.png" alt="Explore Cebu Banner" class="banner">
    <h1 class="hero-title">EXPLORE CEBU</h1>
    <p class="hero-subtitle">Book Your Cebu Travel and Adventure Trip with Us</p>
  </section>

  <section class="about-section">
    <div class="about-card">
      <h2>ABOUT US – <em>WHY DO WE DRIVE?</em></h2>
      <p>
        SR Van Travels is one of those esteemed business corporations that offer standard itineraries that provide clients of all sorts the luxury of being delivered from one place to another. SR Van Travels is one of the many itinerary services present in Cebu. It is a growing transport-service provider whose vision is catered to tourists and locals who require efficient, comfortable, and reliable travel across multiple destinations. With a fleet of well-maintained vans, experienced drivers, and a friendly team, SR Van Travels is more than just a transport service — we're your travel partner.

        Whether you're discovering Cebu for the first time or exploring it all over again, we are here to take you there — safely, comfortably, and on time.
      </p>
      <p class="highlight">Your journey matters. Let SR Van Travels drive you to unforgettable destinations.</p>

      <div class="contacts">
        <h3>CONTACTS:</h3>
        <ul>
          <li><strong>SR Van Travels</strong></li>
          <li>📧 srvantravels@gmail.com</li>
          <li>📞 09452866649, 09478196739, 09166240642, 09166629657, 0916660527, 09569430826</li>
          <li>📍 Jugan, Consolacion, Cebu City, Philippines</li>
        </ul>
      </div>
    </div>
  </section>

  <footer>
    <p>SR Van Travels 2025 ©. All Rights Reserved</p>
    <div class="socials">
      <img src="../img/fb-icon.png" alt="Facebook Icon">
      <img src="../img/ig-icon.png" alt="Instagram Icon">
      <img src="../img/mail-icon.png" alt="Email Icon">
    </div>
  </footer>
</body>
</html>
