<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$voterQuery = "SELECT * FROM voter WHERE username = '$username'";
$voterResult = $conn->query($voterQuery);

$electionDateQuery = "SELECT date1 FROM dateele ORDER BY sl DESC LIMIT 1";
$electionDateResult = $conn->query($electionDateQuery);
$electionDateRow = $electionDateResult->fetch_assoc();
$electionDate = $electionDateRow['date1'];

$electionEndTimeQuery = "SELECT endtime FROM dateele ORDER BY sl DESC LIMIT 1";
$electionEndTimeResult = $conn->query($electionEndTimeQuery);
$electionEndTimeRow = $electionEndTimeResult->fetch_assoc();
$electionEndTime = $electionEndTimeRow['endtime'];

$today = new DateTime();
$electionDateTime = new DateTime($electionDate);
$interval = $today->diff($electionDateTime);
$daysRemaining = $interval->format('%a');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voter Profile</title>
    <link rel="stylesheet" type="text/css" href="../../css/voter_nav.css">
    <link rel="stylesheet" type="text/css" href="../../css/profile.css">
    <style>
        
        body {
            margin: 0;
            font-family: Arial;
        }

        nav {
            background: #04045e;
            position: fixed;
            width: 100%;
            max-width: 250px;
            bottom: 0;
            top: 0;
            display: block;
            min-height: 250px;
            height: 100%;
            color: #fff;
            opacity: 0.8;
            transition: all 300ms;
            -moz-transition: all 300ms;
            -webkit-transition: all 300ms;
        }

        nav .vertical-menu hr {
            opacity: 0.1;
            border-width: 0.5px;
        }

        nav ul {
            width: 90%;
            padding-inline-start: 0;
            margin: 10px;
            height: calc(100% - 20px);
        }

        nav .vertical-menu-logo {
            padding: 20px;
            font-size: 1.3em;
            position: relative;
        }

        nav .vertical-menu-logo .open-menu-btn {
            width: 30px;
            height: max-content;
            position: absolute;
            display: block;
            right: 20px;
            top: 0;
            bottom: 0;
            margin: auto;
            cursor: pointer;
        }

        nav .vertical-menu-logo .open-menu-btn hr {
            margin: 5px 0;
        }
        .photo{
            align-items: center;
            text-align: center;
        }
        ul {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }

        ul li a i {
            margin-right: 10px;
        }

        ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        ul li .dropdown-content {
            display: none;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-left: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        ul li:hover .dropdown-content {
            display: block;
        }

        ul li .dropdown-content a {
            color: #000;
            text-decoration: none;
            display: block;
            padding: 5px 0;
            transition: color 0.3s;
        }

        ul li .dropdown-content a:hover {
            color: #4CAF50;
        }

        .content-wrapper {
            width: calc(100% - 250px);
            height: 100%;
            position: fixed;
            background: #fff;
            left: 250px;
            padding: 20px;
        }

        .closed-menu .content-wrapper {
            width: 100%;
            left: 50px;
        }

        .content-wrapper {
            transition: all 300ms;
        }

        .vertical-menu-wrapper .vertical-menu-logo div {
            transition: all 100ms;
        }

        .closed-menu .vertical-menu-wrapper .vertical-menu-logo div {
            margin-left: -300px;
        }

        .vertical-menu-wrapper .vertical-menu-logo .open-menu-btn {
            transition: all 300ms;
        }

        .closed-menu .vertical-menu-wrapper .vertical-menu-logo .open-menu-btn {
            left: 10px;
            right: 100%;
        }

        .closed-menu .vertical-menu-wrapper ul,
        .closed-menu .vertical-menu-wrapper hr {
            margin-left: -300px;
        }

        .vertical-menu-wrapper ul,
        .vertical-menu-wrapper hr {
            transition: all 100ms;
        }

        .content-wrapper {
            background: #ebebeb;
        }

        .content {
            width: 90%;
            min-height: 90%;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        #countdown.active {
            color: green;
        }
    </style>
</head>
<body>
<nav class="vertical-menu-wrapper">
    <div class="vertical-menu-logo">
        <div>e-Voting System</div>
        <span class="open-menu-btn"><hr><hr><hr></span>
    </div>
    <ul class="vertical-menu">
        <div class="photo">
            <?php
            if ($voterResult->num_rows > 0) {
                $voter = $voterResult->fetch_assoc();
                echo '<img src="data:image/png;base64,' . base64_encode($voter['photo']) . '" alt="Voter Photo" height="80px" width="80px" style="border-radius:50%">';
                echo "</div>";
            } else {
                echo "<h1>Voter Profile</h1>";
                echo "Profile not found.";
            }
            $conn->close();
            ?>
        </div>
        <li><a href="Profile.php"><i class="fas fa-home" style="text-align: center;"></i>Profile</a></li>
        <li><a href="#"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="candidate.php"><i class="fas fa-home"></i>Candidate</a></li>
        <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
<div class="content-wrapper">
    <h1>Karnataka District Level Election</h1>
    <div class="profile-container">
        <?php
        echo "<div class='field'><label>Hello ! Mr/Mis</label><span>" . $voter["name"] . "</span>   This is District Level Online Voting System(e-Voting System) also it is 3<sup>rd</sup> Generation Voting System</div>";
        ?>
        <div class="field">
    <label>Election Date:</label>
    <span><?php echo $electionDate; ?></span>
</div>
<div class="field">
    <label>Countdown For:</label>
    <span id="countdown"></span>
</div>

<script>
    var electionDate = new Date("<?php echo $electionDate; ?>");
    var electionEndTime = new Date("<?php echo $electionEndTime; ?>");
    var countdownSpan = document.getElementById("countdown");

    if (!countdownSpan) {
        console.error("Countdown span element not found.");
    } else {
        var castVoteButton = document.createElement("a");
        castVoteButton.setAttribute("href", "ballot_paper.php");
        castVoteButton.classList.add("button");
        castVoteButton.textContent = "Cast your vote";
        castVoteButton.style.display = "none";
        countdownSpan.parentNode.appendChild(castVoteButton);

        var now = new Date().getTime();

        if (now >= electionDate.getTime() && now <= electionEndTime.getTime()) {
            startCountdown(electionEndTime);
        } else if (now > electionEndTime.getTime()) {
            countdownSpan.innerHTML = "<span class='ended-message'>Election ended!</span>";
            castVoteButton.style.display = "none";
        } else {
            var startCountdownInterval = setInterval(function() {
                var now = new Date().getTime();
                if (now >= electionDate.getTime()) {
                    clearInterval(startCountdownInterval);
                    startCountdown(electionEndTime);
                    castVoteButton.style.display = "inline-block";
                } else {
                    var distance = electionDate.getTime() - now;
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    countdownSpan.innerHTML = "Election starts in: " + days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                }
            }, 1000);
        }

        function startCountdown(endTime) {
            var countdownInterval = setInterval(function() {
                var now = new Date().getTime();
                var distance = endTime.getTime() - now;
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownSpan.innerHTML = "Election ended!";
                    castVoteButton.style.display = "none";
                } else {
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    countdownSpan.innerHTML = "Time remaining: " + days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                }
            }, 1000);
        }
    }
</script>


        </div>
        <style>
            .button {
                display: inline-block;
                margin-top: 5%;
                padding: 10px 20px;
                font-size: 18px;
                font-weight: bold;
                text-decoration: none;
                background-color: #333;
                color: #fff;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .button:hover {
                background-color: #888;
                color: #fff;
                transform: scale(1.1);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }
        </style>
        
    </div>
</div>
<script type="text/javascript">
   
    document.addEventListener("DOMContentLoaded", function(event) {
        var openMenuBtn = document.querySelector('.open-menu-btn');
        var body = document.body;

        openMenuBtn.addEventListener('click', function() {
            if (body.classList.contains('closed-menu')) {
                body.classList.remove('closed-menu');
            } else {
                body.classList.add('closed-menu');
            }
        });
    });
</script>
</body>
</html>