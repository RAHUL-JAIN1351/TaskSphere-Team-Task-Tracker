<?php
$host = "sql308.infinityfree.com"; // Replace with your InfinityFree DB host
$dbname = "if0_38681307_task"; // Replace with your DB name
$username = "if0_38681307";          // Replace with your DB username
$password = "Nh8iwr7D6aHu";         // Replace with your DB password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>