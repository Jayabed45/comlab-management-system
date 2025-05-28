<?php
session_start();
require '../db/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Post announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message']; // Changed from 'content' to 'message'
    $date_posted = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO announcements (title, message, date_posted) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $message, $date_posted);
    $stmt->execute();
    header("Location: announcements.php");
    exit;
}

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Announcements - Admin</title>
</head>
<body>
<h1>Manage Announcements</h1>

<nav>
<ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="active_pcs.php">Active PCs</a></li>
    <li><a href="manage_users.php">Manage Users</a></li>
    <li><a href="manage_rooms.php">Manage Rooms</a></li>
    <li><a href="maintenance_reports.php">Maintenance Reports</a></li>
    <li><a href="announcements.php">Announcements</a></li>
    <li><a href="../login.php">Logout</a></li>
</ul>
</nav>

<h2>Post New Announcement</h2>
<form method="post">
    <label>Title: <input type="text" name="title" required></label><br><br>
    <label>Message: <textarea name="message" rows="5" cols="50" required></textarea></label><br><br>
    <button type="submit" name="post_announcement">Post</button>
</form>

<h2>All Announcements</h2>
<?php if ($announcements->num_rows > 0): ?>
<ul>
    <?php while($a = $announcements->fetch_assoc()): ?>
        <li style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
            <strong><?= htmlspecialchars($a['title']) ?></strong> (<?= $a['date_posted'] ?>)<br>
            <?= nl2br(htmlspecialchars($a['message'])) ?>
        </li>
    <?php endwhile; ?>
</ul>
<?php else: ?>
<p>No announcements posted yet.</p>
<?php endif; ?>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>