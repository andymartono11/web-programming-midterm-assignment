<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = trim($_POST['role']);

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $full_name, $email, $password, $role);

    
    if ($stmt->execute()) {
        header("Location: login.html?registered=success");
        exit();
    } else {
        echo "<div style='color:red; text-align:center; margin-top:20px;'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
