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
        <!-- <img src="images/<?php echo $_SESSION['pp']; ?>" width="100%" id = "profile-picture" > -->
        <div class="dropdown" id = "settings">
            <a href="profile.php"> <h1 id = "profile-name"><?php echo $_SESSION['username']; ?></h1> </a>
          </div>
    
      </div> <!--end of account-greeting -->
    </div> <!-- end of header -->

    <div id = "divider">
    </div> <!-- end of divider -->
  <div class="row"><br>   
    <div class = "col-md-6 col-md-offset-3" >
      <form class="form-horizontal" action = "addmember.php" method="post" enctype="multipart/form-data" >
        <div class="panel" style = "box-shadow: 10px 10px 6px 0px #888888;">
          <!-- style = "overflow:auto;height:650px;" -->
          <div class="panel-heading" style="background-color: #333333;">
          <h3 class="panel-title" style="color:white;font-family:Century Gothic"> 
          <i class="glyphicon glyphicon-user"></i>
          My Profile</h3>
        </div>
        <div class="panel-body"> 
          <?php
          //  echo '<h4> Ring Name: </h4>'.$_SESSION[''];
          ?>
         <!--  <input type="text" placeholder="Ring Name" name="ringname" class="form-control" required autofocus><br> -->
          <!-- <p style="font-family:Century Gothic;">Search for ring mates:</p> -->
          <!-- <input type="text" placeholder="Name Search" name="members" class="form-control"><br> -->
          <div class="row"> 
          </div> 
              
             <div class="panel-group" id="accordion">
              <div class="panel-heading"> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#">
                    First Name: <span class = "elements"><?php echo $_SESSION['fname'] ?></span> </br>

                    </a> 
                </div>  
                <div class="panel-heading"> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#">
                    Last Name: <span class = "elements"><?php echo $_SESSION['lname'] ?> </span></br>
                    </a> 
                </div>  
                <div class="panel-heading"> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#">
                    Contact Number: <span class = "elements"><?php echo $_SESSION['contact_no'] ?></span> </br>
                    </a> 
                </div>
                <div class="panel-heading"> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#">
                    Email Address: <span class = "elements"><?php echo $_SESSION['email'] ?> </span></br>
                    </a> 
                </div>     
            </div>  

          <div class="modal-footer" style = "font-family:Century Gothic;">
            <a href="profile.php" class="btn" style="cursor: pointer;background-color:#D3D3D3">Cancel</a>
            <a href="editprofile.php" class="btn" style="cursor: pointer;background-color:#686868 ">Edit Profile</a>
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