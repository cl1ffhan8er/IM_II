<?php
    session_start();
    include '../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);

    if ($isLoggedIn):
        $username = $_SESSION['username'];
    endif;

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
        <link rel = "stylesheet" href="css/index-styles.css">
        <title>SR Van Travels</title>
    </head>
    <body>
        <h1>HOMEPAGE</h1>
        <ul>
            <li class = "nav">Home</li>
            <?php if ($isLoggedIn): ?>
                <a href="packages.php" class = "nav"><button>Book a Package</button></a>
            <?php else: ?>
                <a href="login/login.php" class = "nav"><button>Book a Package</button></a>
            <?php endif; ?>
            <li class = "nav">Help</li>
            <li class = "nav">About Us</li>
            <?php if ($isLoggedIn): ?>
                <a href="login/logout.php" class = "nav">Log Out</a>
            <?php else: ?>
                <a href="login/login.php" class = "nav">Log In</a>
            <?php endif; ?>
            <li class = "nav"></li>
            <li class = "nav"></li>
            <!-- profile page !-->
            <?php if ($isLoggedIn): ?>
                <a href = "profile.php" class = "nav"><?php echo htmlspecialchars($username); ?></a>
            <?php endif; ?>
        </ul>
        <br>
        <hr>

        <h1>PACKAGES</h1>
        <div class="packages-grid">
            <?php if (empty($packages)): ?>
                
                <p>No packages are available at this time.</p>

            <?php else: ?>
                
                <?php foreach ($packages as $package): ?>
                    <form class="package-card" method="POST" action="package-booking/packagebook-p1.php">
                        <div class="package-content">
                            <h2><?php echo htmlspecialchars($package['package_name']); ?></h2>
                            <p><?php echo htmlspecialchars($package['description']); ?></p>
                            <p class="price">Price: ₱<?php echo number_format($package['price'], 2); ?></p>
                            
                            <input type="hidden" name="package_id" value="<?php echo $package['package_id']; ?>">
                            
                            <input type="hidden" name="package_name" value="<?php echo htmlspecialchars($package['package_name']); ?>">
                            <input type="hidden" name="package_price" value="<?php echo htmlspecialchars($package['price']); ?>">

                            <button type="submit" class="booking-button">View Details & Book</button>
                        </div>
                    </form>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
        <br><br>
        <hr>
        
        <div>
            <h2>Want to be flexible? Book a custom 1D1N Itinerary With Us!</h2>
            <?php if ($isLoggedIn): ?>
                <a href="itinerary-booking/custom-booking.php" class = "nav"><button>Book Now</button></a>
            <?php else: ?>
                <a href="login/login.php" class = "nav"><button>Book Now</button></a>
            <?php endif; ?>
        </div>
        
        <!--
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const cards = document.querySelectorAll('.package-card');
                cards.forEach(function(card) {
                    card.addEventListener('click', function () {
                        window.location.href = 'package-booking/packagebook-p1.php'; 
                    });
                });
            });
        </script> -->
    </body>
</html>