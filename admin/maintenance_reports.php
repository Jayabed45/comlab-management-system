<?php
session_start();
require '../db/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch reports with PC info and reporter info
$sql = "SELECT maintenance_reports.*, pcs.pc_number, rooms.room_number, users.full_name
        FROM maintenance_reports
        LEFT JOIN pcs ON maintenance_reports.pc_id = pcs.pc_id
        LEFT JOIN rooms ON pcs.room_id = rooms.room_id
        LEFT JOIN users ON maintenance_reports.reported_by = users.user_id
        ORDER BY maintenance_reports.report_date DESC";
$reports = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Maintenance Reports - Admin</title>
</head>
<body>
<h1>Maintenance Reports</h1>
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
<table border="1" cellpadding="5" cellspacing="0">
<thead>
<tr>
    <th>Report ID</th>
    <th>Room</th>
    <th>PC Number</th>
    <th>Reported By</th>
    <th>Report Text</th>
    <th>Date</th>
</tr>
</thead>
<tbody>
<?php while($report = $reports->fetch_assoc()): ?>
<tr>
    <td><?= $report['report_id'] ?></td>
    <td><?= htmlspecialchars($report['room_number']) ?></td>
    <td><?= $report['pc_number'] ?></td>
    <td><?= htmlspecialchars($report['full_name']) ?></td>
    <td><?= htmlspecialchars($report['report_text']) ?></td>
    <td><?= $report['report_date'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
