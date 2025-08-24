<?php include 'includes/header.php'; ?>

<div class="static-page">
    <h1>Login to Your Account</h1>

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
    ?>

    <div class="contact-form-container">
        <form action="scripts/login_handler.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn-submit">Login</button>
            </div>
        </form>
        <p style="text-align: center; margin-top: 20px;">Don't have an account? <a href="register.php" style="color: #ffc107;">Register here</a>.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
