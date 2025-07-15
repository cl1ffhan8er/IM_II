<?php
require_once '../../include/connect.php';

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_package'])) {
    $name = $_POST['package_name'];
    $inclusions = $_POST['inclusions'];
    $passenger_count = $_POST['passenger_min'] . '-' . $_POST['passenger_max'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $manager_id = 1; // Replace with session value

    $stmt1 = $conn->prepare("INSERT INTO Itinerary (price, type) VALUES (?, 'PACKAGE')");
    $stmt1->bind_param("d", $price);
    $stmt1->execute();
    $itineraryID = $conn->insert_id;

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid("pkg_") . "." . $ext;
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    $stmt2 = $conn->prepare("INSERT INTO Package_Itinerary (package_id, package_name, inclusions, passenger_count, description, is_made_by_manager, package_picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("isssiss", $itineraryID, $name, $inclusions, $passenger_count, $desc, $manager_id, $imagePath);
    $stmt2->execute();

    header("Location: add_package.php?success=1");
    exit();
}

if (isset($_POST['toggle_status'])) {
    $toggleID = $_POST['toggle_id'];
    $conn->query("UPDATE Package_Itinerary SET is_available = NOT is_available WHERE package_id = $toggleID");
    header("Location: add_package.php");
    exit();
}

$results = $conn->query("SELECT pi.*, i.price FROM Package_Itinerary pi JOIN Itinerary i ON pi.package_id = i.itinerary_ID");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Package</title>
    <link rel="stylesheet" href="add_package_styles.css">
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
        <h1>Add New Package</h1>

        <?php if (isset($_GET['success'])): ?>
            <p style="color: green; font-weight: bold;">Package added successfully!</p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Package Name:</label><br>
            <input type="text" name="package_name" required><br><br>

            <label>Inclusions:</label><br>
            <textarea name="inclusions" rows="3" required></textarea><br><br>

            <label>Passenger Count:</label><br>
            <input type="number" name="passenger_min" min="1" required>
            <span style="margin: 0 10px; font-weight: bold;">TO</span>
            <input type="number" name="passenger_max" min="1" required><br><br>

            <label>Description:</label><br>
            <textarea name="description" rows="3" required></textarea><br><br>

            <label>Route:</label><br>
            <textarea name="description" rows="3" required></textarea><br><br>

            <label>Price (₱):</label><br>
            <input type="number" step="0.01" name="price" required><br><br>

            <label>Upload Image:</label><br>
            <input type="file" name="image" accept="image/*" required><br><br>

            <button type="submit" name="add_package">Add Package</button>
        </form>

        <hr>

        <h2>Existing Packages</h2>
        <div class="card-container">
            <?php while ($row = $results->fetch_assoc()): ?>
                <div class="package-card-wrapper">
                    <a href="view_edit_package.php?package_id=<?= $row['package_ID'] ?>" class="package-card-link">
                        <div class="package-card">
                            <div class="card-image">
                                <?php if (!empty($row['package_picture'])): ?>
                                    <img src="<?= $row['package_picture'] ?>" alt="Package Image">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-info">
                                <h3><?= htmlspecialchars($row['package_name']) ?></h3>
                                <p>₱<?= number_format($row['price'], 2) ?></p>
                            </div>
                        </div>
                    </a>
                    <form method="post" class="toggle-form">
                        <input type="hidden" name="toggle_id" value="<?= $row['package_ID'] ?>">
                        <button type="submit" name="toggle_status" class="toggle-btn <?= $row['is_available'] ? 'active' : 'inactive' ?>">
                            <?= $row['is_available'] ? ' Available' : ' Unavailable' ?>
                        </button>
                    </form>
                </div>

            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
