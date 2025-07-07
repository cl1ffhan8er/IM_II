<?php
    session_start();
    include '../../include/connect.php';
    $isLoggedIn = isset($_SESSION['person_ID']);

    $booking_itinerary = $_SESSION['booking_itinerary'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "../css/booking-styles.css">
    <script src = "scripts/main.js"></script>
    <title>BOOKING SUMMARY & PAYMENT</title>
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
    <br><br>
    <hr>
    <h1>BOOKING SUMMARY</h1>
    <div class = "booking-summary">
        <div class = "text-summary">
            <b>Full Name:</b> 
            <?php 
                if (isset($_SESSION['fname']) && isset($_SESSION['lname'])) {
                    echo htmlspecialchars($_SESSION['fname']) . ' ' . htmlspecialchars($_SESSION['lname']);
                } ?>
            <br>
            <b>Pickup Date:</b> 
            <?php 
                if (isset($_SESSION['date'])) {
                    echo htmlspecialchars($_SESSION['date']);
                } ?>
            <br>
            <b>Pickup Time:</b> 
            <?php 
                if (isset($_SESSION['pickuptime'])) {
                    echo htmlspecialchars($_SESSION['pickuptime']);
                } ?>
            <br>
            <b>Dropoff Time:</b> 
            <?php 
                if (isset($_SESSION['dropofftime'])) {
                    echo htmlspecialchars($_SESSION['dropofftime']);
                } ?>
            <br>
            <b>Number of Party Members:</b> 
            <?php 
                if (isset($_SESSION['pax'])) {
                    echo htmlspecialchars($_SESSION['pax']);
                } ?>
            <br>
            <b>Number of Luggage:</b> 
            <?php 
                if (isset($_SESSION['luggage'])) {
                    echo htmlspecialchars($_SESSION['luggage']);
                } ?>
            <br>
            <b>Comments:</b> 
            <?php 
                if (isset($_SESSION['comments'])) {
                    echo nl2br(htmlspecialchars($_SESSION['comments']));
                } ?>
        </div>
        <div class = "location-summary">
            <b>ITINERARY:</b>
            <div id = "selected-locations-part2"></div>
        </div>
    </div>
    <div class = "payment">
        <h1>PAYMENT DETAILS</h1>
        <p> gcash image here</p>
        <form action="submit-data.php" method="post">
            <label for = "payment_type">Payment Type:</label>
            <select name="payment_type" id="payment_type" required>
                <option value="gcash">GCash</option>
                <option value="cash">Cash</option>
            </select>
            <input type = "submit" value = "COMPLETE BOOKING" name = "submit">
            <button type="button" onclick="history.back()">EDIT BOOKING</button>
        </form>
    </div>

    <script>
        const selectedLocations = <?php echo json_encode($booking_itinerary); ?>;

        function updateDisplay() {
            const container = document.getElementById("selected-locations-part2");
            if (!container) return;
            container.innerHTML = '';

            if (!selectedLocations || selectedLocations.length === 0) {
                container.innerHTML = "<p>No locations were selected.</p>";
                return;
            }

            selectedLocations.forEach(loc => {
                const div = document.createElement("div");
                div.className = "location";
                const customIndicator = loc.isCustom ? ' <span style="color: #007bff; font-size: 0.9em;">(Custom)</span>' : '';
                div.innerHTML = `<p><b>${loc.name}</b>${customIndicator} - ${loc.address}</p>`;
                container.appendChild(div);
            });
        }
        document.addEventListener('DOMContentLoaded', updateDisplay);
    </script>
</body>
</html>
