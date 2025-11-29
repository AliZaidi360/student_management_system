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

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="card stat-card">
                    <h3>Total Students</h3>
                    <div class="value"><?php echo $student_count; ?></div>
                </div>
                <div class="card stat-card">
                    <h3>Total Courses</h3>
                    <div class="value"><?php echo $course_count; ?></div>
                </div>
                <div class="card stat-card">
                    <h3>Present Today</h3>
                    <div class="value"><?php echo $attendance_count; ?></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <h2>Quick Actions</h2>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="php/add_student.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Student</a>
                    <a href="php/add_course.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Course</a>
                    <a href="php/attendance.php" class="btn btn-primary"><i class="fas fa-check"></i> Take
                        Attendance</a>
                </div>
            </div>
            </main>
    </div>
</body>

</html>