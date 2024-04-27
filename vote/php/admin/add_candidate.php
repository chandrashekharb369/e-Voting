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
$data = array(
    "qualification" => "Some Qualification",
    "party" => "Some Party",
    "symbol" => "Some Symbol"
);

global $qualification, $party, $symbol;

// Your existing code...

if (isset($data["qualification"])) {
    $qualification = $data["qualification"];
} else {
    $qualification = "Default Qualification";
}

if (isset($data["party"])) {
    $party = $data["party"];
} else {
    $party = "Default Party";
}

if (isset($data["symbol"])) {
    $symbol = $data["symbol"];
} else {
    $symbol = "Default Symbol";
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
   
    $about = $_POST["about"];
    $party = $_POST["pname"];
    
    


    
$currentYear = date("Y");

$checkQuery = "SELECT * FROM candidate WHERE voter_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $voter_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo "<script>alert('Voter ID already exists. Please choose a different Voter ID.');</script>";
} else {
    
    $insertQuery = "INSERT INTO candidate (voter_id, photo, firstname, lastname, dob, fathername, district, address, qualification, about, party, symbole, yeAr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssssssssssss", $voter_id, $photoData, $firstname, $lastname, $dob, $fathername, $district, $address, $qualification, $about, $party, $symboleData, $currentYear); 
    $photoData = file_get_contents($_FILES["photo"]["tmp_name"]);
    // ...
    $symboleData = file_get_contents($_FILES["symbol"]["tmp_name"]);

    
    $stmt->execute();

    
    if ($stmt->affected_rows > 0) {
         echo "<script>
                alert('Candidate data saved successfully');
                window.history.back();
              </script>";
    } else {
         echo "<script>
                alert('Error data saved successfully');
                window.history.back();
              </script>";
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
            if (photoFileType !== "jpg") {
                alert("Only JPG format files are allowed for the photo.");
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
     <link rel="stylesheet" type="text/css" href="../../css/navia.css">
     <style>
       

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
    width: 90%; /* Adjust this value to increase or decrease the container width */
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
        
        <div class="container">
            <h1>Add Candidate</h1>
       <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
           <div>
    <label for="photo">Photo:</label>
    <input type="file" id="photo" name="photo" accept=".jpg" required>

</div>
<br>
             <div>
                <img class="photoPreview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc;margin-right: 150px;
                ">
                </div>
               <script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photo');
    const symbolInput = document.getElementById('symbol');
    const photoPreview = document.querySelector('.photoPreview');
    const symbolPreview = document.querySelector('.symbolPreview');

    photoInput.addEventListener('change', function (event) {
        previewFile(event.target.files[0], photoPreview);
    });

    symbolInput.addEventListener('change', function (event) {
        previewFile(event.target.files[0], symbolPreview);
    });

    function previewFile(file, previewElement) {
        if (file) {
            const reader = new FileReader();
            reader.onload = function () {
                previewElement.src = reader.result;
                previewElement.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewElement.src = '#';
            previewElement.style.display = 'none';
        }
    }
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
                <select id="qualification" name="qualification" required>
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
            <label for="about">Party:</label>
                <input type="text" id="about" name="pname" required>
            </div><br>
           <div>
    <label for="symbole">Symbol:</label> <!-- Corrected the typo: "symbole" to "symbol" -->
    <input type="file" id="symbol" name="symbol" accept=".jpg" required>
</div><br>
<img class="symbolPreview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc;margin-right: 150px;">
</div>

            <div style="margin-left: 20px;">
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