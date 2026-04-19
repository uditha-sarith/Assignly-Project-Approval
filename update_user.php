<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "projectmanagementdb";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$editId = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "UPDATE Users SET Name='$name', Email='$email', Password='$password', Role='$role' WHERE Id='$editId'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User Details Updated Successfully!'); window.location.href='manage_users.php';</script>";
    }
}

$userData = null;
$result = $conn->query("SELECT * FROM Users WHERE Id='$editId'");
if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Update User Details</h2>
        <form method="POST" action="">
            <input type="text" name="name" value="<?php echo $userData['Name']; ?>" required>
            <input type="text" name="email" value="<?php echo $userData['Email']; ?>" required>
            <input type="text" name="password" value="<?php echo $userData['Password']; ?>" required>
            <select name="role" required>
                <option value="Student" <?php if($userData['Role'] == 'Student') echo 'selected'; ?>>Student</option>
                <option value="Supervisor" <?php if($userData['Role'] == 'Supervisor') echo 'selected'; ?>>Supervisor</option>
            </select>
            <button type="submit">Update</button>
            <button type="button" class="btn-danger" onclick="window.location.href='manage_users.php'">Cancel</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>