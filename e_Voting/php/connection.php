<?php
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

      ?>