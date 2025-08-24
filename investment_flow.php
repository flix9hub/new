<?php
// Protect this page - only logged-in users can see it
require_once 'scripts/auth_check.php';
// We need the DB connection
require_once 'config/db_connect.php';

// Get project ID from URL and validate it
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_VALIDATE_INT);
if (!$project_id) {
    // Redirect if no project ID is provided or invalid
    header('Location: dashboard.php');
    exit();
}

// Fetch user's country from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT country FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Default to international if country is not set for some reason
$user_country = isset($user['country']) ? strtolower(trim($user['country'])) : 'international';

include 'includes/header.php';
?>

<div class="static-page">
    <h1>Investment Agreement</h1>

    <?php if ($user_country === 'india'): ?>
        <!-- Flow for Indian Investors -->
        <div class="investment-flow-box">
            <h2>Instructions for Indian Investors</h2>
            <p>As per regulations, Indian investors are required to complete a manual verification process before payment.</p>
            <ol>
                <li><strong>Download the Agreement:</strong> Please download the investment agreement PDF using the button below.</li>
                <li><strong>Visit Our Office:</strong> Bring the signed agreement to our office at your convenience for a manual discussion and finalization. Our team will assist you with the next steps.</li>
                <li><strong>Admin Approval:</strong> Once your visit is complete, our admin team will enable the payment option in your dashboard for this project. You will receive a notification when it's ready.</li>
            </ol>
            <div class="action-center">
                <a href="assets/agreement.pdf" class="btn-submit" download>Download Agreement PDF</a>
            </div>
        </div>
    <?php else: ?>
        <!-- Flow for International Investors -->
        <div class="investment-flow-box">
            <h2>Agreement for International Investors</h2>
            <p>Please review the investment agreement below and provide your acknowledgment to proceed with the payment.</p>

            <div class="agreement-viewer">
                <h3>Investment Agreement Terms (Sample)</h3>
                <p>This is a placeholder for the on-screen PDF viewer. The full legal document would be displayed here for the user to read.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede. Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam nibh.</p>
            </div>

            <form action="scripts/payment_handler.php" method="POST" style="margin-top: 20px;">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <div class="form-group">
                    <label class="checkbox-label" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <input type="checkbox" name="agreement_acknowledged" required style="width: auto;">
                        I have read and agree to the terms and conditions of the Investment Agreement.
                    </label>
                </div>

                <div class="signature-pad-placeholder">
                    <p><strong>[A digital signature pad would be integrated here]</strong></p>
                </div>

                <div class="action-center">
                    <button type="submit" class="btn-submit">Acknowledge and Proceed to Payment</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
