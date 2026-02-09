<?php
require '../config/database.php';
session_start();

if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Escape the email and add quotes
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM users WHERE email='$email'"; // notice the quotes
    $result = $conn->query($sql);

    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        // Redirect by role
        header("Location: " . ($user['role'] === 'admin' ? "../admin/dashboard.php" : "../user/dashboard.php"));
        exit;
    } else {
        echo "Invalid email or password!";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
