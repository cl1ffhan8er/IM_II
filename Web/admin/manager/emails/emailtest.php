<?php $orderID = $_GET['order_ID'] ?? null; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email Test</title>
    <link rel="stylesheet" href="emailtest_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../../user/images/srvanlogo.png" alt="Logo" class="sidebar-logo">
            <span class="admin-label">Admin Menu</span>
        </div>

        <div class="nav-top">
            <a href="../home.php">BOOKINGS</a>
            <a href="../add_package.php">PLANS</a>
            <a href="../add_locations.php">LOCATIONS</a>
            <a href="../monthly_summary.php">MONTHLY SUMMARY</a>
        </div>

        <div class="nav-bottom">
            <a href="../../../user/login/logout.php" class="logout">Log Out</a>
        </div>
    </div>

    <h1>SEND EMAIL</h1>
    <a href="javascript:history.back()">← Go Back</a>

    <div class="email-layout">
        <form id="contact" action="mail.php" method="post">
            <h1>CONTACT FORM</h1>

            <fieldset>
                <input placeholder="Your Name" name="name" type="text" tabindex="1" autofocus>
            </fieldset>

            <fieldset>
                <input placeholder="Your Email Address" name="email" type="email" tabindex="2">
            </fieldset>

            <fieldset>
                <label for="template">Reason for Contact:</label><br>
                <select id="template" name="template" tabindex="3" required>
                    <option value="itinerary_change">Itinerary Change</option>
                    <option value="itinerary_approval">Itinerary Approval</option>
                    <option value="reject">Reject Booking</option>
                </select>
            </fieldset>

            <fieldset>
                <input placeholder="Subject" name="subject" type="text" required>
            </fieldset>

            <fieldset>
                <textarea name="message" placeholder="Message..." required></textarea>
            </fieldset>

            <fieldset>
                <button type="submit" name="send" id="contact-submit">Submit Now</button>
            </fieldset>
            <input type="hidden" name="order_ID" value="<?= $orderID ?>">
        </form>

        <div class="order-info-box">
            <h2>ORDER DETAILS</h2>
            <p><strong>Order ID:</strong> <?= htmlspecialchars($orderID) ?></p>
            <p><strong>Customer Name:</strong> Juan Dela Cruz</p>
            <p><strong>Email:</strong> juan@example.com</p>
            <p><strong>Destination:</strong> Baguio</p>
            <p><strong>Date:</strong> July 20, 2025</p>
            <p><strong>Status:</strong> Awaiting Approval</p>
        </div>
    </div>
</body>
</html>

