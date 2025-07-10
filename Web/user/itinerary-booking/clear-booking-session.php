<?php
session_start();

// This script clears session data related to an in-progress booking
// without logging the user out.

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

// --- REDIRECT LOGIC ---
// Set a default, safe redirect target.
$redirect_url = '../index.php'; 

// Check if a redirect target was provided in the URL.
if (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) {
    
    // Sanitize the URL to prevent security issues.
    $safe_redirect = filter_var($_GET['redirect_to'], FILTER_SANITIZE_URL);
    
    // Check if the sanitized URL is a relative path within the site.
    // This is a safer check than file_exists() for relative paths.
    if (parse_url($safe_redirect, PHP_URL_HOST) === null) {
        $redirect_url = $safe_redirect;
    }
}

header("Location: " . $redirect_url);
exit();
?>
