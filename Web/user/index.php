<?php
    session_start();
    include '../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);

    /* uncomment after package db is added
    $sql = "SELECT * FROM package ORDER BY RAND() LIMIT 4";
    $result = $conn->query($sql);
    */
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
        </ul>
        <br>
        <hr>

        <h1>PACKAGES</h1>
        <div class = "packages">
            <!--   // uncomment after package db is added 
                <?//php if ($result->num_rows > 0): ?>
                    <?//php while ($row = $result->fetch_assoc()): ?>
                        <div class="package-card">
                            <h2><?//php echo $row['PackageName']; ?></h2>
                            <p><?//php echo $row['Description']; ?></p>
                            <p>Price: <?//php echo $row['Price']; ?></p>
                        </div>
                    <?//php endwhile; ?>
            !-->
            
            <div class = "package-card">
                <h2>Package 1</h2>
                <p>Description of Package 1</p>
                <p>Price: $100</p>
            </div>
            <div class = "package-card">
                <h2>Package 2</h2>
                <p>Description of Package 2</p>
                <p>Price: $100</p>
            </div>
            <div class = "package-card">
                <h2>Package 3</h2>
                <p>Description of Package 3</p>
                <p>Price: $100</p>
            </div>
            <div class = "package-card">
                <h2>Package 4</h2>
                <p>Description of Package 4</p>
                <p>Price: $100</p>
            </div>
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
        
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const cards = document.querySelectorAll('.package-card');
                cards.forEach(function(card) {
                    card.addEventListener('click', function () {
                        window.location.href = 'packages.php'; // TEMPORARY REDIRECT, WILL REDIRECT TO BOOKING
                    });
                });
            });
        </script>
    </body>
</html>