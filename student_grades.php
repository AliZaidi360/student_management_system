<?php
session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header("Location: student_login.php");
    exit();
}

include 'php/connection.php';

$student_id = $_SESSION['student_id'];

// Fetch all grades for the student
$grades = $conn->query("SELECT grades.*, courses.course_name FROM grades LEFT JOIN courses ON grades.course_id = courses.id WHERE grades.student_id = $student_id ORDER BY grades.created_at DESC");

// Calculate GPA
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
    else continue;
    
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
    <title>My Grades - Student Portal</title>
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
                <li><a href="student_grades.php" class="active"><i class="fas fa-star"></i> My Grades</a></li>
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
                    <h1 class="page-title">My Grades</h1>
                    <p style="color: var(--text-secondary);">View all your course grades and academic performance.</p>
                </div>
                <div class="card stat-card" style="display: inline-flex; padding: 1rem 1.5rem;">
                    <div class="stat-icon" style="background-color: #e0e7ff; color: #4338ca; width: 50px; height: 50px; font-size: 1.25rem;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info" style="margin-left: 1rem;">
                        <h3 style="margin: 0; font-size: 0.75rem;">GPA</h3>
                        <div class="value" style="font-size: 1.5rem; margin: 0;"><?php echo $gpa; ?></div>
                    </div>
                </div>
            </header>

            <div class="card">
                <?php if ($grades->num_rows > 0): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Grade</th>
                                    <th>Date Recorded</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $grades->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <i class="fas fa-book" style="color: var(--primary-color);"></i>
                                                <span style="font-weight: 500;"><?php echo htmlspecialchars($row['course_name']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="background-color: #eef2ff; color: var(--primary-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 1.1rem;">
                                                <?php echo htmlspecialchars($row['grade']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('F d, Y', strtotime($row['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 3rem;">
                        <i class="fas fa-star" style="font-size: 3rem; color: var(--text-secondary); margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p style="color: var(--text-secondary); font-size: 1.1rem;">No grades available yet.</p>
                        <p style="color: var(--text-secondary); margin-top: 0.5rem;">Your grades will appear here once they are recorded.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>

