<?php
session_start();
include '../../include/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_booking'])) {
    
    $_SESSION['booking_type'] = 'PACKAGE';
    $_SESSION['package_id'] = $_POST['package_id'];
    $_SESSION['booking_itinerary'] = json_decode($_POST['itinerary_stops'], true);
    $_SESSION['package_name'] = $_POST['package_name'];
    $_SESSION['package_price'] = $_POST['package_price'];
    
    // Now proceed with the redirect
    session_write_close();
    header("Location: booking_step2.php"); // Or whatever your next page is
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['package_id'])) {
    die("Error: No package selected. Please go back and choose a package.");
}

$package_id = $_POST['package_id'];

$package_stmt = $conn->prepare(
    "SELECT pi.package_name, pi.description, pi.package_picture, i.price
     FROM Package_Itinerary pi
     JOIN Itinerary i ON pi.package_id = i.itinerary_ID
     WHERE pi.package_id = ? AND i.type = 'PACKAGE' AND pi.is_available = TRUE"
);
$package_stmt->bind_param("i", $package_id);
$package_stmt->execute();
$package_result = $package_stmt->get_result();

if ($package_result->num_rows === 0) {
    die("Error: The selected package could not be found or is not available.");
}
$package = $package_result->fetch_assoc();
$package_stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Step 1: <?php echo htmlspecialchars($package['package_name']); ?></title>
</head>
<body>

    <div class="container">
        <img src="<?php echo htmlspecialchars($package['package_picture']); ?>" alt="<?php echo htmlspecialchars($package['package_name']); ?>">
        
        <h1><?php echo htmlspecialchars($package['package_name']); ?></h1>
        <p><?php echo htmlspecialchars($package['description']); ?></p>
        <p class="price">Price: ₱<?php echo number_format($package['price'], 2); ?></p>
        
        <form method="POST" action="packagebook-p2.php" class="booking-form">
            <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package_id); ?>">
            <input type="hidden" name="package_name" value="<?php echo htmlspecialchars($package['package_name']); ?>">
            <input type="hidden" name="package_price" value="<?php echo htmlspecialchars($package['price']); ?>">

            <button type="submit" name="proceed_to_booking">Proceed to Booking</button>
        </form>
    </div>

</body>
</html>