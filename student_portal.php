<?php
session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header("Location: student_login.php");
    exit();
}

include 'php/connection.php';

$student_id = $_SESSION['student_id'];

// Fetch student information
$student = $conn->query("SELECT students.*, courses.course_name FROM students LEFT JOIN courses ON students.course_id = courses.id WHERE students.id = $student_id")->fetch_assoc();

// Fetch student grades
$grades = $conn->query("SELECT grades.*, courses.course_name FROM grades LEFT JOIN courses ON grades.course_id = courses.id WHERE grades.student_id = $student_id ORDER BY grades.created_at DESC");

// Fetch attendance summary
$attendance_summary = $conn->query("SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_count,
    SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_count,
    SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late_count
    FROM attendance WHERE student_id = $student_id")->fetch_assoc();

// Fetch recent attendance
$recent_attendance = $conn->query("SELECT attendance.*, courses.course_name FROM attendance LEFT JOIN courses ON attendance.course_id = courses.id WHERE attendance.student_id = $student_id ORDER BY attendance.date DESC LIMIT 10");

// Calculate GPA (assuming grades are A, B, C, D, F)
$gpa_query = $conn->query("SELECT grade FROM grades WHERE student_id = $student_id");
$total_points = 0;
$total_grades = 0;
while ($grade_row = $gpa_query->fetch_assoc()) {
    $grade = strtoupper($grade_row['grade']);
    $points = 0;
    if ($grade == 'A' || $grade == 'A+') $points = 4.0;
    elseif ($grade == 'A-') $points = 3.7;
    elseif ($grade == 'B+') $points = 3.3;
    elseif ($grade == 'B') $points = 3.0;
    elseif ($grade == 'B-') $points = 2.7;
    elseif ($grade == 'C+') $points = 2.3;
    elseif ($grade == 'C') $points = 2.0;
    elseif ($grade == 'C-') $points = 1.7;
    elseif ($grade == 'D+') $points = 1.3;
    elseif ($grade == 'D') $points = 1.0;
    elseif ($grade == 'F') $points = 0.0;
    else continue; // Skip invalid grades
    
    $total_points += $points;
    $total_grades++;
}
$gpa = $total_grades > 0 ? number_format($total_points / $total_grades, 2) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Student Management System</title>
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
                <li><a href="student_portal.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="student_profile.php"><i class="fas fa-user"></i> My Profile</a></li>
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
                    <h1 class="page-title">Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?>!</h1>
                    <p style="color: var(--text-secondary);">View your academic information and progress.</p>
                </div>
            </header>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #e0e7ff; color: #4338ca;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3>GPA</h3>
                        <div class="value"><?php echo $gpa; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #dbeafe; color: #1e40af;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Attendance Rate</h3>
                        <div class="value">
                            <?php 
                            $attendance_rate = $attendance_summary['total_records'] > 0 
                                ? round(($attendance_summary['present_count'] / $attendance_summary['total_records']) * 100, 1) 
                                : 0; 
                            echo $attendance_rate . '%';
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #f3e8ff; color: #7e22ce;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Course</h3>
                        <div class="value" style="font-size: 1.25rem;">
                            <?php echo htmlspecialchars($student['course_name'] ?? 'Not Assigned'); ?>
                        </div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #fef3c7; color: #d97706;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Grades</h3>
                        <div class="value"><?php echo $grades->num_rows; ?></div>
                    </div>
                </div>
            </div>

            <!-- Recent Grades -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="header" style="margin-bottom: 1.5rem;">
                    <h2 style="margin: 0;">Recent Grades</h2>
                    <a href="student_grades.php" class="btn btn-outline">View All</a>
                </div>
                <?php if ($grades->num_rows > 0): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grades->data_seek(0);
                                $count = 0;
                                while ($count < 5 && ($row = $grades->fetch_assoc())): 
                                    $count++;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                                        <td>
                                            <span style="background-color: #eef2ff; color: var(--primary-color); padding: 0.25rem 0.75rem; border-radius: 6px; font-weight: 600;">
                                                <?php echo htmlspecialchars($row['grade']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-secondary); text-align: center; padding: 2rem;">No grades available yet.</p>
                <?php endif; ?>
            </div>

            <!-- Recent Attendance -->
            <div class="card">
                <div class="header" style="margin-bottom: 1.5rem;">
                    <h2 style="margin: 0;">Recent Attendance</h2>
                    <a href="student_attendance.php" class="btn btn-outline">View All</a>
                </div>
                <?php if ($recent_attendance->num_rows > 0): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $recent_attendance->data_seek(0);
                                while ($row = $recent_attendance->fetch_assoc()): 
                                ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                                        <td>
                                            <?php
                                            $status_color = '';
                                            if ($row['status'] == 'Present') $status_color = '#10b981';
                                            elseif ($row['status'] == 'Absent') $status_color = '#ef4444';
                                            else $status_color = '#f59e0b';
                                            ?>
                                            <span style="background-color: <?php echo $status_color; ?>20; color: <?php echo $status_color; ?>; padding: 0.25rem 0.75rem; border-radius: 6px; font-weight: 600;">
                                                <?php echo htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-secondary); text-align: center; padding: 2rem;">No attendance records available yet.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>

