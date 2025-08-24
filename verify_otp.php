<?php include 'includes/header.php'; ?>

<div class="static-page">
    <h1>Verify Your Account</h1>

    <?php
    session_start(); // Start session to access messages

    // Display and clear error messages
    if (isset($_SESSION['error_messages'])) {
        echo '<div class="form-messages error">';
        foreach ($_SESSION['error_messages'] as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
        unset($_SESSION['error_messages']);
    }

    // Display and clear success message
    if (isset($_SESSION['success_message'])) {
        echo '<div class="form-messages success">';
        echo '<p>' . htmlspecialchars($_SESSION['success_message']) . '</p>';
        echo '</div>';
        unset($_SESSION['success_message']);
    }

    // Check if email for verification is in session
    if (!isset($_SESSION['email_for_verification'])) {
        echo '<div class="form-messages error"><p>Could not identify the user for verification. Please try registering again or contact support.</p></div>';
    } else {
        $email = htmlspecialchars($_SESSION['email_for_verification']);
    ?>

    <p style="text-align:center; margin-bottom: 30px;">An email with a 6-digit verification code has been sent to <strong><?php echo $email; ?></strong>. Please enter it below.</p>

    <div class="contact-form-container" style="max-width: 500px;">
        <form action="scripts/verify_otp_handler.php" method="POST">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <div class="form-group">
                <label for="otp">Verification Code (OTP)</label>
                <input type="text" id="otp" name="otp" required pattern="[0-9]{6}" maxlength="6" inputmode="numeric" style="text-align: center; font-size: 1.5rem; letter-spacing: 10px;">
            </div>
            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn-submit">Verify Account</button>
            </div>
        </form>
         <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">Didn't receive the code? <a href="#" style="color: #ffc107;">Resend Code</a></p>
    </div>

    <?php
    } // End else
    ?>

</div>

<?php include 'includes/footer.php'; ?>
