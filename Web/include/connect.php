<?php
$conn = new mysqli('localhost', 'root', '', 'srvan2');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
