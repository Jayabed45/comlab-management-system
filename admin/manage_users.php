<?php
session_start();
require '../db/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle edit user load
$edit_user = null;
if (isset($_GET['edit_user_id'])) {
    $edit_user_id = intval($_GET['edit_user_id']);
    $edit_query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $edit_query->bind_param("i", $edit_user_id);
    $edit_query->execute();
    $edit_result = $edit_query->get_result();
    if ($edit_result->num_rows > 0) {
        $edit_user = $edit_result->fetch_assoc();
    }
}

// Handle add, edit, delete users here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $course = $_POST['course'] ?? null;
        $year = $_POST['year'] ?? null;
        $section = $_POST['section'] ?? null;

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role, course, year, section) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $email, $password, $role, $course, $year, $section);
        $stmt->execute();
        header("Location: manage_users.php");
        exit;
    } elseif (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $course = $_POST['course'] ?? null;
        $year = $_POST['year'] ?? null;
        $section = $_POST['section'] ?? null;

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, password = ?, role = ?, course = ?, year = ?, section = ? WHERE user_id = ?");
            $stmt->bind_param("sssssssi", $full_name, $email, $password, $role, $course, $year, $section, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, role = ?, course = ?, year = ?, section = ? WHERE user_id = ?");
            $stmt->bind_param("ssssssi", $full_name, $email, $role, $course, $year, $section, $user_id);
        }
        $stmt->execute();
        header("Location: manage_users.php");
        exit;
    } elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        // Prevent deleting yourself
        if ($user_id != $_SESSION['user_id']) {
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }
        header("Location: manage_users.php");
        exit;
    }
}

// Fetch all users except admins
$result = $conn->query("SELECT * FROM users WHERE role != 'admin' ORDER BY user_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Users - Admin</title>
</head>
<body>
<h1>Manage Users</h1>

<nav>
  <ul>
    <li><a href="dashboard.php" class="active">Dashboard</a></li>
    <li><a href="active_pcs.php">Active PCs</a></li>
    <li><a href="manage_users.php">Manage Users</a></li>
    <li><a href="manage_rooms.php">Manage Rooms</a></li>
    <li><a href="maintenance_reports.php">Maintenance Reports</a></li>
    <li><a href="announcements.php">Announcements</a></li>
    <li><a href="../login.php">Logout</a></li>
  </ul>
</nav>

<?php if ($edit_user): ?>
<h2>Edit User (ID: <?= $edit_user['user_id'] ?>)</h2>
<form method="post">
    <input type="hidden" name="user_id" value="<?= $edit_user['user_id'] ?>">
    <label>Full Name: <input type="text" name="full_name" value="<?= htmlspecialchars($edit_user['full_name']) ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($edit_user['email']) ?>" required></label><br>
    <label>Password: <input type="password" name="password" placeholder="Leave blank to keep current"></label><br>
    <label>Role:
        <select name="role" required>
            <option value="student" <?= $edit_user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
            <option value="admin" <?= $edit_user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </label><br>
    <label>Course: <input type="text" name="course" value="<?= htmlspecialchars($edit_user['course']) ?>"></label><br>
    <label>Year: <input type="text" name="year" value="<?= htmlspecialchars($edit_user['year']) ?>"></label><br>
    <label>Section: <input type="text" name="section" value="<?= htmlspecialchars($edit_user['section']) ?>"></label><br>
    <button type="submit" name="edit_user">Update User</button>
    <a href="manage_users.php">Cancel</a>
</form>

<?php else: ?>

<h2>Add User</h2>
<form method="post">
    <label>Full Name: <input type="text" name="full_name" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Role:
        <select name="role" required>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select>
    </label><br>
    <label>Course: <input type="text" name="course"></label><br>
    <label>Year: <input type="text" name="year"></label><br>
    <label>Section: <input type="text" name="section"></label><br>
    <button type="submit" name="add_user">Add User</button>
</form>

<?php endif; ?>

<h2>Users List</h2>
<table border="1" cellpadding="5" cellspacing="0">
<thead>
<tr>
    <th>User ID</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Course</th>
    <th>Year</th>
    <th>Section</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['user_id'] ?></td>
    <td><?= htmlspecialchars($row['full_name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= $row['role'] ?></td>
    <td><?= htmlspecialchars($row['course']) ?></td>
    <td><?= htmlspecialchars($row['year']) ?></td>
    <td><?= htmlspecialchars($row['section']) ?></td>
    <td>
        <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this user?');">
                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                <button type="submit" name="delete_user">Delete</button>
            </form>
            <form method="get" style="display:inline-block;">
                <input type="hidden" name="edit_user_id" value="<?= $row['user_id'] ?>">
                <button type="submit">Edit</button>
            </form>
        <?php else: ?>
            N/A
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
