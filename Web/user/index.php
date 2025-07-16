<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();    
    include '../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);

    if (!isset($_SESSION['customer_ID'])) {
        header("Location: login/login.php");
        exit();
    }

    if ($isLoggedIn) {
        $username = $_SESSION['username'];
    }

    $sql = "SELECT
                pi.package_ID AS package_id,
                pi.package_name,
                pi.description,
                pi.package_picture,
                i.price
            FROM
                Package_Itinerary pi
            INNER JOIN
                Itinerary i ON pi.package_id = i.itinerary_ID
            WHERE
                i.type = 'PACKAGE' AND pi.is_available = TRUE
            ORDER BY
                i.price
            LIMIT 4";

    $result = $conn->query($sql);

    $packages = [];
    if ($result) {
        while ($package = $result->fetch_assoc()) {
            $packages[] = $package;
        }
    }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SR Van Travels</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/shared.css?v=1">
    <link rel="stylesheet" href="css/index-styles.css?v=1">
</head>
<body>

    <nav class="navbar">
        <div class="navbar-inner">
            <div class="navbar-logo">
                <img src="images/srvanlogo.png" alt="Logo">
            </div>
            <div class="navbar-links">
                <a href="#" class="nav-item">Home</a>

                <?php if ($isLoggedIn): ?>
                    <a href="packages.php" class="nav-item">Book Package</a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Book Package</a>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <a href="itinerary-booking/custom-booking.php" class="nav-item">Book Itinerary</a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Book Itinerary</a>
                <?php endif; ?>

                <a href="minor/help.php" class="nav-item">Help</a>
                <a href="minor/about-us.php" class="nav-item">About Us</a>

                <?php if ($isLoggedIn): ?>
                    <a href="login/logout.php" class="nav-item">Log Out</a>
                    <a href="profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Log In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="hero-overlay">
            <h1 class="explore-heading">EXPLORE CEBU</h1>
            <p class="explore-subheading">Book Your Cebu Travel and Adventure Trip with Us</p>
        </div>

        <div class="scrolling-text">
            <div class="scrolling-track">
                <div class="scrolling-content">
                    <span class="white">SR VAN TRAVELS&nbsp;</span>
                    <span class="yellow">SR VAN TRAVELS&nbsp;</span>
                    <span class="white">SR VAN TRAVELS&nbsp;</span>
                    <span class="yellow">SR VAN TRAVELS&nbsp;</span>
                    <span class="white">SR VAN TRAVELS&nbsp;</span>
                </div>
            </div>
        </div>
    </section>

    <div class="background">
        <div class="header">Select from our various promos and pre-made plans!</div>

    <div class="packages-container">
        <div class="packages">
            
            <?php if (empty($packages)): ?>
                <p class="no-packages">No packages are available at this time.</p>
            <?php else: ?>
                <?php foreach ($packages as $package): ?>
                    
                    <div class="package-card-wrapper" onclick="submitPackageForm(<?php echo $package['package_id']; ?>)">
                        <div class="package-card">

                            <img src="../<?php echo htmlspecialchars($package['package_picture']); ?>" alt="<?php echo htmlspecialchars($package['package_name']); ?>">
                            <div class="package-details">
                                <div class="package-title-price">
                                    <div class="package-title"><?php echo htmlspecialchars($package['package_name']); ?></div>
                                    <div class="package-price">₱<?php echo number_format($package['price'], 2); ?></div>
                                </div>
                            </div>
                        </div>
                        <form id="package-form-<?php echo $package['package_id']; ?>" method="POST" action="package-booking/packagebook-p1-back.php" style="display: none;">
                            <input type="hidden" name="package_id" value="<?php echo $package['package_id']; ?>">
                            <input type="hidden" name="package_name" value="<?php echo htmlspecialchars($package['package_name']); ?>">
                            <input type="hidden" name="package_price" value="<?php echo htmlspecialchars($package['price']); ?>">
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

        <?php if ($isLoggedIn): ?>
            <a href="packages.php" class="see-more-btn">SEE MORE</a>
        <?php else: ?>
            <a href="login/login.php" class="see-more-btn">SEE MORE</a>
        <?php endif; ?>

        <h2 class="custom-title">Don’t see a plan you like?</h2>
        <p class="custom-subtitle">Book a custom 1Day-1Night Itinerary With Us!</p>

        <?php if ($isLoggedIn): ?>
            <a href="itinerary-booking/custom-booking.php" class="custom-btn">Book Now</a>
        <?php else: ?>
            <a href="login/login.php" class="custom-btn">MAKE A CUSTOM PLAN</a>
        <?php endif; ?>
    </div>

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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const track = document.querySelector('.scrolling-track');
            const content = document.querySelector('.scrolling-content');
            const clone = content.cloneNode(true);
            track.appendChild(clone);
        });

        function submitPackageForm(packageId) {
        const form = document.getElementById(`package-form-${packageId}`);
        if (form) {
            form.submit();
        }
        }
    </script>
</body>
</html>
