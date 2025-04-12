<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TaskSphere - Login/Signup</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: #f4f4f4;
      overflow-x: hidden;
    }
    .main-header {
      background-color: #343a40;
      color: white;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
    }

    .main-header .title {
      font-size: 2.2rem;
      font-weight: bold;
    }

    .form-box {
      width: 350px;
      margin: 100px auto;
      padding: 30px;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      border-radius: 8px;
      position: relative;
      z-index: 1;
    }

    .toggle-btn {
      width: 50%;
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

    /* Blurred background overlay */
    #blur-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      backdrop-filter: blur(6px);
      background-color: rgba(0, 0, 0, 0.2);
      z-index: 9998;
    }

    #forgot-popup {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(0.8);
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.25);
  padding: 30px;
  width: 450px; /* Wider */
  z-index: 9999;
  opacity: 0;
  transition: all 0.4s ease-in-out;
}

#forgot-popup.show {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
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
  border: 6px solid #007bff;
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


<header class="main-header">
    <div>
      <i class="fas fa-bars hamburger" id="hamburgerMenu"></i>
      <span class="title ms-2">TaskSphere</span>
    </div>
    <div class="profile-dropdown" id="profileDropdown">
  <i class="fas fa-user-circle fa-2x text-white" id="profileIcon" style="cursor: pointer;"></i>
  <div class="dropdown-menu" id="dropdownMenu">
    <div class="dropdown-header font-weight-bold text-dark">
      👤 User ID: <?php echo $userId; ?>
    </div>
    <div class="dropdown-divider"></div>
    <a href="logout.php" id="logoutBtn">Logout</a>
  </div>
</div>

  </header>


  
<div class="popup" id="popup-msg">Success</div>

<div class="form-box text-center">
  <div class="btn-group w-100 mb-3" role="group">
    <button class="btn btn-outline-primary toggle-btn" id="login-tab">Login</button>
    <button class="btn btn-outline-secondary toggle-btn" id="signup-tab">Sign Up</button>
  </div>

  <!-- Login Form -->
  <form id="login-form">
    <input type="text" class="form-control mb-2" id="login-username" placeholder="Username" required>
    <input type="password" class="form-control mb-2" id="login-password" placeholder="Password" required>
    <button type="submit" class="btn btn-primary w-100">Login</button>
    <small class="d-block mt-3"><a href="#" id="forgot-link">Forgot Password?</a></small>


    <p class="text-danger mt-2" id="login-error"></p>
  </form>

  <!-- Signup Form -->
  <form id="signup-form" style="display: none;">
    <input type="text" class="form-control mb-2" id="signup-username" placeholder="Username" required>
    <input type="password" class="form-control mb-2" id="signup-password" placeholder="Password" required>
    <button type="submit" class="btn btn-success w-100">Sign Up</button>
    <p class="text-danger mt-2" id="signup-error"></p>
  </form>
</div>

<!-- Blur Overlay -->
<div id="blur-overlay"></div>

<!-- Forgot Password Popup -->
<div id="forgot-popup" class="text-center">
  <h5>Reset Password</h5>
  <form id="forgot-form">
    <input type="text" class="form-control mb-2" id="forgot-username" placeholder="User Name" required>
    <input type="password" class="form-control mb-2" id="new-password" placeholder="New Password" required>
    <input type="password" class="form-control mb-2" id="confirm-password" placeholder="Confirm Password" required>
    <button type="submit" class="btn btn-warning w-100">Reset Password</button>
    <p class="text-danger mt-2" id="forgot-error"></p>
    <button type="button" class="btn btn-link mt-2" id="cancel-forgot">Cancel</button>
  </form>
</div>

<script>
window.addEventListener('load', () => {
    setTimeout(() => {
      document.getElementById('siteLoaderBlur').classList.add('hidden');
    }, 500); // 👈 2 seconds delay
  });
  
  $(document).ready(function () {
    // Tab switching
    $('#login-tab').click(function () {
      $('#signup-form').hide();
      $('#login-form').show();
    });

    $('#signup-tab').click(function () {
      $('#login-form').hide();
      $('#signup-form').show();
    });

    // Login Handler
    $('#login-form').submit(function (e) {
      e.preventDefault();
      const username = $('#login-username').val();
      const password = $('#login-password').val();

      $.post('login.php', { username, password }, function (response) {
        if (response === 'success') {
          showPopup('Logged in successfully!');
        } else {
          $('#login-error').text(response);
        }
      });
    });

    // Signup Handler
    $('#signup-form').submit(function (e) {
      e.preventDefault();
      const username = $('#signup-username').val();
      const password = $('#signup-password').val();

      $.post('signup.php', { username, password }, function (response) {
        if (response === 'success') {
          showPopup('Signed up successfully!');
        } else {
          $('#signup-error').text(response);
        }
      });
    });

    // Live Password Match Check
$('#new-password, #confirm-password').on('input', function () {
  const newPass = $('#new-password').val();
  const confirmPass = $('#confirm-password').val();

  if (newPass && confirmPass && newPass !== confirmPass) {
    $('#forgot-error').text('Passwords do not match.');
    $('#forgot-form button[type="submit"]').prop('disabled', true);
  } else {
    $('#forgot-error').text('');
    $('#forgot-form button[type="submit"]').prop('disabled', !(newPass && confirmPass));
  }
});


    // Show Forgot Password Popup
    $('#forgot-link').click(function (e) {
  e.preventDefault();
  $('#blur-overlay').fadeIn();
  $('#forgot-popup').css('display', 'block'); // Only show it, let CSS animate
  setTimeout(() => $('#forgot-popup').addClass('show'), 10);
});

   $('#cancel-forgot').click(function () {
  $('#forgot-popup').removeClass('show');
  $('#blur-overlay').fadeOut();
  setTimeout(() => $('#forgot-popup').css('display', 'none'), 400); // Match CSS transition
});

    // Forgot Password Handler
    $('#forgot-form').submit(function (e) {
      e.preventDefault();
      const username = $('#forgot-username').val();
      const newPassword = $('#new-password').val();
      const confirmPassword = $('#confirm-password').val();

      if (newPassword !== confirmPassword) {
        $('#forgot-error').text('Passwords do not match.');
        return;
      }

      $.post('reset_password.php', { username, newPassword }, function (response) {
        if (response === 'success') {
          showPopup('Password reset successfully!');
          $('#forgot-popup').removeClass('show').fadeOut();
          $('#blur-overlay').fadeOut();
        } else {
          $('#forgot-error').text(response);
        }
      });
    });

    // Reusable popup message
    function showPopup(msg) {
      $('#popup-msg').text(msg).fadeIn();
      setTimeout(() => {
        $('#popup-msg').fadeOut();
        window.location.href = 'home.php';
      }, 1500);
    }
  });
</script>

</body>
</html>