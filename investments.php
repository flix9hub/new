<?php include 'includes/header.php'; ?>

<div class="static-page">
    <h1>Upcoming Projects</h1>
    <div class="investment-grid">
        <?php
        // Include the database connection
        require_once 'config/db_connect.php';

        // Fetch all projects from the database
        $sql = "SELECT * FROM projects ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($project = $result->fetch_assoc()) {
        ?>
                <!-- Investment Item -->
                <div class="investment-item">
                    <div class="video-container">
                        <iframe src="<?php echo htmlspecialchars($project['video_url']); ?>?si=dgDftArYxrTYQGCQ" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>
                    <div class="investment-info">
                        <p class="hot-deal"><?php echo htmlspecialchars($project['hot_deal_text']); ?></p>
                        <ul class="investment-details">
                            <li>🎥 <strong>Title:</strong> <?php echo htmlspecialchars($project['title']); ?></li>
                            <li>🌟 <strong>Cast:</strong> <?php echo htmlspecialchars($project['cast']); ?></li>
                            <li>📺 <strong>Rights:</strong> <?php echo htmlspecialchars($project['ott_rights']); ?> | <?php echo htmlspecialchars($project['language']); ?> Language</li>
                            <li>💰 <strong>Earn:</strong> <?php echo htmlspecialchars($project['monthly_return_range']); ?> Returns Every Month</li>
                            <li>⏳ <strong>Tenure:</strong> <?php echo htmlspecialchars($project['tenure_months']); ?> Months</li>
                            <li>💵 <strong>Minimum Investment:</strong> ₹<?php echo number_format($project['min_investment']); ?></li>
                            <li>⚙️ <strong>Asset Management Fee:</strong> ₹<?php echo number_format($project['asset_management_fee']); ?></li>
                        </ul>
                        <p class="investment-pitch">
                            <?php echo htmlspecialchars($project['pitch_line1']); ?><br>
                            <?php echo htmlspecialchars($project['pitch_line2']); ?>
                        </p>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<div class="placeholder-content"><p>No investment projects available at the moment. Please check back later.</p></div>';
        }
        $conn->close();
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
