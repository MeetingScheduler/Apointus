<?php
	session_start(); 
	include("../dbconfig.php");
	$user_id = $_SESSION['user_id'];

	$user_name = $_POST['username'];
	$email = $_POST['email'];
  	$password = $_POST['password'];
  	$confirm_password = $_POST['confirmPassword'];
  	$contactnumber = $_POST['contactnumber'];
  	$profpic = $_FILES['profpic']; 
  	$filename = $profpic['name'];
  	$temp = $profpic['tmp_name'];

  	// $user = mysqli_query($con, "SELECT * FROM user WHERE User_id = '$user_id'");
  	// $user = mysqli_fetch_assoc($user);
  	// $user_password= $user['Password'];
  	// echo $user_password;
  	// echo $_POST['confirmPassword'];

  	if($password == $confirm_password) {
	  	if(empty($filename)){
	  		$update_query = mysqli_query($con,"UPDATE user SET Username= '$user_name', Email= '$email', Password= '$password', contact_number= '$contactnumber' WHERE User_id = '$user_id'");
		} else {
			$update_query = mysqli_query($con,"UPDATE user SET Username= '$user_name', Email= '$email', Password= '$password', contact_number= '$contactnumber', Profile_picture= '$filename' WHERE User_id = '$user_id'");
			move_uploaded_file($temp, '../img/'.$filename);
		}
	} else if($password != $confirm_password) {
	
		echo '<div class="alert alert-danger" id="mydiv" style="position:absolute;width:100%;">
						Passwords do not match.</div>';
	}


	mysqli_close($con);
	header('location: ../profile.php');
	//until here
?>