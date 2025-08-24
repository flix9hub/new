<?php
// Start the session to store messages
session_start();

// Include the database connection
require_once '../config/db_connect.php';

// Helper function for handling file uploads
function handle_upload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES[$file_key]['tmp_name'];
        $file_name = $_FILES[$file_key]['name'];
        $file_size = $_FILES[$file_key]['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed extensions and size
        $allowed_exts = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_file_size = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file_ext, $allowed_exts)) {
            return ['error' => "Invalid file type for $file_key. Only JPG, PNG, and PDF are allowed."];
        }

        if ($file_size > $max_file_size) {
            return ['error' => "File size for $file_key exceeds the maximum limit of 5MB."];
        }

        // Generate a unique file name
        $new_file_name = uniqid($file_key . '_', true) . '.' . $file_ext;
        $dest_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_path, $dest_path)) {
            return ['path' => $dest_path];
        } else {
            return ['error' => "Failed to move uploaded file for $file_key."];
        }
    }
    // Return null if no file was uploaded or if there was an error that wasn't UPLOAD_ERR_OK
    return ['path' => null];
}


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Retrieve and Sanitize Form Data ---
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password']; // Hashing handles complexity
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $country = trim($_POST['country']);

    $errors = [];

    // --- Basic Validation ---
    if (empty($fullname) || empty($email) || empty($mobile) || empty($password) || empty($address) || empty($city) || empty($state) || empty($country)) {
        $errors[] = "All text fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // --- Check for duplicate email/mobile in the database ---
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR mobile = ?");
    $stmt->bind_param("ss", $email, $mobile);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "An account with this email or mobile number already exists.";
    }
    $stmt->close();

    // --- File Upload Handling ---
    $upload_dir = '../uploads/documents/';
    $id_front_path = null;
    $id_back_path = null;
    $passport_path = null;

    $id_front_uploaded = isset($_FILES['id_front']) && $_FILES['id_front']['error'] === UPLOAD_ERR_OK;
    $id_back_uploaded = isset($_FILES['id_back']) && $_FILES['id_back']['error'] === UPLOAD_ERR_OK;
    $passport_uploaded = isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK;

    if (($id_front_uploaded && $id_back_uploaded) || $passport_uploaded) {
        if ($id_front_uploaded) { // Process ID front if present
            $id_front_result = handle_upload('id_front', $upload_dir);
            if (isset($id_front_result['error'])) $errors[] = $id_front_result['error'];
            else $id_front_path = $id_front_result['path'];
        }
        if ($id_back_uploaded) { // Process ID back if present
            $id_back_result = handle_upload('id_back', $upload_dir);
            if (isset($id_back_result['error'])) $errors[] = $id_back_result['error'];
            else $id_back_path = $id_back_result['path'];
        }
        if ($passport_uploaded) { // Process passport if present
            $passport_result = handle_upload('passport', $upload_dir);
            if (isset($passport_result['error'])) $errors[] = $passport_result['error'];
            else $passport_path = $passport_result['path'];
        }
    } else {
        $errors[] = "You must upload either National ID (both front and back) or a Passport.";
    }

    // If there are validation errors, redirect back to form
    if (!empty($errors)) {
        $_SESSION['error_messages'] = $errors;
        // Cleanup any files that might have been uploaded before validation failed
        if ($id_front_path && file_exists($id_front_path)) unlink($id_front_path);
        if ($id_back_path && file_exists($id_back_path)) unlink($id_back_path);
        if ($passport_path && file_exists($passport_path)) unlink($passport_path);
        header('Location: ../register.php');
        exit();
    }

    // --- Password Hashing ---
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // --- Database Insertion ---
    $sql = "INSERT INTO users (fullname, email, mobile, password, address, city, state, country, id_front_path, id_back_path, passport_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Bind parameters to the prepared statement
    $stmt->bind_param("sssssssssss", $fullname, $email, $mobile, $hashed_password, $address, $city, $state, $country, $id_front_path, $id_back_path, $passport_path);

    if ($stmt->execute()) {
        // --- TODO: OTP Generation and Email Sending will happen here ---
        $_SESSION['success_message'] = "Registration successful! Please login.";
        header('Location: ../login.php');
        exit();
    } else {
        // If insertion fails, send a generic error and clean up files.
        $_SESSION['error_messages'] = ["Registration failed due to a server error. Please try again."];
        if ($id_front_path && file_exists($id_front_path)) unlink($id_front_path);
        if ($id_back_path && file_exists($id_back_path)) unlink($id_back_path);
        if ($passport_path && file_exists($passport_path)) unlink($passport_path);
        header('Location: ../register.php');
        exit();
    }
    $stmt->close();

} else {
    // If the script is accessed directly, redirect to the registration page
    header("Location: ../register.php");
    exit();
}

// Close the database connection
$conn->close();
?>
