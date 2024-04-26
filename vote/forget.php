<?php

ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "election";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if all required fields are filled
    if (isset($_POST["username"], $_POST["new_password"], $_POST["confirm_password"], $_POST["submit"])) {
        $username = $_POST["username"];
        $newPassword = $_POST["new_password"];
        $confirmPassword = $_POST["confirm_password"];

        
        if ($newPassword !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.');</script>";
            exit();
        }

        
        $checkQuery = "SELECT * FROM voter WHERE username = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "<script>alert('Username not found.');</script>";
            exit();
        }
        $stmt->close();

        
        $updateQuery = "UPDATE voter SET password = ? WHERE username = ?";
        $plainTextPassword = $newPassword;

        
        $stmt = $conn->prepare($updateQuery);
        if (!$stmt) {
            echo "<script>alert('Error in preparing update query: " . $conn->error . "');</script>";
             echo "<script>window.history.go(-1);</script>";
            exit();
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("ss", $plainTextPassword, $username);
        if ($stmt->execute()) {
            echo "<script>alert('Password reset successful for Username: $username');</script>";
             echo "<script>window.history.go(-1);</script>";
            
        } else {
            echo "<script>alert('Error updating password: " . $stmt->error . "');</script>";
             echo "<script>window.history.go(-1);</script>";

        }
      
        $stmt->close();
    } else {
        echo "<script>alert('Error: Please fill in all the required fields.');</script>";
         echo "<script>window.history.go(-1);</script>";
    }
}


$conn->close();
?>
