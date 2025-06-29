<?php
    $config = include 'config.php';

    $conn = new mysqli(
        $config['host'],
        $config['user'],
        $config['password'],
        $config['database']
    );

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>