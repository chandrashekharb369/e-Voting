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
    $voter_id = $_POST["voter_id"];
    $photo = $_FILES["photo"]["tmp_name"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $dob = $_POST["dob"];
    $fathername = $_POST["fathername"];
    $district = $_POST["district"];
    $address = $_POST["address"];
    $qualification = $_POST["qualification"];
    $about = $_POST["about"];
    $party = $_POST["party"];
    $symbole = $_FILES["symbole"]["tmp_name"];


    $checkQuery = "SELECT * FROM candidate WHERE voter_id = '$voter_id'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        
        echo "<script>alert('Voter ID already exists. Please choose a different Voter ID.');</script>";
    } else {
        // Insert the new candidate into the database
        $insertQuery = "INSERT INTO candidate (voter_id, photo, firstname, lastname, dob, fathername, district, address, qualification, about, party, symbole) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssssssssss", $voter_id, $photoData, $firstname, $lastname, $dob, $fathername, $district, $address, $qualification, $about, $party, $symboleData); // Bind the parameters

        // Read the photo file and convert it to binary data
        $photoData = file_get_contents($photo);
        $symboleData = file_get_contents($symbole);

        if ($stmt->execute()) {
            echo "<script>alert('New candidate added successfully.');</script>";
        } else {
            echo "<script>alert('Error adding candidate: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
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
            var voterIdField = document.getElementById("voter_id");
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
                photoField.value = ""; 
                return false;
            }

            // Check the photo file size
            var photoFileSize = photoFile.size / 1024; 
            if (photoFileSize > 500) {
                alert("The photo file size must be less than 500KB.");
                photoField.value = ""; 
                return false;
            }


            return true;
        }
    </script>
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
        .container select[name="district"],
        .container textarea {
            width: 100%;
            height: 30px;
            padding: 10px, 10px;
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
         .photoPreview {
        margin-right: 150px;
        top: 20px;
        right: 20px;
        max-width: 150px;
        max-height: 150px;
        border: 1px solid #ccc;
        
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button::hover {
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
                </div>
            </li>
            <li><a href="polling.php"><i class="fas fa-poll"></i> Polling</a></li>
            <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
    <div class="content-wrapper">
        
        <div class="container">
            <h1>Add Candidate</h1>
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div>
                 <div>
    <label for="photo">Photo:</label>
    <input type="file" id="photo" name="photo" accept=".jpeg" required>
</div><br>
<img class="photoPreview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc;margin-right: 150px;">
</div>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const symbolInput = document.getElementById('photo'); 
    const symbolPreview = document.querySelector('.photoPreview'); 

    symbolInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function () {
                symbolPreview.src = reader.result;
                symbolPreview.style.display = 'block'; 
            };
            reader.readAsDataURL(file);
        } else {
            symbolPreview.src = '#';
            symbolPreview.style.display = 'none'; 
        }
    });
});
</script>

                <div>
            <div>
                <label for="voter_id">Voter ID:</label>
                <input type="text" id="voter_id" name="voter_id" required pattern="[0-9]+" title="Voter ID must contain only numbers.">
            </div>
            <div>
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div>
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div>
                <label for="dob">DOB:</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div>
                <label for="fathername">Father's Name:</label>
                <input type="text" id="fathername" name="fathername" required>
            </div>
            <div>
                <label for="district">District:</label>
                <select id="district" name="district" required>
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
                <label for="address">Address:</label>
                <textarea id="address" name="address" class="no-resize" required></textarea>
            </div>
            <div>
                <label for="qualification">Qualification:</label>
                <select id="district" name="district" required>
                    <option value="">Select Qualification</option>
                    <option value="S.S.L.C">S.S.L.C</option>
                    <option value="P.U.C">P.U.C</option>
                    <option value="B.Sc">B.Sc</option>
                     <option value="B.Com">B.Com</option>
                      <option value="B.A">B.A</option>
                       <option value="M.Sc">M.Sc</option>
                     <option value="P.hd">P.hd</option>
                </select>
            </div>
            <div>
                <label for="about">About:</label>
                <textarea id="about" name="about" class="no-resize" required></textarea>
            </div><br>
           <div>
    <label for="symbole">Symbol:</label> <!-- Corrected the typo: "symbole" to "symbol" -->
    <input type="file" id="symbol" name="symbol" accept=".jpeg" required>
</div><br>
<img class="symbolPreview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc;margin-right: 150px;">
</div>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const symbolInput = document.getElementById('symbol'); 
    const symbolPreview = document.querySelector('.symbolPreview'); 

    symbolInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function () {
                symbolPreview.src = reader.result;
                symbolPreview.style.display = 'block'; 
            };
            reader.readAsDataURL(file);
        } else {
            symbolPreview.src = '#';
            symbolPreview.style.display = 'none'; 
        }
    });
});
</script>
            <div>
                <button name="submit">Add Candidate</button>
                <button name="clear" style="background: red;" type="reset">clear</button>
            </div>
        </form>
    </div>
    </div>
</body>
<script type="text/javascript">
   
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
