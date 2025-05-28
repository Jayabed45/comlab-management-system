<?php
session_start();
$conn = new mysqli("localhost", "root", "", "comlab_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["role"] = $user["role"];
            header("Location: " . ($user["role"] === "admin" ? "admin/dashboard.php" : "student/dashboard.php"));
            exit();
        }
    }
    $error = "Invalid email or password";
}
?>
<!-- HTML Form -->
<form method="POST">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Login</button>
</form>

<div class="register-link"><a href="register.php">Register</a></div>