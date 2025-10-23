<?php
// grades.php
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

$user_id = intval($_SESSION['user_id']);

$student_id = null;
$row = $conn->query("SELECT student_id FROM students WHERE student_id = $user_id LIMIT 1")->fetch_assoc();
if ($row && isset($row['student_id'])) $student_id = intval($row['student_id']);
if (!$student_id) {
    $u = $conn->query("SELECT email, full_name FROM users WHERE user_id = $user_id")->fetch_assoc();
    if ($u) {
        $email = $conn->real_escape_string($u['email']);
        $r = $conn->query("SELECT student_id FROM students WHERE email = '$email' LIMIT 1")->fetch_assoc();
        if ($r && isset($r['student_id'])) $student_id = intval($r['student_id']);
        else {
            $name = $conn->real_escape_string($u['full_name'] ?? '');
            if ($name !== '') {
                $r2 = $conn->query("SELECT student_id FROM students WHERE full_name = '$name' LIMIT 1")->fetch_assoc();
                if ($r2 && isset($r2['student_id'])) $student_id = intval($r2['student_id']);
            }
        }
    }
}

if (!$student_id) {
    echo "<div style='margin:20px'>No linked student record found for this account. Please ask admin to link.</div>";
    exit();
}

$stmt = $conn->prepare("SELECT status, COUNT(*) as cnt FROM attendance WHERE student_id = ? GROUP BY status");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$res = $stmt->get_result();

$score = 0;
while ($row = $res->fetch_assoc()) {
    $status = $row['status'];
    $cnt = intval($row['cnt']);
    if (strtolower($status) === 'present' || strtolower($status) === 'late') {
        $score += $cnt;
    }
}
$stmt->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Grades</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2>🎓 Grades (Attendance-based)</h2>
    <div class="card p-3 mb-3">
      <p><strong>Total Attendance Points:</strong> <?= $score ?></p>
      <small class="text-muted">Present = +1, Late = +1, Absent = +0</small>
    </div>

    <a href="dashboard.php" class="btn btn-secondary">Back</a>
  </div>
</body>
</html>
