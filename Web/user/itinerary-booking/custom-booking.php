<?php
session_start();
include '../../include/connect.php'; 
$isLoggedIn = isset($_SESSION['person_ID']);
$username = $_SESSION['username'] ?? '';

$existing_itinerary = $_SESSION['booking_itinerary'] ?? [];

$locations_result = $conn->query("SELECT location_name, location_address FROM Locations WHERE is_custom_made = FALSE");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CUSTOM BOOKING</title>

  <link rel="stylesheet" href="../css/shared.css" />
  <link rel="stylesheet" href="../css/custom-forms.css?v=1" />

  <script src="scripts/cbp1.js"></script>
  <script src="scripts/main.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

</head>

<body data-is-logged-in="<?php echo $isLoggedIn ? 'true' : 'false'; ?>">

  <nav class="navbar">
      <div class="navbar-inner">
          <div class="navbar-logo">
              <img src="../images/srvanlogo.png" alt="Logo">
          </div>
          <div class="navbar-links">
                <a href="../index.php" class="nav-item">Home</a>

                <?php if ($isLoggedIn): ?>
                    <a href="../packages.php" class="nav-item">Book Package</a>
                <?php else: ?>
                    <a href="../login/login.php" class="nav-item">Book Package</a>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <a href="custom-booking.php" class="nav-item">Book Itinerary</a>
                <?php else: ?>
                    <a href="../login/login.php" class="nav-item">Book Itinerary</a>
                <?php endif; ?>

                <a href="../minor/help.php" class="nav-item">Help</a>
                <a href="../minor/about-us.php" class="nav-item">About Us</a>

                <?php if ($isLoggedIn): ?>
                    <a href="../login/logout.php" class="nav-item">Log Out</a>
                    <a href="../profile.php" class="nav-item"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="../login/login.php" class="nav-item">Log In</a>
                <?php endif; ?>
            </div>
      </div>
  </nav>

  <form id="bookingform" action="customform-p1.php" method="post">

    <div class="form-group-container">
      <div class="form-row">
        <div class="form-group">
          <label for="date">Select Date:</label>
          <input
              type="date"
              id="date"
              name="date"
              min="<?php echo date('Y-m-d'); ?>"
              value="<?php echo htmlspecialchars($_SESSION['date'] ?? ''); ?>"
              required
          />
        </div>

        <div class="form-group">
          <label for="pickuptime">Pickup Time:</label>
          <input
            type="time"
            id="pickuptime"
            name="pickuptime"
            value="<?php echo htmlspecialchars($_SESSION['pickuptime'] ?? ''); ?>"
            required
          />
        </div>

        <div class="form-group">
          <label for="dropofftime">Dropoff Time:</label>
          <input
            type="time"
            id="dropofftime"
            name="dropofftime"
            value="<?php echo htmlspecialchars($_SESSION['dropofftime'] ?? ''); ?>"
            required
          />
        </div>

        <div class="form-group">
          <label for="pickup">Pickup Location:</label>
          <input
            type="text"
            id="pickup"
            name="pickup"
            placeholder="Pickup Location"
            value="<?php echo htmlspecialchars($_SESSION['pickup'] ?? ''); ?>"
            required
          />
        </div>
      </div>
    </div>

    <div class="locations">
      <div class="location-selector">
        <input
          type="text"
          id="searchLocation"
          onkeyup="findLocation()"
          placeholder="Search for locations..."
        />

        <?php
          if ($locations_result && $locations_result->num_rows > 0) {
              while ($row = $locations_result->fetch_assoc()) {
                  $name = htmlspecialchars($row['location_name']);
                  $address = htmlspecialchars($row['location_address']);
                  echo "<div class='location' data-name='{$name}' data-address='{$address}' onclick='addLocation(\"{$name}\", \"{$address}\")'>";
                  echo "<p><b>{$name}</b> {$address}</p>";
                  echo "</div>";
              }
          } else {
              echo "<p>No locations found!</p>";
          }
        ?>
      </div>

      <div class="selected-locations">
        <div id="selected-locations"></div>
        <input type="hidden" name="selected-l" id="hidden-locations" />

        <hr />

        <div class="additional">
          <h4>Destination not in the list? Add a custom location of your choice!</h4>
          <input type="text" id="custom-name" placeholder="Location Name" />
          <input type="text" id="custom-address" placeholder="Location Address" />
          <button type="button" onclick="addCustomLocation()">Add Location</button>
        </div>
      </div>
    </div>

    <div class="disclaimer">
      <p>Disclaimer: Custom itineraries are subject to change by SRVan Travels. Locations may be changed or removed.</p>
    </div>

    <input type="submit" value="Next" />
  </form>

  <footer class="site-footer">
      <div class="footer-container">
          <div class="footer-text">SR Van Travels 2025 ©. All Rights Reserved</div>
          <div class="footer-icons">
              <a href="mailto:srvantravels@gmail.com" class="footer-icon-link" aria-label="Email">
                  <img src="../svg-icons/email.svg" alt="Email Icon" class="footer-icon">
              </a>
              <a href="https://www.facebook.com/profile.php?id=61569662235289" target="_blank" rel="noopener noreferrer" class="footer-icon-link" aria-label="Facebook">
                  <img src="../svg-icons/facebook.svg" alt="Facebook Icon" class="footer-icon">
              </a>
          </div>
      </div>
  </footer>

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

      const bookingForm = document.getElementById('bookingform');
      bookingForm.addEventListener('submit', function(event) {
        const dateInput = document.getElementById('date');
        const pickupTimeInput = document.getElementById('pickuptime');
        const dropoffTimeInput = document.getElementById('dropofftime');

        const selectedDateStr = dateInput.value;
        const pickupTimeStr = pickupTimeInput.value;
        const dropoffTimeStr = dropoffTimeInput.value;

        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

        const selectedDate = new Date(selectedDateStr + 'T00:00:00'); 

        if (dropoffTimeStr <= pickupTimeStr) {
          alert('Error: Drop-off time must be later than the pickup time.');
          event.preventDefault();
          return;
        }

        if (selectedDate.getTime() === today.getTime()) {
          const currentTimeStr = now.toTimeString().slice(0, 5); 
          if (pickupTimeStr < currentTimeStr) {
            alert('Error: Pickup time for today cannot be in the past.');
            event.preventDefault(); 
            return;
          }
        }
      });

    });

    let dragSrcEl = null;
    function setupDragEvents() {
      const items = document.querySelectorAll('.selected-location-item');
      let draggedIndex = null;

      items.forEach((item) => {
        const handle = item.querySelector('.drag-handle');

        handle.addEventListener('dragstart', (e) => {
          draggedIndex = parseInt(item.dataset.index);
          item.classList.add('dragging');
          e.dataTransfer.effectAllowed = 'move';
        });

        handle.addEventListener('dragend', () => {
          item.classList.remove('dragging');
        });

        item.addEventListener('dragover', (e) => {
          e.preventDefault();
          item.classList.add('drag-over');
        });

        item.addEventListener('dragleave', () => {
          item.classList.remove('drag-over');
        });

        item.addEventListener('drop', () => {
          const dropIndex = parseInt(item.dataset.index);
          if (draggedIndex !== null && draggedIndex !== dropIndex) {
            const moved = selectedLocations.splice(draggedIndex, 1)[0];
            selectedLocations.splice(dropIndex, 0, moved);
            updateSelectedLocationsDisplay();
          }
        });
      });
    }
  </script>
</body>
</html>