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
    <title>CUSTOM BOOKING - STEP 2</title>
</head>
<body>
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
    <div class = "locations">
        <div class = "form2">
            <form id = "bookingform2" action = "customform-p2.php" method="post" enctype="multipart/form-data">
                <label for = "fname">First Name:</label>
                <input type = "text" id = "fname" name = "fname" placeholder="First Name" value="<?php echo htmlspecialchars($_SESSION['fname'] ?? ''); ?>" required>
                <label for = "lname">Last Name:</label>
                <input type = "text" id = "lname" name = "lname" placeholder="Last Name" value="<?php echo htmlspecialchars($_SESSION['lname'] ?? ''); ?>" required>
                <br>
                <label for = "pax">Number of Party Members</label>
                <input type = "number" id = "pax" name = "pax" placeholder="Number of Party Members" value="<?php echo htmlspecialchars($_SESSION['pax'] ?? ''); ?>" required>
                <br>
                <label for = "luggage">Number of Luggage:</label>
                <input type = "number" id = "luggage" name = "luggage" placeholder="Number of Luggage" value="<?php echo htmlspecialchars($_SESSION['luggage'] ?? ''); ?>" required>
                <br><br>
                <label for = "comments">Comments/Others: </label>
                <input type = "text" id = "comments" name = "comments" placeholder="Comments/Others" value="<?php echo htmlspecialchars($_SESSION['comments'] ?? ''); ?>">
                <br>
                <label for = "id">Attach Official ID (jpg / png): </label>
                <input type="file" name="id" accept=".jpg, .jpeg, .png" required>
                <br><br>
                <button type="submit">Submit</button>
                <button type="button" onclick="history.back()">Back</button>
            </form>
        </div>
    </div>

    <!-- <script>
        const selectedLocations = <?//php echo json_encode($booking_itinerary); ?>;

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
    </script> !-->
</body>
</html>
