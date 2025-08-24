<?php
session_start();
require_once '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error_messages'] = ["Email and password are required."];
        header('Location: ../login.php');
        exit();
    }

    // Prepare to select the user by email
    $stmt = $conn->prepare("SELECT id, fullname, email, password, is_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password against the hashed password in the database
        if (password_verify($password, $user['password'])) {

            // Check if the account is verified
            if ($user['is_verified']) {
                // Password is correct and account is verified, log the user in
                session_regenerate_id(true); // Regenerate session ID for security
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_fullname'] = $user['fullname'];
                $_SESSION['user_email'] = $user['email'];

                // Redirect to the dashboard (or index for now)
                header('Location: ../index.php'); // TODO: Change to dashboard.php later
                exit();
            } else {
                // Account is not verified
                $_SESSION['error_messages'] = ["Your account is not verified. Please check your email for the verification code."];
                $_SESSION['email_for_verification'] = $email;
                header('Location: ../verify_otp.php');
                exit();
            }

        } else {
            // Invalid password
            $_SESSION['error_messages'] = ["Invalid email or password."];
            header('Location: ../login.php');
            exit();
        }

    } else {
        // User not found
        $_SESSION['error_messages'] = ["Invalid email or password."];
        header('Location: ../login.php');
        exit();
    }

    $stmt->close();

} else {
    // If accessed directly, redirect home
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>
