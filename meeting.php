 <!DOCTYPE html>
<html lang="en">
 <?php
    ob_start();
    session_start();
    include ("dbconfig.php");
    $_SESSION['con'] = $con;

    if(isset($_POST['SaveTime'])) { 
      if (isset($_POST['chosenTime'])) {
        list($day,$time) = explode(',', $_POST['chosenTime']);
        list($stime,$etime) = explode('-', $time);
        $_SESSION['mstartime'] = $stime;

        list($date,$time) = explode('T', $_SESSION['datetime']);
        $_SESSION['datetime'] = $date." ".$stime;
        postMeeting($_POST['chosenTime']);
      }
    } else {
      // echo 'no savetime';
    }
    
    if(isset($_POST['SaveMeeting'])) {
      if ($_POST['attendees']) {
        $_SESSION['title'] = $_POST['Title'];
        $_SESSION['venue'] = $_POST['Venue'];
        $_SESSION['desc'] = $_POST['Description'];
        $_SESSION['dur'] = $_POST['Duration'];
        $_SESSION['datetime'] = $_POST['Datetime'];

        $users = $_POST['attendees'];
        $scheds = array();
        $_SESSION['attendees_id'] = array();
        $j = 0;
        foreach($users as $user) {
          $sq = "SELECT * FROM user WHERE Username = '$user'";
          $result = mysqli_query($con,$sq);
          $row = mysqli_fetch_array($result);
          array_push($_SESSION['attendees_id'], $row['User_id']);
          $sq2 = "SELECT * FROM schedule WHERE User_id = '$row[User_id]'";
          $result2 = mysqli_query($con,$sq2);
          while ($row2 = mysqli_fetch_array($result2)) {
            $scheds[$j][0] = $row2['Day'];
            $scheds[$j][1] = $row2['StartTime'];
            $scheds[$j][2] = $row2['EndTime'];
            $j = $j + 1;
          }
        }   
          list($date,$time) = explode('T', $_SESSION['datetime']);

          $timestamp = strtotime($date);
          $_SESSION['day'] = date('l', $timestamp);
          $_SESSION['mstartime'] = strtotime($time);
          $dur = getDuration($_POST['Duration'], $_POST['Unit']);
          $etime = strtotime("+$dur minutes", $_SESSION['mstartime']); 
          $_SESSION['mEndtime'] = date('g:i a', $etime);
          $_SESSION['mstartime'] = date('g:i a', $_SESSION['mstartime']);
          $stime = $_SESSION['mstartime'];
          $etime = $_SESSION['mEndtime'];
          $choices = array();
          $altime = strtotime("6:00 am");
          $nxtstop = strtotime("+$dur minutes", $altime);
          $j = 0;
          $noSuggestions = false;
          $noConflict = true;
          while ($altime >= strtotime("6:00:00") && $nxtstop <= strtotime("23:59:00")) {
            for ($i=0; $i < count($scheds); $i++) {  // loop thru schedules
              $startime = strtotime($scheds[$i][1]);
              $endtime = strtotime($scheds[$i][2]);
              $meetingstartime = strtotime($_SESSION['mstartime']);
              $meetingEndtime = strtotime($_SESSION['mEndtime']);
              if ($_SESSION['day'] == $scheds[$i][0] && isConflict($meetingstartime, $meetingEndtime, $startime, $endtime) == 1) {
                $noConflict = false;
                $nxtstart = strtotime("+ 30 minutes", $altime);
                $_SESSION['mstartime'] = date('g:i a', $nxtstart);
                $altime = strtotime($_SESSION['mstartime']);
                $nxtstop = strtotime("+$dur minutes", $altime);
                $_SESSION['mEndtime'] = date('g:i a', $nxtstop);
                $i = -1;
                if (!($altime >= strtotime("6:00 am") && $nxtstop <= strtotime("11:59 pm"))) {
                    break;
                }
              }
            }
            if ($j == 0 && $noConflict == true) {
              $noSuggestions = true;
              break;
            }

            $choices[$j][0] = $_SESSION['day'];
            $choices[$j][1] = $_SESSION['mstartime'];
            $choices[$j][2] = $_SESSION['mEndtime'];
            $j = $j + 1;
            $nxtstart = strtotime("+ 30 minutes", $altime);
            $_SESSION['mstartime'] = date('g:i a', $nxtstart);
            $altime = strtotime($_SESSION['mstartime']);
            $nxtstop = strtotime("+$dur minutes", $altime);
            $_SESSION['mEndtime'] = date('g:i a', $nxtstop);
          }
          
          if ($noSuggestions == true) {
            $noSuggestions = false;
            $chosenDatetime = $_SESSION['day'].','.$stime.'-'.$etime;
            postMeeting($chosenDatetime);
            // echo "no suggestions";
          } else {
            if (count($choices) == 0) {
              echo ' 
              <div class = "overlay">
              <div class = "alert alert-info" style = "top:40%;left:42%;position:fixed;">
              <h text-align="center"><em>No common time.</em></h><br>
              <img src = "img/sd.jpg" class="img-circle" style = "  height: 90px;
              width: 100px;padding:10px;"><br>
              <button type="button" 
              class="btn btn-block btn-sm" onclick = "window.setTimeout(history.back(),0);" aria-hidden="true">Reschedule</button>              </div></div>';  
            } else {
              echo '<html> 
              <div class = "overlay" >
              <div class="modal-dialog" style="position:fixed;right:20%;left:20%;">
              <div class="modal-content">
              <div class="modal-header" style="background-color:lightgray;"> 
              <h class="modal-title" id="myModalLabel" style="font-family:Century Gothic;">Time conflicts figured. Select among these suggested time.</h></div>
              <div class="modal-body" style="height:400px;overflow:auto;"> 
              <form class="form-horizontal" action = "meeting.php" method="post" enctype="multipart/form-data" >';
              echo'<table class="table table-hover">';
              for ($i=0; $i < count($choices); $i++) {
                echo'
                <tr><td>
                <div class="radio">
                <label>
                <input type="radio" name="chosenTime" id="'.$i.'" value="'.$choices[$i][0].' , '.$choices[$i][1].' - '.$choices[$i][2].'" style = "cursor: pointer" checked>
                '.$choices[$i][0].' , '.$choices[$i][1].' - '.$choices[$i][2].'              
                </label>
                </div>
                </td></tr> '; 
              }
              echo '</table> 
              </div>
              <div class="modal-footer" style="background-color:lightgray;"> 
              <button type = "submit" class="btn" name="SaveTime" 
              style = "cursor: pointer;background-color: #333333;color:white;font-family:Century Gothic;">Save Time</button>
              </div></form></div></div></div></html>';
            }      
          }
        } 
      } else {
        // echo 'else here';
      }  

    function postMeeting($chosenDatetime) {

      $_SESSION['datetime'] = date('Y-m-d H:i:s', strtotime($_SESSION['datetime']));
      echo '<html> 
            <div class = "overlay"> 
              <div class="modal-dialog" style="position:fixed;top:15%;right:20%;left:20%;font-family:Century Gothic;">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #333333;color:white;">
                  <i class="glyphicon glyphicon-briefcase white"></i> Appointment Set
                  <a href = "profile.php" class="pull-right" style="color:white;">
                    <i class="glyphicon glyphicon-home white" class="btn"></i> Home</a> 
                  </div>
                  <div class="modal-body"> 
                  <table class = "table table-hover">
                    <tr class="success"><td width="30%">What :</td><td>'.$_SESSION['title'].'</td></tr>
                    <tr><td>Agenda :</td><td>'.$_SESSION['desc'].'</td></tr>
                    <tr class="success"><td>Where :</td><td>'.$_SESSION['venue'].'</td></tr>
                    <tr><td>When :</td><td>'.$chosenDatetime.'</td></tr>
                  </table>  
                     </div>  
                </div>
              </div> 
            </div>
            </html>';
      $sql="INSERT INTO meeting (Title, Venue, Description, Duration, Datetime, User_id)
      VALUES ('$_SESSION[title]','$_SESSION[venue]','$_SESSION[desc]','$_SESSION[dur]','$_SESSION[datetime]', '$_SESSION[user_id]')";
  
      if (!mysqli_query($_SESSION['con'],$sql)) {
        die('Error: ' . mysqli_error($_SESSION['con']));
      } else {
        // echo "inserted!";
      }

      $id = mysqli_insert_id($_SESSION['con']);
      // echo 'meeting id: '.$id;
      $sql = "INSERT INTO attendance (M_ID, User_id) Values ($id, $_SESSION[user_id])";
        if (!mysqli_query($_SESSION['con'],$sql)) {
          die('Error: ' . mysqli_error($_SESSION['con']));
        } else {
          // echo "inserted a_id!";
        }
      foreach($_SESSION['attendees_id'] as $a_id) {
        $sql = "INSERT INTO attendance (M_ID, User_id) Values ($id, $a_id)";
        if (!mysqli_query($_SESSION['con'],$sql)) {
          die('Error: ' . mysqli_error($_SESSION['con']));
        } else {
          // echo "inserted a_id!";
        }
      }

      $sql="INSERT INTO notification (Event, Event_ID, User_id)
      VALUES ('meeting', $id, $_SESSION[user_id])";
  
      if (!mysqli_query($_SESSION['con'],$sql)) {
        die('Error: ' . mysqli_error($_SESSION['con']));
      } else {
        // echo "inserted!";
      }

      $id2 = mysqli_insert_id($_SESSION['con']);
      foreach($_SESSION['attendees_id'] as $a_id) {
        $sql="INSERT INTO notify (N_ID, User_id, Response, HasResponded)
              VALUES ($id2, $a_id, 'ACCEPT', 'NO')";
    
        if (!mysqli_query($_SESSION['con'],$sql)) {
          die('Error: ' . mysqli_error($_SESSION['con']));
        } else {
          // echo "inserted!";
        }
      }
      
    }

    function getDuration($dur, $unit) {
      if ($unit == "minutes") {
        return $dur;
      } else if ($unit == "hours") {
        return ($dur*60);
      } else if ($unit == "days") {
        return ($dur*1440);
      }
    }

    function isConflict($chkStartTime, $chkEndTime, $startTime,  $endTime) {
      if($chkStartTime > $startTime && $chkEndTime < $endTime) {
        // Check time is in between start and end time
        // echo "1 Time is in between start and end time";
        return true;
      } elseif(($chkStartTime > $startTime && $chkStartTime < $endTime) || ($chkEndTime > $startTime && $chkEndTime < $endTime)) {
        // Check start or end time is in between start and end time
        // echo "2 ChK start or end Time is in between start and end time";
        return true;
      } elseif($chkStartTime==$startTime || $chkEndTime==$endTime) {
        // Check start or end time is at the border of start and end time
        // echo "3 ChK start or end Time is at the border of start and end time";
        return true;
      } elseif($startTime > $chkStartTime && $endTime < $chkEndTime) {
        // start and end time is in between  the check start and end time.
        // echo "4 start and end Time is overlapping  chk start and end time";
        return true;
      } else {
        // echo "not conflict";
        return false;
      }

    }

 ?>
  <head>   
  <title>Appointus - appointMe</title>
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
      background-color: rgba(0,0,0,0.8);      
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
      <form class="form-horizontal" action = "meeting.php" method="post" enctype="multipart/form-data" >
        <div class="panel" style = "box-shadow: 10px 10px 6px 0px #888888;">
          <!-- style = "overflow:auto;height:650px;" -->
          <div class="panel-heading" style="background-color: #333333;">
          <h3 class="panel-title" style="color:white;font-family:Century Gothic"> 
          <i class="glyphicon glyphicon-briefcase"></i> appointMe</h3>
        </div>
        <div class="panel-body">
          <input type="text" id="meetingtitle" value="" placeholder="Title" name="Title" class="form-control" required autofocus><br>
          <textarea id="meetingdesc" value="" placeholder="Description" name="Description" class="form-control" required></textarea><br>
          <input type="text" id="meetingvenue" value="" placeholder="Venue" name="Venue" class="form-control" required><br>
          <div class="row"> 
            <div class="col-xs-3"> 
              <input type="text" class="form-control" placeholder="Duration" name="Duration" required>
            </div>
            <div class="col-xs-3"> 
              <select type="text" class="form-control" name="Unit" required>
                <option>minutes</option>
                <option>hours</option>
                <option>days</option> 
                </select> 
            </div>
            <div class="col-xs-6"> 
              <input type="datetime-local" class="form-control" name="Datetime" required>
            </div>
            </div> 
            <br>     
             <div class="panel-group" id="accordion">
              <div class="panel">
                <div class="panel-heading"> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#">
                    <i class="glyphicon glyphicon-plus"></i> Add attendee
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
                          echo '<tr class = "active"><td width = "10%"><input type="checkbox" name="attendees[]" value="'.$row['Username'].'"></td>
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
            <button type = "submit" class="btn" name="SaveMeeting" style = "cursor: pointer;background-color: #333333;color:white;">Save changes</button>
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