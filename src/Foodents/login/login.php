<?php


session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
	header("location: ../index.php");
	exit();
}

if(isset($_SESSION['loggedin'])){
  if($_SESSION['loggedin']==true){
  	$fname = $_SESSION['first_name'];
    $lname = $_SESSION['last_name'];
		$em = $_SESSION['email'];
		$us = $_SESSION['username'];
    $log = true;
  }
}else{
	$log = false;
}

if(isset($_SESSION['msg'])){
	$msg = $_SESSION['msg'];
	unset($_SESSION['msg']);
}

if(isset($_SESSION['error'])){
	$error = $_SESSION['error'];
	unset($_SESSION['error']);
}

require("index.php");


$username = $password = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){
	if(empty(trim($_POST['username']))){
		$err = "Username field cannot be blank";
	}
	else{
		$username = trim($_POST['username']);
	}

	if(empty(trim($_POST['password']))){
		$err = "Password field cannot be blank";
	}
	else{
		$password = trim($_POST['password']);
	}


	if(empty($err)){

		$sql = "SELECT user_id, username, password, first_name, last_name,email,profile_url,Bio from USERS where username= ? or email = ?";

		if($stmt = mysqli_prepare($conn,$sql)){
			mysqli_stmt_bind_param($stmt, 'ss', $param_username,$param_email);
			$param_username = $username;
			$param_email = $username;
			if(mysqli_stmt_execute($stmt)){
				// To store the result
				mysqli_stmt_store_result($stmt);

				// If any information has been retrieved or not
				if(mysqli_stmt_num_rows($stmt)==1){
					// Used to bind the fetched stuff to the variables
					mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $firstn, $lastn, $email, $profurl, $bio);

					// Fetch the results into the variables above
					if(mysqli_stmt_fetch($stmt)){
						if(password_verify($password, $hashed_password)){       //(uncomment this when the passwords in the database are hashed)
							session_start();

							$_SESSION['loggedin'] = true;
							$_SESSION['id'] = $id;
							$_SESSION['username'] = $username;
							$_SESSION['first_name'] = $firstn;
							$_SESSION['last_name'] = $lastn;
							$_SESSION['email'] = $email;
							$_SESSION['profurl'] = $profurl;
							$_SESSION['bio'] = $bio;
							header("location: ../index.php");
						}
						else{
							$err =  "Oops. The password is incorrect";
						}

					}
				}
				else{
				$err ="No account was found with these credentials";
				}
			}
			else{
				$err ="Something is wrong with the dollar stmt part";
			}
			mysqli_stmt_close($stmt);
		}

	}

	if(isset($err)){
	  $_SESSION['error'] = $err;
	  header('location: login');
	}

    mysqli_close($conn);

}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Login</title>
    <link rel="shortcut icon" href="./../assets/img/favicon.png">
    <link rel="bookmark" href="./../assets/img/favicon.png">
 </head>
 <body>
	 <?php
 	include("account_navbar.php")
 	?>

<!-- ======= Breadcrumbs ======= -->
<br><br>
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">

    <div class="d-flex justify-content-between align-items-center">
      <h2>Login</h2>
      <ol>
        <li><a href="../index">Home</a></li>
        <li>Login</li>
      </ol>
    </div>

  </div>
</section><!-- End Breadcrumbs -->

    <div class="container middle p-3">
			<?php
			if(isset($msg)){
			echo "<div class='alert alert-success' role='alert'>".$msg."</div>";
			}
			?>
			<?php
			if(isset($error)){
			echo "<div class='alert alert-danger' role='alert'>".$error."</div>";
			}
			?>
 		<form action="login.php" method="POST">
        <h2 class="text-center">Login</h2><br>
        <div class="form-group">
            <label for="username" class="form-label">E-mail/Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter email or username">
        </div>


        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
        </div>
        <br><br>
        <div class="text-center">
            <button class="btn btn-primary">Login</button>
        </div><br>
 		</form>

 		<div class="text-center">
 			<a href="register.php"><button class="btn btn-secondary">Register</button></a>
 		</div><br>
 	</div>
<br><br><br>
<?php include("account_footer.php") ?>
</body>
</html>
