<?php include 'includes/header.php'; ?>

<div class="static-page">
    <h1>Create Your Investor Account</h1>
    <p style="text-align:center; margin-bottom: 30px;">Join Flix9 Hub to turn your passion for cinema into profit.</p>

    <div class="contact-form-container">
        <form action="scripts/register_handler.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="address">Contact Address</label>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required>
                </div>
            </div>

            <h2 style="text-align: center; margin-top: 40px; margin-bottom: 20px; font-size: 1.8rem;">Identity Verification</h2>
            <p style="text-align:center; margin-bottom: 30px;">Please upload your National ID (Front and Back) OR your Passport.</p>

            <div class="form-group">
                <label for="id_front">National ID Card (Front)</label>
                <input type="file" id="id_front" name="id_front" class="file-input">
            </div>
            <div class="form-group">
                <label for="id_back">National ID Card (Back)</label>
                <input type="file" id="id_back" name="id_back" class="file-input">
            </div>
            <p style="text-align:center; font-weight: 700; margin: 20px 0;">OR</p>
            <div class="form-group">
                <label for="passport">Passport</label>
                <input type="file" id="passport" name="passport" class="file-input">
            </div>

            <div class="form-group" style="margin-top: 40px;">
                <button type="submit" class="btn-submit">Register</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
