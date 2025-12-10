<?php
include 'session_check.php';
requireStudent();
include 'connection.php';

$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['student_name'];

// Fetch student's grades
$grades_query = "SELECT g.grade, c.course_name, g.created_at 
                 FROM grades g 
                 JOIN courses c ON g.course_id = c.id 
                 WHERE g.student_id = ? 
                 ORDER BY g.created_at DESC";
$stmt = $conn->prepare($grades_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$grades_result = $stmt->get_result();

// Fetch student's attendance
$attendance_query = "SELECT a.date, a.status, c.course_name 
                     FROM attendance a 
                     JOIN courses c ON a.course_id = c.id 
                     WHERE a.student_id = ? 
                     ORDER BY a.date DESC 
                     LIMIT 50";
$stmt2 = $conn->prepare($attendance_query);
$stmt2->bind_param("i", $student_id);
$stmt2->execute();
$attendance_result = $stmt2->get_result();

// Calculate attendance stats
$attendance_stats_query = "SELECT 
                            COUNT(*) as total,
                            SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
                            SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late
                           FROM attendance 
                           WHERE student_id = ?";
$stmt3 = $conn->prepare($attendance_stats_query);
$stmt3->bind_param("i", $student_id);
$stmt3->execute();
$stats_result = $stmt3->get_result();
$stats = $stats_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Student Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="user-profile">
                    <span><?php echo htmlspecialchars($student_name); ?></span>
                    <span style="font-size: 0.75rem; color: var(--text-secondary);">Student</span>
                    <a href="logout.php" style="font-size: 0.875rem; color: var(--text-secondary); text-decoration: none; margin-top: 0.5rem; display: block;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="student_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#grades"><i class="fas fa-star"></i> My Grades</a></li>
                <li><a href="#attendance"><i class="fas fa-calendar-check"></i> My Attendance</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="page-title">Welcome, <?php echo htmlspecialchars($student_name); ?>!</h1>
                    <p style="color: var(--text-secondary);">View your grades and attendance records.</p>
                </div>
            </header>

            <!-- Attendance Stats -->
            <div class="stats-grid">
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #dcfce7; color: #166534;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Present</h3>
                        <div class="value"><?php echo $stats['present'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #fee2e2; color: #991b1b;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Absent</h3>
                        <div class="value"><?php echo $stats['absent'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #fef9c3; color: #854d0e;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Late</h3>
                        <div class="value"><?php echo $stats['late'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="stat-icon" style="background-color: #dbeafe; color: #1e40af;">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Records</h3>
                        <div class="value"><?php echo $stats['total'] ?? 0; ?></div>
                    </div>
                </div>
            </div>

            <!-- Grades Section -->
            <div class="card" id="grades" style="margin-bottom: 2rem;">
                <h2 style="margin-top: 0; margin-bottom: 1.5rem;">
                    <i class="fas fa-star" style="color: var(--primary-color);"></i> My Grades
                </h2>
                <?php if ($grades_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Grade</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($grade = $grades_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($grade['course_name']); ?></td>
                                    <td>
                                        <span style="
                                            padding: 0.25rem 0.5rem; 
                                            border-radius: 4px; 
                                            font-size: 0.875rem; 
                                            font-weight: 700;
                                            background-color: #f3f4f6;
                                            color: #1f2937;
                                        ">
                                            <?php echo htmlspecialchars($grade['grade']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($grade['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center" style="color: var(--text-secondary); padding: 2rem;">
                        <i class="fas fa-info-circle"></i> No grades recorded yet.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Attendance Section -->
            <div class="card" id="attendance">
                <h2 style="margin-top: 0; margin-bottom: 1.5rem;">
                    <i class="fas fa-calendar-check" style="color: var(--primary-color);"></i> My Attendance
                </h2>
                <?php if ($attendance_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Course</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($attendance = $attendance_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($attendance['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($attendance['course_name']); ?></td>
                                    <td>
                                        <span style="
                                            padding: 0.25rem 0.5rem; 
                                            border-radius: 4px; 
                                            font-size: 0.875rem; 
                                            font-weight: 500;
                                            background-color: <?php
                                                echo $attendance['status'] == 'Present' ? '#dcfce7' :
                                                    ($attendance['status'] == 'Absent' ? '#fee2e2' : '#fef9c3');
                                            ?>;
                                            color: <?php
                                                echo $attendance['status'] == 'Present' ? '#166534' :
                                                    ($attendance['status'] == 'Absent' ? '#991b1b' : '#854d0e');
                                            ?>;
                                        ">
                                            <?php echo htmlspecialchars($attendance['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center" style="color: var(--text-secondary); padding: 2rem;">
                        <i class="fas fa-info-circle"></i> No attendance records found.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>

