<?php
$servername = "localhost"; // Local XAMPP
$username = "root";
$password = "";
$dbname = "student_managment";

// PRODUCTION Credentials (InfinityFree) - Uncomment when deploying
// $servername = "sql100.byetcluster.com";
// $username   = "if0_40601752";
// $password   = "yc0k7298";
// $dbname     = "if0_40601752_studentms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>