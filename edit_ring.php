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
          <i class="glyphicon glyphicon-dashboard white"></i>
          Edit Ring</h3>
        </div>
        <div class="panel-body"> 
          <?php
          //  echo '<h4> Ring Name: </h4>'.$_SESSION[''];
          ?>
         <!--  <input type="text" placeholder="Ring Name" name="ringname" class="form-control" required autofocus><br> -->
          <!-- <p style="font-family:Century Gothic;">Search for ring mates:</p> -->
          <input type="text" placeholder="Name Search" name="members" class="form-control"><br>
          <div class="row"> 
          </div> 
              
             <div class="panel-group" id="accordion">
              <div class="panel">
                <div class="panel-heading"> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#">
                    <i class="glyphicon glyphicon-plus"></i> Add Members
                    </a> 
                </div>  
                <div id="collapseOne" class="panel-collapse collapse in">
                  <div class="panel-body">
                  <div style = "height:100px;overflow:auto;" > 
                    <table class="table table-hover">
                    <?php
                      $sql = "SELECT * FROM user WHERE User_id NOT IN (SELECT user.User_id FROM user INNER JOIN membership ON user.User_id = membership.User_id WHERE membership.R_ID = " . $_GET['ring_id'] . ")";
                      $result = mysqli_query($con,$sql);
                      while($row = mysqli_fetch_array($result)) {
                        if ($row['User_id'] != $_SESSION['user_id']) {
                          echo '<tr class = "active"><td width = "10%"><input type="checkbox" name="members['.$row['User_id'].']" value="'.$row['Username'].'"></td>
                              <td>'.$row['Username'].'</td></tr>';
                        }

                        
                      }
                    ?> 
                     </table>
                    </div>
                  </div>
                </div>
              </div> 
            </div>  

          <div class="modal-footer" style = "font-family:Century Gothic;">
            <a href="profile.php" class="btn" style="cursor: pointer;background-color:#D3D3D3">Cancel</a>
            <input type="hidden" name="ring_id" value="<?php echo $_GET['ring_id']; ?>">
            <button type = "submit" class="btn" name="SaveMember" style = "cursor: pointer;background-color: #333333;color:white;">Save changes</button>
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