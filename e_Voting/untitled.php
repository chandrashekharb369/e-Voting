 <div class="sidebar">
        <div class="logo">e-Voting System</div>
        <ul>
            <li><a href="admin-profile.php"><i class="fas fa-home"></i> Home</a></li>
            <li>
                <a href="#"><i class="fas fa-users"></i> Voter</a>
                <div class="dropdown-content">
                    <a href="add.php">Add Voter</a>
                    <a href="remove.php">Remove Voter</a>
                    <a href="voter_list.php">Voter List</a>
                </div>
            </li>
            <li>
                <a href="#"><i class="fas fa-user-tie"></i> Candidates</a>
                <div class="dropdown-content">
                    <a href="add_candidate.php">Add Candidate</a>
                    <a href="candidate_list.php">Candidate list</a>
                </div>
            </li>
            <li><a href="polling.php"><i class="fas fa-poll"></i> Polling</a></li>
            <li><a href="../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <h1>Remove Voter</h1>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <input type="submit" name="submit" value="Remove Voter">
    </form>

   
<?php
session_start();

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

$partyNames = array();
$votes = array();

// Check if a district is selected
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

<!DOCTYPE html>
<html>
<head>
    <title>Bar Chart</title>
     <link rel="stylesheet" type="text/css" href="../../css/graph.css">
</head>
<body>
    <nav>
        <div class="logo">e-Voting System</div>
        <ul>
            <li><a href="admin-profile.php">Home</a></li>
            <li>
                <a href="#">Voter</a>
                <div class="dropdown-content">
                    <a href="#">Add Voter</a>
                    <a href="remove.php">Remove Voter</a>
                    <a href="voter_list.php">Voter List</a>
                </div>
            </li>
            <li><a href="#">Candidates</a>
                <div class="dropdown-content">
                    <a href="add_candidate.php">Add Candidate</a>
                    <a href="candidate_list.php">Candidate list</a>
            </li>
            <li><a href="polling.php">Polling</a></li>
            <li><a href="../index.html">Logout</a></li>
        </ul>
    </nav>
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
</body>
</html>

<?php
$conn->close();

// Function to generate a random color for the bars
function generateRandomColor()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
?>

</html>