<?php
include 'connection.php';

// Fetch courses for dropdown
$courses = $conn->query("SELECT * FROM courses");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course_id = $_POST['course_id'];

    $sql = "INSERT INTO students (name, email, phone, course_id) VALUES ('$name', '$email', '$phone', '$course_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_students.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Management System</title>
    <link rel="stylesheet" href="../css/style.css">
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
                <li><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="view_students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                <li><a href="add_course.php"><i class="fas fa-book"></i> Courses</a></li>
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
                    <h1 class="page-title">Add Student</h1>
                    <p style="color: var(--text-secondary);">Register a new student to the system.</p>
                </div>
            </header>

            <div class="card" style="max-width: 600px;">
                <?php if (isset($error)): ?>
                    <div style="color: red; margin-bottom: 1rem;"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="course_id">Course</label>
                        <select id="course_id" name="course_id" required>
                            <option value="">Select Course</option>
                            <?php while ($row = $courses->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-primary">Add Student</button>
                        <a href="view_students.php" class="btn btn-outline" style="text-decoration: none;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>