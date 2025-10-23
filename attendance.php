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

$user_id = intval($_SESSION['user_id']);


$row = $conn->query("SELECT student_id FROM students WHERE student_id = $user_id LIMIT 1")->fetch_assoc();
if ($row && isset($row['student_id'])) {
    // Case when user_id == student_id (IDs match)
    $student_id = intval($row['student_id']);
}

if (!$student_id) {
    $u = $conn->query("SELECT email, full_name FROM users WHERE user_id = $user_id")->fetch_assoc();
    if ($u) {
        $email = $conn->real_escape_string($u['email']);
        $row2 = $conn->query("SELECT student_id FROM students WHERE email = '$email' LIMIT 1")->fetch_assoc();
        if ($row2 && isset($row2['student_id'])) {
            $student_id = intval($row2['student_id']);
        } else {
            // 3) try matching by full name as last fallback
            $name = $conn->real_escape_string($u['full_name'] ?? '');
            if ($name !== '') {
                $row3 = $conn->query("SELECT student_id FROM students WHERE full_name = '$name' LIMIT 1")->fetch_assoc();
                if ($row3 && isset($row3['student_id'])) $student_id = intval($row3['student_id']);
            }
        }
    }
}

if (!$student_id) {
    echo "<div style='margin:20px'>No linked student record found for this account. Please ask admin to link your user account to a student record.</div>";
    exit();
}

$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT 1 FROM attendance WHERE student_id = ? AND date = ?");
$stmt->bind_param("is", $student_id, $today);
$stmt->execute();
$already = $stmt->get_result()->num_rows > 0;
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already && isset($_POST['status'])) {
    $status = $_POST['status']; // expect 'Present'|'Late'|'Absent'
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $student_id, $today, $status);
    if ($stmt->execute()) {
        $already = true; // reflect new state
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT date, status FROM attendance WHERE student_id = ? ORDER BY date DESC LIMIT 100");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$history = $stmt->get_result();
$stmt->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Attendance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h2>🕒 Attendance</h2>

    <?php if (!$already): ?>
      <form method="POST" class="card p-3 mb-3">
        <div class="mb-2">
          <select name="status" class="form-select" required>
            <option value="Present">Present</option>
            <option value="Late">Late</option>
            <option value="Absent">Absent</option>
          </select>
        </div>
        <button class="btn btn-success">Mark Attendance for Today</button>
      </form>
    <?php else: ?>
      <div class="alert alert-info">✅ You already marked attendance for today (<?= htmlspecialchars($today) ?>).</div>
    <?php endif; ?>

    <h5>Recent Attendance</h5>
    <table class="table table-striped">
      <thead><tr><th>Date</th><th>Status</th></tr></thead>
      <tbody>
        <?php while ($r = $history->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($r['date']) ?></td>
            <td><?= htmlspecialchars($r['status']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back</a>
  </div>
</body>
</html>
