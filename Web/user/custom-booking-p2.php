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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
    <div class = "locations">
        <div class = "form2">
            <form id = "bookingform2" action = "customform-p2.php" method="post">
                <label for = "fname">First Name:</label>
                <input type = "text" id = "fname" name = "fname" placeholder="First Name" required>
                <label for = "lname">Last Name:</label>
                <input type = "text" id = "lname" name = "lname" placeholder="Last Name" required>
                <br>
                <label for = "pax">Number of Party Members</label>
                <input type = "number" id = "pax" name = "pax" placeholder="Number of Party Members" required>
                <br>
                <label for="date">Pick Up Date:</label>
                <input type="date" id="date" name="date" required>
                <br>
                <label for = "luggage">Number of Luggage:</label>
                <input type = "number" id = "luggage" name = "luggage" placeholder="Number of Luggage" required>
                <br><br>
                <label for = "comments">Comments/Others: </label>
                <input type = "text" id = "comments" name = "comments" placeholder="Comments/Others">
                <br>
                <label for = "id">Attach Official ID (jpg / png): </label>
                <input type="file" name="id" accept="image/jpg, image/png" required>
                <br><br>
                <div class="g-recaptcha" data-sitekey="6LcnWncrAAAAAL2LbA0rX9KktD7JuOVPMgtreV4H"></div>
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class = "selected-locations">
            <p>CHOSEN PIT STOPS</p>
            <div id="selected-locations-part2"></div>
        </div>
    </div>

    <script>
        let selectedLocations = [];

        function loadLocations() {
            const saved = localStorage.getItem("selectedLocations");
            if (saved) {
                selectedLocations = JSON.parse(saved);
                updateDisplay();
            }
        }

        function updateDisplay() {
            const container = document.getElementById("selected-locations-part2");
            container.innerHTML = '';

            selectedLocations.forEach((loc, index) => {
                const div = document.createElement("div");
                div.className = "location-item";
                div.innerHTML = `
                    <p><b>${loc.name}</b> ${loc.address}
                    <button type="button" onclick="removeLocation(${index})">x</button></p>
                `;
                container.appendChild(div);
            });
        }

        function removeLocation(index) {
            selectedLocations.splice(index, 1);
            localStorage.setItem("selectedLocations", JSON.stringify(selectedLocations));
            updateDisplay();
        }

        // Load on page load
        window.addEventListener("DOMContentLoaded", loadLocations);
    </script>
</body>
</html>