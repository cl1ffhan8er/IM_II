<?php
require_once '../../include/connect.php';

$uploadDir_server = "../../package-images/";
$uploadDir_db = "package-images/";

if (!is_dir($uploadDir_server)) {
    mkdir($uploadDir_server, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_package'])) {
    $name = $_POST['package_name'];
    $inclusions = $_POST['inclusions'];
    $passenger_count = $_POST['passenger_min'] . '-' . $_POST['passenger_max'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $route = $_POST['route']; 
    $manager_id = 1; 

    $stmt1 = $conn->prepare("INSERT INTO Itinerary (price, type) VALUES (?, 'PACKAGE')");
    $stmt1->bind_param("d", $price);
    $stmt1->execute();
    $itineraryID = $conn->insert_id;

    $imagePath_for_db = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid("pkg_") . "." . $ext;
        $targetPath_server = $uploadDir_server . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath_server)) {
            $imagePath_for_db = $uploadDir_db . $filename;
        }
    }
    
    $stmt2 = $conn->prepare("INSERT INTO Package_Itinerary (package_id, package_name, inclusions, number_of_PAX, description, route, is_made_by_manager, package_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("isssssis", $itineraryID, $name, $inclusions, $passenger_count, $desc, $route, $manager_id, $imagePath_for_db);
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
        <h1>ADD NEW PACKAGE</h1>

        <form method="POST" enctype="multipart/form-data" class="package-form">
        <?php if (isset($_GET['success'])): ?>
            <p style="color: green; font-weight: bold;">Package added successfully!</p>
        <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="package-form">
    
        <!-- Row 1 -->
        <div class="form-row">
            <div class="form-group">
                <label>Package Name:</label>
                <input type="text" name="package_name" required>
            </div>
            <div class="form-group">
                <label>Inclusions:</label>
                <textarea name="inclusions" rows="3" required></textarea>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="form-row">
            <div class="form-group">
                <label>Passenger Count:</label>
                <div class="passenger-range">
                    <input type="number" name="passenger_min" min="1" required>
                    <span>TO</span>
                    <input type="number" name="passenger_max" min="1" required>
                </div>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="3" required></textarea>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="form-row">
            <div class="form-group">
                <label>Route:</label>
                <textarea name="route" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Attach Package Image:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
        </div>

        <!-- Row 4 -->
        <div class="form-row">
            <div class="form-group">
                <label>Price per PAX (₱):</label>
                <input type="number" step="0.01" name="price" required>
            </div>
        </div>

        <div class="form-button-container">
            <button type="submit" name="add_package">SAVE</button>
        </div>
    </form>


        <hr>

        <h2>EXISTING PACKAGES</h2>
        <div class="card-container">
            <?php while ($row = $results->fetch_assoc()): ?>
                <div class="package-card-wrapper">
                    <a href="view_edit_package.php?package_id=<?= $row['package_ID'] ?>" class="package-card-link">
                        <div class="package-card">
                            <div class="card-image">
                                <?php if (!empty($row['package_picture'])): ?>
                                    <img src="../../<?php echo htmlspecialchars($row['package_picture']); ?>" alt="<?php echo htmlspecialchars($row['package_name']); ?>">
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
