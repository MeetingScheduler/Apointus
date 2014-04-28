<?php

  session_start();
  $connect = mysql_connect('localhost', 'root', '') or die('Cannot connect to server.');
  mysql_select_db('appointus', $connect) or die('Could not connect to database');

  $email = $_POST['email'];
  $password = $_POST['password'];

  $user = mysql_query("SELECT * FROM user WHERE Email = '$email' AND Password = '$password' LIMIT 1");
  if (mysql_num_rows($user) == 0) {
    header('location: ../html/signup.php');
  } else {
    $user = mysql_fetch_assoc($user);
    $_SESSION['user_id'] = $user['User_id'];
    echo "successful";
    header('location: ../profile.php');
  }

?>
