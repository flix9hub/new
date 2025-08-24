<?php
// Protect this page - only logged-in users can see it
require_once 'scripts/auth_check.php';

// Now we can include the header
include 'includes/header.php';
?>

<div class="dashboard-container">
    <h1>Investor Dashboard</h1>

    <div class="dashboard-actions">
        <a href="#" class="btn-dashboard-action">Download Investment Agreement</a>
        <a href="#" class="btn-dashboard-action">Raise a Support Ticket</a>
    </div>

    <!-- Upcoming Investments Section -->
    <div class="dashboard-section">
        <h2>Upcoming Investment Opportunities</h2>
        <div class="investment-grid">
            <!-- Investment Item 1 (structure from investments.php) -->
            <div class="investment-item">
                <div class="video-container">
                    <iframe src="https://www.youtube.com/embed/zeGb3MBZY6Y?si=dgDftArYxrTYQGCQ" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
                <div class="investment-info">
                    <p class="hot-deal">🎬 Hot Deal from Velan Productions (WEB) 🔥 Limited Ticket Size – Grab Yours Before It’s Gone!</p>
                    <ul class="investment-details">
                        <li>🎥 <strong>Title:</strong> Project Alpha</li>
                        <li>🌟 <strong>Cast:</strong> Actor A, Actress B, Director C</li>
                        <li>📺 <strong>Rights:</strong> OTT Rights | Tamil Language</li>
                        <li>💰 <strong>Earn:</strong> 5% – 10% Returns Every Month</li>
                        <li>⏳ <strong>Tenure:</strong> 3 Months</li>
                        <li>💵 <strong>Minimum Investment:</strong> ₹50,000</li>
                        <li>⚙️ <strong>Asset Management Fee:</strong> ₹140</li>
                    </ul>
                    <button class="btn-invest">Invest Now</button>
                </div>
            </div>
            <!-- Investment Item 2 (structure from investments.php) -->
            <div class="investment-item">
                <div class="video-container">
                    <iframe src="https://www.youtube.com/embed/_zWD-SQ-g4g?si=XOwQZ6PRW-dv7Cjx" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
                <div class="investment-info">
                    <p class="hot-deal">🎬 Hot Deal from Kumar Productions (WEB) 🔥 Limited Ticket Size – Grab Yours Before It’s Gone!</p>
                    <ul class="investment-details">
                        <li>🎥 <strong>Title:</strong> Project Beta</li>
                        <li>🌟 <strong>Cast:</strong> Actor X, Actress Y, Director Z</li>
                        <li>📺 <strong>Rights:</strong> OTT Rights | Tamil Language</li>
                        <li>💰 <strong>Earn:</strong> 6% – 12% Returns Every Month</li>
                        <li>⏳ <strong>Tenure:</strong> 4 Months</li>
                        <li>💵 <strong>Minimum Investment:</strong> ₹50,000</li>
                        <li>⚙️ <strong>Asset Management Fee:</strong> ₹140</li>
                    </ul>
                    <button class="btn-invest">Invest Now</button>
                </div>
            </div>
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
