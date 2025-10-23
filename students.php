<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['add'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $class_id = intval($_POST['class_id']);

    $conn->query("INSERT INTO students (full_name, email, class_id) VALUES ('$full_name', '$email', $class_id)");
}

$result = $conn->query("
    SELECT s.student_id, s.full_name, s.email, c.class_name
    FROM students s
    LEFT JOIN classes c ON s.class_id = c.class_id
");

$classes = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2 class="mb-4">👨‍🎓 Manage Students</h2>

    <form method="POST" class="mb-4 card p-3">
      <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name" required>
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

      <select name="class_id" class="form-control mb-2" required>
        <option value="">Select Class</option>
        <?php while ($c = $classes->fetch_assoc()): ?>
          <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
        <?php endwhile; ?>
      </select>

      <button class="btn btn-primary" name="add">Add Student</button>
    </form>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Class</th></tr>
      </thead>
      <tbody>
        <?php while ($r = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $r['student_id'] ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['class_name'] ?? '—') ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
