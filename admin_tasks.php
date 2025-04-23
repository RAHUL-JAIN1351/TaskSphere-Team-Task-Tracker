<?php 
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$host = "sql308.infinityfree.com";
$db = "if0_38681307_task";
$user = "if0_38681307";
$pass = "Nh8iwr7D6aHu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - View User Tasks | TaskSphere</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body { 
            padding: 60px 20px 20px 20px;
            background-color: #f4f6fa;
        }
        .card { margin-top: 20px; }
        .table th, .table td { vertical-align: middle; }
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
        /* Logout button styling */
        .logout-btn {
            position: fixed;
            top: 25px;
            right: 40px;
            background-color: #dc3545;
            color: white;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            transition: all 0.3s ease-in-out;
            z-index: 1000;
        }
        .logout-btn:hover {
            background-color: #c82333;
            color: #ffffff;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.4);
        }
        .logout-btn i {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div id="siteLoaderBlur">
  <div class="loader-spinner"></div>
</div>


<!-- Logout Button -->
<div>
  <a href="logout.php" class="logout-btn">
    <i class="fas fa-sign-out-alt"></i> Logout
  </a>
</div>

<div class="container" style="padding:30px;">
    <h3 class="mb-4 text-primary">🛠 Admin - View User Tasks</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="filterForm" class="form-inline">
                <label class="mr-2">Select User:</label>
                <select class="form-control mr-3" id="userSelect" name="user_id" required>
    <option value="">-- Select --</option>
    <?php
    $res = mysqli_query($conn, "SELECT id, username FROM users ORDER BY username");
    while ($row = mysqli_fetch_assoc($res)) {
        echo '<option value="' . htmlspecialchars($row['username']) . '">' . htmlspecialchars($row['username']) . '</option>';
    }
    ?>
</select>

                <label class="mr-2">From:</label>
                <input type="text" id="fromDate" name="from" class="form-control mr-3" autocomplete="off" required>

                <label class="mr-2">To:</label>
                <input type="text" id="toDate" name="to" class="form-control mr-3" autocomplete="off" required>

                <button type="submit" class="btn btn-primary">Go</button>
            </form>
        </div>
    </div>

    <div id="taskResults" class="mt-4"></div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>

window.addEventListener('load', () => {
    setTimeout(() => {
      document.getElementById('siteLoaderBlur').classList.add('hidden');
    }, 500); // 2 seconds delay
  });
  

  
$(function () {
    $("#fromDate, #toDate").datepicker({ dateFormat: "yy-mm-dd" });

    $("#filterForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: 'fetch_tasks.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#taskResults').html(response);
            }
        });
    });
});

</script>

</body>
</html>