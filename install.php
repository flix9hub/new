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

// You can add more table creation queries here later

echo "<h2>Installation Complete!</h2>";
echo "<p>You can now proceed to use the website. It is recommended to delete this `install.php` file for security reasons.</p>";


$conn->close();
?>
