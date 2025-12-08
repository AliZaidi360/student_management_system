<?php
session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header("Location: student_login.php");
    exit();
}

include 'php/connection.php';

$student_id = $_SESSION['student_id'];

// Fetch student information with course
$student = $conn->query("SELECT students.*, courses.course_name FROM students LEFT JOIN courses ON students.course_id = courses.id WHERE students.id = $student_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Student Portal</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="user-profile">
                    <i class="fas fa-user-graduate" style="font-size: 1.5rem; margin-right: 0.5rem;"></i>
                    <span><?php echo htmlspecialchars($_SESSION['student_name']); ?></span>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="student_portal.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="student_profile.php" class="active"><i class="fas fa-user"></i> My Profile</a></li>
                <li><a href="student_grades.php"><i class="fas fa-star"></i> My Grades</a></li>
                <li><a href="student_attendance.php"><i class="fas fa-calendar-check"></i> My Attendance</a></li>
            </ul>

            <div style="margin-top: auto; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                <a href="student_logout.php" class="btn btn-danger" style="width: 100%;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="page-title">My Profile</h1>
                    <p style="color: var(--text-secondary);">View your personal information and academic details.</p>
                </div>
            </header>

            <div class="card">
                <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
                    <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; color: var(--text-primary);"><?php echo htmlspecialchars($student['name']); ?></h2>
                        <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">Student ID: <?php echo $student['id']; ?></p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    <div>
                        <h3 style="color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Personal Information</h3>
                        <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: var(--border-radius);">
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <i class="fas fa-envelope" style="color: var(--primary-color); width: 20px;"></i>
                                    <span style="font-weight: 600; color: var(--text-secondary);">Email:</span>
                                </div>
                                <div style="margin-left: 1.75rem; color: var(--text-primary);">
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </div>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <i class="fas fa-phone" style="color: var(--primary-color); width: 20px;"></i>
                                    <span style="font-weight: 600; color: var(--text-secondary);">Phone:</span>
                                </div>
                                <div style="margin-left: 1.75rem; color: var(--text-primary);">
                                    <?php echo htmlspecialchars($student['phone']); ?>
                                </div>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <i class="fas fa-calendar" style="color: var(--primary-color); width: 20px;"></i>
                                    <span style="font-weight: 600; color: var(--text-secondary);">Enrolled:</span>
                                </div>
                                <div style="margin-left: 1.75rem; color: var(--text-primary);">
                                    <?php echo date('F d, Y', strtotime($student['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 style="color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Academic Information</h3>
                        <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: var(--border-radius);">
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <i class="fas fa-book" style="color: var(--primary-color); width: 20px;"></i>
                                    <span style="font-weight: 600; color: var(--text-secondary);">Course:</span>
                                </div>
                                <div style="margin-left: 1.75rem; color: var(--text-primary);">
                                    <?php echo htmlspecialchars($student['course_name'] ?? 'Not Assigned'); ?>
                                </div>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <i class="fas fa-id-card" style="color: var(--primary-color); width: 20px;"></i>
                                    <span style="font-weight: 600; color: var(--text-secondary);">Student ID:</span>
                                </div>
                                <div style="margin-left: 1.75rem; color: var(--text-primary);">
                                    <?php echo $student['id']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

