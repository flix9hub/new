<?php
// Start the session to store messages
session_start();

// Include the database connection
require_once '../config/db_connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Retrieve and Sanitize Form Data ---
    // Using mysqli_real_escape_string for protection against some SQL injection,
    // but prepared statements are the primary defense.
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = $_POST['password']; // Don't escape password before hashing
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);

    // --- Validation (to be continued) ---
    $errors = [];

    // Example: Check for empty fields
    if (empty($fullname) || empty($email) || empty($mobile) || empty($password) || empty($address) || empty($city) || empty($state) || empty($country)) {
        $errors[] = "All fields are required.";
    }

    // Example: Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // --- TODO: Check for duplicate email/mobile ---

    // --- TODO: File Upload Handling ---
    // This part is complex and will be handled next.
    // For now, we assume no files or validation for them.


    // If there are validation errors, redirect back to form
    if (!empty($errors)) {
        $_SESSION['error_messages'] = $errors;
        header('Location: ../register.php');
        exit();
    }

    // --- Password Hashing ---
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // --- TODO: Database Insertion ---
    // The final step will be to insert the data using a prepared statement.

    echo "Form submitted successfully. Backend logic for database insertion and file upload is pending.";
    // For now, just print the received data for debugging
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "Hashed Password: " . $hashed_password;


} else {
    // If the script is accessed directly, redirect to the registration page
    header("Location: ../register.php");
    exit();
}

// Close the database connection
$conn->close();
?>
