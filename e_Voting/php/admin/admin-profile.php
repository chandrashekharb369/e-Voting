<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <style>
    body {margin: 0; background: #181824; font-family: Arial;
  background: #181824;
  font-family: Arial;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f0f0f0;
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
    nav .vertical-menu hr{opacity: 0.1; border-width: 0.5px;}
    nav ul{width: 90%; padding-inline-start: 0; margin: 10px; height: calc(100% - 20px); }
    nav .vertical-menu-logo{padding: 20px; font-size: 1.3em; position: relative}
    nav .vertical-menu-logo .open-menu-btn{width: 30px; height: max-content; position: absolute; display: block; right: 20px; top: 0; bottom: 0; margin: auto; cursor: pointer;}
    nav .vertical-menu-logo .open-menu-btn hr{margin: 5px 0}
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

    .content-wrapper{
      text-align: center;
      align-items: center;
      width: calc(100% - 250px);
      height: 100%;
      position: fixed;
      background: #fff;
      left: 250px;
      padding: 20px;
    }
    .closed-menu .content-wrapper{
      width: 100%;
      left: 50px;
    }
    .content-wrapper{
      transition: all 300ms;
    }
    .vertical-menu-wrapper .vertical-menu-logo div{transition: all 100ms;}
    .closed-menu .vertical-menu-wrapper .vertical-menu-logo div{
      margin-left: -300px;
    }
    .vertical-menu-wrapper .vertical-menu-logo .open-menu-btn{transition: all 300ms;}
    .closed-menu .vertical-menu-wrapper .vertical-menu-logo .open-menu-btn{
      left: 10px;
      right: 100%;
    }

    .closed-menu .vertical-menu-wrapper ul,.closed-menu .vertical-menu-wrapper hr{margin-left: -300px;}
    .vertical-menu-wrapper ul, .vertical-menu-wrapper hr{transition: all 100ms;}
    .content-wrapper{background: #ebebeb;}
    .content{
      
      width: 90%;
      min-height: 90%;
      background: #fff;
      border-radius: 10px;
      padding: 30px;
    }

    
    .icon h1{
      margin-top: -50px;
      margin-left: 250px;
    }

    .wall img{
      margin-top: -25px;
      width:100%;
      height: 20%;
    }
    .icon img{
      width: 150px;
      height: 150px;
      border-radius: 100px;
    }

    #slideshow-container {
  position: relative;
  width: 400px;
  height: 300px;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center; 
  margin-left: 22.5%;
  border-radius: 20px;
}

.slideshow-image {
  position: absolute;
  width: 100%;
  height: 100%;
  object-fit: cover; 
  opacity: 0;
  transition: opacity 1s ease-in-out;
}

.active {
  opacity: 1;
}
 marquee{
      overflow: hidden;
      white-space: nowrap;
      width: 100%;
      display: inline-block;
      animation:linear infinite;
    }

   

  </style>
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
             <a href="add_candidate.php">Add Candidate</a>
            <a href="candidate_list.php">Candidate list</a>
          </li>
          <li><a href="polling.php"><i class="fas fa-poll"></i> Polling</a></li>
          <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      
    </ul>
  </nav>
  <div class="content-wrapper">
    <div class="content">
      <h1>Karnataka District Level Election</h1>
     <div id="slideshow-container">
    <img class="slideshow-image active" src="../../images/karele.jpg" alt="Image 1">
    <img class="slideshow-image" src="../../images/su.jpg" alt="Image 2">
    <img class="slideshow-image" src="image3.jpg" alt="Image 3">
  
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

    setInterval(showNextImage, 5000); // Change image every 10 seconds
  </script>
</body>
</html>
