<?php
session_start();
$enteredOtp = $_POST['otp'] ?? '';
$email = $_POST['email'] ?? '';

if (!$enteredOtp || !$email) {
    echo 'OTP and email are required';
    exit();
}

// Check OTP validity
if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp && time() < $_SESSION['otp_expiry']) {
    echo 'success';
} else {
    echo 'invalid';
}
?>
