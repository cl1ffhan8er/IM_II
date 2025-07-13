<?php
session_start();

if (!isset($_SESSION['package_id'])) {
    header("Location: ../index.php"); 
    exit;
}

$package_id = $_SESSION['package_id'];
$package_name = $_SESSION['package_name'];
$package_price = $_SESSION['package_price'];
$package_description = $_SESSION['package_description'];
$package_picture = $_SESSION['package_picture'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Step 1: <?php echo htmlspecialchars($package_name); ?></title>
</head>
<body>
    <div class="container">
        <img src="<?php echo htmlspecialchars($package_picture); ?>" alt="<?php echo htmlspecialchars($package_name); ?>">
        
        <h1><?php echo htmlspecialchars($package_name); ?></h1>
        <p><?php echo htmlspecialchars($package_description); ?></p>
        <p class="price">Price: ₱<?php echo number_format($package_price, 2); ?></p>
        
        <form method="POST" action="packagebook-p2.php" class="booking-form">
            <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package_id); ?>">
            <input type="hidden" name="package_name" value="<?php echo htmlspecialchars($package_name); ?>">
            <input type="hidden" name="package_price" value="<?php echo htmlspecialchars($package_price); ?>">

            <button type="submit" name="proceed_to_booking">Proceed to Booking</button>
        </form>
    </div>
</body>
</html>
