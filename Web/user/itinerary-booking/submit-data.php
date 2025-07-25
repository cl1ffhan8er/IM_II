
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        $down_payment = 500.00;
        $payment_status = "NOT PAID";

        $sqlPayment = "
            INSERT INTO Payment (payment_method, down_payment, payment_status)
            VALUES (?, ?, ?)
        ";
        $stmtPayment = $conn->prepare($sqlPayment);
        if (!$stmtPayment) throw new Exception("Payment prepare failed: " . $conn->error);

        $stmtPayment->bind_param("sds", $payment_method, $down_payment, $payment_status);
        if (!$stmtPayment->execute()) throw new Exception("Payment execute failed: " . $stmtPayment->error);
        $payment_id = $conn->insert_id;
        $stmtPayment->close();

        $sqlItinerary = "INSERT INTO Itinerary (price, type) VALUES (?, 'CUSTOM')";
        $stmtItinerary = $conn->prepare($sqlItinerary);
        if (!$stmtItinerary) throw new Exception("Itinerary prepare failed: " . $conn->error);

        $stmtItinerary->bind_param("d", $down_payment);
        if (!$stmtItinerary->execute()) throw new Exception("Itinerary execute failed: " . $stmtItinerary->error);
        $itinerary_id = $conn->insert_id;
        $stmtItinerary->close();
        
        $sqlCustomers = "INSERT INTO Customer (
            customer_ID, payment_ID,
            number_of_PAX, date_of_travel,
            number_of_luggage, ID_Picture
        ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmtCustomers = $conn->prepare($sqlCustomers);
        if (!$stmtCustomers) throw new Exception("Customer prepare failed: " . $conn->error);

        $stmtCustomers->bind_param("iiisib", 
            $person_id, 
            $payment_id, 
            $pax, 
            $date, 
            $luggage, 
            $fileData
        );

        // send file data (the blob)
        $stmtCustomers->send_long_data(5, $fileData); // Index 7 (zero-based) is 8th parameter (ID_Picture)

        if (!$stmtCustomers->execute()) throw new Exception("Customer execute failed: " . $stmtCustomers->error);
        $stmtCustomers->close();


        $sqlCustom = "INSERT INTO Custom_Itinerary (custom_ID, is_made_by_customer) VALUES (?, ?)";
        $stmtCustom = $conn->prepare($sqlCustom);
        if (!$stmtCustom) throw new Exception("Custom Itinerary prepare failed: " . $conn->error);
        $stmtCustom->bind_param("ii", $itinerary_id, $person_id);
        if (!$stmtCustom->execute()) throw new Exception("Custom Itinerary execute failed: " . $stmtCustom->error);
        $stmtCustom->close();

        $sqlOrder = "INSERT INTO Order_Details (
            customer_ID, payment_ID, driver_ID,
            itinerary_ID, number_of_PAX, date_of_travel,
            time_for_pickup, time_for_dropoff
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        if (!$stmtOrder) throw new Exception("Order_Details prepare failed: " . $conn->error);
        $driver_id = null;
        $stmtOrder->bind_param("iiiissss", $person_id, $payment_id, $driver_id, $itinerary_id, $pax, $date, $pickuptime, $dropofftime);
        if (!$stmtOrder->execute()) throw new Exception("Order_Details execute failed: " . $stmtOrder->error);
        $order_id = $conn->insert_id;
        $stmtOrder->close();

        $sqlNewLocation = "INSERT INTO Locations (location_name, location_address, is_custom_made) VALUES (?, ?, TRUE)";
        $stmtNewLocation = $conn->prepare($sqlNewLocation);
        if (!$stmtNewLocation) throw new Exception("New Location prepare failed: " . $conn->error);

        $sqlItineraryStop = "INSERT INTO Itinerary_Stops (custom_ID, stop_order, location_ID) VALUES (?, ?, ?)";
        $stmtItineraryStop = $conn->prepare($sqlItineraryStop);
        if (!$stmtItineraryStop) throw new Exception("Itinerary_Stops prepare failed: " . $conn->error);

        $stop_order = 1;
        foreach ($selectedLocations as $location) {
            $location_id = null;

            if ($location['isCustom']) {
                $stmtNewLocation->bind_param("ss", $location['name'], $location['address']);
                if (!$stmtNewLocation->execute()) throw new Exception("Error inserting new custom location: " . $stmtNewLocation->error);
                $location_id = $conn->insert_id;
            } else {
                $sqlFindLocation = "SELECT location_ID FROM Locations WHERE location_name = ?";
                $stmtFind = $conn->prepare($sqlFindLocation);
                if (!$stmtFind) throw new Exception("Find Location prepare failed: " . $conn->error);
                $stmtFind->bind_param("s", $location['name']);

                $stmtFind->execute();
                $result = $stmtFind->get_result();
                if ($row = $result->fetch_assoc()) {
                    $location_id = $row['location_ID'];
                }
                $stmtFind->close();
            }

            if ($location_id) {
                $stmtItineraryStop->bind_param("iii", $itinerary_id, $stop_order, $location_id);
                if (!$stmtItineraryStop->execute()) throw new Exception("Itinerary_Stops execute failed: " . $stmtItineraryStop->error);
                $stop_order++;
            } else {
                throw new Exception("Could not find or create location: " . htmlspecialchars($location['name']));
            }
        }

        $stmtNewLocation->close();
        $stmtItineraryStop->close();

        $conn->commit();

        if (isset($_SESSION['id_filepath']) && file_exists($_SESSION['id_filepath'])) {
            unlink($_SESSION['id_filepath']);
        }
        
        $booking_keys_to_clear = [
            'booking_type', 'package_id', 'package_name', 'package_price',
            'booking_itinerary', 'fname', 'lname', 'pax', 'date', 'pickup',
            'pickuptime', 'dropofftime', 'luggage', 'comments', 
            'id_filepath', 'id_filename'
        ];
        foreach ($booking_keys_to_clear as $key) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        }

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
