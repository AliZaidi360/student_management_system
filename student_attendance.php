<?php
session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header("Location: student_login.php");
    exit();
}

include 'php/connection.php';

$student_id = $_SESSION['student_id'];

// Fetch all attendance records for the student
$attendance = $conn->query("SELECT attendance.*, courses.course_name FROM attendance LEFT JOIN courses ON attendance.course_id = courses.id WHERE attendance.student_id = $student_id ORDER BY attendance.date DESC");

// Calculate attendance statistics
$stats = $conn->query("SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_count,
    SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_count,
    SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late_count
    FROM attendance WHERE student_id = $student_id")->fetch_assoc();

$attendance_rate = $stats['total_records'] > 0 
    ? round(($stats['present_count'] / $stats['total_records']) * 100, 1) 
    : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance - Student Portal</title>
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
                <li><a href="student_profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                <li><a href="student_grades.php"><i class="fas fa-star"></i> My Grades</a></li>
                <li><a href="student_attendance.php" class="active"><i class="fas fa-calendar-check"></i> My Attendance</a></li>
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
                    <h1 class="page-title">My Attendance</h1>
                    <p style="color: var(--text-secondary);">View your attendance records and statistics.</p>
                </div>
            </header>

            <!-- Attendance Stats -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #dbeafe; color: #1e40af;">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Attendance Rate</h3>
                        <div class="value"><?php echo $attendance_rate; ?>%</div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #d1fae5; color: #10b981;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Present</h3>
                        <div class="value"><?php echo $stats['present_count']; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #fee2e2; color: #ef4444;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Absent</h3>
                        <div class="value"><?php echo $stats['absent_count']; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #fef3c7; color: #f59e0b;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Late</h3>
                        <div class="value"><?php echo $stats['late_count']; ?></div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="card">
                <h2 style="margin-bottom: 1.5rem;">Attendance Records</h2>
                <?php if ($attendance->num_rows > 0): ?>
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
                                <?php while ($row = $attendance->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <i class="fas fa-calendar" style="color: var(--primary-color);"></i>
                                                <span><?php echo date('F d, Y', strtotime($row['date'])); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <i class="fas fa-book" style="color: var(--primary-color);"></i>
                                                <span><?php echo htmlspecialchars($row['course_name']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $row['status'];
                                            $status_color = '';
                                            $status_bg = '';
                                            if ($status == 'Present') {
                                                $status_color = '#10b981';
                                                $status_bg = '#d1fae5';
                                            } elseif ($status == 'Absent') {
                                                $status_color = '#ef4444';
                                                $status_bg = '#fee2e2';
                                            } else {
                                                $status_color = '#f59e0b';
                                                $status_bg = '#fef3c7';
                                            }
                                            ?>
                                            <span style="background-color: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600;">
                                                <i class="fas fa-<?php echo $status == 'Present' ? 'check' : ($status == 'Absent' ? 'times' : 'clock'); ?>-circle"></i>
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 3rem;">
                        <i class="fas fa-calendar-check" style="font-size: 3rem; color: var(--text-secondary); margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p style="color: var(--text-secondary); font-size: 1.1rem;">No attendance records available yet.</p>
                        <p style="color: var(--text-secondary); margin-top: 0.5rem;">Your attendance records will appear here once they are recorded.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>

