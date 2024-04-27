<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link rel="stylesheet" type="text/css" href="../../css/navia.css">
  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
  

  <nav class="vertical-menu-wrapper">
    <div class="vertical-menu-logo">
      <div>e-Voting System</div>
      <span class="open-menu-btn"><hr><hr><hr></span>
    </div>
    <ul class="vertical-menu">
      <li><a href="#"><i class="fas fa-home"></i>Home</a></li>
      <li><a href="#"><i class="fas fa-user"></i>Voter</a>
          <div class="dropdown-content">
              <a href="add.php">Add Voter</a>
              <a href="remove.php">Remove Voter</a>
              <a href="voter_list.php">Voter List</a></li>
   <li><a href="#"><i class="fas fa-user-tie"></i> Candidate</a>
          <div class="dropdown-content">
             <a href="removecnadi.php">Add Candidate</a>
            <a href="candidate_list.php">Candidate list</a>
            <a href="removecnadi.php">Remove Candidate</a>
          </li>
          <li><a href="polling.php"><i class="fas fa-poll"></i> Polling</a></li>
          <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      
    </ul>
  </nav>
  <div class="content-wrapper">
    <div class="content">
      <h1 align="center">Karnataka District Level Election</h1>
     <div id="slideshow-container">
    <img class="slideshow-image active" src="../../images/karele.jpg" alt="Image 1">
    <img class="slideshow-image" src="../../images/su.jpg" alt="Image 2">
    <img class="slideshow-image" src="image3.jpg" alt="Image 3">
  
  </div>
<div>
  <style type="text/css">
    h2 {
  color: #04045e;
  font-size: 24px;
  margin-bottom: 20px;
}

form {
  max-width: 400px;
  margin: 0 auto;
}

label {
  display: block;
  font-size: 18px;
  color: #333;
  margin-bottom: 8px;
}

input[type="datetime-local"],
input[type="time"],
input[type="submit"] {
  width: calc(100% - 20px);
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 20px;
}

input[type="submit"] {
  background-color: #04045e;
  color: #fff;
  cursor: pointer;
  transition: background-color 0.3s;
}

input[type="submit"]:hover {
  background-color: #020233;
}

  </style>
  <h2 align="center">Set Election Date and Time</h2>
  <form method="POST" action="date.php">
    <label for="election_datetime" align="center">Election Date and Starting Time:</label>
<input type="datetime-local" id="date" name="election_date" required><br>

<label for="end_time">Ending Time:</label>
<input type="datetime-local" id="end_time" name="end_time" required><br>


    

  <label for="end_time"> Announce Candidate Date</label>
<input type="datetime-local" id="end_time" name="announce" required><br>
<input type="submit" value="Save Election">
  </form>
</div>

   <marquee>
   <h4>This Project belogs to karnataka District level election or Member Of Parliment elections of Karnataka</h4>
</marquee>

    </div>

  </div>
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
    
    const slideshowImages = document.querySelectorAll('.slideshow-image');
    let currentImageIndex = 0;

    function showNextImage() {
      slideshowImages[currentImageIndex].classList.remove('active');
      currentImageIndex = (currentImageIndex + 1) % slideshowImages.length;
      slideshowImages[currentImageIndex].classList.add('active');
    }

    setInterval(showNextImage, 5000); 
  </script>
</body>
</html>
