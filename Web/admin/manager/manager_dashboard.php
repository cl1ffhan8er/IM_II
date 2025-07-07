<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>ADMIN DASHBOARD</h1>
    <a href = "../user/login/logout.php">Log Out</a>
</body>
</html>
---->

<?php
session_start();

// ✅ Correct path to DB connection
require_once '../../include/connect.php'; // or config.php if that’s where connection is set up

// ============ ITINERARY STOP HANDLING ============
if (isset($_POST['action']) && $_POST['action'] === 'add_stop') {
    $stmt = $conn->prepare("INSERT INTO Itinerary_Stops (itinerary_ID, location_name, stop_order) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $_POST['itinerary_ID'], $_POST['location_name'], $_POST['stop_order']);
    $stmt->execute();
}

if (isset($_POST['action']) && $_POST['action'] === 'delete_stop') {
    $stmt = $conn->prepare("DELETE FROM Itinerary_Stops WHERE itinerary_stop_ID = ?");
    $stmt->bind_param("i", $_POST['itinerary_stop_ID']);
    $stmt->execute();
}

if (isset($_POST['action']) && $_POST['action'] === 'modify_stop') {
    $stmt = $conn->prepare("UPDATE Itinerary_Stops SET location_name = ?, stop_order = ? WHERE itinerary_stop_ID = ?");
    $stmt->bind_param("sii", $_POST['location_name'], $_POST['stop_order'], $_POST['itinerary_stop_ID']);
    $stmt->execute();
}

// ============ PACKAGE HANDLING ============
if (isset($_POST['action']) && $_POST['action'] === 'add_package') {
    $stmt = $conn->prepare("INSERT INTO Itinerary_Packages (itinerary_ID, description) VALUES (?, ?)");
    $stmt->bind_param("is", $_POST['itinerary_ID'], $_POST['description']);
    $stmt->execute();
}

if (isset($_POST['action']) && $_POST['action'] === 'delete_package') {
    $stmt = $conn->prepare("DELETE FROM Itinerary_Packages WHERE package_ID = ?");
    $stmt->bind_param("i", $_POST['package_ID']);
    $stmt->execute();
}

// ============ DRIVER ASSIGNMENT ============
if (isset($_POST['action']) && $_POST['action'] === 'assign_driver') {
    $stmt = $conn->prepare("UPDATE Itinerary SET driver_ID = ? WHERE itinerary_ID = ?");
    $stmt->bind_param("ii", $_POST['driver_ID'], $_POST['itinerary_ID']);
    $stmt->execute();
}
?>

<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // You can customize this key
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href="admin_dashboard_styles.css">
    <title>Manager Dashboard</title>
</head>
<body>
    <h1>MANAGER DASHBOARD</h1>
    <a href="../user/login/logout.php">Log Out</a>
    <ul class = "nav">
        <?php if ($isLoggedIn): ?>
            <a href="../u.php" class="nav"><li>Home</li></a>
        <?php else: ?>
            <a href="../user/login/login.php" class="nav"><li>Home</li></a>
        <?php endif; ?>
        <?php if ($isLoggedIn): ?>
            <a href="../user/login/login.php" class="nav"><li>Packages</li></a>
        <?php else: ?>
            <a href="../user/login/login.php" class="nav"><li>Packages</li></a>
        <?php endif; ?>
        <?php if ($isLoggedIn): ?>
            <a href="../user/login/login.php" class="nav"><li>Log Out</li></a>
        <?php else: ?>
            <a href="../user/login/login.php" class="nav"><li>Log In</li></a>
        <?php endif; ?>
    </ul>
    <hr>

    <h2>Itinerary Stops</h2>
    <form method="post">
        <input type="hidden" name="action" value="add_stop">
        <label>Itinerary ID: <input type="number" name="itinerary_ID" required></label><br>
        <label>Location Name: <input type="text" name="location_name" required></label><br>
        <label>Stop Order: <input type="number" name="stop_order" required></label><br>
        <button type="submit">Add Stop</button>
    </form>

    <form method="post">
        <input type="hidden" name="action" value="modify_stop">
        <label>Stop ID to Modify: <input type="number" name="itinerary_stop_ID" required></label><br>
        <label>New Location Name: <input type="text" name="location_name" required></label><br>
        <label>New Stop Order: <input type="number" name="stop_order" required></label><br>
        <button type="submit">Modify Stop</button>
    </form>

    <form method="post">
        <input type="hidden" name="action" value="delete_stop">
        <label>Stop ID to Delete: <input type="number" name="itinerary_stop_ID" required></label><br>
        <button type="submit">Delete Stop</button>
    </form>

    <hr>
    <h2>Packages</h2>
    <form method="post">
        <input type="hidden" name="action" value="add_package">
        <label>Itinerary ID: <input type="number" name="itinerary_ID" required></label><br>
        <label>Description: <input type="text" name="description" required></label><br>
        <button type="submit">Add Package</button>
    </form>

    <form method="post">
        <input type="hidden" name="action" value="delete_package">
        <label>Package ID to Delete: <input type="number" name="package_ID" required></label><br>
        <button type="submit">Delete Package</button>
    </form>

    <hr>
    <h2>Assign Driver</h2>
    <form method="post">
        <input type="hidden" name="action" value="assign_driver">
        <label>Itinerary ID: <input type="number" name="itinerary_ID" required></label><br>
        <label>Driver ID: <input type="number" name="driver_ID" required></label><br>
        <button type="submit">Assign Driver</button>
    </form>
</body>
</html>