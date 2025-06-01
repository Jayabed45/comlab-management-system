<?php
session_start();
require '../db/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Add equipment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_equipment'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $room_id = $_POST['room_id'];

    $stmt = $conn->prepare("INSERT INTO equipment (name, description, room_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $desc, $room_id);
    $stmt->execute();
    header("Location: manage_equipment.php");
    exit;
}

// Delete equipment
if (isset($_POST['delete_equipment'])) {
    $id = $_POST['equipment_id'];
    $stmt = $conn->prepare("DELETE FROM equipment WHERE equipment_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_equipment.php");
    exit;
}

$equipments = $conn->query("SELECT equipment.*, rooms.room_number FROM equipment LEFT JOIN rooms ON equipment.room_id = rooms.room_id ORDER BY equipment.equipment_id DESC");
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Equipment - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        nav a span.material-icons {
            margin-right: 12px;
            font-size: 24px;
            vertical-align: middle;
        }
        nav::-webkit-scrollbar {
            width: 6px;
        }
        nav::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        .sidebar {
            transition: width 0.3s;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar w-64 bg-red-700 text-white p-6 flex flex-col overflow-y-auto shadow-lg">
            <div class="flex items-center mb-8">
                <h1 class="text-3xl font-extrabold tracking-wide">Admin</h1>
                <button id="toggleSidebar" class="ml-2 text-white focus:outline-none">
                    <span class="material-icons">chevron_left</span>
                </button>
            </div>

            <p class="text-xs font-semibold uppercase mb-4 tracking-wide text-red-300">Menu</p>

            <ul class="flex-grow space-y-2">
                <li>
                    <a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">dashboard</span>
                        <span class="font-semibold text-lg">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="active_pcs.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">desktop_windows</span>
                        <span class="font-semibold text-lg">Active PCs</span>
                    </a>
                </li>
                <li>
                    <a href="manage_users.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">people</span>
                        <span class="font-semibold text-lg">Users</span>
                    </a>
                </li>
                <li>
                    <a href="manage_rooms.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">meeting_room</span>
                        <span class="font-semibold text-lg">Rooms</span>
                    </a>
                </li>
                <li>
                    <a href="manage_equipment.php" class="flex items-center p-3 rounded-lg bg-red-800 hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">build</span>
                        <span class="font-semibold text-lg">Equipment</span>
                    </a>
                </li>
                <li>
                    <a href="maintenance_reports.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">description</span>
                        <span class="font-semibold text-lg">Reports</span>
                    </a>
                </li>
                <li>
                    <a href="announcements.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">announcement</span>
                        <span class="font-semibold text-lg">Announcements</span>
                    </a>
                </li>
            </ul>

            <div class="mt-auto pt-6 border-t border-red-800">
                <p class="text-xs font-semibold uppercase mb-4 tracking-wide text-red-300">Support</p>
                <ul>
                    <li>
                        <a href="../login.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                            <span class="material-icons">logout</span>
                            <span class="font-semibold text-lg">Logout</span>
                        </a>
                    </li>
  </ul>
            </div>
</nav>

        <!-- Main Content -->
        <main class="flex-grow p-6 md:p-10 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-4xl font-extrabold text-gray-800">Manage Equipment</h2>
                <a href="dashboard.php" class="inline-flex items-center px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800 transition-colors duration-200">
                    <span class="material-icons mr-2">arrow_back</span>
                    Back to Dashboard
                </a>
            </div>

            <!-- Add Equipment Form -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Add New Equipment</h3>
                <form method="post" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Equipment Name</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                            <select name="room_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            <?php while($room = $rooms->fetch_assoc()): ?>
                                    <option value="<?= $room['room_id'] ?>">Room <?= htmlspecialchars($room['room_number']) ?></option>
            <?php endwhile; ?>
        </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" required rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" name="add_equipment"
                            class="px-6 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800 transition-colors duration-200">
                            Add Equipment
                        </button>
                    </div>
</form>
            </div>

            <!-- Equipment List -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Equipment List</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
<tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
                            <tbody class="bg-white divide-y divide-gray-200">
<?php while($eq = $equipments->fetch_assoc()): ?>
<tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($eq['name']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= htmlspecialchars($eq['description']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Room <?= htmlspecialchars($eq['room_number']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <form method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this equipment?');">
            <input type="hidden" name="equipment_id" value="<?= $eq['equipment_id'] ?>">
                                            <button type="submit" name="delete_equipment"
                                                class="text-red-600 hover:text-red-800 font-medium">
                                                Delete
                                            </button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Sidebar toggle functionality
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
        });
    </script>
</body>
</html>
