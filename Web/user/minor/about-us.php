<?php
session_start();
$isLoggedIn = isset($_SESSION['person_ID']);
$username = $_SESSION['username'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us - SR Van Travels</title>
  <link rel="stylesheet" href="../css/shared.css"/>
  <link rel="stylesheet" href="../css/about-us.css"/>

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
                  <a href="../packages.php" class="nav-item">Book</a>
              <?php else: ?>
                  <a href="login/login.php" class="nav-item">Book</a>
              <?php endif; ?>

              <a href="/IM_II-2/Web/user/minor/help.php" class="nav-item">Help</a>
              <a href="/IM_II-2/Web/user/minor/about-us.php" class="nav-item">About Us</a>

              <?php if ($isLoggedIn): ?>
                  <a href="login/logout.php" class="nav-item">Log Out</a>
                  <a href="profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
              <?php else: ?>
                  <a href="login/login.php" class="nav-item">Log In</a>
              <?php endif; ?>
          </div>
      </div>
  </nav>

  <div class="explore-cebu-hero">
    <div class="hero-logo">
      <img src="../images/srvanlogo.png" alt="SR Van Logo">
    </div>
    <div class="hero-text">
      <div class="explore-title">EXPLORE CEBU</div>
      <div class="explore-subtext">Book Your Cebu Travel and Adventure Trip with Us</div>
    </div>
  </div>

  <div class="main-content">
    <div class="content-box">
      <h1>ABOUT US – <i>WHY DO WE DRIVE?</i></h1>
      <p>
        SR Van Travels are one of these esteemed business corporations that offer 
        standard itineraries that provide clients of all sorts the luxury of being 
        destined from one place to another. SR Van Travels is one of the many itinerary 
        services present in Cebu. It is a growing transport service provider whose service 
        caters to tourists and locals who require efficient, comfortable, and reliable travel 
        across multiple destinations. With a fleet of well-maintained vans, experienced 
        drivers, and a friendly team, SR Van Travels is more than just a transport service 
        — we’re your travel partner. Whether you're discovering Cebu for the first time or 
        exploring it all over again, we are here to take you there — safely, comfortably, and on time.
      </p>
      <p class="slogan">Your journey matters. Let SR Van Travels drive you to unforgettable destinations.</p>

<h2>CONTACTS:</h2>
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

      </ul>
    </div>
  </div>

  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-text">SR Van Travels 2025 ©. All Rights Reserved</div>
      <div class="footer-icons">
        <a href="mailto:srvantravels@gmail.com"><img src="../svg-icons/email.svg" alt="Email" class="footer-icon"></a>
        <a href="https://www.facebook.com/profile.php?id=61569662235289" target="_blank"><img src="../svg-icons/facebook.svg" alt="Facebook" class="footer-icon"></a>
      </div>
    </div>
  </footer>

</body>
</html>