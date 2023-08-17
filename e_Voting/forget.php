<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["username"]) && isset($_POST["npassword"]) && isset($_POST["rpassword"])) {
        // Get the form data
        $username = $_POST["username"];
        $newPassword = $_POST["npassword"];
        $reenteredPassword = $_POST["rpassword"];

   s
        if ($newPassword !== $reenteredPassword) {
            echo "<script>alert('Passwords do not match.');</script>";
            exit();
        }

      
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "election";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

     
        $checkQuery = "SELECT * FROM voter WHERE username = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result(); // Store the result to get the number of rows
        if ($stmt->num_rows === 0) {
            echo "<script>alert('Username not found.');</script>";
            $stmt->close();
            $conn->close();
            exit();
        }
        $stmt->close();

      
        $updateQuery = "UPDATE voter SET password = ? WHERE username = ?";
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the password
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $hashedPassword, $username);

        if ($stmt->execute()) {
            echo "<script>alert('Password reset successful for Username: $username');</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.');</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Error: Please fill in all the required fields.');</script>";
    }
}
?>
