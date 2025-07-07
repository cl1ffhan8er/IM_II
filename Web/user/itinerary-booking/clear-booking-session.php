<?php
session_start();

$booking_keys = [
    'date', 'pickup', 'pickuptime', 'dropofftime', 'booking_itinerary',
    'fname', 'lname', 'pax', 'luggage', 'comments',
    'id_filepath', 'id_filename'
];

foreach ($booking_keys as $key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}


if (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) {
    $safe_redirect = filter_var($_GET['redirect_to'], FILTER_SANITIZE_URL);
    if (parse_url($safe_redirect, PHP_URL_HOST) === null && file_exists($safe_redirect)) {
        $redirect_url = $safe_redirect;
    }
}

header("Location: " . $redirect_url);
exit();
?>
