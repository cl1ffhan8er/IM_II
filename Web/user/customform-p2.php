<?php
session_start();

$secret = '6LcnWncrAAAAAI7OgfOQwveHB628E34ZRERZ7psx';
$response = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
$captcha_success = json_decode($verify);

if (!$captcha_success->success) {
    die("CAPTCHA failed. Try again.");
}

header('Location: payment.php')
?>
