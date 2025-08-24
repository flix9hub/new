<?php
// Protect this page - only logged-in users can see it
require_once 'scripts/auth_check.php';

// Now we can include the header
include 'includes/header.php';
?>

<?php
// We need the database connection on this page
require_once 'config/db_connect.php';
?>
<div class="dashboard-container">
    <h1>Investor Dashboard</h1>

    <div class="dashboard-actions">
        <a href="#" class="btn-dashboard-action">Download Investment Agreement</a>
        <a href="#" class="btn-dashboard-action">Raise a Support Ticket</a>
    </div>

    <!-- Notifications Section -->
    <div class="dashboard-section">
        <h2>Notifications</h2>
        <div class="notification-list">
            <?php
            <?php
            // Fetch notifications for the user using the new schema
            $user_id = $_SESSION['user_id'];

            $sql = "SELECT n.id, n.subject, n.message, n.created_at
                    FROM notifications n
                    LEFT JOIN user_notifications un ON n.id = un.notification_id AND un.user_id = ?
                    WHERE (n.is_broadcast = 1 OR n.target_user_id = ?)
                    AND un.id IS NULL
                    ORDER BY n.created_at DESC";

            $stmt = $conn->prepare($sql);
            // Bind the user_id to both placeholders
            $stmt->bind_param("ii", $user_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($notification = $result->fetch_assoc()) {
                    echo '<div class="notification-item">';
                    echo '  <div class="notification-content">';
                    echo '      <strong>' . htmlspecialchars($notification['subject']) . '</strong>';
                    echo '      <p>' . htmlspecialchars($notification['message']) . '</p>';
                    echo '      <small>Posted on: ' . date("F j, Y, g:i a", strtotime($notification['created_at'])) . '</small>';
                    echo '  </div>';
                    echo '  <a href="scripts/mark_notification_read.php?id=' . $notification['id'] . '" class="btn-mark-read" title="Dismiss this notification">Mark as Read</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="placeholder-content"><p>No new notifications.</p></div>';
            }
            $stmt->close();
            ?>
            ?>
        </div>
    </div>

    <!-- Upcoming Investments Section -->
    <div class="dashboard-section">
        <h2>Upcoming Investment Opportunities</h2>
        <div class="investment-grid">
            <?php
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
                            <button class="btn-invest">Invest Now</button>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="placeholder-content"><p>No investment projects available at the moment. Please check back later.</p></div>';
            }
            ?>
        </div>
    </div>

    <!-- My Investments Section (Placeholder) -->
    <div class="dashboard-section">
        <h2>My Investments</h2>
        <div class="placeholder-content">
            <p>Your investment portfolio will be displayed here once you make your first investment.</p>
        </div>
    </div>

    <!-- Analytics Section (Placeholder) -->
    <div class="dashboard-section">
        <h2>Analytics</h2>
        <div class="placeholder-content">
            <p>Charts and growth analytics for your investments will be displayed here.</p>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
