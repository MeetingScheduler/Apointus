<?php
$con=mysqli_connect("localhost","root","","appointus");
 if (mysqli_connect_errno($con)) {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
  } 
 //else {
 // 	echo "connected!";
 // }
 ?>