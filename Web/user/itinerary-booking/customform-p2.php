
<?php
session_start();

$secret = '6LcnWncrAAAAAI7OgfOQwveHB628E34ZRERZ7psx';
$response = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
$captcha_success = json_decode($verify);

if (!$captcha_success->success) {
    die("CAPTCHA failed. Try again.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['fname'] = htmlspecialchars($_POST['fname']);
    $_SESSION['lname'] = htmlspecialchars($_POST['lname']);
    $_SESSION['pax'] = htmlspecialchars($_POST['pax']);
    $_SESSION['luggage'] = htmlspecialchars($_POST['luggage']);
    $_SESSION['comments'] = htmlspecialchars($_POST['comments']);

    if (isset($_SESSION['id_filepath']) && file_exists($_SESSION['id_filepath'])) {
        unlink($_SESSION['id_filepath']);
        unset($_SESSION['id_filepath']);
        unset($_SESSION['id_filename']);
    }

    if (isset($_FILES['id']) && $_FILES['id']['error'] == UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png']; 
        $filename = $_FILES['id']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed)) {
            $newFilename = uniqid('id_', true) . '.' . $filetype;
            $uploadDir = __DIR__ . '/uploads/';
            $destination = $uploadDir . $newFilename;
            
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }
            
            if (move_uploaded_file($_FILES['id']['tmp_name'], $destination)) {
                $_SESSION['id_filepath'] = $destination;
                $_SESSION['id_filename'] = $filename;
            } else {
                die("Error: Could not move the uploaded file. Check server permissions for the 'uploads' directory.");
            }
        } else {
            die("Error: Invalid file type. Please upload a JPG, JPEG, or PNG file.");
        }
    } else {
        $upload_error = $_FILES['id']['error'] ?? UPLOAD_ERR_NO_FILE;
        switch ($upload_error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                die("Error: The uploaded file exceeds the maximum allowed size.");
            case UPLOAD_ERR_NO_FILE:
                die("Error: No file was uploaded. Please select an ID to upload.");
            default:
                die("Error: An unknown error occurred during file upload.");
        }
    }

    header("Location: payment.php"); 
    exit();

} else {
    header("Location: custom-booking-p2.php");
    exit();
}
?>
