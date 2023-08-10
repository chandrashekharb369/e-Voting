<!DOCTYPE html>
<html>
<head>
    <title>Voter Profile</title>
   <link rel="stylesheet" type="text/css" href="../../css/voter_nav.css">
</head>
<body>
    <div class="navbar">
        <a href="voter-profile.php">Home</a>
        <a href="candidate.php">Candidates</a>
        <a href="#">About</a>
    </div>
    <div class="profile-container">
        <?php
        session_start();

        if (!isset($_SESSION["voter"])) {
            header("location: voter_login.php");
            exit();
        }

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

        // Fetch the voter's profile from the database
        $voterQuery = "SELECT * FROM voter WHERE username = '$username'";
        $voterResult = $conn->query($voterQuery);
        if ($voterResult->num_rows > 0) {
            // Voter profile found, display the information
            $voter = $voterResult->fetch_assoc();
            echo "<h1>Candidates</h1>";

            // Get the voter's district
            $district = $voter["district"];

            // Check if the voter's district has candidates
            $candidateQuery = "SELECT * FROM candidate WHERE district = '$district'";
            $candidateResult = $conn->query($candidateQuery);

            if ($candidateResult->num_rows > 0) {
    echo "<p>Total Candidates: " . $candidateResult->num_rows . "</p>"; // Display the total number of candidates
    echo "<table>";
    echo "<tr><th>Sl. No.</th><th>Photo</th><th>Name</th><th>Age</th><th>Address</th><th>Qulification</th><th>About</th><th>Party</th></tr>";
    $serialNumber = 1;
    while ($candidate = $candidateResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $serialNumber . "</td>";
        echo '<td>' . '<img src="data:image/png;base64,' . base64_encode($candidate['photo']) . '" width="50px" height="50px"/>' . '</td>';
        echo "<td>" . $candidate["firstname"] . " " . $candidate["lastname"] . "</td>";
       

        // Calculate the candidate's age using the 'date_of_birth' field
        $dob = new DateTime($candidate["dob"]);
        $currentDate = new DateTime();
        $age = $currentDate->diff($dob)->y;

        echo "<td>" . $age . "</td>";
        echo "<td>" . $candidate["address"] . "</td>";
        echo "<td>" . $candidate["qualification"] . "</td>";
        echo "<td>" . $candidate["about"] . "</td>";
        echo "<td>" . $candidate["party"] . "</td>";

        
        
        
        echo "</tr>";
        $serialNumber++;
    }
    echo "</table>";
} else {
    echo "<p>No candidates found for the selected district.</p>";
}
    }
        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
