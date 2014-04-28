<!DOCTYPE html> 
<html>
<?php

  ob_start();
  session_start();
  include ("dbconfig.php");

  if(isset($_POST["signup"])) { 

	$username = $_POST['Username'];
	$email = $_POST['Email'];
	$password = $_POST['Password'];
	$confirm_password = $_POST['ConfirmPassword'];
	$firstname = $_POST['Firstname'];
	$lastname = $_POST['Lastname'];
	$contactnumber = $_POST['contact_number'];
	$profpic = $_FILES['photo']; 
  	$filename = $profpic['name'];
  	$temp = $profpic['tmp_name'];


	move_uploaded_file($temp, 'img/'.$filename);

	$username_duplicate = mysqli_query($con,"SELECT * FROM user WHERE Username = '$username' LIMIT 1");
  	$username_duplicate_num = mysqli_num_rows($username_duplicate);

  	$email_duplicate = mysqli_query($con,"SELECT * FROM user WHERE Email = '$email' LIMIT 1");
  	$email_duplicate_num = mysqli_num_rows($email_duplicate);

  	// echo $username_duplicate_num;
  	// echo $email_duplicate_num;
  	if ( $username_duplicate_num == 1) {
    	// echo '<p id= username-error>username is already taken</p>';
  		echo '<div class="alert alert-danger" id="mydiv" style="position:absolute;width:100%;">
						Username is already taken.</div>';
  	} else if($email_duplicate_num == 1) {
  		// echo '<p id= email-error>email address is already taken</p>';
  		echo '<div class="alert alert-danger" id="mydiv" style="position:absolute;width:100%;">
						Email Address is already taken.</div>';

  	} else if($password != $confirm_password) {
  		// echo '<p id= password-match-error> passwords does not match </p>';
  		echo '<div class="alert alert-danger" id="mydiv" style="position:absolute;width:100%;">
						Passwords do not match.</div>';
  	} else {

	$sql="INSERT INTO user (Username, Password, Firstname, Lastname, Email, contact_number, Profile_picture) VALUES ('$username', '$password', '$firstname', '$lastname', '$email', '$contactnumber', '$filename' )";
	$result = mysqli_query($con,$sql);

	$query = "SELECT * FROM user WHERE Email='$email'";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_array($result);
	$_SESSION['user_id'] = $row['User_id'];
	header('location: profile.php');

	}

	
  }
 
?>
	<head>
		<title>Sign Up</title>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<link rel="stylesheet" href="css/demo.css">
		<link rel="stylesheet" href="css/sky-forms.css">
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    	
		<script src="js/bootstrap.min.js"></script> 
    <script src="js/jquery.js"></script>

		
	</head>
	<body class="bg-cyan">
		<div id = "sheader">
			<img id = "logo" src="img/appointus-final-logo.png"/>
		</div>

		<div id = "divider">
		</div>

		<div class="body body-s">
		
			<form class="sky-form" action= "signup.php" method= "POST" enctype="multipart/form-data">
				<header>Registration form</header>
				
				<fieldset>					
					<section>
						<label class="input">
							<i class="icon-append icon-user"></i>
							<input type="text" placeholder="Username" name="Username" id= "username" required>
							<b class="tooltip tooltip-bottom-right">Only latin characters and numbers</b>
						</label>
					</section>
					
					<section>
						<label class="input">
							<i class="icon-append icon-envelope-alt"></i>
							<input type="text" placeholder="Email address" name="Email" id= "email" required>
							<b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
						</label>
					</section>
					
					<section>
						<label class="input">
							<i class="icon-append icon-lock"></i>
							<input type="password" placeholder="Password" name="Password" id="password" required>
							<b class="tooltip tooltip-bottom-right">Only latin characters and numbers</b>
						</label>
					</section>
					
					<section>
						<label class="input">
							<i class="icon-append icon-lock"></i>
							<input type="password" placeholder="Confirm password" name="ConfirmPassword" required>
							<b class="tooltip tooltip-bottom-right">Only latin characters and numbers</b>
						</label>
					</section>
				</fieldset>
					
				<fieldset>
					<div class="row">
						<section class="col col-6">
							<label class="input">
								<input type="text" placeholder="First name" name="Firstname" id= "firstname" required>
							</label>
						</section>
						<section class="col col-6">
							<label class="input">
								<input type="text" placeholder="Last name" name="Lastname" id= "lastname" required>
							</label>
						</section>
					</div>
					<section>
						<label class="input">
							<i class="icon-append icon-lock"></i>
							<input type="text" placeholder="Contact Number" name="contact_number" id="contactnumber">
						</label>
					</section>	
					<section>
						<label class="input"> 
							<input type="file" id="" value="" name = "photo" size = "20">
						</label>
					</section>				

				</fieldset>
				<footer>
					<button type="submit" name="signup" class="button">Submit</button>
				</footer>
			</form>
			
		</div>
	</body>
</html>