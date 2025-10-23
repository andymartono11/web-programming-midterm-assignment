<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['add'])) {
    $subject_name = $conn->real_escape_string($_POST['subject_name']);
    $conn->query("INSERT INTO subjects (subject_name) VALUES ('$subject_name')");
}

$result = $conn->query("SELECT * FROM subjects");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Subjects</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2 class="mb-4">📚 Manage Subjects</h2>
    <form method="POST" class="card p-3 mb-4">
      <input type="text" name="subject_name" class="form-control mb-2" placeholder="Subject Name" required>
      <button class="btn btn-primary" name="add">Add Subject</button>
    </form>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr><th>ID</th><th>Subject Name</th></tr>
      </thead>
      <tbody>
        <?php while ($r = $result->fetch_assoc()): ?>
          <tr><td><?= $r['subject_id'] ?></td><td><?= htmlspecialchars($r['subject_name']) ?></td></tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
