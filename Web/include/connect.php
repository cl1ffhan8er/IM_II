<?php
$conn = new mysqli('localhost', 's24100314_businessdb', 'iloysicliff', 's24100314_businessdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
