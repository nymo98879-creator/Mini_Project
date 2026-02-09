<?php
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // "admin" or "user"

    $stmt = $conn->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)");
    if ($stmt->execute([$username, $email, $password, $role])) {
        echo "User registered successfully! <a href='login.php'>Login</a>";
    } else {
        echo "Error!";
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Role: <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br>
    <button type="submit">Register</button>
</form>