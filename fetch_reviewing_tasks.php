<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["username"])) {
    http_response_code(401);
    echo json_encode(["error" => "User not logged in"]);
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

$currentUser = $_SESSION["username"];

$sql = "SELECT title AS task_title, assigned_to, description, due_date, remark 
        FROM task 
        WHERE created_by = ? AND status = 'Reviewing'
        ORDER BY due_date ASC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentUser);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Execution error"]);
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
