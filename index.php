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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TaskSphere - Login/Signup</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>

  .title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #fff;
}

img {
  border-radius: 8px; /* Optional: round corners */
}
    body {
      background-color: #212529;
            color: #f8f9fa;
      overflow-x: hidden;
    }
    .main-header {
      background-color: #212529;
            color: #f8f9fa;
      border-bottom:1px solid #343a40;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
    }

    .main-header .title {
      font-size: 2.2rem;
      font-weight: bold;
      cursor:pointer;
    }

    /* Animated underline on hover */
.main-header .title::after {
  content: "";
  display: block;
  width: 0%;
  height: 2px;
  background: #ffc107;
  transition: width 0.3s;
  margin-top: 5px;
}

.main-header .title:hover::after {
  width: 70%;
  margin-inline: auto;
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
      width: 90%;
  max-width: 450px;
  max-height: 90vh;
  overflow-y: auto;
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
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
  padding: 30px;
  width: 90%;
  max-width: 450px;
  max-height: 90vh;
  overflow-y: auto;
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





.blur-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: none; /* Hidden by default */
  justify-content: center;
  align-items: center;
  backdrop-filter: blur(4px);
  background-color: rgba(0, 0, 0, 0.4); /* Slightly darker for better contrast */
  z-index: 1000;
  /* Center the overlay in the screen */
   /* This is required to center its content when shown */
}


.create-task-popup {
  background-color: #212529 !important;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(255, 255, 255, 0.05); /* subtle white glow */
  width: 90%;
  max-width: 600px;
  animation: popupFadeIn 0.5s ease forwards;
  opacity: 0;
  transform: translateY(20px);
  position: relative;

  /* ✅ Already good additions */
  max-height: 90vh;
  overflow-y: auto;

  /* ✅ Styling */
  color: #f8f9fa;
  font-family: Tahoma, sans-serif;
  text-align: center;
}

