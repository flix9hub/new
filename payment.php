<?php
// Protect this page - only logged-in users can see it
require_once 'scripts/auth_check.php';
// We need the DB connection
require_once 'config/db_connect.php';

// Get project ID from URL and validate it
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_VALIDATE_INT);
if (!$project_id) {
    header('Location: dashboard.php');
    exit();
}

// Fetch project details
$stmt = $conn->prepare("SELECT title, min_investment FROM projects WHERE id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();
$stmt->close();

if (!$project) {
    // Project not found
    header('Location: dashboard.php');
    exit();
}

// TODO: Replace with your actual Razorpay Test Key ID
$razorpay_key_id = 'rzp_test_YOUR_KEY_ID';

include 'includes/header.php';
?>

<div class="static-page">
    <h1>Finalize Your Investment</h1>
    <p style="text-align:center; margin-bottom: 30px;">You are investing in: <strong><?php echo htmlspecialchars($project['title']); ?></strong></p>

    <div class="investment-flow-box">
        <form id="payment-form">
            <div class="form-group">
                <label for="amount">Investment Amount (INR)</label>
                <input type="number" id="amount" name="amount" class="form-control"
                       min="<?php echo htmlspecialchars($project['min_investment']); ?>"
                       max="10000000"
                       value="<?php echo htmlspecialchars($project['min_investment']); ?>"
                       required>
                <small>Minimum investment: ₹<?php echo number_format($project['min_investment']); ?>. Maximum: ₹1,00,00,000.</small>
            </div>

            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">

            <div class="action-center">
                <button type="submit" id="pay-button" class="btn-submit">Pay with Razorpay</button>
            </div>
        </form>
    </div>
</div>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('payment-form').addEventListener('submit', function (e) {
    e.preventDefault();

    var amount = document.getElementById('amount').value;
    var key_id = "<?php echo $razorpay_key_id; ?>";
    var user_name = "<?php echo htmlspecialchars($_SESSION['user_fullname']); ?>";
    var user_email = "<?php echo htmlspecialchars($_SESSION['user_email']); ?>";

    var options = {
        "key": key_id,
        "amount": amount * 100, // Amount in paise
        "currency": "INR",
        "name": "Flix9 Hub",
        "description": "Investment in " + "<?php echo htmlspecialchars($project['title']); ?>",
        "handler": function (response){
            // This function is called after a successful payment
            // We will create a form and post the payment details to our server for verification
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'scripts/payment_success.php';

            // Add payment ID and other details to the form
            var paymentIdInput = document.createElement('input');
            paymentIdInput.type = 'hidden';
            paymentIdInput.name = 'razorpay_payment_id';
            paymentIdInput.value = response.razorpay_payment_id;
            form.appendChild(paymentIdInput);

            // We also need the signature and order ID for verification
            var signatureInput = document.createElement('input');
            signatureInput.type = 'hidden';
            signatureInput.name = 'razorpay_signature';
            signatureInput.value = response.razorpay_signature;
            form.appendChild(signatureInput);

            // The order_id is not available on the client side in this basic integration.
            // A more robust integration would create an order on the server first.
            // For now, we'll proceed without it and focus on signature verification.

            var projectIdInput = document.createElement('input');
            projectIdInput.type = 'hidden';
            projectIdInput.name = 'project_id';
            projectIdInput.value = '<?php echo $project_id; ?>';
            form.appendChild(projectIdInput);

            var amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = amount;
            form.appendChild(amountInput);

            document.body.appendChild(form);
            form.submit();
        },
        "prefill": {
            "name": user_name,
            "email": user_email
        },
        "theme": {
            "color": "#004e92"
        },
        "notes": {
            "project_id": "<?php echo $project_id; ?>"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>

<?php
$conn->close();
include 'includes/footer.php';
?>
