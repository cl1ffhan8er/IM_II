<?php
require_once '../../include/connect.php';

if (!isset($_GET['package_id'])) {
    echo "No package ID provided.";
    exit;
}

$package_id = $_GET['package_id'];
$success = false; // Initialize success flag

// Handle update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_package'])) {
    $name = $_POST['package_name'];
    $inclusions = $_POST['inclusions'];
    $passenger_count = $_POST['passenger_count'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $route = $_POST['route']; 
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    $stmtPrice = $conn->prepare("UPDATE Itinerary SET price = ? WHERE itinerary_ID = ?");
    $stmtPrice->bind_param("di", $price, $package_id);
    $stmtPrice->execute();

    // Handle optional image upload
    $imageQuery = "";
    $imagePath_for_db = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir_server = "../../package-images/";
        $uploadDir_db = "package-images/";

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid("pkg_") . "." . $ext;
        $targetPath_server = $uploadDir_server . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath_server)) {
            $imageQuery = ", package_picture = ?";
            $imagePath_for_db = $uploadDir_db . $filename;
        }
    }

    if ($imageQuery) {
        $stmt = $conn->prepare("UPDATE Package_Itinerary SET package_name = ?, inclusions = ?, number_of_PAX = ?, description = ?, route = ?, is_available = ? $imageQuery WHERE package_id = ?");
        $stmt->bind_param("sssssisi", $name, $inclusions, $passenger_count, $desc, $route, $is_available, $imagePath_for_db, $package_id);
    } else {
        $stmt = $conn->prepare("UPDATE Package_Itinerary SET package_name = ?, inclusions = ?, number_of_PAX = ?, description = ?, route = ?, is_available = ? WHERE package_id = ?");
        $stmt->bind_param("ssssii", $name, $inclusions, $passenger_count, $desc, $route, $is_available, $package_id);
    }
    
    if ($stmt->execute()) {
        $success = true;
    }
}

// Fetch latest package details to display on the form
$stmt = $conn->prepare("SELECT pi.*, i.price FROM Package_Itinerary pi JOIN Itinerary i ON pi.package_id = i.itinerary_ID WHERE pi.package_id = ?");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Package not found.";
    exit;
}
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View/Edit Package</title>
    <link rel="stylesheet" href="add_locations_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../user/images/srvanlogo.png" alt="Logo" class="sidebar-logo">
            <span class="admin-label">Admin Menu</span>
        </div>

        <div class="nav-top">
            <a href="home.php">BOOKINGS</a>
            <a href="add_package.php">PLANS</a>
            <a href="add_locations.php">LOCATIONS</a>
            <a href="monthly_summary.php">MONTHLY SUMMARY</a>
        </div>

        <div class="nav-bottom">
            <a href="../../user/login/logout.php" class="logout">Log Out</a>
        </div>
    </div>
    
    <h1>Edit Package: <?= htmlspecialchars($row['package_name']) ?></h1>

    <?php if ($success): ?>
        <p style="color: green; font-weight: bold;">Package updated successfully!</p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Package Name:</label><br>
        <input type="text" name="package_name" value="<?= htmlspecialchars($row['package_name']) ?>" required><br><br>

        <label>Inclusions:</label><br>
        <textarea name="inclusions" rows="4" required><?= htmlspecialchars($row['inclusions']) ?></textarea><br><br>

        <label>Passenger Count (e.g., 1-10):</label><br>
        <input type="text" name="passenger_count" value="<?= htmlspecialchars($row['number_of_PAX']) ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="4" required><?= htmlspecialchars($row['description']) ?></textarea><br><br>
        
        <label>Route:</label><br>
        <textarea name="route" rows="4" required><?= htmlspecialchars($row['route']) ?></textarea><br><br>

        <label>Price (₱):</label><br>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($row['price']) ?>" required><br><br>

        <label>Current Image:</label><br>
        <?php if (!empty($row['package_picture'])): ?>
            <img src="../../<?= htmlspecialchars($row['package_picture']) ?>" alt="Package Image" width="150"><br>
        <?php else: ?>
            No Image<br>
        <?php endif; ?>

        <label>Change Image (Optional):</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <label><input type="checkbox" name="is_available" <?= $row['is_available'] ? 'checked' : '' ?>> Mark as Available</label><br><br>

        <button type="submit" name="update_package">Update Package</button>
    </form>
    <br>
    <a href="add_package.php">← Back to Package List</a>
</body>
</html>