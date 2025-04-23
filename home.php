
<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$host = "sql308.infinityfree.com"; // Replace with your InfinityFree DB host
$db = "if0_38681307_task";              // Replace with your DB name
$user = "if0_38681307";            // Replace with your DB user
$pass = "Nh8iwr7D6aHu";        // Replace with your DB password

$conn = new mysqli($host, $user, $pass, $db);

$username = $_SESSION['username'];

$query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$userid = $row['id'];

$_SESSION['userid'] = $userid;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TaskSphere | Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>




/* Scrollbar styling for WebKit (Chrome, Edge, Safari) */
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

::-webkit-scrollbar-track {
  background: #212529; /* Matches your page background */
}

::-webkit-scrollbar-thumb {
  background-color: #495057; /* A lighter gray for contrast */
  border-radius: 10px;
  border: 2px solid #212529; /* Creates padding inside track */
}

::-webkit-scrollbar-thumb:hover {
  background-color: #adb5bd; /* Lighter on hover for visibility */
}

/* Scrollbar styling for Firefox */
* {
  scrollbar-width: thin;
  scrollbar-color: #495057 #212529;
}










    body {
      background-color: #f8f9fa;
      overflow-x: hidden;
    }

    .main-header {
      //background-color: #343a40;
      //color: white;
      background-color: #212529;
            color: #f8f9fa;
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









    .profile-dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      padding: 0.5rem 0;
      z-index: 999;
    }

    .dropdown-menu a {
      display: block;
      padding: 0.5rem 1rem;
      color: #333;
      text-decoration: none;
    }

    .dropdown-menu a:hover {
      background: rgba(255, 255, 255, 0.05);
  color: #ffc107;
  box-shadow: 0 0 12px rgba(255, 193, 7, 0.25);
    }




/* Animated underline on hover */
.dropdown-menu a::after {
  content: "";
  display: block;
  width: 0%;
  height: 2px;
  background: #ffc107;
  transition: width 0.3s;
  margin-top: 5px;
}

.dropdown-menu a:hover::after {
  width: 60%;
  margin-inline: auto;
}

.dropdown-header{
    cursor:pointer;
}
.dropdown-header .ID::after{
      content: "";
  display: block;
  width: 0%;
  height: 2px;
  background: #ffc107;
  transition: width 0.3s;
  margin-top: 5px;
}
.dropdown-header .ID:hover::after {
  width: 60%;
  margin-inline: auto;
  cursor:pointer;
}
.dropdown-header .ID:hover {
      background: rgba(255, 255, 255, 0.05);
  color: #ffc107;
  box-shadow: 0 0 12px rgba(255, 193, 7, 0.25);
    }
















    .nav-tabs-container {
      background-color: #ffffff;
      border-bottom: 1px solid #ddd;
      padding: 0.5rem 1rem;
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
      gap: 1rem;
      width: 100%;
      box-sizing: border-box;
      transition: transform 0.3s ease-in-out;
    }

    .nav-tab {
      background-color: #e9ecef;
      border-radius: 10px;
      padding: 0.6rem 1.25rem;
      color: #333;
      text-decoration: none;
      font-weight: 500;
      position: relative;
      transition: background-color 0.3s ease;
      white-space: nowrap;
      flex: 1;
      text-align: center;
    }

    .nav-tab:hover {
      background-color: #d4d4d4;
    }

    .badge-circle {
      background-color: #dc3545;
      color: white;
      font-size: 0.7rem;
      padding: 4px 8px;
      border-radius: 50%;
      position: absolute;
      top: -8px;
      right: -10px;
    }

    .hamburger {
      display: none;
      font-size: 1.5rem;
      cursor: pointer;
    }

    @media (max-width: 992px) {
      .hamburger {
        display: inline;
      }
      .main-header .title {
      font-size: 1.6rem;
    }
      .nav-tabs-container {
        position: absolute;
        top: 60px;
        left: -100%;
        flex-direction: column;
        gap: 0;
        background-color: white;
        width: 250px;
        height: 100vh;
        padding-top: 1rem;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        z-index: 998;
      }

      .nav-tabs-container.active {
        left: 0;
      }

      .nav-tab {
        flex: none;
        text-align: left;
        padding: 1rem;
        border-bottom: 1px solid #ddd;
      }
    }
.blur-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: none; /* hidden by default */
  justify-content: center;
  align-items: center;
  backdrop-filter: blur(4px);
  background-color: rgba(0, 0, 0, 0.4); /* Slightly darker for better contrast */
  z-index: 1000;
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

