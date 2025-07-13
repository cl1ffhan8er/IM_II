<?php
session_start();
include '../../include/connect.php'; 
$isLoggedIn = isset($_SESSION['person_ID']);

$existing_itinerary = $_SESSION['booking_itinerary'] ?? [];

$locations_result = $conn->query("SELECT location_name, location_address FROM Locations WHERE is_custom_made = FALSE");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUSTOM BOOKING</title>
    <link rel="stylesheet" href="../css/shared.css">

    <link rel="stylesheet" href="../css/custom-forms.css?v=1">
    <script src="scripts/cbp1.js"></script>
    <script src="scripts/main.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&family=Spectral&display=swap" rel="stylesheet">
</head>

<body data-is-logged-in="<?php echo $isLoggedIn ? 'true' : 'false'; ?>">

    <nav class="navbar">
        <div class="navbar-inner">
            <div class="navbar-logo">
                <img src="https://placehold.co/109x107" alt="Logo">
            </div>
            <div class="navbar-links">
                <a href="#" class="nav-item">Home</a>

                <?php if ($isLoggedIn): ?>
                    <a href="packages.php" class="nav-item">Book</a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Book</a>
                <?php endif; ?>

                <a href="#" class="nav-item">Help</a>
                <a href="#" class="nav-item">About Us</a>

                <?php if ($isLoggedIn): ?>
                    <a href="login/logout.php" class="nav-item">Log Out</a>
                <?php else: ?>
                    <a href="login/login.php" class="nav-item">Log In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <hr>

    <form id="bookingform" action="customform-p1.php" method="post">
            
    <div class="form-row">
        <div class="form-group">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($_SESSION['date'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="pickuptime">Pickup Time:</label>
            <input type="time" id="pickuptime" name="pickuptime" value="<?php echo htmlspecialchars($_SESSION['pickuptime'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="dropofftime">Dropoff Time:</label>
            <input type="time" id="dropofftime" name="dropofftime" value="<?php echo htmlspecialchars($_SESSION['dropofftime'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="pickup">Pickup Location:</label>
            <input type="text" id="pickup" name="pickup" placeholder="Pickup Location" value="<?php echo htmlspecialchars($_SESSION['pickup'] ?? ''); ?>" required>
        </div>
    </div>

        <div class="locations">
            <div class="location-selector">
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

            <div class="selected-locations">
                <div id="selected-locations"></div>
                <input type="hidden" name="selected-l" id="hidden-locations">

                <div class="additional">
                    <h4>Your destination is not on the list? Add a custom location of your choice!</h4>
                    <input type="text" id="custom-name" placeholder="Location Name">
                    <input type="text" id="custom-address" placeholder="Location Address">
                    <button type="button" onclick="addCustomLocation()">Add Location</button>
                </div>
            </div>
        </div>

        <p>Disclaimer: Custom itineraries are subject to change by SRVan Travels. Locations may be changed or removed.</p>
        <input type="submit" value="Next">
    </form>

    <script>
        const initialLocations = <?php echo json_encode($existing_itinerary); ?>;

        document.addEventListener('DOMContentLoaded', () => {
            if (initialLocations && initialLocations.length > 0) {
                selectedLocations = initialLocations;
            } else {
                const savedData = localStorage.getItem(STORAGE_KEY);
                selectedLocations = savedData ? JSON.parse(savedData) : [];
            }
            updateSelectedLocationsDisplay();
        });
    </script>
</body>
</html>
