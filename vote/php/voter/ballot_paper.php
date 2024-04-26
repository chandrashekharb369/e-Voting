<!DOCTYPE html>
<html>
<head>
    <title>Voter Profile</title>
    <link rel="stylesheet" type="text/css" href="../../css/ballot.css">
    <style type="text/css">
         <style>
        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        /* Navbar styles */
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        
        .navbar a {
            float: left;
            color: #fff;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        
        /* Profile container styles */
        .profile-container {
            margin: 20px;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .profile-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        /* Ballot table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #333;
            color: #fff;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        /* Vote button styles */
        .vote-button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .vote-button:hover {
            background-color: #45a049;
        }
        
        /* Countdown timer styles */
        .countdown {
            font-size: 20px;
            margin-bottom: 20px;

        }
        /* Vote button styles */
button {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

    </style>
    </style>
    <script>
        // Function to update the countdown timer
        function updateCountdownTimer() {
            var countdownElement = document.getElementById("countdown");
            var timeLeft = parseInt(countdownElement.innerText);
            
            if (timeLeft > 0) {
                timeLeft--;
                countdownElement.innerText = timeLeft;
                setTimeout(updateCountdownTimer, 1000); // Update every 1 second (1000 milliseconds)
            } else {
                document.getElementById("vote-form").submit(); // Submit the form when the timer expires
            }
        }

        // Start the countdown timer when the page loads
        window.onload = function() {
            setTimeout(updateCountdownTimer, 1000); // Start the countdown timer after 1 second (1000 milliseconds)
        };
    </script>
</head>
<body>
    <div class="profile-container">
        <h2>Time Left: <span id="countdown">60</span> seconds</h2>
        <form method="POST" action="submit_vote.php" id="vote-form">
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
                    echo "<table>";
                    echo "<tr><th>Sl. No.</th><th>Name</th><th>Party</th><th>Symbole</th><th>Vote</th></tr>";
                    $serialNumber = 1;
                    while ($candidate = $candidateResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $serialNumber . "</td>";
                        echo "<td>" . $candidate["firstname"] . " " . $candidate["lastname"] . "</td>";
                        echo "<td>" . $candidate["party"] . "</td>";
                        echo '<td>' . '<img src="data:image/png;base64,' . base64_encode($candidate['symbole']) . '" width="50px" height="50px"/>' . '</td>';
                        echo '<td><input type="radio" name="vote" value="' . $candidate["party"] . '"></td>';
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
            <button name="cast" type="submit">Cast</button>
        </form>
    </div>
</body>
</html>
