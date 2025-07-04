<?php

include '../../include/connect.php';
session_start();

// handling for registration form
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = md5($password); // hashing the password

    $emailExists = "SELECT * FROM person WHERE email = '$email'";
    $result = $conn->query($emailExists);
    if ($result->num_rows > 0) {
        echo "Email already exists.";
        exit();
    }
    $userExists = "SELECT * FROM person WHERE name = '$username'";
    $result = $conn->query($userExists);
    if ($result->num_rows > 0) {
        echo "Username already exists.";
        exit();
    }

    else {
        $insertUser = "INSERT INTO person (name, password, email, contact_number) 
        VALUES ('$username', '$password', '$email', '$contact')";
        if ($conn->query($insertUser) === TRUE) {
            header("Location: login.php");
            echo "Registration successful!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // hashing the password

    $sql = "SELECT * FROM person WHERE name = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $person = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['personID'] = $person['PersonID'];

        $personID = $person['person_ID'];

        $isEmployee = "SELECT employee_ID FROM employee WHERE employee_ID = '$personID'";
        $employeeResult = $conn->query($isEmployee);
        if ($employeeResult->num_rows > 0) { // user is an employee
            $isManager = "SELECT * FROM manager WHERE manager_ID = '$personID'";
            $managerResult = $conn->query($isManager);
            if ($managerResult->num_rows > 0) { // user is a manager
                header("Location: ../../admin/manager_dashboard.php");
                exit();
            } else {
                header("Location: ../../admin/driver_dashboard.php");
                exit();
            }
        } else { //client
           header("Location: ../index.php");
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>