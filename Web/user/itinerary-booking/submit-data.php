<?php
session_start();
include '../../include/connect.php';

// A list of all required session keys for a booking to be valid.
$required_sessions = [
    'person_ID',
    'fname', 'lname', 'pax', 'id_filepath', 'id_filename', 
    'luggage', 'date', 'pickup', 'pickuptime', 'dropofftime', 
    'booking_itinerary'
];

// Check if all required data exists in the session.
foreach ($required_sessions as $key) {
    if (!isset($_SESSION[$key])) {
        die("Error: Critical session data ('" . htmlspecialchars($key) . "') is missing. Please restart the booking process.");
    }
}

// Ensure the form was submitted correctly.
if (isset($_POST['submit']) && isset($_POST['payment_type'])) {

    // Assign all session data to local variables.
    $person_id = $_SESSION['person_ID'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $pax = $_SESSION['pax'];
    $luggage = $_SESSION['luggage'];
    $date = $_SESSION['date'];
    $pickup = $_SESSION['pickup'];
    $pickuptime = $_SESSION['pickuptime'];
    // Note: The 'dropofftime' variable is available from the session but not used in the new schema's Booking table.
    // It could be stored in the Itinerary description if needed.
    $filePath = $_SESSION['id_filepath'];
    $selectedLocations = json_decode(json_encode($_SESSION['booking_itinerary']), true);
    $payment_method = $_POST['payment_type'];
    $fullName = $fname . ' ' . $lname;
    
    // Verify the uploaded ID file still exists.
    if (!file_exists($filePath)) {
        die("Error: The uploaded ID file could not be found. Please go back and re-upload.");
    }
    $fileData = file_get_contents($filePath);

    // Start a database transaction.
    $conn->begin_transaction();

    try {
        // Step 1: Insert Payment Record
        $down_payment = 500.00;
        $sqlPayment = "INSERT INTO Payment (payment_method, down_payment) VALUES (?, ?)";
        $stmtPayment = $conn->prepare($sqlPayment);
        $stmtPayment->bind_param("sd", $payment_method, $down_payment);
        $stmtPayment->execute();
        $payment_id = $conn->insert_id;
        $stmtPayment->close();

        // Step 2: Insert Itinerary Record
        // In a real application, the total price should be calculated based on locations, pax, etc.
        $total_price = 1500.00; // Example total price
        $sqlItinerary = "INSERT INTO Itinerary (price, type) VALUES (?, 'CUSTOM')";
        $stmtItinerary = $conn->prepare($sqlItinerary);
        $stmtItinerary->bind_param("d", $total_price);
        $stmtItinerary->execute();
        $itinerary_id = $conn->insert_id;
        $stmtItinerary->close();

        // Step 3: Insert Booking Record (REPLACES old 'Customer' and 'Order_Details')
        // This single query now handles the main transaction record.
        $sqlBooking = "INSERT INTO Booking (customer_ID, itinerary_ID, payment_ID, customer_name, passenger_count, pickup_date, number_of_luggage, pickup_location, pickup_time, ID_Picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtBooking = $conn->prepare($sqlBooking);
        $null = NULL; // For the BLOB data
        $stmtBooking->bind_param("iiisisisss", $person_id, $itinerary_id, $payment_id, $fullName, $pax, $date, $luggage, $pickup, $pickuptime, $null);
        $stmtBooking->send_long_data(9, $fileData); // Send the file data for the ID_Picture
        $stmtBooking->execute();
        // We don't need to get the insert_id from here unless another table needs it.
        $stmtBooking->close();

        // Step 4: Insert Custom Itinerary Record
        // This links the Itinerary to the Person who created it.
        $sqlCustomItinerary = "INSERT INTO Custom_Itinerary (custom_id, is_made_by_customer) VALUES (?, ?)";
        $stmtCustomItinerary = $conn->prepare($sqlCustomItinerary);
        $stmtCustomItinerary->bind_param("ii", $itinerary_id, $person_id);
        $stmtCustomItinerary->execute();
        $stmtCustomItinerary->close();
        
        // Step 5: Insert Itinerary Stops
        $sqlNewLocation = "INSERT INTO Locations (location_name, location_address, is_custom_made) VALUES (?, ?, TRUE)";
        $stmtNewLocation = $conn->prepare($sqlNewLocation);
        $sqlItineraryStop = "INSERT INTO Itinerary_Stops (itinerary_ID, location_ID, stop_order) VALUES (?, ?, ?)";
        $stmtItineraryStop = $conn->prepare($sqlItineraryStop);

        foreach ($selectedLocations as $index => $location) {
            $location_id = null;
            $stop_order = $index + 1; // Set the stop order based on its position

            if ($location['isCustom']) {
                $stmtNewLocation->bind_param("ss", $location['name'], $location['address']);
                $stmtNewLocation->execute();
                $location_id = $conn->insert_id;
            } else {
                $stmtFind = $conn->prepare("SELECT location_ID FROM Locations WHERE location_name = ? AND location_address = ?");
                $stmtFind->bind_param("ss", $location['name'], $location['address']);
                $stmtFind->execute();
                $result = $stmtFind->get_result();
                if ($row = $result->fetch_assoc()) {
                    $location_id = $row['location_ID'];
                }
                $stmtFind->close();
            }

            if ($location_id) {
                $stmtItineraryStop->bind_param("iii", $itinerary_id, $location_id, $stop_order);
                $stmtItineraryStop->execute();
            } else {
                throw new Exception("Could not find or create location: " . htmlspecialchars($location['name']));
            }
        }
        $stmtNewLocation->close();
        $stmtItineraryStop->close();

        // If all queries succeed, commit the changes to the database.
        $conn->commit();

        // --- SESSION CLEANUP ---
        $booking_keys_to_clear = [
            'fname', 'lname', 'pax', 'id_filepath', 'id_filename', 
            'luggage', 'date', 'pickup', 'pickuptime', 'dropofftime', 
            'booking_itinerary'
        ];

        foreach ($booking_keys_to_clear as $key) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        }
        
        // Clean up the uploaded file.
        unlink($filePath);

        // Redirect to the success page.
        header("Location: success.php");
        exit;

    } catch (Exception $e) {
        // If any query fails, roll back all changes.
        $conn->rollback();
        die("Booking failed due to a database error. Please try again. Details: " . $e->getMessage());
    } finally {
        // Always close the database connection.
        $conn->close();
    }
} else {
    // If the form was not submitted correctly, redirect to the home page.
    header("Location: ../index.php");
    exit;
}
?>