.toast-popup {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: #28a745;
  color: #fff;
  padding: 14px 24px;
  border-radius: 12px;
  font-weight: bold;
  font-size: 1rem;
  box-shadow: 0 4px 16px rgba(255, 255, 255, 0.1); /* white glow */
  display: none;
  z-index: 9999;
  animation: slideFadeIn 0.4s ease forwards;
  font-family: Tahoma, sans-serif;
}

/* Optional: Smooth fade animation */
@keyframes popupFadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideFadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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














/* Glassy Navbar */
.nav-glass {
  background: linear-gradient(135deg, rgba(25,25,25,0.85), rgba(40,40,40,0.85));
  backdrop-filter: blur(10px);
  border-radius: 0 0 12px 12px;
}

/* Transition for navbar collapse */
.transition-collapse {
  transition: height 0.4s ease, opacity 0.4s ease;
}

/* Link Styles */
.custom-nav-link {
  position: relative;
  padding: 1rem 0.75rem;
  font-weight: 500;
  color: #f8f9fa;
  border-radius: 10px;
  transition: all 0.3s ease-in-out;
}

.custom-nav-link:hover {
  background: rgba(255, 255, 255, 0.05);
  color: #ffc107;
  box-shadow: 0 0 12px rgba(255, 193, 7, 0.25);
}

/* Animated underline on hover */
.custom-nav-link::after {
  content: "";
  display: block;
  width: 0%;
  height: 2px;
  background: #ffc107;
  transition: width 0.3s;
  margin-top: 5px;
}

.custom-nav-link:hover::after {
  width: 60%;
  margin-inline: auto;
}

/* Adjust icon spacing */
.custom-nav-link i {
  font-size: 1rem;
  vertical-align: middle;
}

/* Remove bottom borders on larger screens */
@media (min-width: 992px) {
  .navbar-nav .nav-item {
    border-bottom: none;
  }
  .custom-nav-link {
    padding: 0.75rem 1rem;
  }
}
/* Light Mode */
        body.light-mode {
            background-color: #f8f9fa;
            color: #343a40;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #212529;
            color: #f8f9fa;
        }









/* Default list item style */
#assignedTasksContainer .list-group-item {
  background: linear-gradient(145deg, #1c1f24, #2a2f35);
  color: #f8f9fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  border: 2px solid transparent; /* Initially transparent */
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
  position: relative;
  overflow: hidden;
  line-height: 1.6;
  transition: all 0.3s ease;
}

/* The "snake" border animation */
#assignedTasksContainer .list-group-item::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 100%;
  border: 2px solid #ffc107; /* Yellow border */
  border-radius: 16px;
  transition: width 0.5s ease-in-out; /* Smooth transition */
}

#assignedTasksContainer .list-group-item:hover {
  box-shadow: 0 0 20px 4px rgba(255, 193, 7, 0.7); /* Glowing effect */
  transform: scale(1.05); /* Slight scale-up effect */
}
#assignedTasksContainer h5 {
  font-weight: 900;
  font-size: 1.3rem;
  color: #ffc107;
  margin-bottom: 0.5rem;
}

#assignedTasksContainer p {
  margin: 0.4rem 0;
  font-size: 0.95rem;
}

#assignedTasksContainer small {
  font-size: 0.85rem;
  color: #adb5bd;
}

#assignedTasksContainer .badge {
  background-color: #0dcaf0;
  color: #212529;
  font-weight: 600;
  border-radius: 20px;
  padding: 4px 10px;
  font-size: 0.8rem;
  margin-left: 8px;
  box-shadow: 0 0 6px rgba(13, 202, 240, 0.5);
}

#assignedTasksContainer a {
  color: #0dcaf0;
  font-weight: 700;
  text-decoration: none;
  font-size: 1.1rem;
  display: inline-block;
  margin-bottom: 0.3rem;
  transition: color 0.3s;
}

#assignedTasksContainer a:hover {
  color: #ffc107;
  text-decoration: underline;
}






/* Custom style for reviewing task items */
.custom-task-item {
  border: 2px solid #212529 !important;
  background-color: #2a2f35; /* Optional: to match your dark UI */
  color: #f8f9fa;
  border-radius: 16px;
  transition: all 0.3s ease;
}

.custom-task-item:hover {
  box-shadow: 0 0 20px rgba(255, 193, 7, 0.3); /* Optional glow */
  transform: scale(1.02);
}












/* Default list item style */
#completedTasksContainer .list-group-item {
  background: linear-gradient(145deg, #1c1f24, #2a2f35);
  color: #f8f9fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  border: 2px solid transparent; /* Initially transparent */
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
  position: relative;
  overflow: hidden;
  line-height: 1.6;
  transition: all 0.3s ease;
}

