<?php
session_start();
require_once '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($email) || empty($otp)) {
        $_SESSION['error_messages'] = ["Please enter the verification code."];
        $_SESSION['email_for_verification'] = $email; // Preserve email for the form
        header('Location: ../verify_otp.php');
        exit();
    }

    // Prepare to select the user by email
    $stmt = $conn->prepare("SELECT id, fullname, otp, is_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Check if account is already verified
        if ($user['is_verified']) {
            $_SESSION['success_message'] = "This account is already verified. Please login.";
            header('Location: ../login.php');
            exit();
        }

        // Verify the OTP
        if ($otp == $user['otp']) {
            // OTP is correct, update user status to verified and clear OTP
            $update_stmt = $conn->prepare("UPDATE users SET is_verified = 1, otp = NULL WHERE id = ?");
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();
            $update_stmt->close();

            // Log the user in by setting session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_fullname'] = $user['fullname'];

            // Clear the temporary session variable
            unset($_SESSION['email_for_verification']);

            $_SESSION['success_message'] = "Verification successful! Welcome to your dashboard.";
            header('Location: ../index.php'); // TODO: Redirect to an actual investor dashboard later
            exit();

        } else {
            // Invalid OTP
            $_SESSION['error_messages'] = ["Invalid verification code. Please try again."];
            $_SESSION['email_for_verification'] = $email; // Preserve email for the form
            header('Location: ../verify_otp.php');
            exit();
        }

    } else {
        // User not found - should be rare if they just registered
        $_SESSION['error_messages'] = ["User not found. Please try registering again."];
        header('Location: ../register.php');
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
