
<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("location: admin-login.php");
    exit();
}
include 'conn.php';

if (isset($_POST["submit"])) {
    $photo = $_FILES["photo"]["tmp_name"];
    $username = $_POST["username"];
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $father = $_POST["father"];
    $district = $_POST["district"];
    $address = $_POST["address"];
    $password = $_POST["password"];

    // Calculate age from date of birth
    $dob_timestamp = strtotime($dob);
    $age = date('Y') - date('Y', $dob_timestamp);
    if (date('md', $dob_timestamp) > date('md')) {
        $age--;
    }

    // Check if the voter's age is over 18
    if ($age < 18) {
        echo "<script>alert('Voter must be at least 18 years old.');</script>";
        echo "<script>window.history.go(-1);</script>"; // Go back one step in history
        exit(); 
    }

    // Check if the voter already exists in the database
    $checkQuery = "SELECT * FROM voter WHERE username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Voter already exists, show error message
        echo "<script>alert('Voter already exists. Please choose a different Voter ID.');</script>";
        echo "<script>window.history.go(-1);</script>"; // Go back one step in history
        exit(); 
    } else {
        // Insert the new voter into the database
        $insertQuery = "INSERT INTO voter (photo, username, name, dob, father, district, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssssss", $photoData, $username, $name, $dob, $father, $district, $address, $password);

        // Read the photo file and convert it to binary data
        $photoData = file_get_contents($photo);

        if ($stmt->execute()) {
            echo "<script>alert('New voter added successfully.');</script>";
            echo "<script>window.history.go(-1);</script>"; // Go back one step in history
            exit(); 
        } else {
            echo "<script>alert('Error adding voter. Please try again.');</script>";
            echo "<script>window.history.go(-1);</script>"; // Go back one step in history
            exit(); 
        }

        $stmt->close();
    }

    $checkStmt->close(); // Close the check statement
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Voter</title>
    <style>
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
            overflow-y: scroll;
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

        .container {
            width: 80%;
            max-width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

       
        .container label,
        select {
            font-weight: bold;
        }

        .container input {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }

        .container div {
            flex: 0 0 calc(50% - 10px);
        }

        .container input[type="text"],
        .container input[type="date"],
        .container input[type="password"],
        .container select[name="district"],
         
        .container textarea {
            width: 90%;
            height: 20px;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .no-resize {
            width: 300px;
            resize: none;
        }

        .container textarea {
            height: 60px;
        }
        #photoPreview {
          
        top: 20px;
        right: 20px;
        max-width: 150px;
        max-height: 150px;
        border: 1px solid #ccc;
        display: none; 
        }


        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .container p {
            margin-top: 10px;
        }

        .container p.error {
            color: red;
        }

        .container p.success {
            color: green;
        }
    </style>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const labels = document.querySelectorAll('.container label');
            labels.forEach(function(label) {
                const asterisk = document.createElement('span');
                asterisk.textContent = '*';
                asterisk.style.color = 'red';
                asterisk.style.marginLeft = '5px';
                label.appendChild(asterisk);
            });
        });

        function validateForm() {
            var voterIdField = document.getElementById("username");
            var voterIdValue = voterIdField.value;
            if (!/^[0-9]+$/.test(voterIdValue)) {
                alert("Voter ID must contain only numbers.");
                voterIdField.focus();
                return false;
            }

            var photoField = document.getElementById("photo");
            var photoFile = photoField.files[0];
            var photoFileName = photoFile.name;
            var photoFileType = photoFileName.split(".").pop().toLowerCase();
            if (photoFileType !== "jpeg") {
                alert("Only JPEG format files are allowed for the photo.");
                photoField.value = ""; // Clear the file input
                return false;
            }

            // Check the photo file size
            var photoFileSize = photoFile.size / 1024; // Size in KB
            if (photoFileSize > 500) {
                alert("The photo file size must be less than 500KB.");
                photoField.value = ""; // Clear the file input
                return false;
            }

            // Other form validation rules can be added here

            return true;
        }
    </script>
