<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_system"; 

$conn = new mysqli($servername, $username, $password, $dbname);
echo "Connected to database: " . $dbname;


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
