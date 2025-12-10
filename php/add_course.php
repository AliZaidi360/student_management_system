<?php
include 'session_check.php';
requireAdmin();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $sql = "INSERT INTO courses (course_name) VALUES ('$course_name')";
    if ($conn->query($sql) === TRUE) {
        $success = "Course added successfully";
    } else {
        $error = "Error: " . $conn->error;
    }
}

$courses = $conn->query("SELECT * FROM courses");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - Student Management System</title>
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
                <li><a href="add_course.php" class="active"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="add_grade.php"><i class="fas fa-star"></i> Grades</a></li>
                <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li><a href="view_attendance.php"><i class="fas fa-list-alt"></i> View Attendance</a></li>
                <li><a href="view_grades.php"><i class="fas fa-clipboard-list"></i> View Grades</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="page-title">Manage Courses</h1>
                    <p style="color: var(--text-secondary);">Add and view courses.</p>
                </div>
            </header>

            <div class="card" style="max-width: 600px; margin-bottom: 2rem;">
                <h3>Add New Course</h3>
                <?php if (isset($success)): ?>
                    <div style="color: green; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div style="color: red; margin-bottom: 1rem;"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="course_name">Course Name</label>
                        <input type="text" id="course_name" name="course_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </div>

            <div class="card">
                <h3>Existing Courses</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Course Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $courses->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['course_name']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>