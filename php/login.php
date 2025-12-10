<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'] ?? '';

    if ($user_type == 'admin') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            header('Location: ../index.php?error=Please fill in all fields');
            exit();
        }

        // Check admin credentials
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            // Simple password check (in production, use password_verify with hashed passwords)
            if ($password === $admin['password']) {
                $_SESSION['user_type'] = 'admin';
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];
                header('Location: admin_dashboard.php');
                exit();
            } else {
                header('Location: ../index.php?error=Invalid username or password');
                exit();
            }
        } else {
            header('Location: ../index.php?error=Invalid username or password');
            exit();
        }
    } elseif ($user_type == 'student') {
        $student_id = $_POST['student_id'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($student_id) || empty($email)) {
            header('Location: ../index.php?error=Please fill in all fields');
            exit();
        }

        // Check student credentials
        $stmt = $conn->prepare("SELECT id, name, email FROM students WHERE id = ? AND email = ?");
        $stmt->bind_param("is", $student_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            $_SESSION['user_type'] = 'student';
            $_SESSION['user_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_email'] = $student['email'];
            header('Location: student_dashboard.php');
            exit();
        } else {
            header('Location: ../index.php?error=Invalid Student ID or Email');
            exit();
        }
    } else {
        header('Location: ../index.php?error=Invalid user type');
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>

