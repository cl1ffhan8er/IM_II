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
    <link rel = "stylesheet" href = "css/booking-styles.css">
    <title>CUSTOM BOOKING</title>
</head>
<body>
    <h1>Book A Custom One-Day Itinerary</h1>
    <ul>
            <li class = "nav"><a href = "index.php">Home</a></li>
            <li class = "nav">Help</li>
            <li class = "nav">About Us</li>
            <li class = "nav"><a href="login/logout.php" class = "nav">Log Out</a></li>
    </ul>
    <br><hr>
    <div>
        <form id = "bookingform" action="customform-p1.php" method="post">
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
                    <input type="text" id="searchLocation" onkeyup="findLocation()" placeholder="Search for locations...">
                    <div class = "location" data-name = "Sto. Nino Church" data-address = "Address 1"
                    onclick = "addLocation('Sto. Nino Church', 'Address 1')">
                        <p><b>Sto. Nino Church</b> Random City, Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "Oslob" data-address = "Address 2"
                    onclick = "addLocation('Oslob', 'Address 2')">
                        <p><b>Oslob</b> Random City, Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "Moalboal" data-address = "Address 3"
                    onclick = "addLocation('Moalboal', 'Address 3')">
                        <p><b>Moalboal</b> Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "TOPS" data-address = "Address 4"
                    onclick = "addLocation('TOPS', 'Address 4')">
                        <p><b>TOPS</b> Cebu City, Philippines</p>
                    </div>
                    <div class = "location" data-name = "Magellans Cross" data-address = "Address 5" 
                    onclick = "addLocation('Magellans Cross', 'Address 5')">
                        <p><b>Magellan's Cross</b> Random City, Cebu City, Philippines</p>
                    </div>
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
        const selectedLocations = [];

        function addLocation(name, address) {
            const combined = { name, address };
            const uniqueKey = name + " - " + address;

            // Prevent duplicates
            if (!selectedLocations.some(loc => loc.name === name && loc.address === address)) {
                selectedLocations.push(combined);
                updateSelectedLocationsDisplay();
            }
        }

        function addCustomLocation() {
            const nameInput = document.getElementById("custom-name");
            const addressInput = document.getElementById("custom-address");

            const name = nameInput.value.trim();
            const address = addressInput.value.trim();

            if (!name || !address) {
                alert("Please fill out both fields.");
                return;
            }

            addLocation(name, address);

            // Clear inputs
            nameInput.value = "";
            addressInput.value = "";
        }

        function updateSelectedLocationsDisplay() {
            const container = document.getElementById("selected-locations");
            const hiddenInput = document.getElementById("hidden-locations");

            container.innerHTML = "";

            selectedLocations.forEach(loc => {
                const locationDiv = document.createElement("div");
                locationDiv.className = "location";
                locationDiv.dataset.name = loc.name;
                locationDiv.dataset.address = loc.address;

                const p = document.createElement("p");
                p.innerHTML = `
                    <b>${loc.name}</b> ${loc.address}, Philippines
                    <button type="button" onclick="removeLocation('${loc.name}', '${loc.address}')">x</button>
                `;

                locationDiv.appendChild(p);
                container.appendChild(locationDiv);
            });

            // Update hidden input for form submission
                hiddenInput.value = JSON.stringify(selectedLocations);
            }
        
        function removeLocation(name, address) {
            const index = selectedLocations.findIndex(loc => loc.name === name && loc.address === address);
            if (index !== -1) {
                selectedLocations.splice(index, 1);
                updateSelectedLocationsDisplay();
            }
        }

        function findLocation() {
                const query = document.getElementById("searchLocation").value.toLowerCase();
                const locations = document.querySelectorAll(".location-selector .location");

                locations.forEach(loc => {
                    const name = loc.dataset.name.toLowerCase();
                    const address = loc.dataset.address.toLowerCase();

                    if (name.includes(query) || address.includes(query)) {
                        loc.style.display = "block";
                    } else {
                        loc.style.display = "none";
                    }
                });
            }

        window.addEventListener("DOMContentLoaded", () => {
            const saved = localStorage.getItem("selectedLocations");
            if (saved) {
                const parsed = JSON.parse(saved);
                parsed.forEach(loc => selectedLocations.push(loc));
                updateSelectedLocationsDisplay();
            }
        });

        function addLocation(name, address) {
            const existing = selectedLocations.find(loc => loc.name === name && loc.address === address);
            if (!existing) {
                selectedLocations.push({ name, address });
                updateSelectedLocationsDisplay();
                saveSelectedLocations();
            }
        }

        function addCustomLocation() {
            const name = document.getElementById("custom-name").value.trim();
            const address = document.getElementById("custom-address").value.trim();
            if (!name || !address) {
                alert("Please fill out both fields.");
                return;
            }
            addLocation(name, address);
            document.getElementById("custom-name").value = "";
            document.getElementById("custom-address").value = "";
        }

        function updateSelectedLocationsDisplay() {
            const container = document.getElementById("selected-locations");
            const hiddenInput = document.getElementById("hidden-locations");

            container.innerHTML = "";
            selectedLocations.forEach(loc => {
                const div = document.createElement("div");
                div.className = "location";
                div.innerHTML = `<p><b>${loc.name}</b> ${loc.address}</p>`;
                container.appendChild(div);
            });

            hiddenInput.value = JSON.stringify(selectedLocations);
        }

        document.getElementById("bookingform").addEventListener("submit", function () {
            localStorage.setItem("selectedLocations", JSON.stringify(selectedLocations));
        });

        window.addEventListener("beforeunload", function (e) {
            if (!document.getElementById("bookingform").dataset.submitting) {
                localStorage.removeItem("selectedLocations");
            }
        });

        document.getElementById("bookingform").addEventListener("submit", function () {
            this.dataset.submitting = "true"; 
            localStorage.setItem("selectedLocations", JSON.stringify(selectedLocations));
        });

    
    </script>

</body>
</html>