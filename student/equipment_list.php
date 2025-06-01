<?php
session_start();
require '../db/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

// Fetch all rooms and their equipment
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number ASC");
$equipment_by_room = [];
$equipment_result = $conn->query("SELECT equipment.*, rooms.room_number FROM equipment JOIN rooms ON equipment.room_id = rooms.room_id ORDER BY rooms.room_number, equipment.name");
while ($eq = $equipment_result->fetch_assoc()) {
    $equipment_by_room[$eq['room_number']][] = $eq;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Equipment List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar w-64 bg-red-700 text-white p-6 flex flex-col overflow-y-auto shadow-lg">
            <div class="flex items-center mb-8">
                <h1 class="text-3xl font-extrabold tracking-wide">Student</h1>
                <button id="toggleSidebar" class="ml-2 text-white focus:outline-none">
                    <span class="material-icons">chevron_left</span>
                </button>
            </div>
            <p class="text-xs font-semibold uppercase mb-4 tracking-wide text-red-300">Menu</p>
            <ul class="flex-grow space-y-2">
                <li><a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200"><span class="material-icons">dashboard</span><span class="font-semibold text-lg">Dashboard</span></a></li>
                <li><a href="select_pc.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200"><span class="material-icons">desktop_windows</span><span class="font-semibold text-lg">Select PC</span></a></li>
                <li><a href="equipment_list.php" class="flex items-center p-3 rounded-lg bg-red-800 hover:bg-red-600 transition-colors duration-200"><span class="material-icons">build</span><span class="font-semibold text-lg">Room Equipment</span></a></li>
                <li><a href="report_issue.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200"><span class="material-icons">report_problem</span><span class="font-semibold text-lg">Report Issue</span></a></li>
                <li><a href="announcements.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200"><span class="material-icons">announcement</span><span class="font-semibold text-lg">Announcements</span></a></li>
            </ul>
            <div class="mt-auto pt-6 border-t border-red-800">
                <p class="text-xs font-semibold uppercase mb-4 tracking-wide text-red-300">Support</p>
                <ul>
                    <li><a href="../login.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200"><span class="material-icons">logout</span><span class="font-semibold text-lg">Logout</span></a></li>
                </ul>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="container mx-auto px-6 py-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Room Equipment List</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php while ($room = $rooms->fetch_assoc()): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-sm text-gray-500">Room</span>
                                <h3 class="text-3xl font-extrabold text-gray-900"><?= htmlspecialchars($room['room_number']) ?></h3>
                            </div>
                            <span class="material-icons text-gray-300 text-5xl">meeting_room</span>
                        </div>
                        <div class="flex-1">
                        <?php if (!empty($equipment_by_room[$room['room_number']])): ?>
                            <ul class="space-y-3">
                                <?php foreach ($equipment_by_room[$room['room_number']] as $eq): ?>
                                    <li class="bg-gray-50 rounded-lg p-3">
                                        <div class="font-semibold text-gray-900 flex items-center">
                                            <span class="material-icons text-red-600 mr-2">build</span>
                                            <?= htmlspecialchars($eq['name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1 ml-7"> <?= htmlspecialchars($eq['description']) ?> </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-gray-400 italic text-center py-8">No equipment listed for this room.</div>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
        });
    </script>
</body>
</html> 