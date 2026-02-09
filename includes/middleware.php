<?php
session_start();

// Check if user is logged in
function auth()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /auth/login.php");
        exit();
    }
}   

// Check role
function isAdmin()
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: /client/dashboard.php");
        exit();
    }
}

function isUser()
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
        header("Location: /admin/dashboard.php");
        exit();
    }
    
}
?>