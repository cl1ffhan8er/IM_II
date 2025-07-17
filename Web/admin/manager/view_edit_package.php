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
    <link rel="stylesheet" href="view_edit_package_styles.css">
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
    <div class="content">
        <a href="add_package.php" class="button-link">Back to Package List</a>

        <h1>EDIT PACKAGE: <?= htmlspecialchars($row['package_name']) ?></h1>

        <?php if ($success): ?>
            <p style="color: green; font-weight: bold;">Package updated successfully!</p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="package-form">

            <div class="form-row">
                <div class="form-group">
                    <label>Package Name:</label>
                    <input type="text" name="package_name" value="<?= htmlspecialchars($row['package_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Inclusions:</label>
                    <textarea name="inclusions" rows="3" required><?= htmlspecialchars($row['inclusions']) ?></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Passenger Count:</label>
                    <?php 
                        $minmax = explode('-', $row['number_of_PAX']);
                        $min = $minmax[0] ?? '';
                        $max = $minmax[1] ?? '';
                    ?>
                    <div class="passenger-range">
                        <input type="number" name="passenger_min" min="1" value="<?= htmlspecialchars($min) ?>" required>
                        <span>TO</span>
                        <input type="number" name="passenger_max" min="1" value="<?= htmlspecialchars($max) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="3" required><?= htmlspecialchars($row['description']) ?></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Route:</label>
                    <textarea name="route" rows="3" required><?= htmlspecialchars($row['route']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Current Image:</label><br>
                    <?php if (!empty($row['package_picture'])): ?>
                        <img src="../../<?= htmlspecialchars($row['package_picture']) ?>" alt="Package Image" width="150"><br>
                    <?php else: ?>
                        <div class="no-image">No Image</div>
                    <?php endif; ?>

                    <label>Change Image (Optional):</label>
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price per PAX (₱):</label>
                    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($row['price']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><input type="checkbox" name="is_available" <?= $row['is_available'] ? 'checked' : '' ?>> Mark as Available</label>
                </div>
            </div>

            <div class="form-button-container">
                <button type="submit" name="update_package">UPDATE</button>
            </div>
        </form>
    </div>
</body>
</html>