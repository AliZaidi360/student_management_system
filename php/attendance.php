<?php
include 'connection.php';

$courses = $conn->query("SELECT * FROM courses");
$students = [];
$selected_course_id = "";
$date = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fetch_students'])) {
        $selected_course_id = $_POST['course_id'];
        $date = $_POST['date'];
        $students = $conn->query("SELECT * FROM students WHERE course_id = $selected_course_id");
    } elseif (isset($_POST['save_attendance'])) {
        $course_id = $_POST['course_id'];
        $date = $_POST['date'];
        $attendance_data = $_POST['status']; // Array of student_id => status

        foreach ($attendance_data as $student_id => $status) {
            $sql = "INSERT INTO attendance (student_id, course_id, status, date) VALUES ('$student_id', '$course_id', '$status', '$date')";
            $conn->query($sql);
        }
        $success = "Attendance recorded successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Student Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i> SMS
            </div>
            <ul class="nav-links">
                <li><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="view_students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                <li><a href="add_course.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="attendance.php" class="active"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1 class="page-title">Take Attendance</h1>
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
                        <div class="form-group" style="margin-bottom: 0; flex: 1;">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" value="<?php echo $date; ?>" required>
                        </div>
                        <button type="submit" name="fetch_students" class="btn btn-primary">Fetch Students</button>
                    </div>
                </form>
            </div>

            <?php if (!empty($students) && $students->num_rows > 0): ?>
                <div class="card">
                    <form method="POST" action="">
                        <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                        <input type="hidden" name="date" value="<?php echo $date; ?>">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $students->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td>
                                            <label style="display: inline-block; margin-right: 1rem;">
                                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="Present"
                                                    checked> Present
                                            </label>
                                            <label style="display: inline-block; margin-right: 1rem;">
                                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="Absent">
                                                Absent
                                            </label>
                                            <label style="display: inline-block;">
                                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="Late"> Late
                                            </label>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 1.5rem; text-align: right;">
                            <button type="submit" name="save_attendance" class="btn btn-primary">Save Attendance</button>
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