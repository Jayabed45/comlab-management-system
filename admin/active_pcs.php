<?php
session_start();
require '../db/db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// SQL to fetch active PCs with user and room info
$sql = "SELECT pcs.pc_id, pcs.pc_number, pcs.is_active, 
               users.full_name, usage_logs.login_time, rooms.room_number
        FROM pcs
        LEFT JOIN usage_logs ON pcs.pc_id = usage_logs.pc_id AND usage_logs.logout_time IS NULL
        LEFT JOIN users ON usage_logs.user_id = users.user_id
        LEFT JOIN rooms ON pcs.room_id = rooms.room_id
        WHERE pcs.is_active = 1
        ORDER BY rooms.room_number, pcs.pc_number";

$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Active PCs - Admin</title>
</head>
<body>
<h1>Active PCs</h1>
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
<table>
<thead>
<tr>
    <th>Room</th>
    <th>PC Number</th>
    <th>Used By</th>
    <th>Login Time</th>
</tr>
</thead>
<tbody>
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['room_number'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['pc_number']) ?></td>
        <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['login_time'] ?? 'N/A') ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="4">No active PCs found.</td>
    </tr>
<?php endif; ?>
</tbody>
</table>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
