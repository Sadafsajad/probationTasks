<?php
// Database configuration
$dbHost = 'localhost'; // Your database host (usually 'localhost')
$dbUsername = 'root'; // Your database username
$dbPassword = 'mysql'; // Your database password
$dbName = 'db_ProbationTest'; // Your database name

// Attempt to connect to the database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display error message
    die("Connection failed: " . $e->getMessage());
}
?>