@keyframes popupFadeIn {
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.close-btn {
  position: absolute;
  top: 20px;
  right: 25px;
  font-size: 1.2rem;
  cursor: pointer;
  color: #555;
  background-color: #212529;
  color: #f8f9fa;
}

.close-btn:hover {
  color: #ffc107;
}






footer {
  background: rgba(24, 26, 29, 0.85);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-top: 1px solid rgba(255, 255, 255, 0.05);
  color: #ccc;
  font-size: 15px;
  line-height: 1.7;
  padding: 27px 0 30px;
  box-shadow: 0 -1px 30px rgba(0, 0, 0, 0.3);
  animation: fadeInUp 1.2s ease-out both;
}

footer h5 {
  color: #fff;
  margin-bottom: 20px;
  font-weight: 600;
  font-size: 18px;
  text-shadow: 0 0 6px rgba(0, 123, 255, 0.4);
  display: flex;
  align-items: center;
  gap: 8px;
}

footer a {
  color: #bbb;
  text-decoration: none;
  display: inline-block;
  margin-bottom: 8px;
  padding-left: 5px;
  position: relative;
  transition: all 0.3s ease;
}

footer a::before {
  content: '';
  position: absolute;
  left: 0;
  bottom: 2px;
  width: 0%;
  height: 2px;
  background: #ffc107;
  transition: 0.3s ease;
}

footer a:hover {
  color: #fff;
  transform: translateX(6px);
  text-shadow: 0 0 8px rgba(13, 110, 253, 0.8);
}

footer a:hover::before {
  width: 80%;
}

.footer-bottom {
  border-top: 1px solid #2b2e31;
  margin-top: 30px;
  padding-top: 20px;
  text-align: center;
  font-size: 14px;
  color: #888;
  text-shadow: 0 0 5px rgba(255, 255, 255, 0.05);
}

.footer-icons {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  margin-top: 10px;
}

.footer-icons a {
  font-size: 22px;
  color: #bbb;
  transition: color 0.3s ease, transform 0.3s ease;
}

.footer-icons a:hover {
  color: #ffc107;
  transform: scale(1.2) rotate(-2deg);
  text-shadow: 0 0 10px rgba(13, 110, 253, 0.6);
}

footer p i {
  margin-right: 8px;
  color: #bbb;
}

@media (max-width: 767px) {
  footer .row > div {
    margin-bottom: 30px;
  }
}

/* Fade-in animation */
@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Footer link list styling */
.footer-links {
  padding-left: 0;
  margin-top: 10px;
}

.footer-links li {
  margin-bottom: 8px;
  padding-left: 28px; /* indent so text aligns under heading text, adjust as needed */
  position: relative;
}

/* Optional: add a subtle bullet or icon before each link */
.footer-links li::before {
  content: '›';
  position: absolute;
  left: 0;
  color: #bbb;             /* neutral gray */
  font-size: 16px;
  line-height: 1;
  transition: color 0.3s ease;
}

/* Keep your existing link hover underline-replacement */
.footer-links a {
  color: #bbb;
  text-decoration: none;
  transition: color 0.3s ease, transform 0.3s ease, text-shadow 0.3s ease;
}

.footer-links a:hover {
  color: #fff;
  transform: translateX(4px);
  text-shadow: 0 0 8px rgba(13, 110, 253, 0.8);
}
/* On hover—highlight bullet in blue */
.footer-links li:hover::before {
  color: #ffc107;
}

/* And keep the link text highlighting on hover */
.footer-links a:hover {
  color: #fff;
  transform: translateX(4px);
  text-shadow: 0 0 8px rgba(13, 110, 253, 0.8);
}

/* If you want the bullet to also slide with the text */
.footer-links li:hover a {
  transform: translateX(6px);
}

footer .container .row h5{
    cursor:pointer;
}

footer .container .row h5:hover {
  color: #fff;
  transform: translateX(5px);
  text-shadow: 0 0 8px rgba(13, 110, 253, 0.8);
  transition: color 0.3s ease, transform 0.3s ease, text-shadow 0.3s ease;
}
/* On hover—highlight bullet in blue */
footer .container .row h5:hover::before {
  color: #ffc107;
}








html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  background-color: #181a1d; /* match main background */
}
main {
  flex: 1;
}






  </style>
</head>
<body>

<main>

<div id="siteLoaderBlur">
  <div class="loader-spinner"></div>
</div>








<header class="main-header">
    <div class="d-flex align-items-center">
  <span class="title">Tasksphere Task Assigner</span>
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

<div class="form-box text-center" style="background-color: #212529;
            color: #f8f9fa;box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);border:2px solid #343a40;">
  <div class="btn-group w-100 mb-3" role="group">
    <button class="btn btn-outline-primary toggle-btn" id="login-tab">Login</button>
    <button class="btn btn-outline-secondary toggle-btn" id="signup-tab">Sign Up</button>
  </div>

  <!-- Login Form -->
  <form id="login-form">
    <input type="text" class="form-control mb-2" id="login-username" placeholder="Username" required style="background-color: #212529;
            color: #f8f9fa;box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5);border:2px solid #343a40;font-weight:bold;" autocomplete="off">
    <input type="password" class="form-control mb-2" id="login-password" placeholder="Password" required style="background-color: #212529;
            color: #f8f9fa;box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);border:2px solid #343a40;font-weight:bold;">
    <button type="submit" class="btn btn-primary w-100" >Login</button>
    <small class="d-block mt-3"><a href="#" id="forgot-link" style="font-family:Tahoma;">Forgot Password?</a></small>


    <p class="text-danger mt-2" id="login-error"></p>
  </form>

















