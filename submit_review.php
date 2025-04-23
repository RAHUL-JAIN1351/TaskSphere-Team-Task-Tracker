<?php
session_start();
header('Content-Type: application/json'); // Tell JS we’re returning JSON

if (!isset($_SESSION["username"])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit();
}

// DB credentials
$host = "sql308.infinityfree.com";
$db   = "if0_38681307_task";
$user = "if0_38681307";
$pass = "Nh8iwr7D6aHu";

// Create DB connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit();
}

$currentUser = $_SESSION["username"];
$title = $_POST["title"] ?? '';

if (empty($title)) {
    echo json_encode(["success" => false, "error" => "Missing task title"]);
    exit();
}

// Update task to 'Reviewing'
$stmt = $conn->prepare("UPDATE task SET status = 'Reviewing' WHERE title = ? AND assigned_to = ?");
$stmt->bind_param("ss", $title, $currentUser);

if ($stmt->execute()) {
    echo json_encode(["success" => true]); // ✅ success
} else {
    echo json_encode(["success" => false, "error" => "Update failed"]);
}

$stmt->close();
$conn->close();