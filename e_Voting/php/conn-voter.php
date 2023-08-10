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