<!-- Signup Form -->
<form id="signup-form" style="display: none;">
  <input type="text" class="form-control mb-2" id="signup-username" placeholder="Username" required style="background-color: #212529; color: #f8f9fa; box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5); border:2px solid #343a40; font-weight:bold;" autocomplete="off">

  <input type="email" class="form-control mb-2" id="signup-email" placeholder="Email" required style="background-color: #212529; color: #f8f9fa; box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5); border:2px solid #343a40; font-weight:bold;" autocomplete="off">
  <small class="text-danger mb-2 d-block" id="email-error"></small>

  <input type="tel" class="form-control mb-2" id="signup-mobile" placeholder="Mobile Number" required pattern="[0-9]{10}" title="Enter 10 digit mobile number" style="background-color: #212529; color: #f8f9fa; box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5); border:2px solid #343a40; font-weight:bold;" autocomplete="off">
  <small class="text-danger mb-2 d-block" id="mobile-error"></small>

  <input type="password" class="form-control mb-2" id="signup-password" placeholder="Password" required style="background-color: #212529; color: #f8f9fa; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4); border:2px solid #343a40; font-weight:bold;">

  <button type="submit" class="btn btn-success w-100" id="signup-btn" disabled>Sign Up</button>
  <p class="text-danger mt-2" id="signup-error"></p>
</form>




<!-- OTP Verification Popup 
<div id="otp-popup" class="text-center" style="background-color: #212529; color: #f8f9fa; box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5); border:2px solid #343a40;">
  <h5 style="font-weight:bold;">Enter OTP</h5>
  <form id="otp-form">
    <input type="text" class="form-control mb-2" id="otp-input" placeholder="Enter OTP" required style="background-color: #212529; color: #f8f9fa; box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5); border:2px solid #343a40; font-weight:bold;" autocomplete="off">
    <button type="submit" class="btn btn-warning w-100">Verify OTP</button>
    <p class="text-danger mt-2" id="otp-error"></p>
    <button type="button" class="btn btn-link mt-1 text-warning" id="cancel-otp" style="font-family:Tahoma;">Cancel</button>
  </form>
</div>
-->


<div class="blur-overlay" id="otp-popup">
  <div class="create-task-popup">
    <h5 style="font-weight:bold;">Enter OTP</h5>
  <form id="otp-form">
    <input type="text" class="form-control mb-2" id="otp-input" placeholder="Enter OTP" required style="background-color: #212529; color: #f8f9fa; box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5); border:2px solid #343a40; font-weight:bold;" autocomplete="off">
    <button type="submit" class="btn btn-warning w-100">Verify OTP</button>
    <p class="text-danger mt-2" id="otp-error"></p>
    <button type="button" class="btn btn-link mt-1 text-warning" id="cancel-otp" style="font-family:Tahoma;">Cancel</button>
  </form>
  </div>
</div>











</div>

<!-- Blur Overlay -->
<div id="blur-overlay"></div>

<!-- Forgot Password Popup -->
<div id="forgot-popup" class="text-center" style="background-color: #212529;
            color: #f8f9fa;box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5);border:2px solid #343a40;">
  <h5 style="font-weight:bold;">Reset Password</h5>
  <form id="forgot-form">
    <input type="text" class="form-control mb-2" id="forgot-username" placeholder="User Name" required style="background-color: #212529;
            color: #f8f9fa;box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5);border:2px solid #343a40;font-weight:bold;" autocomplete="off">
    <input type="password" class="form-control mb-2" id="new-password" placeholder="New Password" required style="background-color: #212529;
            color: #f8f9fa;box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5);border:2px solid #343a40;font-weight:bold;" autocomplete="off">
    <input type="password" class="form-control mb-2" id="confirm-password" placeholder="Confirm Password" required style="background-color: #212529;
            color: #f8f9fa;box-shadow: 0 8px 16px rgba(0, 0, 3, 0.5);border:2px solid #343a40;font-weight:bold;" autocomplete="off">
    <button type="submit" class="btn btn-warning w-100">Reset Password</button>
    <p class="text-danger mt-2" id="forgot-error"></p>
    <button type="button" class="btn btn-link mt-1 text-warning" id="cancel-forgot" style="font-family:Tahoma;">Cancel</button>
  </form>
</div>



</main>