/* The "snake" border animation */
#completedTasksContainer .list-group-item::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 100%;
  border-radius: 16px;
  transition: width 0.5s ease-in-out; /* Smooth transition */
  box-shadow: 0 5px 24px 10px rgba(255, 193, 7, 0.7); /* Glowing effect */
}

#completedTasksContainer .list-group-item:hover {
  box-shadow: 0 0 20px 4px rgba(255, 193, 7, 0.7); /* Glowing effect */
  transform: scale(1.05); /* Slight scale-up effect */
}
#completedTasksContainer h5 {
  font-weight: 900;
  font-size: 1.3rem;
  color: #ffc107;
  margin-bottom: 0.5rem;
}

#completedTasksContainer p {
  margin: 0.4rem 0;
  font-size: 0.95rem;
}

#completedTasksContainer small {
  font-size: 0.85rem;
  color: #adb5bd;
}

#completedTasksContainer .badge {
  background-color: #0dcaf0;
  color: #212529;
  font-weight: 600;
  border-radius: 20px;
  padding: 4px 10px;
  font-size: 0.8rem;
  margin-left: 8px;
  box-shadow: 0 0 6px rgba(13, 202, 240, 0.5);
}

#completedTasksContainer a {
  color: #0dcaf0;
  font-weight: 700;
  text-decoration: none;
  font-size: 1.1rem;
  display: inline-block;
  margin-bottom: 0.3rem;
  transition: color 0.3s;
}

#completedTasksContainer a:hover {
  color: #ffc107;
  text-decoration: underline;
}








/* Default list item style */
#completedAssignedTasksContainer .list-group-item {
  background: linear-gradient(145deg, #1c1f24, #2a2f35);
  color: #f8f9fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  border: 2px solid transparent; /* Initially transparent */
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
  position: relative;
  overflow: hidden;
  line-height: 1.6;
  transition: all 0.3s ease;
}

/* The "snake" border animation */
#completedAssignedTasksContainer .list-group-item::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 100%;
  border-radius: 16px;
  transition: width 0.5s ease-in-out; /* Smooth transition */
  box-shadow: 0 5px 24px 10px red /* Glowing effect */
}

#completedAssignedTasksContainer .list-group-item:hover {
  box-shadow: 0 0 20px 4px rgba(255, 193, 7, 0.7); /* Glowing effect */
  transform: scale(1.05); /* Slight scale-up effect */
}
#completedAssignedTasksContainer h5 {
  font-weight: 900;
  font-size: 1.3rem;
  color: #ffc107;
  margin-bottom: 0.5rem;
}

#completedAssignedTasksContainer p {
  margin: 0.4rem 0;
  font-size: 0.95rem;
}

#completedAssignedTasksContainer small {
  font-size: 0.85rem;
  color: #adb5bd;
}

#completedAssignedTasksContainer .badge {
  background-color: #0dcaf0;
  color: #212529;
  font-weight: 600;
  border-radius: 20px;
  padding: 4px 10px;
  font-size: 0.8rem;
  margin-left: 8px;
  box-shadow: 0 0 6px rgba(13, 202, 240, 0.5);
}

#completedAssignedTasksContainer a {
  color: #0dcaf0;
  font-weight: 700;
  text-decoration: none;
  font-size: 1.1rem;
  display: inline-block;
  margin-bottom: 0.3rem;
  transition: color 0.3s;
}

#completedAssignedTasksContainer a:hover {
  color: #ffc107;
  text-decoration: underline;
}













/* Initially hide collapse */
.navbar-collapse {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s ease, opacity 0.3s ease; /* Add opacity transition */
  opacity: 0; /* Initially hide content */
}

/* Show collapse on toggle */
.navbar-collapse.show {
  max-height: 500px;
  opacity: 1; /* Fade in when expanded */
}

/* Show hamburger only when screen is < 1000px */
@media (max-width: 1240px) {
  .navbar-toggler {
    display: block !important;
  }

  .navbar-collapse {
    max-height: 0 !important;
    opacity: 0 !important;
  }

  .navbar-collapse.show {
    max-height: 500px !important;
    opacity: 1 !important; /* Ensure smooth fade-in */
  }
}

