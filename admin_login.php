<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: admin_tasks.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - TaskSphere</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: #212529;
      color: #f8f9fa;
      overflow-x: hidden;
    }
    .admin-login-box {
      width: 90%;
      max-width: 400px;
      margin: 100px auto;
      background-color: #212529;
      border: 2px solid #343a40;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
      text-align: center;
    }
    .admin-login-box h3 {
      margin-bottom: 25px;
      font-weight: bold;
    }
    .admin-login-box input {
      background-color: #212529;
      color: #f8f9fa;
      border: 2px solid #343a40;
      font-weight: bold;
      box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5);
    }
    .admin-login-box input::placeholder {
      color: #aaa;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
      font-weight: bold;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .text-danger {
      margin-top: 10px;
    }
    .popup {
      position: fixed;
      top: 20px;
      right: 20px;
      background: #28a745;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      display: none;
      z-index: 9999;
    }
   #siteLoaderBlur {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  backdrop-filter: blur(5px);
  background-color: rgba(255, 255, 255, 0.3); /* subtle white tint */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  transition: opacity 0.5s ease, visibility 0.5s;
}
#siteLoaderBlur.hidden {
  opacity: 0;
  visibility: hidden;
}
/* Spinner */
.loader-spinner {
  width: 60px;
  height: 60px;
  border: 6px solid #ffc107;
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
  </style>
</head>
<body>


<div id="siteLoaderBlur">
  <div class="loader-spinner"></div>
</div>


<div class="admin-login-box">
  <h3>Admin Login</h3>
  <form id="admin-login-form">
    <input type="text" class="form-control mb-3" id="admin-username" placeholder="Admin Username" autocomplete='off' required>
    <input type="password" class="form-control mb-3" id="admin-password" placeholder="Password" required>
    <button type="submit" class="btn btn-primary w-100">Login</button>
    <p class="text-danger" id="admin-login-error"></p>
  </form>
</div>

<div class="popup" id="admin-popup">Success</div>



<script>

window.addEventListener('load', () => {
    setTimeout(() => {
      document.getElementById('siteLoaderBlur').classList.add('hidden');
    }, 500); // 2 seconds delay
  });


$(document).ready(function () {
  $('#admin-login-form').submit(function (e) {
    e.preventDefault();
    const username = $('#admin-username').val();
    const password = $('#admin-password').val();

    $('#adminLoader').fadeIn();

    $.post('admin_login_process.php', { username, password })
      .done(function (response) {
        if (response.trim() === 'success') {
          $('#admin-popup').text('Admin login successful!').fadeIn();
          setTimeout(() => {
            $('#admin-popup').fadeOut();
            window.location.href = 'admin_tasks.php';
          }, 1500);
        } else {
          $('#admin-login-error').text(response);
        }
      })
      .fail(function () {
        $('#admin-login-error').text('Server error. Please try again.');
      })
      .always(function () {
        $('#adminLoader').fadeOut(); // Always hide loader
      });
  });
});

</script>

</body>
</html>
