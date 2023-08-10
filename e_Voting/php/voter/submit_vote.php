<?php
session_start();

if (!isset($_SESSION["voter"])) {
    header("location: voter_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    $username = $_SESSION["voter"];
    $vote = $_POST["vote"]; // Get the selected candidate's party from the form data

    // Check if the voter has already cast their vote
    $checkVoteQuery = "SELECT * FROM polling WHERE username = '$username'";
    $checkVoteResult = $conn->query($checkVoteQuery);
    if ($checkVoteResult->num_rows > 0) {
        // Voter has already cast their vote
        header("Location: already_voted.php");
        exit();
    }

    // Fetch the voter's district from the database
    $voterQuery = "SELECT district,username FROM voter WHERE username = '$username'";
    $voterResult = $conn->query($voterQuery);
    if ($voterResult->num_rows > 0) {
        $voter = $voterResult->fetch_assoc();
        $district = $voter["district"];
        $voterid = $voter["username"];

        // Insert the vote into the polling table
        $insertQuery = "INSERT INTO polling (username, district, party) VALUES ('$username', '$district', '$vote')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: exit.php");
            exit();
        } else {
            echo "Error submitting vote: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>
