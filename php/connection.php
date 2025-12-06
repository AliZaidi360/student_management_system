<?php
$servername = "sql100.infinityfree.com";
$username = "if0_40601752";
$password = "yc0k7298";  // your actual MySQL password
$dbname = "if0_40601752_studenttms"; // EXACT database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
