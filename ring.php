 <!DOCTYPE html>
<html lang="en">
 <?php
    ob_start();
    session_start();
    include ("dbconfig.php");

    if (isset($_POST['SaveRing'])) {
        if ($_POST['members']) {
          $members = $_POST['members'];
          $_SESSION['members_id'] = array();
          $sql="INSERT INTO ring (Ringname, User_id)
                VALUES ('$_POST[ringname]','$_SESSION[user_id]')";
          if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($_SESSION['con']));
          } else {
            // echo "ring inserted!";
          }
          $id = mysqli_insert_id($con);
          $sql="INSERT INTO membership (R_ID, User_id)
                VALUES ($id, $_SESSION[user_id])";
            if (!mysqli_query($con,$sql)) {
              die('Error: ' . mysqli_error($con));
            } else {
              // echo "membership success!";
            }
           // echo 'ring id: '.$id;
          foreach ($members as $member) {
            $sql = "SELECT * FROM user WHERE Username = '$member'";
            $result = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($result);
            array_push($_SESSION['members_id'], $row['User_id']);
            $sql="INSERT INTO membership (R_ID, User_id)
                VALUES ($id, $row[User_id])";
            if (!mysqli_query($con,$sql)) {
              die('Error: ' . mysqli_error($con));
            } else {
              // echo "membership success!";
            }
          }
          $sql="INSERT INTO notification (Event, Event_ID, User_id)
            VALUES ('membership', $id, $_SESSION[user_id])";
        
            if (!mysqli_query($con,$sql)) {
              die('Error: ' . mysqli_error($con));
            } else {
              // echo "inserted!";
            }

            $id2 = mysqli_insert_id($con);
            foreach($_SESSION['members_id'] as $m_id) {
              $sql2="INSERT INTO notify (N_ID, User_id, Response, HasResponded)
                    VALUES ($id2, $m_id, 'ACCEPT', 'NO')";
          
              if (!mysqli_query($con,$sql2)) {
                die('Error: ' . mysqli_error($con));
              } else {
                // echo "inserted!";
              }
            }
          echo '<html> 
            <div class = "overlay"> 
              <div class="modal-dialog" style="position:fixed;top:15%;right:20%;left:20%;font-family:Century Gothic;">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #333333;color:white;">
                  <i class="glyphicon glyphicon-briefcase white"></i> Success
                  <a href = "profile.php" class="pull-right" style="color:white;">
                    <i class="glyphicon glyphicon-home white" class="btn"></i> Home</a> 
                  </div>
                  <div class="modal-body"> 
                  <table class = "table table-hover">
                    <tr class="success"><td width="30%">Ring Created Successfully!</td></tr>
                  </table>  
                     </div>  
                </div>
              </div> 
            </div>
            </html>';
        }
    }
  
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
      <form class="form-horizontal" action = "ring.php" method="post" enctype="multipart/form-data" >
        <div class="panel" style = "box-shadow: 10px 10px 6px 0px #888888;">
          <!-- style = "overflow:auto;height:650px;" -->
          <div class="panel-heading" style="background-color: #333333;">
          <h3 class="panel-title" style="color:white;font-family:Century Gothic"> 
          <i class="glyphicon glyphicon-dashboard white"></i>
          Add Ring</h3>
        </div>
        <div class="panel-body"> 
          <input type="text" placeholder="Ring Name" name="ringname" class="form-control" required autofocus><br>
          <p style="font-family:Century Gothic;">Search for ring mates:</p>
          <input type="text" placeholder="Name Search" name="Title" class="form-control"><br>
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
                      $sql = "SELECT * FROM user";
                      $result = mysqli_query($con,$sql);
                      while($row = mysqli_fetch_array($result)) {
                        if ($row['User_id'] != $_SESSION['user_id']) {
                          echo '<tr class = "active"><td width = "10%"><input type="checkbox" name="members[]" value="'.$row['Username'].'"></td>
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
            <button type = "submit" class="btn" name="SaveRing" style = "cursor: pointer;background-color: #333333;color:white;">Save changes</button>
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