<?php
/**
 * disable_smtp.php
 *
 * A script to disable SMTP on a phpBB 3.3 board by updating the database configuration.
 *
 * Usage:
 * 1. Place this script in your phpBB root directory.
 * 2. Run the script from the command line or browser.
 *
 * Ensure to delete or secure this script after running to prevent unauthorized access.
 *
 * Copyright 2024 phpBBmodders (https://phpbbmodders.com) board@phpbbmodders.com
 */

// Include the phpBB config file to get database connection info
$configFile = __DIR__ . '/config.php';

if (!file_exists($configFile)) {
   die('Config file not found.');
}

include($configFile);

// Validate database connection settings
if (!isset($dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix)) {
   die('Database configuration variables are missing.');
}

// Establish a database connection using PDO
try {
   $dsn = "mysql:host=$dbhost;port=$dbport;dbname=$dbname;charset=utf8mb4";
   $pdo = new PDO($dsn, $dbuser, $dbpasswd);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   die('Connection failed: ' . $e->getMessage());
}

// SQL query to disable SMTP by setting the smtp_delivery setting to 0
$sql = "UPDATE ${table_prefix}config SET config_value = '0' WHERE config_name = 'smtp_delivery'";

try {
   $stmt = $pdo->prepare($sql);
   $stmt->execute();
   echo "SMTP has been successfully disabled on your phpBB board.";
} catch (PDOException $e) {
   die('Failed to disable SMTP: ' . $e->getMessage());
}

// Close the database connection
$pdo = null;

?>
