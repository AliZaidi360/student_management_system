<?php
session_start();
include 'php/connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $student_id = $_POST['student_id'];

    // Login using email or student ID
    $sql = "SELECT * FROM students WHERE email = '$email' OR id = '$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $_SESSION['student_logged_in'] = true;
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['name'];
        $_SESSION['student_email'] = $student['email'];
        header("Location: student_portal.php");
        exit();
    } else {
        $error = "Invalid email or student ID!";
    }
}

// If already logged in, redirect to portal
if (isset($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] === true) {
    header("Location: student_portal.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Student Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-card {
            background-color: var(--surface-color);
            border-radius: var(--border-radius);
            padding: 3rem;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .login-header h1 {
            margin: 0;
            color: var(--text-primary);
        }

        .error-message {
            background-color: #fee2e2;
            color: var(--danger-color);
            padding: 0.75rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            text-align: center;
        }

        .login-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .login-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .login-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-graduate"></i>
                <h1>Student Portal</h1>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">Login to view your information</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                <div class="form-group">
                    <label for="student_id"><i class="fas fa-id-card"></i> Student ID (Optional)</label>
                    <input type="number" id="student_id" name="student_id" placeholder="Enter your student ID">
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Use email or student ID to login</small>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="login-links">
                <a href="login.php"><i class="fas fa-user-shield"></i> Admin Login</a>
            </div>
        </div>
    </div>
</body>

</html>

