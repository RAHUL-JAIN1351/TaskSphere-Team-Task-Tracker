<?php
session_start();

// REUSE EXISTING DB CONNECTION FROM main context (if embedded)
$servername = "sql308.infinityfree.com";
$username_db = "if0_38681307";
$password_db = "Nh8iwr7D6aHu";
$database = "if0_38681307_task"; // Change if different

$conn = new mysqli($servername, $username_db, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    echo "DB CONNECT ERROR";
    exit;
}

// Check if user session is set
if (!isset($_SESSION['username'])) {
    echo "0";
    exit;
}

$loggedUser = $_SESSION['username'];

$sql = "SELECT COUNT(*) AS pending_count FROM tasks WHERE assigned_to = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "SQL PREP ERROR: " . $conn->error;
    exit;
}

$stmt->bind_param("s", $loggedUser);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo $row['pending_count'];
} else {
    echo "0";
}
?>