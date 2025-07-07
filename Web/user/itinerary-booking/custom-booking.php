<?php
    session_start();
    include '../../include/connect.php'; 
    $isLoggedIn = isset($_SESSION['person_ID']);
    
    // Check if an itinerary already exists in the session and pass it to JavaScript.
    $existing_itinerary = $_SESSION['booking_itinerary'] ?? [];

    $locations_result = $conn->query("SELECT location_name, location_address FROM Locations WHERE is_custom_made = FALSE");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "../css/booking-styles.css">
    <script src = "scripts/cbp1.js"></script>
    <script src = "scripts/main.js"></script>
    <title>CUSTOM BOOKING</title>
</head>
<body data-is-logged-in="<?php echo $isLoggedIn ? 'true' : 'false'; ?>">
    <h1>Book A Custom One-Day Itinerary</h1>
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
    <br><hr>
    <div>
        <form id = "bookingform" action="customform-p1.php" method="post">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_SESSION['date'] ?? ''); ?>" required>
            <br><br>
            <label for = "pickuptime">Pickup Time: </label>
            <input type="time" id="pickuptime" name="pickuptime" value="<?php echo htmlspecialchars($_SESSION['pickuptime'] ?? ''); ?>" required>
            <label for = "dropofftime">Dropoff Time: </label>
            <input type="time" id="dropofftime" name="dropofftime" value="<?php echo htmlspecialchars($_SESSION['dropofftime'] ?? ''); ?>" required>
            <label for="pickup">Pickup Location:</label>
            <input type="text" id="pickup" name="pickup" placeholder="Pickup Location" value="<?php echo htmlspecialchars($_SESSION['pickup'] ?? ''); ?>" required>
            <br><br>
            <hr>
            <div class = "locations">
                <div class = "location-selector">
                    <input type="text" id="searchLocation" onkeyup="findLocation()" placeholder="Search for locations...">
                    
                    <?php
                        if ($locations_result && $locations_result->num_rows > 0) {
                            while($row = $locations_result->fetch_assoc()) {
                                $name = htmlspecialchars($row['location_name']);
                                $address = htmlspecialchars($row['location_address']);
                                echo "<div class='location' data-name='{$name}' data-address='{$address}' onclick='addLocation(\"{$name}\", \"{$address}\")'>";
                                echo "<p><b>{$name}</b> - {$address}</p>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No locations found!</p>";
                        }
                    ?>

                </div>
                <div class = "selected-locations">
                    <div id="selected-locations"></div>
                    <input type="hidden" name="selected-l" id="hidden-locations">
                    <hr>
                    <div class = "additional">
                        <h4>Your destination is not on the list? Add a custom location of your choice!</h4>
                        <input type="text" id="custom-name" placeholder="Location Name">
                        <input type="text" id="custom-address" placeholder="Location Address">
                        <button type="button" onclick="addCustomLocation()">Add Location</button>
                    </div>
                </div>
            </div>
            <br><br>
            <p>Disclaimer: Custom itineraries are subject to change by SRVan Travels. Locations may be changed or removed.</p>
            <input type="submit" value="next">
        </form>
    </div>
    <script>
        const initialLocations = <?php echo json_encode($existing_itinerary); ?>;

        document.addEventListener('DOMContentLoaded', () => {
            // If we have locations from the PHP session, use them.
            // Otherwise, check local storage as a fallback for page refreshes.
            if (initialLocations && initialLocations.length > 0) {
                selectedLocations = initialLocations;
            } else {
                const savedData = localStorage.getItem(STORAGE_KEY);
                if (savedData) {
                    try {
                        selectedLocations = JSON.parse(savedData);
                    } catch(e) {
                        selectedLocations = [];
                    }
                } else {
                    selectedLocations = [];
                }
            }
            updateSelectedLocationsDisplay();
        });
    </script>
</body>
</html>
