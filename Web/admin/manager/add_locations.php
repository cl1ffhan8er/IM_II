<?php
require_once '../../include/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['location_name'];
    $address = $_POST['location_address'];

    $stmt = $conn->prepare("INSERT INTO Locations (location_name, location_address, is_custom_made) VALUES (?, ?, 0)");
    $stmt->bind_param("ss", $name, $address);
    $stmt->execute();

    header("Location: add_locations.php");
    exit;
}

$locations = $conn->query("SELECT * FROM Locations WHERE is_custom_made = 0");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Locations</title>
    <link rel="stylesheet" href="add_locations_styles.css">
</head>
<body>
    <div class="sidebar">
        <div class="nav-top">
            <h3>Admin Menu</h3>
            <a href="home.php">🏠 Bookings</a>
            <a href="add_package.php">📦 Plans</a>
            <a href="add_locations.php">📍 Locations</a>
            <a href="monthly_summary.php">📊 Monthly Summary</a>
        </div>

        <div class="nav-bottom">
            <a href="../../user/login/logout.php">🚪 Log Out</a>
        </div>
    </div>

    <div class="content">
        <h1>Manage Locations</h1>

        <form method="POST">
            <h3>Add New Location</h3>
            <label>Name: <input type="text" name="location_name" required></label><br>
            <label>Address: <input type="text" name="location_address" required></label><br>
            <button type="submit">Add Location</button>
        </form>

        <h3>All Locations</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
            </tr>
            <?php while ($row = $locations->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['location_ID'] ?></td>
                    <td><?= $row['location_name'] ?></td>
                    <td><?= $row['location_address'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
