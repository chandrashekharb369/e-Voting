<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["username"]) && isset($_POST["npassword"]) && isset($_POST["rpassword"])) {
        // Get the form data
        $username = $_POST["username"];
        $newPassword = $_POST["npassword"];
        $reenteredPassword = $_POST["rpassword"];

        // Validate the passwords
        if ($newPassword !== $reenteredPassword) {
            echo "<h2>Passwords do not match.</h2>";
            exit();
        }

        // Connect to the database (replace with your database connection code)
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "election";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the username exists in the database
        $checkQuery = "SELECT * FROM voter WHERE username = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
           echo "<script>prompt('Passwords do not match.');</script>";
        
            $conn->close();
            exit();
        }

        // Update the password in the database
        $updateQuery = "UPDATE voter SET password = ? WHERE username = ?";
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the password
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $hashedPassword, $username);
        
        if ($stmt->execute()) {
            echo "<script>prompt('Password reset successful for Username: $username');</script>";
        } else {
            echo "<script>prompt('Error: Please fill in all the required fields.');</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<h2>Error: Please fill in all the required fields.</h2>";
    }
}
?>
