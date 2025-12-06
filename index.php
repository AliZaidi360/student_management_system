<?php
include 'php/connection.php';

// Fetch stats
$student_count = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$course_count = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$attendance_count = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE date = CURDATE() AND status = 'Present'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="user-profile">
                    <span>Admin</span>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="php/view_students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                <li><a href="php/add_course.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="php/add_grade.php"><i class="fas fa-star"></i> Grades</a></li>
                <li><a href="php/attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="page-title">Student Management Dashboard</h1>
                    <p style="color: var(--text-secondary);">Overview of students, courses, and today's attendance.</p>
                </div>
            </header>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #e0e7ff; color: #4338ca;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Students</h3>
                        <div class="value"><?php echo $student_count; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #f3e8ff; color: #7e22ce;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Courses</h3>
                        <div class="value"><?php echo $course_count; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #dbeafe; color: #1e40af;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Present Today</h3>
                        <div class="value"><?php echo $attendance_count; ?></div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <h2>Quick Actions</h2>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Use these shortcuts to quickly manage
                    students, courses, and attendance.</p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="php/add_student.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Student</a>
                    <a href="php/view_students.php" class="btn btn-outline"><i class="fas fa-list"></i> View
                        Students</a>
                    <a href="php/add_course.php" class="btn btn-outline"><i class="fas fa-plus"></i> Add Course</a>
                    <a href="php/add_grade.php" class="btn btn-outline"><i class="fas fa-star"></i> Add Grades</a>
                    <a href="php/attendance.php" class="btn btn-outline"><i class="fas fa-check"></i> Take
                        Attendance</a>
                </div>
            </div>
        </main>
    </div>
</body>

</html>