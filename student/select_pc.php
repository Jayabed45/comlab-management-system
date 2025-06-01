<?php
session_start();
require '../db/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if student is already using a PC
$current_pc_check = $conn->query("SELECT * FROM usage_logs WHERE user_id = $user_id AND logout_time IS NULL");
if ($current_pc_check->num_rows > 0) {
    header("Location: dashboard.php");
    exit;
}

// Handle PC selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_pc'])) {
    $pc_id = intval($_POST['pc_id']);

    // Verify PC is available
    $pc_check = $conn->query("SELECT * FROM pcs WHERE pc_id = $pc_id AND is_active = 1 AND used_by IS NULL");
    if ($pc_check->num_rows > 0) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Update PC to be used by student
            $stmt = $conn->prepare("UPDATE pcs SET used_by = ? WHERE pc_id = ?");
            $stmt->bind_param("ii", $user_id, $pc_id);
            $stmt->execute();

            // Log the usage
            $stmt2 = $conn->prepare("INSERT INTO usage_logs (user_id, pc_id, login_time) VALUES (?, ?, NOW())");
            $stmt2->bind_param("ii", $user_id, $pc_id);
            $stmt2->execute();

            $conn->commit();
            header("Location: dashboard.php");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Failed to select PC. Please try again.";
        }
    } else {
        $error = "PC is no longer available.";
    }
}

// Get room filter
$selected_room = isset($_GET['room']) ? $_GET['room'] : '';

// Get all equipment grouped by room_id
$equipment_by_room = [];
$equipment_result = $conn->query("SELECT equipment.*, rooms.room_number FROM equipment JOIN rooms ON equipment.room_id = rooms.room_id");
while ($eq = $equipment_result->fetch_assoc()) {
    $equipment_by_room[$eq['room_number']][] = $eq;
}

// Get all available PCs grouped by room
$pcs_by_room = [];
$available_pcs_result = $conn->query("SELECT pcs.pc_id, pcs.pc_number, pcs.cpu, pcs.ram, pcs.storage, pcs.gpu, pcs.os, rooms.room_number, rooms.room_id FROM pcs JOIN rooms ON pcs.room_id = rooms.room_id WHERE pcs.is_active = 1 AND pcs.used_by IS NULL ORDER BY rooms.room_number, pcs.pc_number");
while ($pc = $available_pcs_result->fetch_assoc()) {
    $pcs_by_room[$pc['room_number']][] = $pc;
}

