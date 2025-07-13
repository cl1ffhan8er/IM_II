<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="../css/booking-styles.css"> 
</head>
<body>
    <div class="confirmation-container">
        <h1>Booking Confirmed!</h1>
        <p>Thank you for your booking. Your details have been submitted successfully.</p>
        
        <?php
        // Get the order ID from the URL parameter
        $order_id = $_GET['order_id'] ?? 'N/A';
        ?>
        
        <h2>Order Details</h2>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
        <p>Your booking is currently marked as **PENDING**.</p>
        <p>A manager will review your booking shortly.</p>
        
        <a href="http://localhost/IM_II/Web/user/">Return to Home</a>
    </div>
</body>
</html>