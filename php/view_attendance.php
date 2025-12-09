<?php
include 'connection.php';

$courses = $conn->query("SELECT * FROM courses");
$attendance_records = [];
$selected_course_id = "";
$date = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_GET['course_id'])) {
    // Handle both POST (from form) and GET (if we want to support links, though form is POST)
    // Sticking to POST as per other files for consistency
    $selected_course_id = $_POST['course_id'] ?? '';
    $date = $_POST['date'] ?? '';

    if (!empty($selected_course_id)) {
        $sql = "SELECT a.date, s.name as student_name, a.status 
                FROM attendance a 
                JOIN students s ON a.student_id = s.id 
                WHERE a.course_id = '$selected_course_id'";

        if (!empty($date)) {
            $sql .= " AND a.date = '$date'";
        }

        $sql .= " ORDER BY a.date DESC, s.name ASC";

        $attendance_records = $conn->query($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance - Student Management System</title>
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
                <li><a href="view_attendance.php" class="active"><i class="fas fa-list-alt"></i> View Attendance</a>
                </li>
                <li><a href="view_grades.php"><i class="fas fa-clipboard-list"></i> View Grades</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="page-title">View Attendance</h1>
                    <p style="color: var(--text-secondary);">View attendance records by course and date.</p>
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
                        <div class="form-group" style="margin-bottom: 0; flex: 1;">
                            <label for="date">Date (Optional)</label>
                            <input type="date" id="date" name="date" value="<?php echo $date; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">View Records</button>
                    </div>
                </form>
            </div>

            <?php if (!empty($selected_course_id)): ?>
                <div class="card">
                    <?php if ($attendance_records && $attendance_records->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $attendance_records->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['student_name']; ?></td>
                                        <td>
                                            <span style="
                                                padding: 0.25rem 0.5rem; 
                                                border-radius: 4px; 
                                                font-size: 0.875rem; 
                                                font-weight: 500;
                                                background-color: <?php
                                                echo $row['status'] == 'Present' ? '#dcfce7' :
                                                    ($row['status'] == 'Absent' ? '#fee2e2' : '#fef9c3');
                                                ?>;
                                                color: <?php
                                                echo $row['status'] == 'Present' ? '#166534' :
                                                    ($row['status'] == 'Absent' ? '#991b1b' : '#854d0e');
                                                ?>;
                                            ">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No attendance records found for this selection.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>