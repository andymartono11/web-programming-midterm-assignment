<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | School System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8fafc;
      font-family: 'Poppins', sans-serif;
    }
    .navbar {
      background-color: #0d6efd;
    }
    .navbar-brand, .nav-link, .btn-logout {
      color: white !important;
    }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      transition: 0.2s;
      background: white;
    }
    .card:hover {
      transform: translateY(-4px);
    }
    .dashboard-container {
      margin-top: 80px;
    }
    h2 {
      font-weight: 600;
    }
    .card h4 {
      font-size: 1.1rem;
      color: #0d6efd;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">School System</a>
      <div class="d-flex">
        <a href="logout.php" class="btn btn-outline-light btn-sm ms-2">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container dashboard-container">
    <h2 class="text-center mb-5">Welcome to the Dashboard</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="students.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>👨‍🎓 Manage Students</h4>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="teachers.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>👩‍🏫 Manage Teachers</h4>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="classes.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>🏫 Manage Classes</h4>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="subjects.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>📘 Manage Subjects</h4>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="announcements.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>📢 Announcements</h4>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="profile.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>🙍‍♂️ My Profile</h4>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="attendance.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>🕒 Attendance</h4>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="grades.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4>📊 Grades</h4>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
