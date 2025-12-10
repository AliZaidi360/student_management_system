<?php
session_start();
// If already logged in, redirect to appropriate dashboard
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] == 'admin') {
        header('Location: php/admin_dashboard.php');
        exit();
    } elseif ($_SESSION['user_type'] == 'student') {
        header('Location: php/student_dashboard.php');
        exit();
    }
}

$error = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .login-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: #f3f4f6;
            padding: 0.5rem;
            border-radius: 12px;
        }

        .login-tab {
            flex: 1;
            padding: 0.75rem 1rem;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border: none;
        }

        .login-tab.active {
            background: white;
            color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }

        .login-form {
            display: none;
        }

        .login-form.active {
            display: block;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-message i {
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-group label i {
            color: var(--primary-color);
            width: 20px;
        }

        .login-btn {
            width: 100%;
            padding: 0.875rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .login-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -1px rgba(79, 70, 229, 0.3);
        }

        .login-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="login-header">
                <h1>Student Management System</h1>
                <p>Sign in to access your account</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <div class="login-tabs">
                <button class="login-tab active" data-tab="admin">
                    <i class="fas fa-user-shield"></i> Admin
                </button>
                <button class="login-tab" data-tab="student">
                    <i class="fas fa-user-graduate"></i> Student
                </button>
            </div>

            <!-- Admin Login Form -->
            <form id="admin-form" class="login-form active" method="POST" action="php/login.php">
                <input type="hidden" name="user_type" value="admin">
                <div class="form-group">
                    <label for="admin_username">
                        <i class="fas fa-user"></i>
                        Username
                    </label>
                    <input type="text" id="admin_username" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="admin_password">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" id="admin_password" name="password" required>
                </div>
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In as Admin
                </button>
            </form>

            <!-- Student Login Form -->
            <form id="student-form" class="login-form" method="POST" action="php/login.php">
                <input type="hidden" name="user_type" value="student">
                <div class="form-group">
                    <label for="student_id">
                        <i class="fas fa-id-card"></i>
                        Student ID
                    </label>
                    <input type="text" id="student_id" name="student_id" required>
                </div>
                <div class="form-group">
                    <label for="student_email">
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <input type="email" id="student_email" name="email" required>
                </div>
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In as Student
                </button>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab, element) {
            // Update tab buttons
            document.querySelectorAll('.login-tab').forEach(btn => {
                btn.classList.remove('active');
            });
            if (element) {
                element.classList.add('active');
            } else {
                document.querySelectorAll('.login-tab').forEach(btn => {
                    if ((tab === 'admin' && btn.textContent.includes('Admin')) || 
                        (tab === 'student' && btn.textContent.includes('Student'))) {
                        btn.classList.add('active');
                    }
                });
            }

            // Update forms
            document.getElementById('admin-form').classList.remove('active');
            document.getElementById('student-form').classList.remove('active');

            if (tab === 'admin') {
                document.getElementById('admin-form').classList.add('active');
            } else {
                document.getElementById('student-form').classList.add('active');
            }
        }

        // Add click handlers to tabs
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.login-tab').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tab = this.getAttribute('data-tab');
                    switchTab(tab, this);
                });
            });
        });
    </script>
</body>

</html>
