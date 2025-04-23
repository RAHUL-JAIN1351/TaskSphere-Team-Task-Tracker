<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

$email = $_POST['email'] ?? '';

if (!$email) {
    echo 'Email is required';
    exit();
}

// Generate OTP
$otp = rand(100000, 999999);

// Store OTP in the session or database (for demo purposes, we use session)
session_start();
$_SESSION['otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 300; // OTP expiry time (5 minutes)

// Send OTP to the user's email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rahuljain.6895@gmail.com';
    $mail->Password = 'rdlf nask izgq tpyi ';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('rahuljain.6895@gmail.com', 'TaskSphere');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for TaskSphere Signup';
    $mail->Body    = "Your OTP for TaskSphere signup is <strong>$otp</strong>.<br><br>It will expire in 5 minutes.";

    $mail->send();
    echo 'success';
} catch (Exception $e) {
    echo 'failed';
}
?>
