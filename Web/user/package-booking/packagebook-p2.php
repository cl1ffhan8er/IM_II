<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Step 2: Your Booking Details</title>
    </head>
<body>
    <h1>Your Details for: <?php echo htmlspecialchars($_SESSION['package_name']); ?></h1>
    <p>Price: ₱<?php echo number_format($_SESSION['package_price'], 2); ?></p>
    <hr>

    <form action="packageform-p2.php" method="POST" enctype="multipart/form-data">

        <h3>Enter Your Details:</h3>
        <p><label>First Name: <input type="text" name="fname" required></label></p>
        <p><label>Last Name: <input type="text" name="lname" required></label></p>
        <p><label>Number of Passengers: <input type="number" name="pax" value="1" min="1" max="100"required></label></p>
        
        <p><label>Pickup Date: <input type="date" name="date" required></label></p>
        <p><label>Pickup Time: <input type="time" name="pickuptime" required></label></p>
        <p><label>Dropoff Time: <input type="time" name="dropofftime" required></label></p>
        <p><label>Number of Luggage (optional): <input type="number" name="luggage" value="0" min="0"></label></p>
        <p><label>Upload Picture of Valid ID: <input type="file" name="id" required></label></p>
        
    
        <button type="button" onclick="window.location.href='../index.php'">Back to Packages</button>
        <button type="submit">Proceed to Summary</button>
    </form>
</body>
</html>