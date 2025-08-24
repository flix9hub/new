<?php
// --- Database Configuration ---
$servername = "localhost";
$dbname = "u191663925_flix9hub";
$username = "u191663925_flix9hub";
$password = "Kalachand@1974";

// --- Create Connection ---
$conn = new mysqli($servername, $username, $password, $dbname);

// --- Check Connection ---
if ($conn->connect_error) {
    // In a real application, you would log this error instead of killing the script.
    // For this project, we will show a generic error to the user.
    // In a production environment, you might want to redirect to an error page.
    die("Database connection failed. Please try again later.");
}

// --- Set Character Set ---
if (!$conn->set_charset("utf8mb4")) {
    // Handle error if character set can't be set
    // For now, we'll just ignore, but in production, this should be logged.
}
?>
