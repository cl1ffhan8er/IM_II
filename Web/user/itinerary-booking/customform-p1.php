<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['date'] = htmlspecialchars($_POST['date']);
    $_SESSION['pickup'] = htmlspecialchars($_POST['pickup']);
    $_SESSION['pickuptime'] = htmlspecialchars($_POST['pickuptime']);
    $_SESSION['dropofftime'] = htmlspecialchars($_POST['dropofftime']);

    if (isset($_POST['selected-l'])) {
        $selectedLocationsJSON = $_POST['selected-l']; 
        $decodedLocations = json_decode($selectedLocationsJSON);

        if (json_last_error() === JSON_ERROR_NONE) {
            $_SESSION['booking_itinerary'] = $decodedLocations;
        } else {
            die("Error: Invalid location data received from the form.");
        }
    } else {
        $_SESSION['booking_itinerary'] = [];
    }

    header("Location: custom-booking-p2.php"); 
    exit();

} else {
    header("Location: custom-booking.php"); 
    exit();
}
?>
