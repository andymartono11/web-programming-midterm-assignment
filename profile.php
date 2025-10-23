<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_system";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$display_name = $_SESSION['full_name'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2>👤 My Profile</h2>
    <div class="card p-3 mb-3">
      <p><strong>Display name:</strong> <?= htmlspecialchars($display_name ?: ($user['full_name'] ?? '')) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
      <p><strong>Role:</strong> <?= htmlspecialchars($user['role'] ?? '-') ?></p>
      <!-- add more fields if exists in your users table -->
    </div>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</body>
</html>
