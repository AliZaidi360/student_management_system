<?php
include 'connection.php';

$courses = $conn->query("SELECT * FROM courses");
$students = [];
$selected_course_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fetch_students'])) {
        $selected_course_id = $_POST['course_id'];
        $students = $conn->query("SELECT * FROM students WHERE course_id = $selected_course_id");
    } elseif (isset($_POST['save_grades'])) {
        $course_id = $_POST['course_id'];
        $grades = $_POST['grade']; // Array of student_id => grade

        foreach ($grades as $student_id => $grade) {
            if (!empty($grade)) {
                $sql = "INSERT INTO grades (student_id, course_id, grade) VALUES ('$student_id', '$course_id', '$grade')";
                $conn->query($sql);
            }
        }
        $success = "Grades recorded successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Grades - Student Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="user-profile">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Admin</span>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="view_students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                <li><a href="add_course.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="add_grade.php" class="active"><i class="fas fa-star"></i> Grades</a></li>
                <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1 class="page-title">Add Grades</h1>
            </div>

            <div class="card" style="margin-bottom: 2rem;">
                <?php if (isset($success)): ?>
                    <div style="color: green; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>
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
                        <button type="submit" name="fetch_students" class="btn btn-primary">Fetch Students</button>
                    </div>
                </form>
            </div>

            <?php if (!empty($students) && $students->num_rows > 0): ?>
                <div class="card">
                    <form method="POST" action="">
                        <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $students->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td>
                                            <input type="text" name="grade[<?php echo $row['id']; ?>]"
                                                placeholder="Enter Grade (e.g. A, B+)" style="width: 150px;">
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 1.5rem; text-align: right;">
                            <button type="submit" name="save_grades" class="btn btn-primary">Save Grades</button>
                        </div>
                    </form>
                </div>
            <?php elseif (isset($_POST['fetch_students'])): ?>
                <div class="card">
                    <p class="text-center">No students found in this course.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>