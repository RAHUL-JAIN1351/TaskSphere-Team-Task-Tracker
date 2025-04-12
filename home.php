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

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$stmt->close();

if (!$userData) {
    echo json_encode(['error' => 'user not found']);
    exit();
}

$userId = $userData['id'];


$stmt = $conn->prepare("
    SELECT 
        SUM(CASE WHEN assigned_to = ? AND status IN ('Pending', 'Re-do') THEN 1 ELSE 0 END) AS pending_count,
        SUM(CASE WHEN created_by = ? THEN 1 ELSE 0 END) AS total_count,
        SUM(CASE WHEN created_by = ? AND status = 'Reviewing' THEN 1 ELSE 0 END) AS reviewing_count,
        SUM(CASE WHEN assigned_to = ? AND status = 'Completed' THEN 1 ELSE 0 END) AS completed_assigned_count,
        SUM(CASE WHEN created_by = ? AND status = 'Completed' THEN 1 ELSE 0 END) AS completed_created_count
    FROM task
");

$stmt->bind_param("sssss", $username, $username, $username, $username, $username);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$pendingCount           = $result['pending_count'];
$totalCount             = $result['total_count'];
$reviewingCount         = $result['reviewing_count'];
$completedAssignedCount = $result['completed_assigned_count'];
$completedCreatedCount  = $result['completed_created_count'];

$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TaskSphere | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <style>
    body {
      background-color: #f8f9fa;
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
      background-color: #f1f1f1;
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
  background-color: rgba(0, 0, 0, 0.3);
  z-index: 1000;
}


.create-task-popup {
  background-color: #fff;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  width: 90%;
  max-width: 600px;
  animation: popupFadeIn 0.5s ease forwards;
  opacity: 0;
  transform: translateY(20px);
  position: relative;

  /* ✅ Add these two lines */
  max-height: 90vh;
  overflow-y: auto;
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
    }

    .close-btn:hover {
      color: red;
    }

    .toast-popup {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #28a745;
      color: #fff;
      padding: 14px 24px;
      border-radius: 12px;
      font-weight: 500;
      font-size: 1rem;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
      display: none;
      z-index: 9999;
      animation: slideFadeIn 0.4s ease forwards;
    }

    @keyframes slideFadeIn {
      0% {
        opacity: 0;
        transform: translateX(30px);
      }
      100% {
        opacity: 1;
        transform: translateX(0);
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


  <!-- Header -->
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

  <!-- Toast -->
  <div class="toast-popup" id="welcomeToast">
    👋 Welcome, <?php echo $_SESSION["username"]; ?>!
  </div>

  <!-- Nav Tabs -->
  <div class="nav-tabs-container" id="navTabs">
    <a href="#" class="nav-tab" id="createTaskLink">Create Task</a>
    <a href="#" class="nav-tab" id="pendingTasksLink">Pending Tasks <span class="badge-circle"><?php echo $pendingCount; ?></span></a>
    <a href="#" class="nav-tab" id="assignedTasksLink">Assigned Tasks <span class="badge-circle"><?php echo $totalCount; ?></span></a>
    <a href="#" class="nav-tab" id="reviewingTasksLink">Reviewing Tasks <span class="badge-circle"><?php echo $reviewingCount; ?></span></a>
    <a href="#" class="nav-tab" id="completedTasksLink">Completed Tasks <span class="badge-circle"><?php echo $completedAssignedCount; ?></span></a>
    <a href="#" class="nav-tab" id="completedAssignedTasksLink">Completed Assigned Tasks <span class="badge-circle"><?php echo $completedCreatedCount; ?></span></a>
  </div>

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
        <button type="submit" class="btn btn-success w-100 fw-semibold">Create Task</button>
      </form>
    </div>
  </div>

  <!-- Pending Tasks Modal -->
  <div class="blur-overlay" id="pendingTasksOverlay">
    <div class="create-task-popup position-relative">
      <i class="fas fa-times close-btn" id="closePendingTasks"></i>
      <h3 class="mb-4 text-primary fw-bold">Your Pending Tasks</h3>
      <div id="pendingTasksContainer"></div>
    </div>
  </div>

  <!-- Assigned Tasks Modal -->
<div class="blur-overlay" id="assignedTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closeAssignedTasks"></i>
    <h3 class="mb-4 text-primary fw-bold">Tasks You Assigned</h3>
    <div id="assignedTasksContainer"></div>
  </div>
</div>

<!-- Reviewing Tasks Modal -->
<div class="blur-overlay" id="reviewingTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closereviewingTasks"></i>
    <h3 class="mb-4 text-primary fw-bold">Tasks Under Review</h3>
    <div id="reviewingTasksContainer"></div>
  </div>
</div>

<!-- Completed Tasks Modal -->
<div class="blur-overlay" id="completedTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closecompletedTasks"></i>
    <h3 class="mb-4 text-primary fw-bold">Tasks Completed</h3>
    <div id="completedTasksContainer"></div>
  </div>
</div>


<!-- Completed Assigned Tasks Modal -->
<div class="blur-overlay" id="completedAssignedTasksOverlay">
  <div class="create-task-popup position-relative">
    <i class="fas fa-times close-btn" id="closecompletedAssignedTasks"></i>
    <h3 class="mb-4 text-primary fw-bold">Assigned Tasks Which Are Completed</h3>
    <div id="completedAssignedTasksContainer"></div>
  </div>
</div>



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
    $('#pendingTasksContainer').html('<div class="alert alert-warning">No pending tasks assigned to you.</div>');
  } else {
    let html = '<div class="list-group">';
    tasks.forEach((task, index) => {
  const statusColor = task.status === 'Re-do' ? 'bg-danger' : 'bg-primary';
  const statusLabel = task.status;

  html += `
    <div class="list-group-item mb-3 border rounded shadow-sm p-3">
      <div class="d-flex align-items-center mb-2">
        <span class="rounded-circle me-2 ${statusColor}" style="width: 12px; height: 12px; display: inline-block;"></span>
        <h5 class="mb-0 text-primary">${task.task_title}</h5>
        <span class="ms-2 badge bg-light text-dark small">${statusLabel}</span>
      </div>
      <p class="mb-1"><strong>Assigned By:</strong> ${task.assigned_by}</p>
      <p class="mb-2"><strong>Description:</strong> ${task.description}</p>
      <small><strong>Due Date:</strong> ${task.due_date}</small>
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="form-check m-0">
          <input class="form-check-input mark-complete-checkbox" type="checkbox" id="complete_${index}">
          <label class="form-check-label ms-2" for="complete_${index}">
            Mark as Complete
          </label>
        </div>
        <button class="btn btn-sm btn-primary submit-review-btn d-none" data-task="${task.task_title}" disabled>
          Submit for Review
        </button>
      </div>
      <textarea class="form-control mt-2 remark-input d-none" placeholder="Add a remark before submitting..."></textarea>
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
        $('#assignedTasksContainer').html('<div class="alert alert-warning">You haven\'t assigned any tasks yet.</div>');
      } else {
        let html = '<div class="list-group">';
        tasks.forEach(task => {
          html += `
            <div class="list-group-item mb-2 border rounded shadow-sm">
              <h5 class="mb-1 text-primary">${task.task_title}</h5>
              <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
              <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
              <small><strong>Due Date:</strong> ${task.due_date}</small>
            </div>`;
        });
        html += '</div>';
        $('#assignedTasksContainer').html(html); // ✅ Inject into the popup only
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
    $('#reviewingTasksContainer').html('<div class="alert alert-warning">No Tasks Assigned By You Are Under Review.</div>');
  } else {
    let html = '<div class="list-group">';
    tasks.forEach((task, index) => {
      html += `
        <div class="list-group-item mb-3 border rounded shadow-sm p-3">
          <h5 class="mb-2 text-primary">${task.task_title}</h5>
          <p><strong>Assigned To:</strong> ${task.assigned_to}</p>
          <p><strong>Description:</strong> ${task.description}</p>
          <small><strong>Due Date:</strong> ${task.due_date}</small>

          <div class="mt-3">
            <label><strong>Remark:</strong></label>
            <textarea class="form-control mb-2 remark-box" rows="2">${task.remark || ''}</textarea>

            <label><strong>Update Status:</strong></label>
            <select class="form-select mb-2 status-select">
              <option value="Completed">Completed</option>
              <option value="Re-do">Re-do</option>
            </select>

            <button class="btn btn-success update-task-btn" data-task="${task.task_title}">Submit</button>
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
        $('#completedTasksContainer').html('<div class="alert alert-warning">No Tasks Completed.</div>');
      } else {
        let html = '<div class="list-group">';
        tasks.forEach(task => {
          html += `
            <div class="list-group-item mb-2 border rounded shadow-sm">
              <h5 class="mb-1 text-primary">${task.task_title}</h5>
              <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
              <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
              <small><strong>Due Date:</strong> ${task.due_date}</small>
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
        $('#completedAssignedTasksContainer').html('<div class="alert alert-warning">No Tasks Completed.</div>');
      } else {
        let html = '<div class="list-group">';
        tasks.forEach(task => {
          html += `
            <div class="list-group-item mb-2 border rounded shadow-sm">
              <h5 class="mb-1 text-primary">${task.task_title}</h5>
              <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
              <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
              <small><strong>Due Date:</strong> ${task.due_date}</small>
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
      <h5 class="mb-1 text-primary">${task.task_title}</h5>
      <p class="mb-1"><strong>Assigned To:</strong> ${task.assigned_to}</p>
      <p class="mb-1"><strong>Description:</strong> ${task.description}</p>
      <p class="mb-1"><strong>Status:</strong> <span class="badge bg-info text-dark">${task.status}</span></p>
      <small><strong>Due Date:</strong> ${task.due_date}</small>
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