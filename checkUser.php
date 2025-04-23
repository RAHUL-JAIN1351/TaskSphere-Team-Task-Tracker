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

// Check if any field exists in the database
$query = "SELECT * FROM users WHERE username = '$username' OR email = '$email' OR phone = '$phone'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if ($user['username'] == $username) {
        echo 'username_exists';
    } elseif ($user['email'] == $email) {
        echo 'email_exists';
    } elseif ($user['phone'] == $phone) {
        echo 'phone_exists';
    }
} else {
    echo 'no_user';
}
?>