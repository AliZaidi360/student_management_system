<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit();
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin';
}

// Function to check if user is student
function isStudent() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'student';
}

// Function to require admin access
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../index.php?error=Access denied. Admin access required.');
        exit();
    }
}

// Function to require student access
function requireStudent() {
    if (!isStudent()) {
        header('Location: ../index.php?error=Access denied. Student access required.');
        exit();
    }
}
?>

