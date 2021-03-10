<?php
session_start();
$log = false;
if(isset($_SESSION['loggedin'])){
  if($_SESSION['loggedin']==true){
  	$fname = $_SESSION['first_name'];
    $lname = $_SESSION['last_name'];
    $log = true;
  }
}
 ?>

<header id="header" class="fixed-top">
      <div class="container d-flex align-items-center">

        <h1 class="logo me-auto"><a href="index.php"><span>F</span>OODENTS</a></h1>
        <!--Above command for text-title and below for a logo -->
        <!-- <a href="index.html" class="logo me-auto "><img src="assets/img/logo.png" alt="" class="img-fluid"></a> -->

        <!-- <a class="navbar-brand logo me-auto" href="index.html">
    <img src="assets/img/logo.png" width="30" height="30" class="d-inline-block align-top " alt="">
    oodents
  </a> -->


        <nav id="navbar" class="navbar order-last order-lg-0">
          <a href="index.php" class="active">Home</a>

          <span class="dropdown"><a href="#"><span>About</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="about.php">About Us</a></li>
              <li><a href="team.php">Team</a></li>
              <li><a href="testimonials.php">Testimonials</a></li>
            </ul>
          </span>

          <a href="fridge.php">Fridge</a>
          <a href="search.php">Search Recipe</a>
          <a href="contact.php">Contact</a>
          <?php
            if($log){
              echo "<a href='./login/logout.php'>Logout ($fname"." "."$lname)</a>";
            }
            else{
              echo "<a href='./login/login.php'>Login</a>";
            }
           ?>
          <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>

      </div>
</header>