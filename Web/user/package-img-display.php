<?php

include '../include/connect.php';

if (!isset($_GET['id'])) {
    exit('No image ID provided.');
}

$package_id = $_GET['id'];

$stmt = $conn->prepare("SELECT package_picture FROM Package_Itinerary WHERE package_id = ?");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($imageData);
    $stmt->fetch();
    //header("Content-Type: image/png"); 

    echo $imageData;
}

$stmt->close();
$conn->close();
?>