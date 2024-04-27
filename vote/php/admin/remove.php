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

if (isset($_POST["submit"])) {
    $district = $_POST["district"];

 
    $query = "SELECT photo, username, name, dob FROM voter WHERE district = '$district'";
    $result = $conn->query($query);
    $totalVoters = $result->num_rows; // Get the total number of voters
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
    <link rel="stylesheet" type="text/css" href="../../css/navia.css">
    <style type="text/css">
           
         label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        select[name="reason"]  {
            width: 40%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            text-align: center;
            display: block;
            width: 20%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        p {
            margin-top: 10px;
        }

        p.success {
            color: green;
        }

        p.error {
            color: red;
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
                    <a href="removecnadi.php">Remove Candidate</a>
                </div>
            </li>
            <li><a href="polling.php"><i class="fas fa-poll"></i> Polling</a></li>
            <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
    <div class="content-wrapper">
        <div class="remove">
  <form method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="reason">Reason:</label>
    <select name="reason" id="reason" onchange="showReasonTextarea()">
        <option value="missing">--select--</option>
        <option value="missing">Missing</option>
        <option value="change_address">Change of Address</option>
        <option value="dead">Dead</option>
        <option value="other">Other</option>
    </select>
    
    <div id="reasonTextareaContainer" style="display: none;">
        <label for="reasonTextarea">Specify Reason:</label>
        <textarea id="reasonTextarea" name="reasonTextarea"></textarea>
    </div>
    
    <div style="text-align: center;">
        <input type="submit" name="submit" value="Remove Voter">
    </div>

</form>
<script type="text/javascript">
    function showReasonTextarea() {
        var reasonSelect = document.getElementById("reason");
        var reasonTextareaContainer = document.getElementById("reasonTextareaContainer");

        if (reasonSelect.value === "other") {
            reasonTextareaContainer.style.display = "block";
        } else {
            reasonTextareaContainer.style.display = "none";
        }
    }
</script>

     <?php

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
    $reason = $_POST["reason"];

    // Check if the voter exists in the database
    $checkQuery = "SELECT * FROM voter WHERE username = '$username'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Voter found, retrieve all details
        $voterQuery = "SELECT * FROM voter WHERE username = '$username'";
        $voterResult = $conn->query($voterQuery);

        if ($voterResult->num_rows > 0) {
            $voterData = $voterResult->fetch_assoc();

            $deleteTime = date('Y-m-d H:i:s');

            
            $insertQuery = "INSERT INTO delete_voter (username, name, dob, district, address, reason, delete_time) 
                            VALUES ('$username', '{$voterData['name']}', '{$voterData['dob']}', '{$voterData['district']}', '{$voterData['address']}', '$reason', '$deleteTime')";

            if ($conn->query($insertQuery) === TRUE) {
                // Delete the voter from the voter table
                $deleteQuery = "DELETE FROM voter WHERE username = '$username'";
                if ($conn->query($deleteQuery) === TRUE) {
                     echo "<script>alert('voter removed successfully. Reason: $reason.');</script>";
                  
                } else {
                    echo "<p style='color: red;'>Error removing voter. Please try again.</p>";
                }
            } else {
                echo "<p style='color: red;'>Error inserting voter into delete_voter table. Please try again.</p>";
            }
        } else {
            echo "<p style='color: red;'>Error retrieving voter details. Please try again.</p>";
        }
    } else {
        echo "<script>alert('Voter not found.');</script>";
        
    }
}



$conn->close();
?>
</div>
<div class="list">
    <?php


if (!isset($_SESSION["admin"])) {
    header("location: admin-login.php");
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


// Check if a district is selected
if (isset($_POST["submit01"])) {
    $district = $_POST["district"];

    // Fetch users from the database based on the district
    $query = "SELECT * FROM delete_voter WHERE district = '$district'";
    $result = $conn->query($query);
    $totalVoters = $result->num_rows; // Get the total number of voters
}

// Fetch all unique districts from the voter table
$districtQuery = "SELECT DISTINCT district FROM delete_voter";
$districtResult = $conn->query($districtQuery);

$conn->close();
?>
<style type="text/css">
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
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: #fff;
        }
    </style>

        <h1>User List</h1>
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
            <input type="submit" name="submit01" value="Select">
        
        </form>

        <?php
        if (isset($_POST["district"])) {
            if ($result->num_rows > 0) {
                echo "<p>Total Voters: " . $totalVoters . "</p>"; // Display the total number of voters
                echo "<table>";
                echo "<tr><th>Sl. No.</th><th>Voter ID</th><th>Nmae</th><th>Address</th><th>Delete Time</th><th>Reason</th></tr>";
                $serialNumber = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $serialNumber . "</td>";
                   
                    echo "<td>" . $row["username"] . "</td>";
                     echo "<td>" . $row["name"] . "</td>";
                      echo "<td>" . $row["address"] . "</td>";
                       echo "<td>" . $row["delete_time"] . "</td>";
                        echo "<td>" . $row["reason"] . "</td>";
                    echo "</tr>";
                    $serialNumber++;
                }
                echo "</table>";
            } else {
                echo "<p>No users found for the selected district.</p>";
            }
        }
        ?>
    </div>
    <script type="text/javascript">
        // JavaScript code goes here
    </script>
</body>
</html>

    
</div>
</body>
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
</html>
