<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'Supervisor') {
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

$supervisorId = $_SESSION['userId'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['projectId'])) {
    $projectId = $_POST['projectId'];

    $checkSupSql = "SELECT * FROM Matches WHERE SupervisorId = '$supervisorId'";
    $supResult = $conn->query($checkSupSql);

    if ($supResult->num_rows == 0) {
        $checkSql = "SELECT * FROM Matches WHERE ProjectId = '$projectId'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows == 0) {
            $insertSql = "INSERT INTO Matches (ProjectId, SupervisorId, Status) VALUES ('$projectId', '$supervisorId', 'Matched')";
            if ($conn->query($insertSql) === TRUE) {
                echo "<script>alert('Project Matched Successfully!'); window.location.href='browse_projects.php';</script>";
            }
        }
    }
}

$hasSelected = false;
$checkSupMatch = "SELECT * FROM Matches WHERE SupervisorId = '$supervisorId'";
$matchResult = $conn->query($checkSupMatch);
if ($matchResult && $matchResult->num_rows > 0) {
    $hasSelected = true;
}

$projects = [];
$sql = "SELECT p.*, m.SupervisorId, m.Status, u.Name AS StudentName, u.Email AS StudentEmail 
        FROM Projects p 
        LEFT JOIN Matches m ON p.Id = m.ProjectId 
        LEFT JOIN Users u ON p.StudentId = u.Id";
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
    <title>Browse Projects</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container" style="width: 80%;">
        <h2>Available Projects</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Abstract</th>
                    <th>Tech Stack</th>
                    <th>Research Area</th>
                    <th>Document</th>
                    <th>Student Details</th>
                    <th>Action</th>
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
                                <?php if ($project['SupervisorId'] == $_SESSION['userId']): ?>
                                    <?php echo $project['StudentName']; ?> <br>
                                    <small><?php echo $project['StudentEmail']; ?></small>
                                <?php else: ?>
                                    <span style="color: gray;">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (empty($project['SupervisorId'])): ?>
                                    <?php if (!$hasSelected): ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="projectId" value="<?php echo $project['Id']; ?>">
                                            <button type="submit">Select Project</button>
                                        </form>
                                    <?php endif; ?>
                                <?php elseif ($project['SupervisorId'] == $_SESSION['userId']): ?>
                                    <button disabled style="background-color: #28a745; color: white;">Selected by You</button>
                                <?php else: ?>
                                    <button disabled style="background-color: #6c757d; color: white;">Taken</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No projects available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <button type="button" class="btn-danger" onclick="window.location.href='supervisor_dashboard.html'">Back to Dashboard</button>
    </div>
</body>
</html>
<?php
$conn->close();
?>