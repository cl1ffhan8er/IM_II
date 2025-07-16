<?php
session_start();
include '../../include/connect.php'; 

if (!isset($_SESSION['person_ID'])) {
    die("Error: User is not logged in. Please log in to complete the booking.");
}

$payment_type = $_POST['payment_type'] ?? die("Error: Invalid payment submission.");
$customer_id = $_SESSION['person_ID']; 
$itinerary_id = $_SESSION['package_id'] ?? die("Error: Itinerary ID missing from session.");
$pax = intval($_SESSION['pax'] ?? 1);
$date_of_travel = $_SESSION['date'] ?? null;
$time_for_pickup = $_SESSION['pickuptime'] ?? null;
$time_for_dropoff = $_SESSION['dropofftime'] ?? null;
$luggage = $_SESSION['luggage'] ?? 0;
$filePath = $_SESSION['id_filepath'];
$payment_method = $_POST['payment_type'];

if (!file_exists($filePath)) {
        die("Error: The uploaded ID file could not be found. Please go back and re-upload.");
}
$fileData = file_get_contents($filePath);

if (!$date_of_travel || !$time_for_pickup) {
    die("Error: Essential booking details (date or pickup time) are missing.");
}

$conn->begin_transaction();

try {
    // Insert Payment 
    // add downpayment test
    $payment_status = 'NOT PAID';

    $payment_sql = "INSERT INTO Payment (payment_method, down_payment, payment_status) VALUES (?, 0, ?)";
    $payment_stmt = $conn->prepare($payment_sql);
    if (!$payment_stmt) throw new Exception("Payment prepare failed: " . $conn->error);

    $payment_stmt->bind_param("ss", $payment_type, $payment_status);
    if (!$payment_stmt->execute()) throw new Exception("Payment execute failed: " . $payment_stmt->error);
    $payment_id = $conn->insert_id; 
    $payment_stmt->close();

    // Insert into Customer
    $customer_sql = "INSERT INTO Customer (
            customer_ID, 
            payment_ID, 
            number_of_PAX, 
            date_of_travel, 
            number_of_luggage, 
            ID_Picture
        ) VALUES (?, ?, ?, ?, ?, ?)";

    $customer_stmt = $conn->prepare($customer_sql);
    if (!$customer_stmt) throw new Exception("Customer prepare failed: " . $conn->error);
   
    $customer_stmt->bind_param(
        "iiisib", 
        $customer_id, 
        $payment_id, 
        $pax, 
        $date, 
        $luggage, 
        $fileData
    );

    $customer_stmt->send_long_data(5, $fileData);
    if (!$customer_stmt->execute()) throw new Exception("Customer execute failed: " . $customer_stmt->error);   
    $customer_stmt->close();

    $order_sql = "INSERT INTO Order_Details (
            customer_ID, payment_ID, driver_ID,
            itinerary_ID, number_of_PAX, date_of_travel,
            time_for_pickup, time_for_dropoff
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $order_stmt = $conn->prepare($order_sql);
    if (!$order_stmt) throw new Exception("Order_Details prepare failed: " . $conn->error);
    $driver_id = null; 
    $order_stmt->bind_param("iiiissss", $customer_id, $payment_id, $driver_id, $itinerary_id, $pax, $date_of_travel, $time_for_pickup, $time_for_dropoff);
    if(!$order_stmt->execute()) throw new Exception("Order_Details execute failed: " . $order_stmt->error);
    $order_id = $conn->insert_id;
    $order_stmt->close();

    $conn->commit();

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
    $conn->rollback();
    die("Error completing booking: " . $e->getMessage());
} finally {
    $conn->close();
}
?>
