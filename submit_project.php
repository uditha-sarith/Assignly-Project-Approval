<?php
session_start();

if (!isset($_SESSION['userId'])) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_SESSION['userId'];
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $techStack = $_POST['techStack'];
    $researchArea = $_POST['researchArea'];

    $targetDir = "uploads/";
    
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES["projectDoc"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowedTypes = array('pdf', 'docx');

    if (in_array(strtolower($fileType), $allowedTypes)) {
        if (move_uploaded_file($_FILES["projectDoc"]["tmp_name"], $targetFilePath)) {
            $sql = "INSERT INTO Projects (Title, Abstract, TechStack, ResearchArea, StudentId, FilePath) 
                    VALUES ('$title', '$abstract', '$techStack', '$researchArea', '$studentId', '$targetFilePath')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                        alert('Project Submitted Successfully!');
                        window.location.href='student_dashboard.html';
                      </script>";
            } else {
                echo "<script>
                        alert('Database Error!');
                        window.location.href='submit_project.html';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('File Upload Failed!');
                    window.location.href='submit_project.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Only PDF and DOCX files are allowed!');
                window.location.href='submit_project.html';
              </script>";
    }
}

$conn->close();
?>