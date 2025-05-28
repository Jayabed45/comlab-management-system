<?php
session_start();
require '../db/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Toggle PC active status
if (isset($_GET['toggle_pc'])) {
    $pc_id = intval($_GET['toggle_pc']);
    // Get current status
    $res = $conn->query("SELECT is_active FROM pcs WHERE pc_id = $pc_id");
    $row = $res->fetch_assoc();
    $new_status = $row['is_active'] ? 0 : 1;

    // Update pc status and clear used_by if deactivated
    if ($new_status == 0) {
        $stmt = $conn->prepare("UPDATE pcs SET is_active = 0, used_by = NULL WHERE pc_id = ?");
    } else {
        $stmt = $conn->prepare("UPDATE pcs SET is_active = 1 WHERE pc_id = ?");
    }
    $stmt->bind_param("i", $pc_id);
    $stmt->execute();

    header("Location: manage_rooms.php");
    exit;
}

// Fetch rooms and PCs
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Rooms and PCs - Admin</title>
</head>
<body>
<h1>Manage Rooms and PCs</h1>
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
<?php while($room = $rooms->fetch_assoc()): ?>
    <h2>Room <?= htmlspecialchars($room['room_number']) ?></h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>PC Number</th>
                <th>Active</th>
                <th>Used By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $pcs = $conn->query("SELECT pcs.*, users.full_name FROM pcs LEFT JOIN users ON pcs.used_by = users.user_id WHERE pcs.room_id = " . $room['room_id'] . " ORDER BY pc_number ASC");
        while($pc = $pcs->fetch_assoc()):
        ?>
            <tr>
                <td><?= $pc['pc_number'] ?></td>
                <td><?= $pc['is_active'] ? 'Yes' : 'No' ?></td>
                <td><?= $pc['used_by'] ? htmlspecialchars($pc['full_name']) : 'N/A' ?></td>
                <td>
                    <a href="manage_rooms.php?toggle_pc=<?= $pc['pc_id'] ?>">
                        <?= $pc['is_active'] ? 'Deactivate' : 'Activate' ?>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endwhile; ?>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
