<?php
require_once '../../include/connect.php';

if (!isset($_GET['package_id'])) {
    echo "No package ID provided.";
    exit;
}

$package_id = $_GET['package_id'];

// Handle update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_package'])) {
    $name = $_POST['package_name'];
    $inclusions = $_POST['inclusions'];
    $passenger_count = $_POST['passenger_count'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    // Update Itinerary price
    $stmtPrice = $conn->prepare("UPDATE Itinerary SET price = ? WHERE itinerary_ID = ?");
    $stmtPrice->bind_param("di", $price, $package_id);
    $stmtPrice->execute();

    // Handle optional image upload
    $imageQuery = "";
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid("pkg_") . "." . $ext;
        $uploadDir = "uploads/";
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imageQuery = ", package_picture = ?";
            $imagePath = $targetPath;
        }
    }

    if ($imageQuery) {
        $stmt = $conn->prepare("UPDATE Package_Itinerary SET package_name = ?, inclusions = ?, passenger_count = ?, description = ?, is_available = ? $imageQuery WHERE package_id = ?");
        $stmt->bind_param("ssisisi", $name, $inclusions, $passenger_count, $desc, $is_available, $imagePath, $package_id);
    } else {
        $stmt = $conn->prepare("UPDATE Package_Itinerary SET package_name = ?, inclusions = ?, passenger_count = ?, description = ?, is_available = ? WHERE package_id = ?");
        $stmt->bind_param("ssisii", $name, $inclusions, $passenger_count, $desc, $is_available, $package_id);
    }
    $stmt->execute();
    $success = true;
}

// Fetch package details
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

    <?php if (!empty($success)): ?>
        <p style="color: green; font-weight: bold;">Package updated successfully!</p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Package Name:</label><br>
        <input type="text" name="package_name" value="<?= htmlspecialchars($row['package_name']) ?>" required><br><br>

        <label>Inclusions:</label><br>
        <input type="text" name="inclusions" value="<?= htmlspecialchars($row['inclusions']) ?>" required><br><br>

        <label>Passenger Count:</label><br>
        <input type="number" name="passenger_count" min="1" value="<?= $row['passenger_count'] ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="3" required><?= htmlspecialchars($row['description']) ?></textarea><br><br>

        <label>Price (₱):</label><br>
        <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" required><br><br>

        <label>Current Image:</label><br>
        <?php if (!empty($row['package_picture'])): ?>
            <img src="<?= $row['package_picture'] ?>" width="150"><br>
        <?php else: ?>
            No Image<br>
        <?php endif; ?>

        <label>Change Image:</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <label><input type="checkbox" name="is_available" <?= $row['is_available'] ? 'checked' : '' ?>> Mark as Available</label><br><br>

        <button type="submit" name="update_package">Update Package</button>
    </form>
    <br>
    <a href="add_package.php">← Back to Package List</a>
</body>
</html>
