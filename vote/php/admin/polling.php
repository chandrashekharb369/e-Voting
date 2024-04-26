<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("location: admin-login.php");
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


if (isset($_POST["district"])) {
    $district = $_POST["district"];

    // Fetch users from the database based on the district
    $query = "SELECT photo, username, name, dob FROM voter WHERE district = '$district'";
    $result = $conn->query($query);
    $totalVoters = $result->num_rows; 
}


$districtQuery = "SELECT DISTINCT district FROM voter";
$districtResult = $conn->query($districtQuery);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <style type="text/css">
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
    min-height: 100%; /* Ensure the content wrapper expands to fit the content */
    position: absolute; /* Change position to absolute */
    background: #fff;
    left: 250px;
    padding: 20px;
    overflow-y: auto; /* Add vertical scrollbar when content exceeds height */
    top: 0; /* Align content to the top */
    bottom: 0; /* Ensure content wrapper stretches to the bottom */
}

nav.vertical-menu-wrapper {
    position: fixed; /* Keep the navigation bar fixed */
    width: 250px;
    top: 0;
    bottom: 0; /* Stretch navigation bar to the bottom */
    background: #04045e;
    color: #fff;
    opacity: 0.8;
    z-index: 100; /* Ensure navigation bar stays above content */
    overflow-y: auto; /* Add vertical scrollbar when content exceeds height */
}

.closed-menu .content-wrapper {
    width: 100%;
    left: 0;
}

.closed-menu .vertical-menu-wrapper {
    width: 50px; /* Shrink navigation bar width when closed */
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

         .form-container {
            margin-top: 10px;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            text-align: center;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="date"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container textarea {
            height: 80px;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-container p {
            margin-top: 10px;
        }

        .form-container p.error {
            color: red;
        }

        .form-container p.success {
            color: green;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .chart-container {
            width: 400px;
            height: 400px;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .bar {
            width: 20px;
            background-color: blue;
            margin-bottom: 6px;
        }

        
        .bar {
            position: relative;
        }

        .vote-count {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-weight: bold;
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
            <li><a href="admin-profile.php"><i class="fas fa-home"></i>Home</a></li>
            <li>
                <a href="#"><i class="fas fa-user"></i>Voter</a>
                <div class="dropdown-content">
                    <a href="add.php">Add Voter</a>
                    <a href="remove.php">Remove Voter</a>
                    <a href="voter_list.php">Voter List</a>
                </div>
            </li>
            <li>
                <a href="#"><i class="fas fa-user-tie"></i> Candidate</a>
                <div class="dropdown-content">
                    <a href="add_candidate.php">Add Candidate</a>
                    <a href="candidate_list.php">Candidate list</a>
                </div>
            </li>
            <li><a href="polling.php"><i class="fas fa-poll"></i> Polling</a></li>
            <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
    <?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("location: admin-login.php");
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

$partyNames = array();
$votes = array();


if (isset($_POST["district"])) {
    $district = $_POST["district"];

    $query = "SELECT party, COUNT(username) AS total_votes FROM polling WHERE district = '$district' GROUP BY party";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $partyNames[] = $row['party'];
        $votes[] = intval($row['total_votes']);
    }
}

$districtQuery = "SELECT DISTINCT district FROM polling";
$districtResult = $conn->query($districtQuery);
?>

    <div class="content-wrapper">
         <form method="post">
        <label for="district">Select District:</label>
        <select id="district" name="district">
            <?php
            if ($districtResult->num_rows > 0) {
                while ($row = $districtResult->fetch_assoc()) {
                    echo "<option value='" . $row["district"] . "'>" . $row["district"] . "</option>";
                }
            }
            ?>
        </select>
        <input type="submit" name="submit" value="Select">
    </form>

    <?php if (isset($_POST["district"])) : ?>
        <div class="container">
            <div class="chart-container">
        <?php
        for ($i = 0; $i < count($partyNames); $i++) {
            $barHeight = $votes[$i] * 2; // Adjust the scale as needed
            $barColor = generateRandomColor(); // Generate a random color for each party

            echo '<div class="bar" style="height: ' . $barHeight . 'px; background-color: ' . $barColor . ';">';
            echo '<span class="vote-count">' . $votes[$i] . '</span>'; // Display vote count
            echo '<div>'.$partyNames[$i].'</div>';
            echo '</div>';
        }
        ?>
    </div>
        </div>

    <?php endif; ?>

     <script>
        function generateRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>


<?php
$conn->close();


function generateRandomColor()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
?>

    </div>  
</body>
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
</html>