</head>
<body>
    <nav class="vertical-menu-wrapper">
        <div class="vertical-menu-logo">
            <div>e-Voting System</div>
            <span class="open-menu-btn"><hr><hr><hr></span>
        </div>
        <ul class="vertical-menu">
            <li><a href="#"><i class="fas fa-home"></i>Home</a></li>
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
        <div class="container">
            <h1>Add Voter</h1>
            <form method="post" enctype="multipart/form-data">
                <div>
                    <label for="photo">Photo:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" required>
                </div>

                <div>
                <img id="photoPreview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc;margin-right: 150px;
                ">
                </div>
                <script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');

    photoInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function () {
                photoPreview.src = reader.result;
                photoPreview.style.display = 'block'; // Show the preview
            };
            reader.readAsDataURL(file);
        } else {
            photoPreview.src = '#';
            photoPreview.style.display = 'none'; // Hide the preview
        }
    });
});

</script>
<?php
include 'conn.php';

// Fetch the last voter ID from the database
$lastVoterIdQuery = "SELECT MAX(username) AS last_voter_id FROM voter";
$lastVoterIdResult = $conn->query($lastVoterIdQuery);

// Set a default value if there are no records in the table yet
$lastVoterId = 1000; // Assuming 1000 is the starting voter ID

if ($lastVoterIdResult->num_rows > 0) {
    $row = $lastVoterIdResult->fetch_assoc();
    $lastVoterId = intval($row["last_voter_id"]);
}

// Generate the new auto-incremented voter ID
$newVoterId = $lastVoterId + 1;

// Close the PHP tag to write the HTML and JavaScript code
?>

    <script type="text/javascript">
        function generateVoterId() {
            // Fetch the last inserted voter ID from the input field
            var lastVoterId = parseInt(document.getElementById('username').value);

            // Check if the lastVoterId is a valid number
            if (isNaN(lastVoterId)) {
                alert('Invalid Voter ID');
                return;
            }

            // Increment the last voter ID to get the new voter ID
            var newVoterId = lastVoterId + 1;

            // Update the input field with the new auto-generated voter ID
            document.getElementById('username').value = newVoterId;
        }
    </script>

   
    <div>
        <label for="username" autocomplete="off">Voter ID:</label>
        <input type="number" id="username" name="username" value="<?php echo $newVoterId; ?>" required>
        <button type="button" onclick="generateVoterId()">Generate Voter ID</button>
    </div>
 

 <div>
                    <label for="name" autocomplete="off">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="father" autocomplete="off">Father:</label>
                    <input type="text" id="father" name="father" required>
                </div>
                 <div>
                <label for="district">District:</label>
                <select id="district" name="district" style="height: 40px;" required>
                    <option value="">Select District</option>
                    <option value="Bagalkot">Bagalkot</option>
                    <option value="Ballari">Ballari</option>
                    <option value="Belagavi">Belagavi</option>
                    <option value="Bengaluru Rural">Bengaluru Rural</option>
                    <option value="Bengaluru Urban">Bengaluru Urban</option>
                    <option value="Bidar">Bidar</option>
                    <option value="Chamarajanagar">Chamarajanagar</option>
                    <option value="Chikkaballapur">Chikkaballapur</option>
                    <option value="Chikkamagaluru">Chikkamagaluru</option>
                    <option value="Chitradurga">Chitradurga</option>
                    <option value="Dakshina Kannada">Dakshina Kannada</option>
                    <option value="Davanagere">Davanagere</option>
                    <option value="Dharwad">Dharwad</option>
                    <option value="Gadag">Gadag</option>
                    <option value="Hassan">Hassan</option>
                    <option value="Haveri">Haveri</option>
                    <option value="Kalaburagi">Kalaburagi</option>
                    <option value="Kodagu">Kodagu</option>
                    <option value="Kolar">Kolar</option>
                    <option value="Koppal">Koppal</option>
                    <option value="Mandya">Mandya</option>
                    <option value="Mysuru">Mysuru</option>
                    <option value="Raichur">Raichur</option>
                    <option value="Ramanagara">Ramanagara</option>
                    <option value="Shivamogga">Shivamogga</option>
                    <option value="Tumakuru">Tumakuru</option>
                    <option value="Udupi">Udupi</option>
                    <option value="Uttara Kannada">Uttara Kannada</option>
                    <option value="Vijayapura">Vijayapura</option>
                    <option value="Yadgir">Yadgir</option>
                </select>
            </div>
                <div>
                    <label for="dob">DOB:</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div>
                    <label for="address" autocomplete="off">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div>
                    <label for="password" autocomplete="off">Password:</label><br>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <button type="submit" name="submit" style="height: 40px;">Add Voter</button>
                    
                </div>
            </form>
        </div>
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
    </div>
</body>
</html>
