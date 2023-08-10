<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'election';

$conn = mysqli_connect($host, $user, $password, $db_name);

$sql = "SELECT first_name,last_name,father_name,address,qulification,income FROM tumkuru;
// Assuming you have already established a database connection stored in the $conn variable
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    
    echo "<table>";
    echo "<tr><th>Name</th><th>last name</th><th>father name</th><th>address</th><th>Qualification</th><th>income</th></tr>";

    // Display candidate information
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['first_name'] . "</td>";
        echo "<td>" . $row['last_name'] . "</td>";
        echo "<td>" . $row['father_name'] . "</td>";
        echo "<td>" . $row['address'] . "</td>";
        echo "<td>" . $row['qualification'] . "</td>";
        echo "<td>" . $row['income'] . "</td>";
      
        echo "</tr>";
    }

    echo "</table>";
    ?>