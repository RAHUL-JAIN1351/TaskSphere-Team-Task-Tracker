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
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$taskTitle = $data["task_title"];
$newStatus = $data["status"];
$remark = $data["remark"];

$sql = "UPDATE task SET status = ?, remark = ? WHERE title = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $newStatus, $remark, $taskTitle);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to update task"]);
}

$stmt->close();
$conn->close();
?>