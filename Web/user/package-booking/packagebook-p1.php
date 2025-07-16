<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include '../../include/connect.php';  // ✅ FIXED

$isLoggedIn = isset($_SESSION['person_ID']);
$person_id = $_SESSION['person_ID'] ?? null;
$username = $_SESSION['username'] ?? '';

if (!isset($_SESSION['package_id'])) {
    header("Location: ../index.php"); 
    exit;
}

$package_id = $_SESSION['package_id'];
$package_name = $_SESSION['package_name'];
$package_price = $_SESSION['package_price'];
$package_description = $_SESSION['package_description'];
$package_picture = $_SESSION['package_picture'];

// initialize fallback values
$route = $inclusions = $number_of_PAX = '';

if ($conn) {
    $stmt = $conn->prepare("SELECT route, inclusions, number_of_PAX FROM Package_Itinerary WHERE package_ID = ? AND is_available = 1");
    if ($stmt) {
        $stmt->bind_param("i", $package_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $route = $row['route'] ?? '';
            $inclusions = $row['inclusions'] ?? '';
            $number_of_PAX = $row['number_of_PAX'] ?? '';
        }
        $stmt->close();
    } else {
        echo "SQL prepare failed: " . $conn->error;
    }
} else {
    echo "Database connection failed.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Step 1: <?php echo htmlspecialchars($package_name); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/shared.css">
    <link rel="stylesheet" href="../css/package-book.css">

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

                <a href="../minor/help.php" class="nav-item">Help</a>
                <a href="../minor/about-us.php" class="nav-item">About Us</a>

                <?php if ($isLoggedIn): ?>
                    <a href="login/logout.php" class="nav-item">Log Out</a>
                    <a href="../profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Log In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="main-image">
        <img src="../../<?= htmlspecialchars($package_picture) ?>" alt="Tour Image">
        </div>

        <div class="container-inner">
        <div class="package-header">
            <h1><?= htmlspecialchars($package_name) ?></h1>
            <div class="price">Php <?= number_format($package_price, 0) ?> per PAX</div>
        </div>

        <div class="package-details">
        <div class="details-column">
            <h3>ROUTE:</h3>
            <ul>
                <?php 
                $route_items = array_filter(array_map('trim', explode(',', $route)));
                foreach ($route_items as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="details-column">
            <h3>INCLUSIONS:</h3>
            <ul>
                <?php 
                $inclusion_items = array_filter(array_map('trim', explode(',', $inclusions)));
                foreach ($inclusion_items as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="description">
            <?= nl2br(htmlspecialchars($package_description)) ?>
        </div>
    </div>
    <div class="action-buttons">
        <div class="button-wrapper">
            <button class="back-button" onclick="window.location.href='../packages.php'">BACK TO PLANS</button>
        </div>
        <div class="button-wrapper">
            <form method="POST" action="packagebook-p2.php">
                <input type="hidden" name="package_id" value="<?= htmlspecialchars($package_id) ?>">
                <input type="hidden" name="package_name" value="<?= htmlspecialchars($package_name) ?>">
                <input type="hidden" name="package_price" value="<?= htmlspecialchars($package_price) ?>">
                <button type="submit" class="book-button">BOOK</button>
            </form>
        </div>
    </div>
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

</body>
</html>
