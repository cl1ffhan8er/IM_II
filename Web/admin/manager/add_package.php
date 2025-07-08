<?php
require_once '../../include/connect.php';

// Make sure uploads folder exists
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_package'])) {
    $name = $_POST['package_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $manager_id = 1; // Replace this with $_SESSION['manager_ID'] later if logged in

    // 1. Insert into Itinerary (supertype)
    $stmt1 = $conn->prepare("INSERT INTO Itinerary (price, type) VALUES (?, 'PACKAGE')");
    $stmt1->bind_param("d", $price);
    $stmt1->execute();
    $itineraryID = $conn->insert_id;

    // 2. Handle file upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid("pkg_") . "." . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    // 3. Insert into Package_Itinerary (subtype)
    $stmt2 = $conn->prepare("INSERT INTO Package_Itinerary (package_id, package_name, is_made_by_manager, package_picture, description) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("isiss", $itineraryID, $name, $manager_id, $imagePath, $desc);
    $stmt2->execute();

    $success = "Package added successfully!";

    header("Location: add_package.php?success=1");
    exit();
}

if (isset($_POST['toggle_status'])) {
    $toggleID = $_POST['toggle_id'];

    // Flip the current status
    $conn->query("UPDATE Package_Itinerary 
                  SET is_available = NOT is_available 
                  WHERE package_id = $toggleID");
}

// Fetch all existing packages
$results = $conn->query("
    SELECT pi.*, i.price 
    FROM Package_Itinerary pi
    JOIN Itinerary i ON pi.package_id = i.itinerary_ID
");
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
        </div>

        <div class="nav-bottom">
            <a href="../../user/login/logout.php">🚪 Log Out</a>
        </div>
    </div>

    <div class="content">
        <h1>Add New Package</h1>

        <?php if (!empty($success)): ?>
            <p style="color: green; font-weight: bold;"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Package Name:</label><br>
            <input type="text" name="package_name" required><br><br>

            <label>Description:</label><br>
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

                        <form method="post" class="toggle-form">
                            <input type="hidden" name="toggle_id" value="<?= $row['package_id'] ?>">
                            <button type="submit" name="toggle_status" class="toggle-btn <?= $row['is_available'] ? 'active' : 'inactive' ?>">
                                <?= $row['is_available'] ? ' Available' : ' Unavailable' ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