<footer>
  <div class="container">
    <div class="row">
      <!-- About -->
      <div class="col-md-4">
        <h5><i class="bi bi-info-circle-fill me-2"></i> About TaskSphere</h5>
        <p>TaskSphere helps teams streamline task flow, boost collaboration, and increase productivity with a clean, centralized management platform.</p>
      </div>

      <!-- Quick Links -->
<div class="col-md-2">
  <h5><i class="bi bi-lightning-charge-fill me-2"></i> Quick Links</h5>
  <ul class="footer-links list-unstyled">
    <li><a href="#">Home</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="#">Contact</a></li>
    <li><a href="#">Support</a></li>
  </ul>
</div>


      <!-- Contact -->
      <div class="col-md-3">
        <h5><i class="bi bi-envelope-fill me-2"></i> Contact</h5>
        <p><i class="bi bi-envelope me-2"></i> support@tasksphere.com</p>
        <p><i class="bi bi-phone me-2"></i> +91 98765 43210</p>
      </div>

      <!-- Social Media -->
      <div class="col-md-3">
        <h5><i class="bi bi-share-fill me-2"></i> Connect With Us</h5>
        <div class="footer-icons d-flex flex-wrap">
          <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="https://www.instagram.com/rahul_j_jain?igsh=czZnNjR6OXdnYnk3" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
    </div>

    <div class="footer-bottom" style="font-size:1rem;font-weight:bold;">
      &copy; <span id="year"></span> Rahul Jain. All rights reserved.
    </div>
  </div>
</footer>

<script>
  document.getElementById("year").textContent = new Date().getFullYear();
</script>














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

   









// Validate Email
$('#signup-email').on('input', function() {
  const email = $(this).val().trim();
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
  if (!emailPattern.test(email)) {
    $('#email-error').text('Invalid email format.');
  } else {
    $('#email-error').text('');
  }
  validateSignupForm();
});

// Validate Mobile Number
$('#signup-mobile').on('input', function() {
  const mobile = $(this).val().trim();
  const mobilePattern = /^[0-9]{10}$/;
  
  if (!mobilePattern.test(mobile)) {
    $('#mobile-error').text('Mobile must be 10 digits.');
  } else {
    $('#mobile-error').text('');
  }
  validateSignupForm();
});


// General form validation to enable/disable submit button
function validateSignupForm() {
  const email = $('#signup-email').val().trim();
  const mobile = $('#signup-mobile').val().trim();

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const mobilePattern = /^[0-9]{10}$/;

  let valid = true;

  if (!emailPattern.test(email) || !mobilePattern.test(mobile)) {
    valid = false;
  }

  $('#signup-btn').prop('disabled', !valid);
}


























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










// OTP handler
$('#signup-form').submit(function (e) {
  e.preventDefault();
  
  const username = $('#signup-username').val();
  const email = $('#signup-email').val();
  const mobile = $('#signup-mobile').val();
  const password = $('#signup-password').val();

  // Show OTP popup
  $('#otp-popup').css('display', 'flex');

  // Send OTP to the user's email
  $.post('send_otp.php', { email: email }, function (response) {
    if (response === 'success') {
      // Wait for user to enter OTP
      $('#otp-form').submit(function (e) {
        e.preventDefault();
        
        const enteredOtp = $('#otp-input').val().trim();

        // Verify OTP
        $.post('verify_otp.php', { otp: enteredOtp, email: email }, function (otpResponse) {
          if (otpResponse === 'success') {
            // Proceed with adding the user to the database
            $.post('signup.php', { username, email, mobile, password }, function (signupResponse) {
              if (signupResponse === 'success') {
                showPopup('Signed up successfully!');
              } else {
                $('#signup-error').text(signupResponse);
              }
            });
          } else {
            $('#otp-error').text('Invalid OTP. Please try again.');
          }
        });
      });
    } else {
      $('#otp-error').text('Failed to send OTP. Please try again.');
    }
  });
});

// Close OTP popup if user cancels
$('#cancel-otp').click(function () {
  $('#otp-popup').hide();
  $('#blur-overlay').fadeOut();
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
