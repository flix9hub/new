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

    <!-- My Investments Section -->
    <div class="dashboard-section">
        <h2>My Investments</h2>
        <div class="my-investments-container">
            <?php
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT
                        i.id,
                        p.title AS project_title,
                        i.amount,
                        i.investment_date,
                        i.tenure_months,
                        i.status,
                        (SELECT SUM(il.payout_amount) FROM investment_ledger il WHERE il.investment_id = i.id) AS total_returns
                    FROM
                        investments i
                    JOIN
                        projects p ON i.project_id = p.id
                    WHERE
                        i.user_id = ?
                    ORDER BY
                        i.investment_date DESC";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
            ?>
                <table class="investments-table">
                    <thead>
                        <tr>
                            <th>Project Title</th>
                            <th>Amount Invested</th>
                            <th>Investment Date</th>
                            <th>Tenure</th>
                            <th>Total Returns</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($investment = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($investment['project_title']); ?></td>
                                <td>₹<?php echo number_format($investment['amount']); ?></td>
                                <td><?php echo date("M j, Y", strtotime($investment['investment_date'])); ?></td>
                                <td><?php echo htmlspecialchars($investment['tenure_months']); ?> Months</td>
                                <td>₹<?php echo number_format($investment['total_returns'] ?? 0, 2); ?></td>
                                <td><span class="status-badge status-<?php echo htmlspecialchars($investment['status']); ?>"><?php echo ucfirst($investment['status']); ?></span></td>
                                <td class="actions-cell">
                                    <a href="#" class="btn-action extend">Extend</a>
                                    <a href="#" class="btn-action withdraw">Withdraw</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo '<div class="placeholder-content"><p>You have not made any investments yet.</p></div>';
            }
            $stmt->close();
            ?>
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
