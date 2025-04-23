<?php

session_start();
header('Content-Type: application/json');

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


$user_id = $_SESSION['user_id'] ?? null;


// Query for new assigned tasks not yet notified
$sql = "SELECT id FROM tasks WHERE assigned_to = ? AND notified = 0 LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Mark task as notified
    $task_id = $row['id'];
    $update = $conn->prepare("UPDATE tasks SET notified = 1 WHERE id = ?");
    $update->bind_param("i", $task_id);
    $update->execute();

    echo json_encode(['status' => 'new_task']);
} else {
    echo json_encode(['status' => 'no_task']);
}
?>
