<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "election";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $date1 = $_POST['election_date'];

    $endtime = $_POST['end_time'];

    // Fetch maximum 'sl' value from the table
    $max_sl_query = "SELECT MAX(sl) as max_sl FROM dateele";
    $result = $conn->query($max_sl_query);
    $row = $result->fetch_assoc();
    $max_sl = $row['max_sl'];

    // Increment 'sl' value
    $sl = $max_sl + 1;

    // SQL to insert data into election table
    $sql = "INSERT INTO dateele (date1, endtime, sl)
          VALUES ('$date1','$endtime', $sl)";

    if ($conn->query($sql) === TRUE) {
        // Data saved successfully, display alert and go back one page
        echo "<script>
                alert('Election data saved successfully');
                window.history.back();
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
