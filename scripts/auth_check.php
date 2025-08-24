<?php
// This script checks if a user is logged in.
// It should be included at the top of any page that requires authentication.

// Make sure session is started (it's also started in header.php, but this is a good safeguard)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user_id session variable is not set
if (!isset($_SESSION['user_id'])) {
    // If not logged in, store a message and redirect to the login page.
    $_SESSION['error_messages'] = ["You must be logged in to view that page."];

    // To be safe, we need to ensure the Location header path is correct
    // from any script depth. A simple way is to define a base URL.
    // For now, we assume this script is used by files in the root directory.
    header("Location: login.php");
    exit();
}
?>
