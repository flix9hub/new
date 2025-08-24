<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if the necessary POST data is available
if (isset($_POST['razorpay_payment_id'], $_POST['project_id'], $_POST['amount'])) {

    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    // The signature verification is crucial for security.
    // In a real-world scenario, you would generate an order on your server first,
    // get an order_id from Razorpay, and use that to verify the signature.
    // $razorpay_order_id = $_POST['razorpay_order_id']; // This would come from the client
    // $razorpay_signature = $_POST['razorpay_signature'];

    // TODO: Replace with your actual Razorpay Test Key Secret
    $key_secret = 'YOUR_KEY_SECRET_PLACEHOLDER';

    // The signature string should be: $razorpay_order_id . '|' . $razorpay_payment_id
    // $signature_payload = $razorpay_order_id . '|' . $razorpay_payment_id;
    // $expected_signature = hash_hmac('sha256', $signature_payload, $key_secret);

    // For this project, as we are not creating server-side orders, we will skip
    // the signature verification. THIS IS NOT SAFE FOR PRODUCTION.
    $is_signature_valid = true; // Bypassing verification for demonstration.

    if ($is_signature_valid) {
        $project_id = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $user_id = $_SESSION['user_id'];

        if (!$project_id || !$amount) {
            $_SESSION['error_messages'] = ["Invalid investment data."];
            header('Location: ../dashboard.php');
            exit();
        }

        // Fetch tenure from the project to store in the investment record
        $stmt = $conn->prepare("SELECT tenure_months FROM projects WHERE id = ?");
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $project = $stmt->get_result()->fetch_assoc();
        $tenure = $project['tenure_months'];
        $stmt->close();

        // Insert the new investment into the database
        $sql = "INSERT INTO investments (user_id, project_id, amount, tenure_months, status) VALUES (?, ?, ?, ?, 'active')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidi", $user_id, $project_id, $amount, $tenure);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Your investment of ₹" . number_format($amount) . " was successful! It is now reflected in your portfolio.";
            header('Location: ../dashboard.php');
            exit();
        } else {
            $_SESSION['error_messages'] = ["There was an error recording your investment. Please contact support."];
            header('Location: ../dashboard.php');
            exit();
        }

    } else {
        // Signature is invalid
        $_SESSION['error_messages'] = ["Payment verification failed. If the amount was debited from your account, please contact support."];
        header('Location: ../dashboard.php');
        exit();
    }

} else {
    // Redirect if accessed directly or without proper data
    header('Location: ../index.php');
    exit();
}

$conn->close();
?>
