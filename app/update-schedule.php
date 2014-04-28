<?php
	session_start(); 
	include("dbconfig.php");
	$user_id = $_SESSION['user_id'];
    $user = mysql_query("SELECT * FROM users WHERE User_id = $user_id LIMIT 1");
    $user = mysql_fetch_assoc($user);
	$day = $_POST['day'];
	$stime = $_POST['stime'];
  	$etime = $_POST['etime'];
	$update_query = mysql_query("UPDATE schedule SET Day= '$day', StartTime= '$stime', EndTime= '$etime' WHERE User_id = '$user_id'");

?>