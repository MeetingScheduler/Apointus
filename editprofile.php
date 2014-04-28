 <!DOCTYPE html>
<html lang="en">
 <?php
    ob_start();
    session_start();
    include ("dbconfig.php");
  
 ?>
  <head>   

  <meta charset = "c">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/profile.css" rel="stylesheet" media="screen"> 
    <link rel="stylesheet" type="text/css" href="css/myprofile.css">
     
    <script src="js/ajax.googleapis.com.ajax.libs.jquery.1.4.2.jquery.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>   

    <style type="text/css">
    ::-webkit-scrollbar {width: 6px; height: 4px; background-color:white; }
    ::-webkit-scrollbar-thumb { background-color:#D3D3D3; -webkit-border-radius: 1ex; }
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      z-index: 10;
      background-color: rgba(0,0,0,0.5);      
    }
    </style> 
  </head>

 <body>
  <div id = "header">
      <img id = "logo" src="img/appointus-final-logo.png"/>

      <div id = "account-greeting">
        <img id = "profile-picture" src ="img/default-profile-pic.png"/>
       
        <div class="dropdown" id = "settings">
            <a href="profile.php"> <h1 id = "profile-name"><?php echo $_SESSION['username']; ?></h1> </a>
          </div>
    
      </div> <!--end of account-greeting -->
    </div> <!-- end of header -->

    <div id = "divider">
    </div> <!-- end of divider -->
  <div class="row"><br>   
    <div class = "col-md-6 col-md-offset-3" >
      <form id = "editprofile" action= "app/update-profile.php" method="POST" enctype="multipart/form-data"> <!-- here pud Celle -->
        <div class="panel" style = "box-shadow: 10px 10px 6px 0px #888888;">
          <div class="panel-heading" style="background-color: #333333;">
            <h3 class="panel-title" style="color:white;font-family:Century Gothic"> 
          <i class="glyphicon glyphicon-user"></i>
          Edit Profile</h3>
          </div>
           <div class="row"> 
          </div> 

          <div class="panel-group" id="accordion">
            <div class="modal-body" id = "prof-content">
               <div class="form-group">
              <br>
                <label for="user_name">Username</label>
                <input type="text" class="form-control" id="user_name" name= "username" 
                value= <?php $_SESSION['username'] = str_replace(' ', '&nbsp;', $_SESSION['username']);
                echo $_SESSION['username']; ?>>            
              </div>

          <div class="form-group">
                <label for="con_num">Contact Number</label>
                <input type="tel" class="form-control" id="con_num" name= "contactnumber" 
                value= <?php $_SESSION['contact_no'] = str_replace(' ', '&nbsp;', $_SESSION['contact_no']);
                echo $_SESSION['contact_no']; ?>> 
              </div>

             <div class="form-group">
              <label for="emailad">Email address</label>
              <input type="email" class="form-control" id="emailad" name="email"
              value= <?php $_SESSION['email'] = str_replace(' ', '&nbsp;', $_SESSION['email']);
                  echo $_SESSION['email']; ?>> 
               
              </div>

              <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
              </div>

              <div class="form-group">
              <label for="confirmPassword">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Password"required>
              </div>
              
              <div class="form-group">
              <label for="profpic">Change Profile Picture</label>
              <input type="file" id="profpic" name="profpic">
              </div>
              <!-- <input type="submit" name="submit" value="Submit"> -->
              <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" style = "background-color:#D3D3D3">Close</button> 
          <button type = "submit" name = "submit" value = "Submit" class = "btn btn-primary" style = "background-color:#686868">Save Changes</button>
          <!-- <input type="submit" name="submit" value="Save Changes"> -->
          </div>
            </div>
          </div>

           
        </div>
          
        </form>
    </div>
  </div>
<script type="text/javascript">
setTimeout(function() {
  $("#mydiv").fadeOut(); 
}, 3000);   
</script>  
</body>
</html>