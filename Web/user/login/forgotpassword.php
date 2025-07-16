<?php
include '../../include/connect.php';

if (isset($_POST['change-password'])) {

    $email = trim($_POST['email_reset']);
    $newPassword = trim($_POST['new_password']);

    if (empty($email) || empty($newPassword)) {
        echo "Please fill in all fields.";
        exit;
    }

    $stmt = $conn->prepare("SELECT email FROM Person WHERE email = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
 
        $hashedPassword = md5($newPassword);

        $updateStmt = $conn->prepare("UPDATE Person SET password = ? WHERE email = ?");
        if ($updateStmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        
        $updateStmt->bind_param("ss", $hashedPassword, $email);

        // Execute the update
        if ($updateStmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: Could not reset the password.";
        }
        $updateStmt->close();

    } else {
        echo "<div style='font-family: Arial, sans-serif; text-align: center; padding: 50px;'>";
        echo "<h2>No account found with that email address.</h2>";
        echo "<p><a href='login.php'>Try again</a></p>";
        echo "</div>";
    }

    $stmt->close();

} else {
    header("Location: login.php");
    exit();
}

$conn->close();
?>
