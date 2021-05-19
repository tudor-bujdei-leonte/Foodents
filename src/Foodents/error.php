<?php
header( "refresh:6;url=index" );
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
   <title>Recipe Results</title>
  <link rel="shortcut icon" href="./assets/img/favicon.png">
  <link rel="bookmark" href="./assets/img/favicon.png">

   <!-- =======================================================
   * Template Name: Company - v4.0.1
   * Template URL: https://bootstrapmade.com/company-free-html-bootstrap-template/
   * Author: BootstrapMade.com
   * License: https://bootstrapmade.com/license/
   ======================================================== -->
 </head>

 <body>

 <?php
 include("navbar.php")
  ?>

   <main id="main">

     <!-- ======= Breadcrumbs ======= -->
     <section id="breadcrumbs" class="breadcrumbs">
       <div class="container">

         <div class="d-flex justify-content-between align-items-center">
           <h2></h2>
           <ol>
             <li><a href="index">Home</a></li>
           </ol>
         </div>

       </div>
     </section><!-- End Breadcrumbs -->


     <section id="contact" class="contact">
       <div class="section-title" data-aos="fade-up" style = "padding-bottom: 0px">
         <h2><strong>Redirecting in <span id="timer">5</span> ...</strong></h2>
       </div>

             <div class="row justify-content-center" data-aos="fade-up">

                 <img src="./assets/img/stories.jpg" alt="" style="height:70vh; width:70vh">

             </div>

         </section>

   </main><!-- End #main -->

 <?php include("footer.php") ?>

 <script>
 $(document).ready(function() {
var i =5

var x = setInterval(function(){
   as();
   i--;
   if(i<0){
     clearInterval(x);
   }
 },1000)


function as(){
  $("#timer").text(i);
}



 })
 </script>
 </body>
 </html>