/* When screen is greater than 1000px, show the menu as usual */
@media (min-width: 1000px) {
  .navbar-toggler {
    display: none !important;
  }

  .navbar-collapse {
    max-height: none !important;
    opacity: 1 !important; /* Ensure the menu is fully visible */
    overflow: visible !important;
  }
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

<body class="dark-mode">

<main>

<div id="siteLoaderBlur">
  <div class="loader-spinner"></div>
</div>


  <!-- Header -->
  <header class="main-header">
    <div class="d-flex align-items-center">
  <span class="title">Tasksphere</span>
</div>

    <div class="profile-dropdown" id="profileDropdown">
  <i class="fas fa-user-circle fa-2x text-white" id="profileIcon" style="cursor: pointer;"></i>

  <div class="dropdown-menu" id="dropdownMenu" style="background-color: #212529; color: #f8f9fa; font-weight: bold; text-align: center;">
  <div class="dropdown-header ID font-weight-bold d-flex justify-content-center align-items-center" style="color: #f8f9fa;">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#f8f9fa" class="bi bi-person-fill mr-2" viewBox="0 0 16 16">
      <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
    </svg>&ensp;
    User ID: <?php echo $_SESSION['userid']; ?>
  </div>
  <div class="dropdown-divider"></div>
  <a href="logout.php" id="logoutBtn" style="color: #f8f9fa; font-weight: bold;">Logout</a>
</div>


</div>

  </header>

  <!-- Toast -->
  <div class="toast-popup" id="welcomeToast">
    👋 Welcome, <?php echo $_SESSION["username"]; ?>!
  </div>












<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark nav-glass border-bottom shadow-lg">
  <div class="container-fluid">

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navTabs"
      aria-controls="navTabs" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse transition-collapse" id="navTabs">
      <ul class="navbar-nav w-100 d-flex flex-column flex-lg-row text-center gap-lg-2">

        <li class="nav-item flex-fill">
          <a href="#" class="nav-link custom-nav-link" id="createTaskLink">
            <i class="fas fa-plus-circle me-2 text-primary"></i> Create Task
          </a>
        </li>

        <li class="nav-item flex-fill">
          <a href="#" class="nav-link custom-nav-link" id="pendingTasksLink">
            <i class="fas fa-hourglass-half me-2 text-danger"></i> Pending Tasks
            <span class="badge rounded-pill bg-danger ms-1"><?php echo $pendingCount; ?></span>
          </a>
        </li>

        <li class="nav-item flex-fill">
          <a href="#" class="nav-link custom-nav-link" id="assignedTasksLink">
            <i class="fas fa-user-check me-2 text-warning"></i> Assigned Tasks
            <span class="badge rounded-pill bg-warning text-dark ms-1"><?php echo $totalCount; ?></span>
          </a>
        </li>

        <li class="nav-item flex-fill">
          <a href="#" class="nav-link custom-nav-link" id="reviewingTasksLink">
            <i class="fas fa-search me-2 text-info"></i> Reviewing Tasks
            <span class="badge rounded-pill bg-info text-dark ms-1"><?php echo $reviewingCount; ?></span>
          </a>
        </li>

        <li class="nav-item flex-fill">
          <a href="#" class="nav-link custom-nav-link" id="completedTasksLink">
            <i class="fas fa-check-circle me-2 text-success"></i> Completed Tasks
            <span class="badge rounded-pill bg-success ms-1"><?php echo $completedAssignedCount; ?></span>
          </a>
        </li>

        <li class="nav-item flex-fill">
          <a href="#" class="nav-link custom-nav-link" id="completedAssignedTasksLink">
            <i class="fas fa-list-check me-2 text-light"></i> My Created Completed Tasks
            <span class="badge rounded-pill bg-primary ms-1"><?php echo $completedCreatedCount; ?></span>
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>

























  <!-- Main Content -->
  <main class="p-3" id="mainContent"></main>

  <!-- Create Task Modal -->
  <div class="blur-overlay" id="taskOverlay">
    <div class="create-task-popup position-relative">
      <i class="fas fa-times close-btn" id="closePopup"></i>
      <h3 class="mb-4 text-primary fw-bold">Create New Task</h3>
      <form id="createTaskForm">
        <div class="mb-3">
          <label class="form-label fw-semibold">Task Title</label>
          <input type="text" class="form-control" id="taskTitle" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Description</label>
          <textarea class="form-control" id="taskDesc" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Assign To</label>
          <select class="form-select" id="assignee" required>
            <option value="">Loading users...</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Due Date</label>
          <input type="date" class="form-control" id="dueDate" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 fw-semibold">Create Task</button>
      </form>
    </div>
  </div>

<!-- Pending Tasks Modal -->
<div class="blur-overlay" id="pendingTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closePendingTasks"></i>
    <h3 class="mb-4 text-danger fw-bold">Your Pending Tasks</h3>
    <div id="pendingTasksContainer" style="background-color: #212529; color: #f8f9fa; font-family: Tahoma;"></div>
  </div>
</div>


  <!-- Assigned Tasks Modal -->
<div class="blur-overlay" id="assignedTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closeAssignedTasks"></i>
    <h3 class="mb-4 text-warning fw-bold">Tasks You Assigned</h3>
    <div id="assignedTasksContainer" style="background-color: #212529;color: #f8f9fa; font-family:Tahoma;"></div>
  </div>
</div>

<!-- Reviewing Tasks Modal -->
<div class="blur-overlay" id="reviewingTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closereviewingTasks"></i>
    <h3 class="mb-4 text-info fw-bold">Tasks Under Review</h3>
    <div id="reviewingTasksContainer"></div>
  </div>
</div>

<!-- Completed Tasks Modal -->
<div class="blur-overlay" id="completedTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closecompletedTasks"></i>
    <h3 class="mb-4 text-success fw-bold">Tasks Completed</h3>
    <div id="completedTasksContainer"></div>
  </div>
</div>


<!-- Completed Assigned Tasks Modal -->
<div class="blur-overlay" id="completedAssignedTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closecompletedAssignedTasks"></i>
    <h3 class="mb-4 text-primary fw-bold">My Created Completed Tasks</h3>
    <div id="completedAssignedTasksContainer"></div>
  </div>
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
















  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  
<script>

  window.addEventListener('load', () => {
    setTimeout(() => {
      document.getElementById('siteLoaderBlur').classList.add('hidden');
    }, 500); // 👈 2 seconds delay
  });

  $(document).ready(function () {




    
     $('.navbar-toggler').on('click', function () {
      $('.navbar-collapse').toggleClass('show');
    });

    function updateTaskCounts() {
  $.ajax({
    url: 'get_task_counts.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      $('.nav-link#pendingTasksLink .badge').text(data.pending);
      $('.nav-link#assignedTasksLink .badge').text(data.total);
      $('.nav-link#reviewingTasksLink .badge').text(data.reviewing);
      $('.nav-link#completedTasksLink .badge').text(data.completedA);
      $('.nav-link#completedAssignedTasksLink .badge').text(data.completedC);
    }
  });
}

// Initial call
updateTaskCounts();

// Repeat every 15 seconds
setInterval(updateTaskCounts, 1000);








    
    $('#welcomeToast').fadeIn(300).delay(2000).fadeOut(100);

    $('#profileIcon').click(e => {
      e.stopPropagation();
      $('#dropdownMenu').toggle();
    });

    $(document).click(() => $('#dropdownMenu').hide());
    $('#dropdownMenu').click(e => e.stopPropagation());
    $('#hamburgerMenu').click(() => $('#navTabs').toggleClass('active'));

    $('#createTaskLink').click(e => {
      e.preventDefault();
      $('#taskOverlay').css("display", "flex").hide().fadeIn(300);
    });

    $('#closePopup').click(() => $('#taskOverlay').fadeOut(300));
    $('#taskOverlay').click(e => { if ($(e.target).is('#taskOverlay')) $('#taskOverlay').fadeOut(300); });



    $('#createTaskForm').submit(function (e) {
  e.preventDefault();

  const taskData = {
    title: $('#taskTitle').val(),
    description: $('#taskDesc').val(),
    assignee: $('#assignee').val(),
    due_date: $('#dueDate').val()
  };

  $.post('create_task.php', taskData, function () {
    // ✅ Hide the popup
    $('#taskOverlay').fadeOut(300);

    // Reset form & show success toast
    $('#createTaskForm')[0].reset();
    $('<div class="toast-popup" style="background-color: #28a745;">✅ Task Created!</div>')
      .appendTo('body').fadeIn(300).delay(2000).fadeOut(300, function () { $(this).remove(); });
  });
});


    $.get('get_users.php', function (users) {
      const $assignee = $('#assignee');
      $assignee.empty().append('<option value="">Select User</option>');
      users.forEach(user => $assignee.append(`<option value="${user.username}">${user.username}</option>`));
    });

    $('#dueDate').datepicker({ dateFormat: 'yy-mm-dd', minDate: 0 });

    $('#pendingTasksLink').click(function (e) {
  e.preventDefault();
  $.ajax({
    url: 'fetch_pending_tasks.php',
    method: 'GET',
    dataType: 'json',
    success: function (tasks) {
  if (!tasks.length) {
    $('#pendingTasksContainer').html('<div class="alert alert-warning" style="background-color: #212529;color: #f8f9fa; font-family:Tahoma;font-weight:999;">No pending tasks assigned to you.</div>');
  } else {
    let html = '<div class="list-group">';
    tasks.forEach((task, index) => {
  const statusColor = task.status === 'Re-do' ? 'bg-danger' : 'bg-danger';
  const statusLabel = task.status;

  html += `
  <div class="list-group-item mb-3 border rounded shadow-sm p-3" style="background-color: #212529; color: #f8f9fa; font:Tahoma bold;">
    <div class="d-flex align-items-center mb-2">
      <span class="rounded-circle me-2 ${statusColor}" style="width: 12px; height: 12px; display: inline-block;"></span>
      <h5 class="mb-0 text-danger" style="font-weight:999;font-family:Tahoma;">${task.task_title}</h5>
      <span class="ms-2 badge bg-danger text-white small">${statusLabel}</span>
    </div>
    <p class="mb-1"><strong>Assigned By:</strong> ${task.assigned_by}</p>
    <p class="mb-2"><strong>Description:</strong> ${task.description}</p>
    <p class="mb-2"><small><strong>Due Date:</strong> ${task.due_date}</small></p>
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="form-check m-0">
        <input class="form-check-input mark-complete-checkbox bg-danger" type="checkbox" id="complete_${index}">
        <label class="form-check-label ms-2" for="complete_${index}">
          Submit For Review
        </label>
      </div>
      <button class="btn btn-sm btn-danger submit-review-btn d-none" data-task="${task.task_title}" disabled>
        Submit for Review
      </button>
    </div>
    <textarea class="form-control mt-2 remark-input d-none" placeholder="Add a remark before submitting..." style="background-color: #212529; color: #f8f9fa; font:Tahoma bold;">${task.remark || ''}</textarea>


  </div>`;

});


    html += '</div>';
    $('#pendingTasksContainer').html(html);

    // Show textarea and submit button when checkbox is checked
$('#pendingTasksContainer').on('change', '.mark-complete-checkbox', function () {
  const container = $(this).closest('.list-group-item');
  const textarea = container.find('.remark-input');
  const button = container.find('.submit-review-btn');

  if (this.checked) {
    textarea.removeClass('d-none');
    button.removeClass('d-none').prop('disabled', true);

    // Remove previous input listener to avoid duplicates
    textarea.off('input').on('input', function () {
      const hasText = $(this).val().trim().length > 0;
      button.prop('disabled', !hasText);
    });

  } else {
    textarea.addClass('d-none').val('');
    button.addClass('d-none').prop('disabled', true);
  }
});



    // Handle submit click
    $('.submit-review-btn').click(function () {
      const $taskItem = $(this).closest('.list-group-item');
      const taskTitle = $(this).data('task');
      const remark = $taskItem.find('textarea.remark-input').val().trim();

      if (remark === '') {
        alert("Please enter a remark before submitting.");
        return;
      }

      $.ajax({
        url: 'mark_task_review.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ task_title: taskTitle, remark: remark }),
        success: function () {
          $('<div class="toast-popup" style="background-color: #28a745;">✅ Task submitted for review!</div>')
            .appendTo('body')
            .fadeIn(300)
            .delay(2000)
            .fadeOut(300, function () {
              $(this).remove();
            });
          $('#pendingTasksOverlay').fadeOut(300);
        },
        error: function () {
          alert("❌ Failed to submit for review.");
        }
      });
    });
  }

  $('#pendingTasksOverlay').css("display", "flex").hide().fadeIn(300);
}
,
    error: function (xhr) {
      console.error(xhr.responseText);
      $('#pendingTasksContainer').html('<div class="alert alert-danger">Failed to fetch tasks.</div>');
      $('#pendingTasksOverlay').css("display", "flex").hide().fadeIn(300);
    }
  });
});


$('#assignedTasksLink').click(function (e) {
  e.preventDefault();
  $.ajax({
    url: 'fetch_assigned_tasks.php',
    method: 'GET',
    dataType: 'json',
    success: function (tasks) {
      if (!tasks.length) {
        $('#assignedTasksContainer').html('<div class="alert alert-warning">You haven"t assigned any tasks yet.</div>');
      } else {
        let html = '<div class="list-group">';
        tasks.forEach(task => {
          html += `
            <div class="list-group-item mb-3 shadow-sm p-3">
              <h5 class="mb-1 text-warning">${task.task_title}</h5>
              <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
              <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
              <small><strong>Due Date:</strong> ${task.due_date}</small>
            </div>`;
        });
        html += '</div>';
        $('#assignedTasksContainer').html(html); // Inject the tasks into the popup
      }
      $('#assignedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      $('#assignedTasksContainer').html('<div class="alert alert-danger">Failed to fetch tasks.</div>');
      $('#assignedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    }
  });
});



$('#reviewingTasksLink').off('click').on('click', function (e) {
  e.preventDefault();
  $.ajax({
    url: 'fetch_reviewing_tasks.php',
    method: 'GET',
    dataType: 'json',
    success: function (tasks) {
  if (!tasks.length) {
    $('#reviewingTasksContainer').html('<div class="alert alert-warning" style="background-color: #212529;color: #f8f9fa; font-family:Tahoma;font-weight:999;">No Tasks Assigned By You Are Under Review.</div>');
  } else {
    let html = '<div class="list-group">';
    tasks.forEach((task, index) => {
      html += `
        <div class="list-group-item mb-3 custom-task-item rounded shadow-sm p-3" style="color:white;font-family:system-ui;">
          <h5 class="mb-2 text-info" style="font-weight:bold;font-family:system-ui;">${task.task_title}</h5>
          <p><strong>Assigned To:</strong> ${task.assigned_to}</p>
          <p><strong>Description:</strong> ${task.description}</p>
          <small><strong>Due Date:</strong> ${task.due_date}</small>

          <div class="mt-3">
            <label><strong>Remark:</strong></label>
            <textarea class="form-control mb-2 remark-box" rows="2" style="background-color: #2a2f35;color:white;font-weight:bold;">${task.remark || ''}</textarea>

            <label ><strong>Update Status:</strong></label>
            <select class="form-select mb-2 status-select" style="background-color: #2a2f35;color:white;font-weight:bold;">
              <option value="Completed">Completed</option>
              <option value="Re-do">Re-do</option>
            </select>

            <button class="btn btn-info update-task-btn" data-task="${task.task_title}">Submit</button>
          </div>
        </div>`;
    });
    html += '</div>';
    $('#reviewingTasksContainer').html(html);

    // Submit handler
    $('.update-task-btn').click(function () {
      const container = $(this).closest('.list-group-item');
      const taskTitle = $(this).data('task');
      const updatedRemark = container.find('.remark-box').val().trim();
      const selectedStatus = container.find('.status-select').val();

      $.ajax({
        url: 'update_review_task.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          task_title: taskTitle,
          status: selectedStatus,
          remark: updatedRemark
        }),
        success: function () {
  $('<div class="toast-popup" style="background-color: #007bff;">✅ Task updated!</div>')
    .appendTo('body')
    .fadeIn(300)
    .delay(2000)
    .fadeOut(300, function () {
      $(this).remove();
    });

  // ✅ Close the modal
  $('#reviewingTasksOverlay').fadeOut(300);
}
,
        error: function () {
          alert("❌ Failed to update task.");
        }
      });
    });
  }

  $('#reviewingTasksOverlay').css("display", "flex").hide().fadeIn(300);
}
,
    error: function (xhr) {
      console.error(xhr.responseText);
      $('#reviewingTasksContainer').html('<div class="alert alert-danger">Failed to fetch tasks.</div>');
      $('#reviewingTasksOverlay').css("display", "flex").hide().fadeIn(300);
    }
  });
});



$('#completedTasksLink').off('click').on('click', function (e) {
  e.preventDefault();
  $.ajax({
    url: 'fetch_completed_tasks.php',
    method: 'GET',
    dataType: 'json',
    success: function (tasks) {
      if (!tasks.length) {
        $('#completedTasksContainer').html('<div class="alert alert-warning" style="background-color: #212529;color: #f8f9fa; font-family:Tahoma;font-weight:999;">No Tasks Completed.</div>');
      } else {
        let html = '<div class="list-group">';
        tasks.forEach(task => {
          html += `
            <div class="list-group-item mb-3 custom-task-item rounded shadow-sm p-3">
              <h5 class="mb-1 text-success">${task.task_title}</h5>
              <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
              <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
              <p class="mb-1"><strong>Due Date:</strong> ${task.due_date}</p>
            </div>`;
        });
        html += '</div>';
        $('#completedTasksContainer').html(html);
      }

      $('#completedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      $('#completedTasksContainer').html('<div class="alert alert-danger">Failed to fetch tasks.</div>');
      $('#completedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    }
  });
});


$('#completedAssignedTasksLink').off('click').on('click', function (e) {
  e.preventDefault();
  $.ajax({
    url: 'fetch_assigned_completed_tasks.php',
    method: 'GET',
    dataType: 'json',
    success: function (tasks) {
      if (!tasks.length) {
        $('#completedAssignedTasksContainer').html('<div class="alert alert-warning" style="background-color: #212529;color: #f8f9fa; font-family:Tahoma;font-weight:999;">No Tasks Created By You Are Completed.</div>');
      } else {
        let html = '<div class="list-group">';
        tasks.forEach(task => {
          html += `
            <div class="list-group-item mb-3 custom-task-item rounded shadow-sm p-3" style="font-family:system-ui;">
              <h5 class="mb-1 text-primary">${task.task_title}</h5>
              <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
              <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
              <p class="mb-1"><strong>Due Date:</strong> ${task.due_date}</p>
            </div>`;
        });
        html += '</div>';
        $('#completedAssignedTasksContainer').html(html);
      }

      $('#completedAssignedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      $('#completedAssignedTasksContainer').html('<div class="alert alert-danger">Failed to fetch tasks.</div>');
      $('#completedAssignedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    }
  });
});


$('#closecompletedAssignedTasks').click(() => $('#completedAssignedTasksOverlay').fadeOut(300));
$('#completedAssignedTasksOverlay').click(e => {
  if ($(e.target).is('#completedAssignedTasksOverlay')) $('#completedAssignedTasksOverlay').fadeOut(300);
});


$('#closecompletedTasks').click(() => $('#completedTasksOverlay').fadeOut(300));
$('#completedTasksOverlay').click(e => {
  if ($(e.target).is('#completedTasksOverlay')) $('#completedTasksOverlay').fadeOut(300);
});


$('#closereviewingTasks').click(() => $('#reviewingTasksOverlay').fadeOut(300));
$('#reviewingTasksOverlay').click(e => {
  if ($(e.target).is('#reviewingTasksOverlay')) $('#reviewingTasksOverlay').fadeOut(300);
});

$('#closeAssignedTasks').click(() => $('#assignedTasksOverlay').fadeOut(300));
$('#assignedTasksOverlay').click(e => {
  if ($(e.target).is('#assignedTasksOverlay')) $('#assignedTasksOverlay').fadeOut(300);
});




    $('#closePendingTasks').click(() => $('#pendingTasksOverlay').fadeOut(300));
    $('#pendingTasksOverlay').click(e => {
      if ($(e.target).is('#pendingTasksOverlay')) $('#pendingTasksOverlay').fadeOut(300);
    });

    $('#assignedTasksLink').off('click').on('click', function (e) {
  e.preventDefault();
  $.ajax({
    url: 'fetch_assigned_tasks.php',
    method: 'GET',
    dataType: 'json',
    success: function (tasks) {
      if (!tasks.length) {
        $('#assignedTasksContainer').html('<div class="alert alert-warning">You haven\'t assigned any tasks yet.</div>');
      } else {
        let html = '<div class="list-group">';
        
        tasks.forEach(task => {
  html += `
    <div class="list-group-item mb-2 border rounded shadow-sm">
      <h5 class="mb-1 text-warning">${task.task_title}</h5>
      <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
      <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
      <p class="mb-1"><strong>Status:</strong> <span class="badge bg-info text-dark">${task.status}</span></p>
       <p class="mb-1"><strong class="mb-1">Due Date:</strong> ${task.due_date}</p>
    </div>`;
});

        html += '</div>';
        $('#assignedTasksContainer').html(html);
      }

      $('#assignedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      $('#assignedTasksContainer').html('<div class="alert alert-danger">Failed to fetch tasks.</div>');
      $('#assignedTasksOverlay').css("display", "flex").hide().fadeIn(300);
    }
  });
});


$('#closeAssignedTasks').click(() => $('#assignedTasksOverlay').fadeOut(300));
$('#assignedTasksOverlay').click(e => {
  if ($(e.target).is('#assignedTasksOverlay')) $('#assignedTasksOverlay').fadeOut(300);
});


    $(document).on('click', '.submit-review-btn', function () {
  const taskTitle = $(this).data('task');
  const isChecked = $(this).siblings('.form-check').find('input').is(':checked');

  if (!isChecked) {
    alert('Please mark the task as complete before submitting for review.');
    return;
  }

  // AJAX call to update the status in DB
  $.post('submit_review.php', { title: taskTitle }, function (response) {
    $('<div class="toast-popup" style="background-color: #007bff;">🚀 Task submitted for review!</div>')
      .appendTo('body').fadeIn(300).delay(2000).fadeOut(300, function () { $(this).remove(); });
  }).fail(function () {
    alert('Failed to submit for review. Try again.');
  });
});


  });
</script>


</body>
</html>
