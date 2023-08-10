<!DOCTYPE html>
 <html>
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
       <link rel="stylesheet" type="text/css" href="../../css/profile.css">
     <title></title>
 </head>
 
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
            echo "<h1>Voter Profile</h1>";

            // Display the logged-in voter's photo
            echo '<img src="data:image/png;base64,' . base64_encode($voter['photo']) . '" alt="Voter Photo" height="100px" width="100px">';

            echo "<div class='details'>";
            echo "<div class='field'><label>Voter ID:</label><span>" . $voter["username"] . "</span></div>";
            echo "<div class='field'><label>Name:</label><span>" . $voter["name"] . "</span></div>";
            echo "<div class='field'><label>DOB:</label><span>" . $voter["dob"] . "</span></div>";
            echo "<div class='field'><label>Address:</label><span>" . $voter["address"] . "</span></div>";
           

            
            // Add more fields as necessary
            echo "</div>";

        } else {
            // Voter profile not found
            echo "<h1>Voter Profile</h1>";
            echo "Profile not found.";
        }

        // Close the database connection
        $conn->close();
        ?>
        <body>
 
 </body>
 </html>