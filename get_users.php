<?php
session_start();
header('Content-Type: application/json');

$host = "sql308.infinityfree.com";
$db = "if0_38681307_task";
$user = "if0_38681307";
$pass = "Nh8iwr7D6aHu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$currentUser = $_SESSION['username'];

$stmt = $conn->prepare("SELECT username FROM users WHERE username != ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = ["username" => $row["username"]];
}

echo json_encode($users);
$conn->close();
