<?php
$conn = new mysqli("localhost", "root", "", "school_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['add'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $conn->query("INSERT INTO announcements (title, content, created_at) VALUES ('$title', '$content', NOW())");
}

$result = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Announcements</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2 class="mb-4">📢 Announcements</h2>

    <form method="POST" class="card p-3 mb-4">
      <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>
      <textarea name="content" class="form-control mb-2" placeholder="Content" required></textarea>
      <button class="btn btn-primary" name="add">Post Announcement</button>
    </form>

    <div class="list-group">
      <?php while ($r = $result->fetch_assoc()): ?>
        <div class="list-group-item">
          <h5><?= htmlspecialchars($r['title']) ?></h5>
          <p><?= htmlspecialchars($r['content']) ?></p>
          <small class="text-muted"><?= $r['created_at'] ?></small>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
