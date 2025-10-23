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
    $class_name = $conn->real_escape_string($_POST['class_name']);
    $conn->query("INSERT INTO classes (class_name) VALUES ('$class_name')");
}

$result = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Classes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2 class="mb-4">🏫 Manage Classes</h2>

    <form method="POST" class="mb-4 card p-3">
      <input type="text" name="class_name" class="form-control mb-2" placeholder="Class Name" required>
      <button class="btn btn-primary" name="add">Add Class</button>
    </form>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr><th>ID</th><th>Class Name</th></tr>
      </thead>
      <tbody>
        <?php while ($r = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $r['class_id'] ?></td>
            <td><?= htmlspecialchars($r['class_name']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
