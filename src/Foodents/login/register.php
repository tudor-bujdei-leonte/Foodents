<?php
require("index.php");
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
    header("location: login.php");
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

if(isset($_SESSION['error'])){
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

// Initialising the values
$username = $password = $confirm_password = $email= $firstname = $lastname = '';
// $username_err = $password_err = $confirm_password_err = $email_err = $firstname_err = $lastname_err = '' ;

// Checking if form has been submitted or not (similar to isset())
if($_SERVER['REQUEST_METHOD']=="POST"){
    if(empty(trim($_POST['username']))){
        $err = 'Email cannot be blank';
    }
    else{

    // ***************************************************************************
    // Checking if username already exists
        $sql = "SELECT user_id from USERS WHERE username=?";

        // Prepare the SQL query and bind the username param to it (in place of the question mark)
        if($stmt = mysqli_prepare($conn,$sql)){
            // Bind params to the query, s means string here.
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST['username']);

            if(mysqli_stmt_execute($stmt)){

                // To store the result received (doesn't cause performance loss)
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt)==1){
                    $err = "Username already in use";
                }
                else{
                    $username = strtolower(trim($_POST['username']));
                }
            }
        }
        else{
            echo("Something went wrong with the dollar stmt part");
        }

        if(empty($err)){
          if(strpos($username,' ')){
              $err = "Username cannot contain spaces";
          }
          else{
              if(!preg_match('/^[a-z0-9]+$/',$username)){
                  $err = "Username can contain only letters and numbers";
              }
          }
        }




        mysqli_stmt_close($stmt);

        // ***************************************************************************
        // Checking if email already exists
        $sql = "SELECT user_id from USERS WHERE email=?";

        // Prepare the SQL query and bind the username param to it (in place of the question mark)
        if($stmt = mysqli_prepare($conn,$sql)){
            // Bind params to the query, s means string here.
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST['email']);

            if(mysqli_stmt_execute($stmt)){

                // To store the result received (doesn't cause performance loss)
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt)==1){
                    $err = "E-mail already in use";
                }
                else{
                    $email = strtolower(trim($_POST['email']));
                }
            }
        }
        else{
            echo("Something went wrong with the dollar stmt part");
        }

        if(empty($err)){
          if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
              $err = "Email not valid";
          }
        }


        mysqli_stmt_close($stmt);

        // ***************************************************************************

        // Checking first name and last name
        $firstname = trim($_POST['firstn']);
        $lastname = trim($_POST['lastn']);
        if(empty($firstname)){
            $err = "First Name cannot be empty";
        }

        if(empty($lastname)){
            $err = "Last Name cannot be empty";
        }

        $firstname = strtolower($firstname);
        $firstname = ucfirst($firstname);
        $firstname = explode(' ', $firstname);
        $firstname = $firstname[0];

        $lastname = strtolower($lastname);
        $lastname = ucfirst($lastname);
        $lastname = explode(' ', $lastname);
        $lastname = $lastname[0];

        if(!preg_match("/^[A-Z][a-z]*$/",$firstname)){
            $err = "First Name cannot contain spaces";
        }

        if(!preg_match("/^[A-Z][a-z]*$/",$lastname)){
            $err = "Last Name cannot contain spaces";
        }


    }



// Checking the password constraints
if(empty(trim($_POST['password']))) {
    $err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password']))<6){
    $err = "Password needs to be greater than 6 characters";
}
else{
    $password = trim($_POST['password']);
}

// Checking the confirm password
if(empty(trim($_POST["confirmpass"]))){
    $err = "Confirm password field cannot be empty";
}
else{
    $confirm_password = trim($_POST["confirmpass"]);
    if( empty($err) && $password != trim($_POST["confirmpass"])){
        $err = "Passwords don't match";
            }
}




// Checking if any errors before entering into database
if(empty($err)){
    $sql = "INSERT INTO USERS (username, password,email, first_name,last_name) VALUES (?,?,?,?,?)";

    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "sssss", $username, $param_password, $email, $firstname, $lastname);
        $param_password = password_hash($password , PASSWORD_DEFAULT);
        if(mysqli_stmt_execute($stmt)){
            $_SESSION['msg'] = "Account Registered. Please login to continue.";
            header("location: login.php");
        }
        else{
            echo "Something went wrong with the second dollar stmt part";
        }

    mysqli_stmt_close($stmt);
}}

if(isset($err)){
  $_SESSION['error'] = $err;
  header('location: register');
}

mysqli_close($conn);
}

 ?>


<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
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
        <h2>Register</h2>
        <ol>
            <li><a href="../index">Home</a></li>
            <li>Register</li>
        </ol>
    </div>

</div>
 </section><!-- End Breadcrumbs -->

	<div class="container p-4 middle">
    <?php
    if(isset($error)){
    echo "<div class='alert alert-danger' role='alert'>".$error."</div>";
    }
    ?>
		<form action="register.php" method="POST">
		<h2 class="text-center">Register</h2><br>
		<div class="form-group mb-3">
			<label for="email" class="form-label">E-mail</label>
			<input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
		</div>

		<div class="form-group mb-3">
			<label for="username" class="form-label">Username</label>
			<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
		</div>
		<div class="form-group mb-3">
			<label for="firstn" class="form-label">First Name</label>
			<input type="text" class="form-control" id="firstn" name="firstn" placeholder="Enter First Name">
		</div>
		<div class="form-group mb-3">
			<label for="lastn" class="form-label">Last Name</label>
			<input type="text" class="form-control" id="lastn" name="lastn" placeholder="Enter Last Name">
		</div>





		<div class="form-group mb-3">
			<label for="pass" class="form-label">Password</label>
			<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
		</div>
		<div class="form-group mb-3">
			<label for="pass" class="form-label">Password</label>
			<input type="password" class="form-control" id="confirmpass" name="confirmpass" placeholder="Confirm Password">
		</div><br>
    	<center><small class='text-muted'>By registering, you agree to our <a href="../terms">Terms and Conditions.</a></small></center> <br>
		<div class="text-center">
			<button class="btn btn-primary">Register</button>
        </div><br>
        </form>

        <div class="text-center">
            <a href="login.php"><button class="btn btn-secondary">Login</button></a>
        </div>
    </div>
<br><br><br>
<?php include("account_footer.php") ?>
</body>
</html>
