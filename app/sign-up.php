<?php

  session_start();
  include ("dbconfig.php");
  echo $_POST['Username']."hello";
  $_SESSION['name'] = $_POST['Username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $contactnumber = $_POST['contactnumber'];
  $profpic = $_FILES['profpic']['name'];

  $query = mysql_query("SELECT * FROM user WHERE Username = '$_SESSION[name]' OR Email = '$email' LIMIT 1");
  $row = mysql_num_rows($query);
  if ( $row == 1) {

    //header('location: ../html/signup.php');
    echo 'username is already taken';
  } else {
    echo 'take a look again';
  $sql="INSERT INTO user(Username, Password, Firstname, Lastname, Email, contact_number) VALUES ('$_SESSION[name]', '$password', '$firstname', '$lastname', '$email', '$contactnumber')";

  $result = mysql_query($sql) or die (mysql_error());
  $user = mysql_fetch_array($result);
  $_SESSION['user_id'] = mysql_insert_id();
      // if (!mysql_query($sql)) {
      //   die('Error: ' . mysql_error($connect));
      // }
  header('location: profile.php');
  }

?>