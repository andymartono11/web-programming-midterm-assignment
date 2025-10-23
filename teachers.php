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

// Add new teacher
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $conn->query("INSERT INTO teachers (name, email) VALUES ('$name', '$email')");
}

$result = $conn->query("SELECT * FROM teachers");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Teachers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2 class="mb-4">👩‍🏫 Manage Teachers</h2>

    <form method="POST" class="mb-4 card p-3">
      <input type="text" name="name" class="form-control mb-2" placeholder="Teacher Name" required>
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <button class="btn btn-primary" name="add">Add Teacher</button>
    </form>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr><th>ID</th><th>Name</th><th>Email</th></tr>
      </thead>
      <tbody>
        <?php while ($r = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $r['teacher_id'] ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
