<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logout</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: #fff;
    }

    /* Loader Styles */
    #loader {
      position: fixed;
      width: 100%;
      height: 100%;
      background: #1e1e2f;
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .spinner {
      border: 6px solid #f3f3f3;
      border-top: 6px solid #667eea;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Message Styles */
    #logoutMsg {
      display: none;
      background-color: rgba(0, 0, 0, 0.75);
      padding: 30px 40px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      font-size: 1.3rem;
    }

    #logoutMsg span {
      font-size: 2rem;
      display: block;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<!-- Loader -->
<div id="loader">
  <div class="spinner"></div>
</div>

<!-- Logout Message -->
<div id="logoutMsg">
  <span>✅</span>
  Logged out successfully!
</div>

<script>
  $(document).ready(function () {
    // Simulate a small delay before showing message
    setTimeout(function () {
      $('#loader').fadeOut(500, function () {
        $('#logoutMsg').fadeIn(500);
      });

      // Redirect after message is shown
      setTimeout(function () {
        window.location.href = 'index.php';
      }, 2500);
    }, 1000); // loader stays for 1 second
  });
</script>

</body>
</html>
