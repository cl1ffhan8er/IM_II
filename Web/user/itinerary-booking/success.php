<?php
    session_start();
    $isLoggedIn = isset($_SESSION['person_ID']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src = "scripts/main.js"></script>
    <title>Booking Successful</title>
</head>
<body>
    <ul>
            <li class="nav"><a href="../index.php">Home</a></li>
            <li class="nav"><a href="../help.php">Help</a></li>
            <li class="nav"><a href="../about.php">About Us</a></li>
            <?php if ($isLoggedIn): ?>
                <li class="nav"><a onclick="logout()" style="cursor:pointer;">Log Out</a></li>
            <?php else: ?>
                <li class="nav"><a href="../login/login.php">Log In</a></li>
            <?php endif; ?>
    </ul>
    <h1>Booking Submitted Successfully!</h1>
    <p>Thank you for booking with SRVan Travels. A confirmation email has been sent to you.</p>

    <script>
        localStorage.removeItem('customItineraryLocations');
    </script>
</body>
</html>
