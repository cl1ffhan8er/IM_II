<?php
session_start();
include '../../include/connect.php';

if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);

    $emailExists = "SELECT * FROM Person WHERE email = '$email'";
    $result = $conn->query($emailExists);
    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "An account with that email already exists.";
        header("Location: login.php");
        exit();
    }
    
    $userExists = "SELECT * FROM Person WHERE name = '$username'";
    $result = $conn->query($userExists);
    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "That username is already taken.";
        header("Location: login.php");
        exit();
    }

    $insertUser = "INSERT INTO Person (name, password, email, contact_number) 
                   VALUES ('$username', '$password', '$email', '$contact')";
    if ($conn->query($insertUser) === TRUE) {
        $_SESSION['success_message'] = "Registration successful! Please log in.";
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
    }
    header("Location: login.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM Person WHERE name = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $person = $result->fetch_assoc();
        $_SESSION['username'] = $person['name']; 
        $_SESSION['person_ID'] = $person['person_ID'];
        $personID = $person['person_ID'];

        $isEmployee = "SELECT employee_ID FROM Employee WHERE employee_ID = '$personID'";
        $employeeResult = $conn->query($isEmployee);
        if ($employeeResult->num_rows > 0) {
            $isManager = "SELECT * FROM Manager WHERE manager_ID = '$personID'";
            $managerResult = $conn->query($isManager);
            if ($managerResult->num_rows > 0) { 
                header("Location: ../../admin/manager/home.php");
            } else {
                $_SESSION['driver_ID'] = $personID;
                header("Location: ../../admin/driver/driver_dashboard.php");
            }
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
}
?>