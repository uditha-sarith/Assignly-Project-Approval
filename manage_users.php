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

if (isset($_GET['delete'])) {
    $delId = $_GET['delete'];
    $conn->query("DELETE FROM Users WHERE Id='$delId'");
    header("Location: manage_users.php");
    exit();
}

$users = [];
$sql = "SELECT * FROM Users WHERE Role != 'Admin'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container" style="width: 80%;">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($users) > 0): ?>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?php echo $u['Id']; ?></td>
                        <td><?php echo $u['Name']; ?></td>
                        <td><?php echo $u['Email']; ?></td>
                        <td><?php echo $u['Role']; ?></td>
                        <td>
                            <button class="btn-secondary" style="width:auto; padding:5px 10px;" onclick="window.location.href='update_user.php?id=<?php echo $u['Id']; ?>'">Edit</button>
                            <button class="btn-danger" style="width:auto; padding:5px 10px;" onclick="if(confirm('Are you sure you want to delete this user?')) window.location.href='manage_users.php?delete=<?php echo $u['Id']; ?>'">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <button type="button" class="btn-danger" onclick="window.location.href='admin_dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
<?php $conn->close(); ?>