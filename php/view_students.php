<?php
include 'connection.php';

// Fetch students with course names
$sql = "SELECT students.*, courses.course_name FROM students LEFT JOIN courses ON students.course_id = courses.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Student Management System</title>
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
                <li><a href="view_students.php" class="active"><i class="fas fa-user-graduate"></i> Students</a></li>
                <li><a href="add_course.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="add_grade.php"><i class="fas fa-star"></i> Grades</a></li>
                <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="page-title">Students</h1>
                    <p style="color: var(--text-secondary);">Manage enrolled students.</p>
                </div>
                <a href="add_student.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Student</a>
            </header>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['course_name'] ? $row['course_name'] : 'N/A'; ?></td>
                                    <td>
                                        <a href="update_student.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No students found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>