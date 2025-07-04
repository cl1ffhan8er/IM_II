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
    <h1>PAYMENT</h1>
    
</body>
</html>