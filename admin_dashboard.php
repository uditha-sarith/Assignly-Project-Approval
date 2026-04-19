<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assigly - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1 class="brand-title">Assigly</h1>
        <h2>Admin Dashboard</h2>
        <button class="btn-secondary" onclick="window.location.href='manage_users.php'">Manage Users</button>
        <button class="btn-danger" onclick="window.location.href='logout.php'">Logout</button>
    </div>
</body>
</html>