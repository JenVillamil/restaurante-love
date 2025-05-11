<?php
// Database configuration
$host = 'localhost';
$dbname = 'restaurante_love';
$username = 'root';
$password = '';

// Establish connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>