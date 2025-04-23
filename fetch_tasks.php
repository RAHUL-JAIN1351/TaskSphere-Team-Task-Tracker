<?php
session_start();

$host = "sql308.infinityfree.com";
$db = "if0_38681307_task";
$user = "if0_38681307";
$pass = "Nh8iwr7D6aHu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo "<div class='alert alert-danger'>Database connection failed</div>";
    exit();
}

$user_id = $_POST['user_id'];
$from = $_POST['from'];
$to = $_POST['to'];

if (!$user_id || !$from || !$to) {
    echo "<div class='alert alert-danger'>All fields are required!</div>";
    exit;
}

$query = "SELECT * FROM task WHERE assigned_to = ? AND created_at BETWEEN ? AND ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $user_id, $from, $to); // all strings now

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-hover'>";
    echo "<thead class='thead-dark'>
            <tr>
                <th>Task ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Created Date</th>
                <th>Due Date</th>
                <th>Submitted At</th>
                <th>Status</th>
            </tr>
          </thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . htmlspecialchars($row['description']) . "</td>
                <td>" . htmlspecialchars($row['created_by']) . "</td>
                <td>{$row['created_at']}</td>
                <td>{$row['due_date']}</td>
                <td>{$row['submitted_at']}</td>
                <td><span class='badge badge-" . 
                     ($row['status'] == 'Completed' ? 'success' : ($row['status'] == 'Pending' ? 'warning' : 'danger')) . 
                     "'>{$row['status']}</span></td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>No tasks found for selected user and date range.</div>";
}
?>
