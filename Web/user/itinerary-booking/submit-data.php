<?php
session_start();
include '../../include/connect.php';

$required_sessions = [
    'person_ID',
    'fname', 'lname', 'pax', 'id_filepath', 'id_filename', 
    'luggage', 'date', 'pickup', 'pickuptime', 'dropofftime', 
    'booking_itinerary'
];

foreach ($required_sessions as $key) {
    if (!isset($_SESSION[$key])) {
        die("Error: Critical session data ('" . htmlspecialchars($key) . "') is missing. Please restart the booking process from the beginning.");
    }
}

if (isset($_POST['submit']) && isset($_POST['payment_type'])) {

    $person_id = $_SESSION['person_ID'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $pax = $_SESSION['pax'];
    $luggage = $_SESSION['luggage'];
    $date = $_SESSION['date'];
    $pickup = $_SESSION['pickup'];
    $pickuptime = $_SESSION['pickuptime'];
    $dropofftime = $_SESSION['dropofftime'];
    $filePath = $_SESSION['id_filepath'];
    $selectedLocations = $_SESSION['booking_itinerary'];
    $payment_method = $_POST['payment_type'];
    $fullName = $fname . ' ' . $lname;
    
    if (!file_exists($filePath)) {
        die("Error: The uploaded ID file could not be found. Please go back and re-upload.");
    }
    $fileData = file_get_contents($filePath);

    $conn->begin_transaction();

    try {
        // Step 1: Insert into Payment table first
        $down_payment = 500.00; // Example down payment
        $sqlPayment = "INSERT INTO Payment (payment_method, down_payment, payment_status) VALUES (?, ?, FALSE)";
        $stmtPayment = $conn->prepare($sqlPayment);
        if (!$stmtPayment) throw new Exception("Payment prepare failed: " . $conn->error);
        
        $stmtPayment->bind_param("sd", $payment_method, $down_payment);
        if (!$stmtPayment->execute()) throw new Exception("Payment execute failed: " . $stmtPayment->error);
        $payment_id = $conn->insert_id;
        $stmtPayment->close();

        // Step 2: Insert into Itinerary table
        $sqlItinerary = "INSERT INTO Itinerary (price) VALUES (?)";
        $stmtItinerary = $conn->prepare($sqlItinerary);
        if (!$stmtItinerary) throw new Exception("Itinerary prepare failed: " . $conn->error);
        
        $stmtItinerary->bind_param("d", $down_payment);
        if (!$stmtItinerary->execute()) throw new Exception("Itinerary execute failed: " . $stmtItinerary->error);
        $itinerary_id = $conn->insert_id;
        $stmtItinerary->close();

        // Step 3: Insert into Customer table with the new payment_id
        $sqlCustomers = "INSERT INTO Customer (customer_ID, itinerary_ID, payment_ID, customer_name, passenger_count, pickup_date, number_of_luggage, pickup_location, pickup_time, ID_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtCustomers = $conn->prepare($sqlCustomers);
        if (!$stmtCustomers) throw new Exception("Customer prepare failed: " . $conn->error);
        
        $null = NULL;
        $stmtCustomers->bind_param("iiisisisss", $person_id, $itinerary_id, $payment_id, $fullName, $pax, $date, $luggage, $pickup, $pickuptime, $null);
        $stmtCustomers->send_long_data(9, $fileData);
        if (!$stmtCustomers->execute()) throw new Exception("Customer execute failed: " . $stmtCustomers->error);
        $stmtCustomers->close();

        // Step 4: Insert into Order_Details with all necessary IDs
        $sqlOrder = "INSERT INTO Order_Details (customer_ID, itinerary_ID, payment_ID, number_of_PAX, date_of_travel, time_for_pickup, time_for_dropoff) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        if (!$stmtOrder) throw new Exception("Order_Details prepare failed: " . $conn->error);
        
        $stmtOrder->bind_param("iiiisss", $person_id, $itinerary_id, $payment_id, $pax, $date, $pickuptime, $dropofftime);
        if (!$stmtOrder->execute()) throw new Exception("Order_Details execute failed: " . $stmtOrder->error);
        $stmtOrder->close();

        // Step 5: Insert Itinerary Stops
        $sqlNewLocation = "INSERT INTO Locations (location_name, location_address, is_custom_made) VALUES (?, ?, TRUE)";
        $stmtNewLocation = $conn->prepare($sqlNewLocation);
        if (!$stmtNewLocation) throw new Exception("New Location prepare failed: " . $conn->error);

        $sqlItineraryStop = "INSERT INTO Itinerary_Stops (itinerary_ID, location_ID) VALUES (?, ?)";
        $stmtItineraryStop = $conn->prepare($sqlItineraryStop);
        if (!$stmtItineraryStop) throw new Exception("Itinerary_Stops prepare failed: " . $conn->error);

        foreach ($selectedLocations as $location) {
            $location_id = null;
            if ($location->isCustom) {
                $stmtNewLocation->bind_param("ss", $location->name, $location->address);
                if (!$stmtNewLocation->execute()) throw new Exception("Error inserting new custom location: " . $stmtNewLocation->error);
                $location_id = $conn->insert_id;
            } else {
                $sqlFindLocation = "SELECT location_ID FROM Locations WHERE location_name = ? AND location_address = ?";
                $stmtFind = $conn->prepare($sqlFindLocation);
                if (!$stmtFind) throw new Exception("Find Location prepare failed: " . $conn->error);
                
                $stmtFind->bind_param("ss", $location->name, $location->address);
                $stmtFind->execute();
                $result = $stmtFind->get_result();
                if ($row = $result->fetch_assoc()) {
                    $location_id = $row['location_ID'];
                }
                $stmtFind->close();
            }

            if ($location_id) {
                $stmtItineraryStop->bind_param("ii", $itinerary_id, $location_id);
                if (!$stmtItineraryStop->execute()) throw new Exception("Itinerary_Stops execute failed: " . $stmtItineraryStop->error);
            } else {
                throw new Exception("Could not find location: " . htmlspecialchars($location->name));
            }
        }
        $stmtNewLocation->close();
        $stmtItineraryStop->close();

        $conn->commit();

        unlink($filePath);
        session_unset();
        session_destroy();

        header("Location: success.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Booking failed due to a database error. Please try again. Details: " . $e->getMessage());
    } finally {
        $conn->close();
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
