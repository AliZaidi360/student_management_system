<?php
include 'session_check.php';
requireAdmin();
include 'connection.php';

$courses = $conn->query("SELECT * FROM courses");
$grades_records = [];
$selected_course_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_GET['course_id'])) {
    $selected_course_id = $_POST['course_id'] ?? $_GET['course_id'] ?? '';

    if (!empty($selected_course_id)) {
        $sql = "SELECT g.grade, s.name as student_name, c.course_name
                FROM grades g 
                JOIN students s ON g.student_id = s.id 
                JOIN courses c ON g.course_id = c.id
                WHERE g.course_id = '$selected_course_id'
                ORDER BY s.name ASC";

        $grades_records = $conn->query($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Grades - Student Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="user-profile">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" style="font-size: 0.875rem; color: var(--text-secondary); text-decoration: none; margin-top: 0.5rem; display: block;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="view_students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                <li><a href="add_course.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="add_grade.php"><i class="fas fa-star"></i> Grades</a></li>
                <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li><a href="view_attendance.php"><i class="fas fa-list-alt"></i> View Attendance</a></li>
                <li><a href="view_grades.php" class="active"><i class="fas fa-clipboard-list"></i> View Grades</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="page-title">View Grades</h1>
                    <p style="color: var(--text-secondary);">View student grades by course.</p>
                </div>
            </header>

            <div class="card" style="margin-bottom: 2rem;">
                <form method="POST" action="">
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div class="form-group" style="margin-bottom: 0; flex: 1;">
                            <label for="course_id">Select Course</label>
                            <select id="course_id" name="course_id" required>
                                <option value="">Select Course</option>
                                <?php
                                $courses->data_seek(0);
                                while ($row = $courses->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $selected_course_id)
                                           echo 'selected'; ?>><?php echo $row['course_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">View Grades</button>
                    </div>
                </form>
            </div>

            <?php if (!empty($selected_course_id)): ?>
                <div class="card">
                    <?php if ($grades_records && $grades_records->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Course</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $grades_records->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['student_name']; ?></td>
                                        <td><?php echo $row['course_name']; ?></td>
                                        <td>
                                            <span style="
                                                padding: 0.25rem 0.5rem; 
                                                border-radius: 4px; 
                                                font-size: 0.875rem; 
                                                font-weight: 700;
                                                background-color: #f3f4f6;
                                                color: #1f2937;
                                            ">
                                                <?php echo $row['grade']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No grade records found for this course.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>