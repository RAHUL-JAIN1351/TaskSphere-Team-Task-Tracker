<?php
session_start();

if (!isset($_SESSION["username"])) {
    http_response_code(401);
    echo "Unauthorized";
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "sql308.infinityfree.com"; // Replace with your InfinityFree DB host
$db = "if0_38681307_task";              // Replace with your DB name
$user = "if0_38681307";            // Replace with your DB user
$pass = "Nh8iwr7D6aHu";        // Replace with your DB password

    $conn = new mysqli($host, $user, $pass,$db);
    if ($conn->connect_error) {
        http_response_code(500);
        echo "Database connection failed: " . $conn->connect_error;
        exit();
    }

    // Get data from POST request
    $title = $_POST["title"] ?? '';
    $description = $_POST["description"] ?? '';
    $assignee = $_POST["assignee"] ?? '';
    $due_date = $_POST["due_date"] ?? '';
    $created_by = $_SESSION["username"];

    if (empty($title) || empty($description) || empty($assignee) || empty($due_date)) {
        http_response_code(400);
        echo "All fields are required.";
        exit();
    }

    // Insert into `task` table
    $stmt = $conn->prepare("INSERT INTO task (title, description, assigned_to, due_date, created_by, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("sssss", $title, $description, $assignee, $due_date, $created_by);

    if ($stmt->execute()) {
        echo "Task created successfully.";
    } else {
        http_response_code(500);
        echo "Failed to create task.";
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo "Invalid request method.";
}