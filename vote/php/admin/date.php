<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = ""; // Replace with your database password if set
    $dbname = "election";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statements to prevent SQL injection
    $date1 = $conn->real_escape_string($_POST['election_date']);
    $endtime = $conn->real_escape_string($_POST['end_time']);
    $announce = $conn->real_escape_string($_POST['announce']);

    // Get the maximum sl value
    $max_sl_query = "SELECT MAX(sl) as max_sl FROM dateele";
    $result = $conn->query($max_sl_query);
    $row = $result->fetch_assoc();
    $max_sl = $row['max_sl'];

    // Increment sl value
    $sl = $max_sl + 1;

    // Insert data into dateele table
    $sql = "INSERT INTO dateele (date1, endtime, sl) VALUES ('$date1','$endtime', $sl)";

    // Insert data into announced table
    $sql1 = "INSERT INTO announced (date_time, sl) VALUES ('$announce', $sl)";

    // Perform both insertions in a transaction to ensure atomicity
    $conn->begin_transaction();
    $success = true;
    if (!$conn->query($sql) || !$conn->query($sql1)) {
        $success = false;
    }

    if ($success) {
        $conn->commit();
        echo "<script>
                alert('Election data saved successfully');
                window.history.back();
              </script>";
    } else {
        $conn->rollback();
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
