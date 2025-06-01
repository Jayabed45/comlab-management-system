<?php
$conn = new mysqli("localhost", "root", "", "comlab_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST["first_name"]);
    $last_name = $conn->real_escape_string($_POST["last_name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $year = $conn->real_escape_string($_POST["year"]);
    $section = $conn->real_escape_string($_POST["section"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = "student"; // default
    $course = "BSIT"; // default

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_email = $conn->query("SELECT * FROM users WHERE email='$email'");

        if ($check_email->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $sql = "INSERT INTO users (full_name, email, password, role, course, year, section) 
                    VALUES ('$first_name $last_name', '$email', '$hashed_password', '$role', '$course', '$year', '$section')";

            if ($conn->query($sql)) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed! " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Computer Lab Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Add any necessary custom styles here */
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center">

    <!-- Header Section -->
    <header class="w-full bg-white shadow-md py-4">
        <div class="container mx-auto flex items-center justify-between px-4 relative">
            <!-- Left Logo -->
            <img src="assets/images/bsit.png" alt="Logo 1" class="h-10 mr-2">

            <!-- Centered Text -->
            <div class="flex-1 flex justify-center">
                <h1 class="text-xl font-bold text-gray-800 text-center">COMPUTER LABORATORY MANAGEMENT SYSTEM</h1>
            </div>

            <!-- Right Logo -->
            <img src="assets/images/ctu.png" alt="Logo 2" class="h-10 ml-2">
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow container mx-auto px-4 py-8 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full max-w-2xl">
            <div class="bg-red-700 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">REGISTER ACCOUNT</h2>
            </div>

            <div class="p-6">
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                        <input type="text" name="first_name" id="first_name" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">Year & Section:</label>
                        <div class="mt-1 grid grid-cols-2 gap-4">
                            <select name="year" id="year" required
                                class="block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm appearance-none">
                                <option value="" disabled selected>Select Year</option>
                                <option value="1st year">1st year</option>
                                <option value="2nd year">2nd year</option>
                                <option value="3rd year">3rd year</option>
                                <option value="4th year">4th year</option>
                            </select>
                            <select name="section" id="section" required
                                class="block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm appearance-none">
                                <option value="" disabled selected>Select Section</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                        <input type="password" name="confirm_password" id="confirm_password" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Register
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="login.php" class="font-medium text-red-600 hover:text-red-500">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

</body>

</html>