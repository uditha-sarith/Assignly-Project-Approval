<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'Student') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectmanagementdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentId = $_SESSION['userId'];
$projects = [];

$sql = "SELECT p.*, m.Status, u.Name AS SupervisorName 
        FROM Projects p 
        LEFT JOIN Matches m ON p.Id = m.ProjectId 
        LEFT JOIN Users u ON m.SupervisorId = u.Id 
        WHERE p.StudentId = '$studentId'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Projects</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container" style="width: 80%;">
        <h2>My Projects</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Abstract</th>
                    <th>Tech Stack</th>
                    <th>Research Area</th>
                    <th>Document</th>
                    <th>Match Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($projects) > 0): ?>
                    <?php foreach($projects as $project): ?>
                        <tr>
                            <td><?php echo $project['Title']; ?></td>
                            <td><?php echo $project['Abstract']; ?></td>
                            <td><?php echo $project['TechStack']; ?></td>
                            <td><?php echo $project['ResearchArea']; ?></td>
                            <td>
                                <?php if(!empty($project['FilePath'])): ?>
                                    <a href="<?php echo $project['FilePath']; ?>" target="_blank" style="color: blue; text-decoration: underline;">View File</a>
                                <?php else: ?>
                                    No File
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($project['SupervisorName'])): ?>
                                    <span style="color: #28a745; font-weight: bold;">Matched with: <?php echo $project['SupervisorName']; ?></span>
                                <?php else: ?>
                                    <span style="color: #ffc107; font-weight: bold;">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No projects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <button type="button" class="btn-danger" onclick="window.location.href='student_dashboard.html'">Back to Dashboard</button>
    </div>
</body>
</html>
<?php
$conn->close();
?>