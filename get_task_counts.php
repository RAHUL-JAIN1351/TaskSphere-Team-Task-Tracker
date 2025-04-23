<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$host = "sql308.infinityfree.com"; // Replace with your InfinityFree DB host
$db = "if0_38681307_task";              // Replace with your DB name
$user = "if0_38681307";            // Replace with your DB user
$pass = "Nh8iwr7D6aHu";        // Replace with your DB password

$conn = new mysqli($host, $user, $pass, $db);

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$stmt->close();

if (!$userData) {
    echo json_encode(['error' => 'user not found']);
    exit();
}

$userId = $userData['id'];


$stmt = $conn->prepare("
    SELECT 
        SUM(CASE WHEN assigned_to = ? AND status IN ('Pending', 'Re-do') THEN 1 ELSE 0 END) AS pending_count,
        SUM(CASE WHEN created_by = ? THEN 1 ELSE 0 END) AS total_count,
        SUM(CASE WHEN created_by = ? AND status = 'Reviewing' THEN 1 ELSE 0 END) AS reviewing_count,
        SUM(CASE WHEN assigned_to = ? AND status = 'Completed' THEN 1 ELSE 0 END) AS completed_assigned_count,
        SUM(CASE WHEN created_by = ? AND status = 'Completed' THEN 1 ELSE 0 END) AS completed_created_count
    FROM task
");

$stmt->bind_param("sssss", $username, $username, $username, $username, $username);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();


echo json_encode([
    'pending'    => $result['pending_count'],
    'total'      => $result['total_count'],
    'reviewing'  => $result['reviewing_count'],
    'completedA' => $result['completed_assigned_count'],
    'completedC' => $result['completed_created_count'],
]);
?>