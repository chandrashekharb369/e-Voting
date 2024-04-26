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
     ?><!DOCTYPE html>
<html>
<head>
    <title>Voter Profile</title>
    <link rel="stylesheet" type="text/css" href="../../css/voter_nav.css">
    <link rel="stylesheet" type="text/css" href="../../css/profile.css">
    <link rel="stylesheet" type="text/css" href="../../navi.css">
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
   </style>
</head>
<body>
     <nav class="vertical-menu-wrapper">
        <div class="vertical-menu-logo">
         
            <div>e-Voting System</div>
            <span class="open-menu-btn"><hr><hr><hr></span>
        </div>
        <ul class="vertical-menu">
            <li><a href="voter-profile.php"><i class="fas fa-home"></i>Home</a></li>
            
            <div class="photo">
            
    
    <div class="content-wrapper">
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

// Fetch the voter's profile from the database using prepared statements
$stmt = $conn->prepare("SELECT *, YEAR(CURRENT_DATE) - YEAR(dob) - (RIGHT(CURRENT_DATE, 5) < RIGHT(dob, 5)) AS age FROM voter WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $voter = $result->fetch_assoc();
    ?>
    <div class='profile-container'>
        <div class='profile-header'>
            <div class="photo">
                <img src="data:image/png;base64,<?= base64_encode($voter['photo']) ?>" alt="Voter Photo" class="profile-photo" height="100px" width="100px">
            </div>
            <div class='profile-info'>
                <h1><?= $voter["name"] ?></h1>
                <div class='field'><label style="color: black;">Voter ID:</label><span><?= $voter["username"] ?></span></div>
                <div class='field'><label style="color: black;">DOB:</label><span><?= $voter["dob"] ?></span></div>
                <div class='field'><label style="color: black;">Age:</label><span><?= $voter["age"] ?></span></div>
                <div class='field'><label style="color: black;">Address:</label><span><?= $voter["address"] ?></span></div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo "<div class='profile-container'>";
    echo "<h1>Voter Profile</h1>";
    echo "Profile not found.";
    echo "</div>"; // Close profile-container
}

// Close the database connection...
$stmt->close();
$conn->close();
?>


    <script type="text/javascript">
    // Wait for the DOM to be fully loaded
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
<?php
$district = $voter["district"];
$_SESSION['ballot'] = $district;
?>
    <script type="text/javascript">
    // Wait for the DOM to be fully loaded
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
<?php
$district = $voter["district"];
$_SESSION['ballot'] = $district;
?>

</html>
