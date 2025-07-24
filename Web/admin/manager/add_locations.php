<?php
require_once '../../include/connect.php';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalResult = $conn->query("SELECT COUNT(*) AS total FROM Locations WHERE is_custom_made = 0");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['location_name'];
    $address = $_POST['location_address'];

    $stmt = $conn->prepare("INSERT INTO Locations (location_name, location_address, is_custom_made) VALUES (?, ?, 0)");
    $stmt->bind_param("ss", $name, $address);
    $stmt->execute();

    header("Location: add_locations.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM Locations WHERE is_custom_made = 0 LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$locations = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Locations</title>
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

<div class="content">
    <h1>MANAGE LOCATIONS</h1>

        <form method="POST">
            <h3>Add New Location</h3>
            <label>Name: <input type="text" name="location_name" required></label><br>
            <label>Address: <input type="text" name="location_address" required></label><br>
            <button type="submit">ADD</button>
        </form>

    <h3>ALL LOCATIONS</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>ADDRESS</th>
        </tr>
        <?php while ($row = $locations->fetch_assoc()): ?>
            <tr>
                <td><?= $row['location_ID'] ?></td>
                <td><?= $row['location_name'] ?></td>
                <td><?= $row['location_address'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
        <?php endif; ?>

        <span>Page <?= $page ?> of <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
