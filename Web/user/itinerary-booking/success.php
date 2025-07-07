

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src = "scripts/main.js"></script>
    <title>PACKAGES</title>

</head>
<body>
    <ul>
            <li class="nav"><a href="clear-booking-session.php?redirect_to=../index.php">Home</a></li>
            <li class="nav"><a href="clear-booking-session.php?redirect_to=../help.php">Help</a></li>
            <li class="nav"><a href="clear-booking-session.php?redirect_to=../about.php">About Us</a></li>
            <?php if ($isLoggedIn): ?>
                <li class="nav"><a onclick="logout()" style="cursor:pointer;">Log Out</a></li>
            <?php else: ?>
                <li class="nav"><a href="../login/login.php">Log In</a></li>
            <?php endif; ?>
    </ul>
    <h1>SUCCESSFULLY SUBMITTED DATA</h1>
</body>
</html>