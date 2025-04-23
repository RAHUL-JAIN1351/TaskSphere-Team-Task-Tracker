<?php
session_start();

// Dummy admin credentials
$admin_user = 'Dinesh';
$admin_pass = 'Dinesh@123';

// Get POST data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === $admin_user && $password === $admin_pass) {
    $_SESSION['admin'] = $username;
    $_SESSION['admin_logged_in'] = true;
    echo 'success';
} else {
    echo 'Invalid admin credentials';
}
?>