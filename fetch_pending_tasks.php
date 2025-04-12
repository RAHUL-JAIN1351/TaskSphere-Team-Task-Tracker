<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

// DB credentials
$host = "sql308.infinityfree.com";
$db   = "if0_38681307_task";
$user = "if0_38681307";
$pass = "Nh8iwr7D6aHu";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    http_response_code(500); // Server error
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

$currentUser = $_SESSION["username"];

$sql = "SELECT 
            title AS task_title, 
            description, 
            created_by AS assigned_by, 
            due_date,
            status
        FROM task 
        WHERE assigned_to = ? AND status IN ('Pending', 'Re-do')
        ORDER BY due_date ASC";


$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "SQL error: " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $currentUser);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Execution error: " . $stmt->error]);
    exit();
}

$result = $stmt->get_result();

$tasks = [];

while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode($tasks);

$stmt->close();
$conn->close();
?>
