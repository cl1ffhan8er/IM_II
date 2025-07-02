<?php
    session_start();
    include '../include/connect.php';
    $isLoggedIn = isset($_SESSION['personID']);

    /* uncomment after package db is added
    $sql = "SELECT * FROM package";
    $result = $conn->query($sql);
    */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "booking-styles.css">
    <title>CUSTOM BOOKING</title>
</head>
<body>
    <h1>Book A Custom One-Day Itinerary</h1>
    <br><hr>
    <div>
        <form action="customform.php" method="post">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" required>
            <br><br>
            <label for="vehicle">Select Vehicle:</label>
            <select id="vehicle" name="vehicle" required>
                <option value="van">Van</option>
                <option value="car">Car</option>
            </select>
            <label for="pickup">Pickup Location:</label>
            <input type="text" id="pickup" name="pickup" placeholder="Pickup Location" required>
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" placeholder="Destination" required>
            <br><br>
            <hr>
            <div class = "locations">
                <div class = "location-selector">
                    <div class = "location" data-name = "Location 1" data-address = "Address 1"
                    onclick = "addLocation('Location 1', 'Address 1')">
                        <p><b>Location 1</b> Random City, Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "Location 2" data-address = "Address 2"
                    onclick = "addLocation('Location 2', 'Address 2')">
                        <p><b>Location 2</b> Random City, Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "Location 3" data-address = "Address 3"
                    onclick = "addLocation('Location 3', 'Address 3')">
                        <p><b>Location 3</b> Random City, Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "Location 4" data-address = "Address 4"
                    onclick = "addLocation('Location 4', 'Address 4')">
                        <p><b>Location 4</b> Random City, Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "Location 5" data-address = "Address 5" 
                    onclick = "addLocation('Location 5', 'Address 5')">
                        <p><b>Location 5</b> Random City, Cebu City, Philippines</p>
                    </div>
                </div>
                <div class = "selected-locations">
                    <div id="selected-locations"></div>
                    <input type="hidden" name="selected-l" id="hidden-locations">
                </div>
            </div>
            <br><br>
            <input type="submit" value="book">
        </form>
    </div>

    <script>
        const selectedLocations = [];

        function addLocation(name, address) {
            const key = `${name}-${address}`;
            const index = selectedLocations.findIndex(loc => loc.key === key);

            if (index > -1) {
                // Unselect if already selected
                selectedLocations.splice(index, 1);
                document.querySelectorAll('.location').forEach(loc => {
                    if (loc.getAttribute('data-name') === name && loc.getAttribute('data-address') === address) {
                        loc.classList.remove('selected');
                    }
                });
            } else {
                // Add to selected list
                selectedLocations.push({ key, name, address });
                document.querySelectorAll('.location').forEach(loc => {
                    if (loc.getAttribute('data-name') === name && loc.getAttribute('data-address') === address) {
                        loc.classList.add('selected');
                    }
                });
            }

            updateSelectedLocations();
        }

        function updateSelectedLocations() {
            const selectedDiv = document.getElementById("selected-locations");
            const hiddenInput = document.getElementById("hidden-locations");

            // Display the selections
            selectedDiv.innerHTML = "";
            selectedLocations.forEach(loc => {
                const div = document.createElement("div");
                div.classList = "location";
                div.innerHTML = `
                    <p><b>${loc.name}  </b>${loc.address}</p>
                `;
                selectedDiv.appendChild(div);
            });

            // Store in hidden input as JSON
            hiddenInput.value = JSON.stringify(selectedLocations);
        }
    </script>

</body>
</html>