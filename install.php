<?php
// --- Database Configuration ---
$servername = "localhost";
$dbname = "u191663925_flix9hub";
$username = "u191663925_flix9hub";
$password = "Kalachand@1974";

echo "<h1>Flix9 Hub Installation</h1>";

// --- Create Connection ---
$conn = new mysqli($servername, $username, $password);

// Check Connection
if ($conn->connect_error) {
    die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
}
echo "<p style='color:green;'>Connected to MySQL server successfully.</p>";

// --- Create Database ---
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_create_db) === TRUE) {
    echo "<p style='color:green;'>Database '$dbname' created or already exists.</p>";
} else {
    die("<p style='color:red;'>Error creating database: " . $conn->error . "</p>");
}

// --- Select the Database ---
$conn->select_db($dbname);

// --- SQL to Create Users Table ---
$sql_create_users_table = "
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `mobile` VARCHAR(20) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `address` TEXT NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `country` VARCHAR(100) NOT NULL,
    `id_front_path` VARCHAR(255) DEFAULT NULL,
    `id_back_path` VARCHAR(255) DEFAULT NULL,
    `passport_path` VARCHAR(255) DEFAULT NULL,
    `otp` VARCHAR(10) DEFAULT NULL,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// --- Execute Query ---
if ($conn->query($sql_create_users_table) === TRUE) {
    echo "<p style='color:green;'>Table 'users' created successfully or already exists.</p>";
} else {
    echo "<p style='color:red;'>Error creating table 'users': " . $conn->error . "</p>";
}

// --- Dropping old notifications table for schema change ---
$conn->query("DROP TABLE IF EXISTS `notifications`");
echo "<p style='color:orange;'>Old 'notifications' table dropped if it existed.</p>";

// --- SQL to Create New Notifications Table ---
$sql_create_notifications_table = "
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `is_broadcast` TINYINT(1) NOT NULL DEFAULT 0,
    `target_user_id` INT(11) UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
if ($conn->query($sql_create_notifications_table) === TRUE) {
    echo "<p style='color:green;'>Table 'notifications' (new schema) created successfully.</p>";
} else {
    echo "<p style='color:red;'>Error creating new 'notifications' table: " . $conn->error . "</p>";
}

// --- SQL to Create User Notifications Table for read status ---
$sql_create_user_notifications_table = "
CREATE TABLE IF NOT EXISTS `user_notifications` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `notification_id` INT(11) UNSIGNED NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`notification_id`) REFERENCES `notifications`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `user_notification_read` (`user_id`, `notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
if ($conn->query($sql_create_user_notifications_table) === TRUE) {
    echo "<p style='color:green;'>Table 'user_notifications' created successfully.</p>";
} else {
    echo "<p style='color:red;'>Error creating 'user_notifications' table: " . $conn->error . "</p>";
}

// --- SQL to Insert Sample Notifications ---
// This is for demonstration purposes. In production, you'd remove this part.
$check_empty = $conn->query("SELECT id FROM `notifications` LIMIT 1");
if ($check_empty && $check_empty->num_rows == 0) {
    $conn->query("INSERT INTO `notifications` (`subject`, `message`, `is_broadcast`) VALUES ('Welcome to Flix9 Hub!', 'We are excited to have you on board. Explore our upcoming projects.', 1)");
    $conn->query("INSERT INTO `notifications` (`subject`, `message`, `target_user_id`) VALUES ('Documents Under Review', 'Hi, we have received your identity documents and they are under review.', 1)");
    echo "<p style='color:green;'>Sample notifications inserted successfully.</p>";
}

echo "<h2>Installation Complete!</h2>";
echo "<p>You can now proceed to use the website. It is recommended to delete this `install.php` file for security reasons.</p>";


$conn->close();
?>
