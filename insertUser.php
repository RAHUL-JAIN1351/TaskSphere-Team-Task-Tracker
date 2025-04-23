<?php
session_start();
$host = "sql308.infinityfree.com"; // Replace with your InfinityFree DB host
$db = "if0_38681307_task";              // Replace with your DB name
$user = "if0_38681307";            // Replace with your DB user
$pass = "Nh8iwr7D6aHu";        // Replace with your DB password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo "Database connection failed";
    exit();
}

$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$query = "INSERT INTO users (username, email, phone, password) VALUES ('$username', '$email', '$phone', '$password')";
if (mysqli_query($conn, $query)) {
    echo 'success';
} else {
    echo 'fail';
}
?>