// Get all rooms for filter
$rooms = $conn->query("SELECT DISTINCT room_number FROM rooms ORDER BY room_number");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select PC - Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary: #dc2626;
            --primary-dark: #b91c1c;
        }

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

        .pc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .pc-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .pc-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .pc-card.selected {
            border: 2px solid var(--primary);
            background-color: #fef2f2;
        }

        .status-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge.available {
            background-color: #dcfce7;
            color: #166534;
        }

        .room-filter {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .select-room {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em;
            padding-right: 2.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }
    </style>
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
                <li>
                    <a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">dashboard</span>
                        <span class="font-semibold text-lg">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="select_pc.php" class="flex items-center p-3 rounded-lg bg-red-800 hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">desktop_windows</span>
                        <span class="font-semibold text-lg">Select PC</span>
                    </a>
                </li>
                <li>
                    <a href="equipment_list.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">build</span>
                        <span class="font-semibold text-lg">Room Equipment</span>
                    </a>
                </li>
                <li>
                    <a href="report_issue.php" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <span class="material-icons">report_problem</span>
                        <span class="font-semibold text-lg">Report Issue</span>
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
        <main class="flex-1 overflow-y-auto">
            <div class="container mx-auto px-6 py-8">
                <?php if (isset($error)): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Select a PC</h2>
                        <p class="mt-2 text-gray-600">Choose an available computer from the list below</p>
                    </div>
                    <div class="room-filter p-4">
                        <form method="get" class="flex items-center">
                            <select name="room" onchange="this.form.submit()"
                                class="select-room w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">All Rooms</option>
                                <?php $rooms->data_seek(0); while ($room = $rooms->fetch_assoc()): ?>
                                    <option value="<?= $room['room_number'] ?>" <?= $selected_room == $room['room_number'] ? 'selected' : '' ?>>
                                        Room <?= htmlspecialchars($room['room_number']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </form>
                    </div>
                </div>

                <?php
                // Determine which rooms to show
                $room_keys = array_keys($pcs_by_room);
                if ($selected_room) {
                    $room_keys = array_intersect($room_keys, [$selected_room]);
                }
                foreach ($room_keys as $room_number):
                    $room_equipment = isset($equipment_by_room[$room_number]) ? $equipment_by_room[$room_number] : [];
                    $room_pcs = $pcs_by_room[$room_number];
                ?>
                    <div class="mb-10">
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800">Room <?= htmlspecialchars($room_number) ?></h3>
                        <?php if (count($room_equipment) > 0): ?>
                            <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
                                <h4 class="text-lg font-semibold mb-2 text-gray-800">Equipment</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <?php foreach ($room_equipment as $eq): ?>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h5 class="font-medium text-gray-900"><?= htmlspecialchars($eq['name']) ?></h5>
                                            <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($eq['description']) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <form method="post" id="pcSelectionForm<?= htmlspecialchars($room_number) ?>">
                            <div class="pc-grid">
                                <?php foreach ($room_pcs as $pc): ?>
                                    <div class="pc-card" onclick="selectPC(this, <?= $pc['pc_id'] ?>, '<?= htmlspecialchars($room_number) ?>')">
                                        <div class="p-6">
                                            <div class="status-badge available">
                                                <span class="material-icons text-sm">check_circle</span>
                                                Available
                                            </div>
                                            <div class="flex items-center justify-between mb-6">
                                                <div>
                                                    <span class="text-sm text-gray-500">Room</span>
                                                    <p class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($pc['room_number']) ?></p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-sm text-gray-500">PC Number</span>
                                                    <p class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($pc['pc_number']) ?></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center text-gray-600 mb-2">
                                                <span class="material-icons mr-2">computer</span>
                                                <span>Ready to use</span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 mt-2 mb-2">
                                                <div><span class="font-semibold">CPU:</span> <?= htmlspecialchars($pc['cpu'] ?? '-') ?></div>
                                                <div><span class="font-semibold">RAM:</span> <?= htmlspecialchars($pc['ram'] ?? '-') ?></div>
                                                <div><span class="font-semibold">Storage:</span> <?= htmlspecialchars($pc['storage'] ?? '-') ?></div>
                                                <div><span class="font-semibold">GPU:</span> <?= htmlspecialchars($pc['gpu'] ?? '-') ?></div>
                                                <div><span class="font-semibold">OS:</span> <?= htmlspecialchars($pc['os'] ?? '-') ?></div>
                                            </div>
                                            <input type="radio" name="pc_id" value="<?= $pc['pc_id'] ?>" class="hidden" required>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="submit" name="select_pc"
                                class="w-full btn-primary py-4 rounded-lg text-white flex items-center justify-center text-lg font-semibold mt-8"
                                onclick="return confirm('Are you sure you want to use this PC?')">
                                <span class="material-icons mr-2">desktop_windows</span>
                                Use Selected PC
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
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

        // PC selection functionality
        function selectPC(element, pcId, room) {
            // Remove selected class from all cards
            document.querySelectorAll('.pc-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            element.classList.add('selected');

            // Update radio button
            const radio = element.querySelector('input[type="radio"]');
            radio.checked = true;
        }

        // Form validation
        document.getElementById('pcSelectionForm').addEventListener('submit', function(e) {
            const selectedPc = document.querySelector('input[name="pc_id"]:checked');
            if (!selectedPc) {
                e.preventDefault();
                alert('Please select a PC first');
            }
        });
    </script>
</body>

</html>