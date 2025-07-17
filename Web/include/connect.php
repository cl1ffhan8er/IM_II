<?php
$conn = new mysqli('localhost', 'root', '', 's24100314_businessdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
