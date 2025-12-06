<?php
$servername = "localhost"; // Keep this as localhost for most hosts including InfinityFree
// UPDATE THESE WITH YOUR INFINITYFREE CREDENTIALS
$username = "root";      // Replace with your InfinityFree MySQL Username (e.g., if0_345...)
$password = "";          // Replace with your InfinityFree MySQL Password (from vPanel)
$dbname = "student_managment"; // Replace with your InfinityFree Database Name (e.g., if0_345_student_managment)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>