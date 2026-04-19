<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectmanagementdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE Email='$email' AND Password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['userId'] = $row['Id'];
        $_SESSION['role'] = $row['Role'];
        $_SESSION['name'] = $row['Name'];

        if ($row['Role'] == 'Student') {
            header("Location: student_dashboard.html");
        } else if ($row['Role'] == 'Supervisor') {
            header("Location: supervisor_dashboard.php");
        } else if ($row['Role'] == 'Admin') {
            header("Location: admin_dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid Username/Email or Password!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Assignly</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">Assign<span>ly</span></div>
    </div>
    
    <div class="container login-container">
        <h2>Welcome Back</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email or Username</label>
                <input type="text" name="email" placeholder="Enter your email or username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign In</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='register.html'">Create an Account</button>
        </form>
    </div>
</body>
</html>