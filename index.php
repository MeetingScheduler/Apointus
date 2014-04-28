<!DOCTYPE html>
<html>
  	<?php
	    session_start();
	    require_once ( 'dbconfig.php');

		if(isset($_POST['login'])) { 
			$email=$_POST["email"];
			$password=$_POST["password"];

			if (!$email || !$password) {
				// die("<html><br><p>You need to provide an <br> Email_Address and Password.</p></html>");
				echo '<div class="alert alert-danger" id="mydiv" style="position:absolute;width:100%;">
						You need to provide a valid Email_Address and Password.</div>';
			}
			$query = "SELECT * FROM user WHERE Email ='$email' AND Password='$password'";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);

			if(!$row) { 
				echo '<div class="alert alert-danger" id="mydiv" style="position:absolute;width:100%;">Login not successful</div>';  
			} else { 
				$_SESSION['user_id'] = $row['User_id'];
				$_SESSION['username'] = $row['Username'];
				$_SESSION['fname'] = $row['Firstname'];
				$_SESSION['lname'] = $row['Lastname'];
				$_SESSION['email_ad'] = $row['Email'];
				$_SESSION['password'] = $row['Password'];
				$_SESSION['contact_no'] = $row['contact_number']; 

				header("Location: profile.php");
			}
		}
	?>
	<head>
		<title>Appointus</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    	<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/bootstrap.min.js"></script> 
		<script src="js/loginBox.js"></script>
    	<script src="js/log_in.js"></script> 
    	<script src="js/jquery.js"></script>

	</head>
	<body>
		<div id= "wrapper">
			<div id = "sign-in"> 
				<a href="#" id = "login-button" style = "text-decoration: none; "> <h1> SIGN IN </h1></a>
				<div id = "login-box">
					<form id="login-form" action= "index.php" method= "POST">
			            <fieldset id="body">
			            	<fieldset> 
			            			<h3 class = "labelfield">Email Address:</h3>
			                	<input type="text" name="email" id="email" placeholder= "Email Address" required/>
			              	</fieldset>

			              	<fieldset> 
			              		<h3 class = "labelfield">Password:</h3>
			                	<input type="password" name="password" id="password" placeholder = "Password" required/>
			              	</fieldset>
			            	</fieldset>
			            	<input type="submit" id="login" name = "login" value="Sign in"/>
		        </form> 
				</div>
			</div>
			<div id = "here">
				<img id = "logo" src="img/1.jpg">

				<div id = "content">
					<p id = "tired">Tired of Scheduling Problems?</p>
					<br>
					<a href="signup.php"><p id = "register">REGISTER NOW!</p></a>
				</div>
			</div>

		</div> <!--end of wrapper -->
	</body>

<script type="text/javascript">
setTimeout(function() {
  $("#mydiv").fadeOut(); 
}, 1000);   
</script> 
</html>