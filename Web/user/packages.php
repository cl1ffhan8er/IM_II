<?php
    session_start();
    include '../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);

    if ($isLoggedIn):
        $username = $_SESSION['username'];
    endif;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT
            pi.package_id,
            pi.package_name,
            pi.description,
            pi.package_picture,
            i.price
        FROM
            Package_Itinerary pi
        INNER JOIN
            Itinerary i ON pi.package_id = i.itinerary_ID
        WHERE
            i.type = 'PACKAGE' AND pi.is_available = TRUE";

if (!empty($search)) {
    // Escape user input to prevent SQL injection
    $search = $conn->real_escape_string($search);
    $sql .= " AND (pi.package_name LIKE '%$search%' OR pi.description LIKE '%$search%')";
}

$sql .= " ORDER BY i.price LIMIT 4";


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
    <link rel="stylesheet" href="css/shared.css?v=1">
    <link rel="stylesheet" href="css/packages-styles.css?v=1">
    <title>PACKAGES</title>

</head>
<body>
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

                <a href="#" class="nav-item">Help</a>
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

<div class="search-container">
    <form action="packages.php" method="GET">
        <input
            type="text"
            name="search"
            class="search-bar"
            placeholder="Search packages..."
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
        >
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<div class="packages-grid">
<?php if (!empty($packages)): ?>
    <?php foreach ($packages as $package): ?>
        <div class="package-card" onclick="location.href='package-booking/packagebook-p1-back.php?package_id=<?= $package['package_id']; ?>'">
            <img src="<?= htmlspecialchars($package['package_picture']) ?>" class="package-image" alt="Image of <?= htmlspecialchars($package['package_name']) ?>">
            <div class="package-content">
                <h2><?= htmlspecialchars($package['package_name']) ?></h2>
                <p><?= htmlspecialchars($package['description']) ?></p>
                <p class="price">₱<?= number_format($package['price'], 2) ?> per PAX</p>
                <p style="font-size: 12px; color: #555;">6–12 persons</p>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No packages available right now.</p>
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
</body>
</html>