<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["username"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$host = "sql308.infinityfree.com";
$db   = "if0_38681307_task";
$user = "if0_38681307";
$pass = "Nh8iwr7D6aHu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$taskTitle = $data["task_title"];
$remark = $data["remark"] ?? "";
$currentUser = $_SESSION["username"];

// Ensure remark column exists in your DB
$sql = "UPDATE task SET status = 'Reviewing', remark = ? WHERE title = ? AND assigned_to = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $remark, $taskTitle, $currentUser);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to update task"]);
}

$stmt->close();
$conn->close();
?>