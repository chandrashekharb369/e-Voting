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
    $query = "SELECT * FROM candidate WHERE district = '$district'";
    $result = $conn->query($query);
    $totalVoters = $result->num_rows; 
}


$districtQuery = "SELECT DISTINCT district FROM candidate";
$districtResult = $conn->query($districtQuery);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
     <link rel="stylesheet" type="text/css" href="../../css/navia.css">
     <style type="text/css">
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
            <input type="submit" name="submit" value="Select">
        </form>

        <?php
        if (isset($_POST["district"])) {
            if ($result->num_rows > 0) {
                echo "<p>Total Candidates: " . $totalVoters . "</p>"; 
                echo "<table>";
                echo "<tr><th>Sl. No.</th><th>Photo</th><th>voter id</th><th>Name</th><th>Father</th><th>Age</th><th>Party</th></tr>";
                $serialNumber = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $serialNumber . "</td>";
                    echo '<td>' . '<img src="data:image/png;base64,' . base64_encode($row['photo']) . '" width="50px" height="50px"/>' . '</td>';
                    echo "<td>" . $row["voter_id"] . "</td>";
                    echo "<td>" . $row["firstname"] .  $row["lastname"]."</td>";
                     echo "<td>" . $row["fathername"] ."</td>";
                    $dob = $row["dob"];
                    $age = date_diff(date_create($dob), date_create('today'))->y;
                    echo "<td>" . $age . "</td>";
                     echo '<td>' . '<img src="data:image/png;base64,' . base64_encode($row['symbole']) . '" width="50px" height="50px"/>' . '</td>';
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
