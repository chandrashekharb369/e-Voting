<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin"])) {
    header("location: admin-login.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "election";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

if (isset($_POST["submit"])) {
    // Sanitize user inputs
    $voter_id = sanitizeInput($_POST["voter_id"]);
    $reason = sanitizeInput($_POST["reason"]);

    // Use prepared statements to prevent SQL injection
    $checkQuery = $conn->prepare("SELECT * FROM candidate WHERE voter_id = ?");
    $checkQuery->bind_param("s", $voter_id);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $voterData = $checkResult->fetch_assoc();

        $deleteTime = date('Y-m-d H:i:s');
        
        // Insert into deletecandi table
        $insertQuery = $conn->prepare("INSERT INTO deletecandi (voter_id, firstname, dob, district, address, reason, yeAr) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertQuery->bind_param("sssssss", $voter_id, $voterData['firstname'], $voterData['dob'], $voterData['district'], $voterData['address'], $reason, $voterData['yeAr']);
        
        if ($insertQuery->execute()) {
            // Delete the voter from the candidate table
            $deleteQuery = $conn->prepare("DELETE FROM candidate WHERE voter_id = ?");
            $deleteQuery->bind_param("s", $voter_id);
            if ($deleteQuery->execute()) {
                echo "<script>alert('Voter removed successfully. Reason: $reason.');</script>";
            } else {
                echo "<p style='color: red;'>Error removing candidate. Please try again.</p>";
            }
        } else {
            echo "<p style='color: red;'>Error inserting candidate into deletecandi table. Please try again.</p>";
        }
    } else {
        echo "<script>alert('Voter not found.');</script>";
    }
}

// Fetch unique districts from deletecandi table
$districtQuery = "SELECT DISTINCT district FROM deletecandi";
$districtResult = $conn->query($districtQuery);

// Fetch unique years from deletecandi table
$yearQuery = "SELECT DISTINCT yeAr FROM deletecandi";
$yearResult = $conn->query($yearQuery);

// Fetch data based on selected district and year
if (isset($_POST["submit01"])) {
    $district = sanitizeInput($_POST["district"]);
    $year = sanitizeInput($_POST["year"]);

    // Fetch users from the database based on the district and year
    $query = "SELECT * FROM deletecandi WHERE district = '$district' AND yeAr = '$year'";
    $result = $conn->query($query);
    $totalVoters = $result->num_rows; // Get the total number of voters
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
     <link rel="stylesheet" type="text/css" href="../../css/navia.css">
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
    <label for="voter_id">voter_id:</label>
    <input type="text" id="voter_id" name="voter_id" required>
    
    <label for="reason">Reason:</label>
    <select name="reason" id="reason" onchange="showReasonTextarea()">
        <option value=" ">--select--</option>
        <option value="Personal Reasons">Personal Reasons</option>
        <option value="Health Issues">Health Issues</option>
        <option value="Legal Issues">Legal Issues:</option>
        <option value="Lack of Support">Lack of Support</option>
        <option value="Financial Constraints">Financial Constraints</option>
    </select>
    
    <div id="reasonTextareaContainer" style="display: none;">
        <label for="reasonTextarea">Specify Reason:</label>
        <textarea id="reasonTextarea" name="reasonTextarea"></textarea>
    </div>
    
    <div style="text-align: center;">
        <input type="submit" name="submit" value="Remove Candidate">
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
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        select {
            padding: 8px;
            border-radius: 4px;
        }

        input[type="submit"] {
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
    </style>

        <h1>Candidate List</h1>
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

        <label for="year">Select Year:</label>
        <select id="year" name="year">
            <?php
            if ($yearResult->num_rows > 0) {
                while ($row = $yearResult->fetch_assoc()) {
                    echo "<option value='" . $row["yeAr"] . "'>" . $row["yeAr"] . "</option>";
                }
            }
            ?>
        </select>

        <input type="submit" name="submit01" value="Select">
    </form>

    <?php
    if (isset($_POST["district"])) {
        if ($result->num_rows > 0) {
            echo "<p>Total Candidates: " . $totalVoters . "</p>"; // Display the total number of voters
            echo "<table>";
            echo "<tr><th>Sl. No.</th><th>Voter ID</th><th>Name</th><th>Address</th><th>Year</th><th>Reason</th></tr>";
            $serialNumber = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $serialNumber . "</td>";
                echo "<td>" . $row["voter_id"] . "</td>";
                echo "<td>" . $row["firstname"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["yeAr"] . "</td>";
                echo "<td>" . $row["reason"] . "</td>";
                echo "</tr>";
                $serialNumber++;
            }
            echo "</table>";
        } else {
            echo "<p>No users found for the selected district and year.</p>";
        }
    }
    ?>
    
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
