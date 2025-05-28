<?php
$conn = new mysqli("localhost", "root", "", "comlab_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST["full_name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $role = $conn->real_escape_string($_POST["role"]); // get role from form
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // For students, get course, year, section; else set to NULL
    if ($role === "student") {
        $course = "'" . $conn->real_escape_string($_POST["course"]) . "'";
        $year = "'" . $conn->real_escape_string($_POST["year"]) . "'";
        $section = "'" . $conn->real_escape_string($_POST["section"]) . "'";
    } else {
        $course = "NULL";
        $year = "NULL";
        $section = "NULL";
    }

    // Check if email exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (full_name, email, password, role, course, year, section) 
                VALUES ('$full_name', '$email', '$password', '$role', $course, $year, $section)";
        if ($conn->query($sql)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed! " . $conn->error;
        }
    }
}
?>

<!-- HTML Form -->
<form method="POST">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="text" name="full_name" required placeholder="Full Name"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>

    <label for="role">Role:</label>
    <select name="role" id="role" onchange="toggleStudentFields()" required>
        <option value="student">Student</option>
        <option value="admin">Admin</option>
    </select><br>

    <div id="studentFields">
        <label for="course">Course:</label>
        <select name="course" id="course">
            <option value="BSIT">BSIT</option>
        </select><br>

        <label for="year">Year:</label>
        <select name="year" id="year">
            <option value="1st year">1st year</option>
            <option value="2nd year">2nd year</option>
            <option value="3rd year">3rd year</option>
            <option value="4th year">4th year</option>
        </select><br>

        <label for="section">Section:</label>
        <select name="section" id="section">
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
        </select><br>
    </div>

    <button type="submit">Register</button>
</form>

<script>
function toggleStudentFields() {
    const role = document.getElementById('role').value;
    document.getElementById('studentFields').style.display = (role === 'student') ? 'block' : 'none';
}
// Initialize the form correctly
toggleStudentFields();
</script>
