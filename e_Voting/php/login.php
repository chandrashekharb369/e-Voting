<?php
/* Assuming you have a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "election";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for any connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the user is an admin
    $adminQuery = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $adminResult = $conn->query($adminQuery);

    if ($adminResult->num_rows > 0) {
        // Admin login successful
        header('location: ../html/admin.html'); 
        // Redirect to admin page or perform any other action for admin
    } else {
        // Check if the user is a voter
        $voterQuery = "SELECT * FROM voter WHERE username = '$username' AND password = '$password'";
        $voterResult = $conn->query($voterQuery);

        if ($voterResult->num_rows > 0) {
            // Voter login successful
            header('location: ../html/voter.html'); 
            // Redirect to voter page or perform any other action for voter
        } else {
            // Invalid credentials
            echo "<script>alert('Invalid username or password. Please try again.');</script>";
        }
    }
}*/
session_start();

// Assuming you have a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "election";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for any connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the user is an admin
    $adminQuery = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $adminResult = $conn->query($adminQuery);

    if ($adminResult->num_rows > 0) {
        // Admin login successful
        $_SESSION["admin"] = $username;
        header('location: admin/admin-profile.php');
        // Redirect to admin profile page or perform any other action for admin
    } else {
        // Check if the user is a voter
        $voterQuery = "SELECT * FROM voter WHERE username = '$username' AND password = '$password'";
        $voterResult = $conn->query($voterQuery);

        if ($voterResult->num_rows > 0) {
            // Voter login successful
            $_SESSION["voter"] = $username;
            header('location: voter/voter-profile.php');
            // Redirect to voter profile page or perform any other action for voter
        } else {
            // Invalid credentials
            echo "<script>alert('Invalid username or password. Please try again.');</script>";
        }
    }
}
?>
