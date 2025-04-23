<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Include PHPMailer
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Database credentials
$host = "sql308.infinityfree.com";
$db = "if0_38681307_task";
$user = "if0_38681307";
$pass = "Nh8iwr7D6aHu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo "Database connection failed";
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$em = $_POST['email'] ?? '';
$mob = $_POST['mobile'] ?? '';

if (!$username || !$password) {
    echo "All fields are required.";
    exit();
}

// Check if username exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Username already exists.";
    exit();
}

// Insert user
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, email, contact, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $em, $mob, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;

    // Send welcome email
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Or your SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'rahuljain.6895@gmail.com'; // Use your Gmail
        $mail->Password = 'rdlf nask izgq tpyi ';   // Use App Password (not your Gmail password)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('rahuljain.6895@gmail.com', 'TaskSphere');
        $mail->addAddress($em, $username);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to TaskSphere!';
        $mail->Body    = "Hello <strong>$username</strong>,<br><br>Welcome to <strong>TaskSphere</strong>! Your account has been successfully created.<br><br>Thank you!";

        $mail->send();
        echo "success";
    } catch (Exception $e) {
        echo "success"; // User signed up, just mail failed
        // Optionally: log $mail->ErrorInfo
    }
} else {
    echo "Signup failed. Try again.";
}

$conn->close();
