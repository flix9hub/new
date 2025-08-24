<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // We can't use auth_check.php directly due to redirect path issues.
    // Handle it manually here.
    $_SESSION['error_messages'] = ["You must be logged in to perform that action."];
    header("Location: ../login.php");
    exit();
}

require_once '../config/db_connect.php';

$notification_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$user_id = $_SESSION['user_id'];

if ($notification_id) {
    // Use INSERT IGNORE to prevent errors if the record already exists.
    // This makes the action idempotent, which is good practice.
    // A user might click the link twice.
    // The `is_read` column is not strictly needed with this logic,
    // but we'll set it to 1 for clarity and future use (e.g., "show read notifications").
    $sql = "INSERT IGNORE INTO user_notifications (user_id, notification_id, is_read) VALUES (?, ?, 1)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $notification_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to the dashboard regardless of whether the query did anything.
header("Location: ../dashboard.php");
exit();
?>
