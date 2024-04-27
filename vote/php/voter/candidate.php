<!DOCTYPE html>
<html>
<head>
    <title>Voter Profile</title>
    <link rel="stylesheet" type="text/css" href="../../css/voter_nav.css">
    <script>
        // Countdown timer function
        function countdownTimer(announcementTime) {
            console.log("Announcement Time: ", announcementTime); // Debugging statement

            var countDownDate = new Date(announcementTime).getTime();

            console.log("Countdown Date: ", countDownDate); // Debugging statement

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                
                document.getElementById("countdown").innerHTML = "Remaing Time For Announcement for Candidate List   :"+ days + "d " + hours + "h "
                + minutes + "m " + seconds + "s ";

                
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("countdown").innerHTML = "<h1>Candidate List</h1>";
                    document.getElementById("candidateList").style.display = "block"; 
                }
            }, 1000);
        }

        
        window.onload = function() {
            <?php
            
            session_start();

            if (!isset($_SESSION["voter"])) {
                header("location: voter_login.php");
                exit();
            }

            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "election";

            
            $conn = new mysqli($servername, $username, $password, $dbname);

            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            
            $announcementQuery = "SELECT date_time FROM announced WHERE sl = (SELECT MAX(sl) FROM announced) LIMIT 1";
            $announcementResult = $conn->query($announcementQuery);

           
            if ($announcementResult->num_rows > 0) {
               
                $announcement = $announcementResult->fetch_assoc();
                $announcementTime = $announcement["date_time"];

                
                echo "var announcementTime = '" . $announcementTime . "';";
                echo "countdownTimer(announcementTime);"; 
            } else {
              
                echo "document.getElementById('countdown').innerHTML = 'ANNOUNCEMENT TIME NOT SET';";
            }

            
            $conn->close();
            ?>
        };
    </script>
</head>
<body>
    <div class="navbar">
        <a href="voter-profile.php">Home</a>
        <a href="candidate.php">Candidates</a>
        <a href="#">About</a>
    </div>
    <div class="profile-container">
        <div id="countdown"></div> <!-- Display the countdown timer here -->
        <div id="candidateList" style="display: none;">
           
                     <?php
            
            session_start();

           
            if (!isset($_SESSION["voter"])) {
                header("location: voter_login.php");
                exit();
            }

            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "election";

           
            $conn = new mysqli($servername, $username, $password, $dbname);

            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            
            $username = $_SESSION["voter"];

            
            $voterQuery = "SELECT * FROM voter WHERE username = '$username'";
            $voterResult = $conn->query($voterQuery);

          
            if ($voterResult->num_rows > 0) {
               
                $voter = $voterResult->fetch_assoc();

                

               
                $district = $voter["district"];

               
                $candidateQuery = "SELECT * FROM candidate WHERE district = '$district'";
                $candidateResult = $conn->query($candidateQuery);

              
                if ($candidateResult->num_rows > 0) {
                    
                    echo "<p>Total Candidates: " . $candidateResult->num_rows . "</p>";

                    
                    echo "<table>";
                    echo "<tr><th>Sl. No.</th><th>Photo</th><th>Name</th><th>Age</th><th>Address</th><th>Qualification</th><th>About</th><th>Party</th></tr>";
                    $serialNumber = 1;
                    while ($candidate = $candidateResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $serialNumber . "</td>";
                        echo '<td>' . '<img src="data:image/png;base64,' . base64_encode($candidate['photo']) . '" width="50px" height="50px"/>' . '</td>';
                        echo "<td>" . $candidate["firstname"] . " " . $candidate["lastname"] . "</td>";

                       
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
            } else {
              
                header("location: voter_login.php");
                exit();
            }

           
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
