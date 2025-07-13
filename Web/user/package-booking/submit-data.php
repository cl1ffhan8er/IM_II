<?php
session_start();
include '../../include/connect.php'; 

// 1. Check for active session
if (!isset($_SESSION['person_ID'])) {
    die("Error: User is not logged in. Please log in to complete the booking.");
}

// 2. Retrieve data from POST and SESSION
$payment_type = $_POST['payment_type'] ?? die("Error: Invalid payment submission.");
$customer_id = $_SESSION['person_ID']; 

// Assuming the package_id is stored in the session from the previous page
$itinerary_id = $_SESSION['package_id'] ?? die("Error: Itinerary ID missing from session.");

// Retrieve other booking details from session
$pax = intval($_SESSION['pax'] ?? 1);
$date_of_travel = $_SESSION['date'] ?? null;
$time_for_pickup = $_SESSION['pickuptime'] ?? null;
$time_for_dropoff = $_SESSION['dropofftime'] ?? null;
$luggage = $_SESSION['luggage'] ?? 0;

// Validate that essential date/time info is present
if (!$date_of_travel || !$time_for_pickup) {
    die("Error: Essential booking details (date or pickup time) are missing.");
}

// Start a database transaction for data integrity
$conn->begin_transaction();

try {
    // Step A: Insert into Payment table to get a unique payment_ID for this specific booking.
    $payment_stmt = $conn->prepare("INSERT INTO Payment (payment_method, down_payment, payment_status) VALUES (?, 0, FALSE)");
    if (!$payment_stmt) throw new Exception("Payment prepare failed: " . $conn->error);
    $payment_stmt->bind_param("s", $payment_type);
    $payment_stmt->execute();
    $payment_id = $conn->insert_id; 
    $payment_stmt->close();

    // Step B: Fetch the customer's full name from the Person table.
    $person_stmt = $conn->prepare("SELECT name FROM Person WHERE person_ID = ?");
    if (!$person_stmt) throw new Exception("Person prepare failed: " . $conn->error);
    $person_stmt->bind_param("i", $customer_id);
    $person_stmt->execute();
    $person_result = $person_stmt->get_result();
    if ($person_result->num_rows === 0) throw new Exception("Customer not found.");
    $person = $person_result->fetch_assoc();
    $customer_name = $person['name'];
    $person_stmt->close();

    // Step C: Insert a new booking record into the Customer table.
    // This table acts as the booking log, so a new row is created for every booking.
    $customer_insert_stmt = $conn->prepare(
        "INSERT INTO Customer (customer_ID, payment_ID, itinerary_ID, customer_name, passenger_count, pickup_date, number_of_luggage, pickup_location, pickup_time, ID_Picture) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)"
    );
    if (!$customer_insert_stmt) throw new Exception("Customer insert prepare failed: " . $conn->error);
    
    // For package tours, pickup location might be standard. Using a placeholder.
    $pickup_location_placeholder = "Standard Package Pickup"; 
    
    $customer_insert_stmt->bind_param(
        "iiisisiss", 
        $customer_id, 
        $payment_id, 
        $itinerary_id,
        $customer_name,
        $pax, 
        $date_of_travel, 
        $luggage, 
        $pickup_location_placeholder,
        $time_for_pickup
    );
    $customer_insert_stmt->execute();
    $customer_insert_stmt->close();

    // Step D: Insert into Order_Details, linking to the booking via the composite key.
    // FIX: Removed the non-existent 'itinerary_ID' column from this query.
    $order_stmt = $conn->prepare(
        "INSERT INTO Order_Details (customer_ID, payment_ID, number_of_PAX, date_of_travel, time_for_pickup, time_for_dropoff, status) 
         VALUES (?, ?, ?, ?, ?, ?, 'PENDING')"
    );
    if (!$order_stmt) throw new Exception("Order_Details prepare failed: " . $conn->error);
    
    $order_stmt->bind_param("iiisss", $customer_id, $payment_id, $pax, $date_of_travel, $time_for_pickup, $time_for_dropoff);
    $order_stmt->execute();
    $order_id = $conn->insert_id; 
    $order_stmt->close();

    // If all inserts were successful, commit the transaction
    $conn->commit();
    
    // Step E: Clean up Session and Redirect
    unset(
        $_SESSION['package_id'],
        $_SESSION['date'],
        $_SESSION['pickuptime'],
        $_SESSION['dropofftime'],
        $_SESSION['pax'],
        $_SESSION['luggage']
    );
    
    header("Location: booking_confirmation.php?order_id=" . $order_id);
    exit();

} catch (Exception $e) {
    // If any error occurred, roll back the transaction and display a clear message
    $conn->rollback();
    die("Error completing booking: " . $e->getMessage());
} finally {
    $conn->close();
}
?>
