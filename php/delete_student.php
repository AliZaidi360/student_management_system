<?php
include 'session_check.php';
requireAdmin();
include 'connection.php';

$id = $_GET['id'];

$sql = "DELETE FROM students WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: view_students.php");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>