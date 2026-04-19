<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectmanagementdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $role = $_POST['role'];

    $checkSql = "SELECT * FROM Users WHERE Email='$email' OR Id='$userId'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo "<script>
                alert('Registration Failed! User ID or Email already exists.');
                window.location.href='register.html';
              </script>";
    } else {
        $sql = "INSERT INTO Users (Id, Name, Email, Password, Role) VALUES ('$userId', '$name', '$email', '$pass', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Registration Successful!');
                    window.location.href='index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Registration Failed! Please try again.');
                    window.location.href='register.html';
                  </script>";
        }
    }
}

$conn->close();
?>