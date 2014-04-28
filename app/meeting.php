 <!DOCTYPE html>
<html lang="en">
 <?php
    ob_start();
    session_start();
    include ("dbconfig.php");
    
    if(isset($_POST['SaveMeeting'])) {
      if ($_POST['attendees']) {
        $users = $_POST['attendees'];
        $scheds = array();
        $j = 0;
        foreach($users as $user) {
          $sq = "SELECT * FROM user WHERE Username = '$user'";
          $result = mysql_query($con,$sq);
          $row = mysql_fetch_array($result);
          $sq2 = "SELECT * FROM schedule WHERE U_ID = '$row[U_ID]'";
          $result2 = mysql_query($con,$sq2);
          while ($row2 = mysql_fetch_array($result2)) {
            $scheds[$j][0] = $row2['Day'];
            $scheds[$j][1] = $row2['StartTime'];
            $scheds[$j][2] = $row2['StartPeriod'];
            $scheds[$j][3] = $row2['EndTime'];
            $scheds[$j][4] = $row2['EndPeriod'];
            // echo $scheds[$j][0].','.$scheds[$j][1].' '.$scheds[$j][2].'-'.$scheds[$j][3].' '.$scheds[$j][4];
            // echo '<br>';
            $j = $j + 1;
          }
      }   
          $timestamp = strtotime($_POST['MDate']);
          $day = date('l', $timestamp);

          $_SESSION['mstartime'] = strtotime($_POST['Time']);
          $dur = getDuration($_POST['Duration'], $_POST['Unit']);
          echo "duration: ".$dur;
          $etime = strtotime("+$dur minutes", $_SESSION['mstartime']); 
          $_SESSION['mEndtime'] = date('g:i a', $etime);
          $_SESSION['mstartime'] = date('g:i a', $_SESSION['mstartime']);
          // echo '<br>meeting schedule: ';
          // echo $day.','.$_SESSION['mstartime'].'-'.$_SESSION['mEndtime'].'<br><br>';  
           // echo 'scheds--------<br>';
          $altime = strtotime("6:30 am");
          for ($i=0; $i < count($scheds); $i++) { 
            $startime = strtotime($scheds[$i][1]." ".$scheds[$i][2]);
            $endtime = strtotime($scheds[$i][3]." ".$scheds[$i][4]);
            $meetingstartime = strtotime($_SESSION['mstartime']);
            $meetingEndtime = strtotime($_SESSION['mEndtime']);
            // echo $scheds[$i][0].','.$scheds[$i][1].' '.$scheds[$i][2].'-'.$scheds[$i][3].' '.$scheds[$i][4].': isConflict = ';
            if ($day == $scheds[$i][0] && isConflict($meetingstartime, $meetingEndtime, $startime, $endtime) == 1) {
              // echo 'true!';
              $nxtstart = strtotime("+ 30 minutes", $altime);
              $_SESSION['mstartime'] = date('g:i a', $nxtstart);
              $altime = strtotime($_SESSION['mstartime']);
              $nxtstop = strtotime("+$dur minutes", $altime);
              $_SESSION['mEndtime'] = date('g:i a', $nxtstop);
              // echo '<br>try: '.$day.','.$_SESSION['mstartime'].'-'.$_SESSION['mEndtime'];
              $i = -1;
            } else {
              // echo 'false!';
            }
            // echo '<br>';
          }

              // echo '<br>meeting schedule: ';
              // echo $day.','.$_SESSION['mstartime'].'-'.$_SESSION['mEndtime'].'<br><br>';  
        // }

        echo '<html>  
        <div class="modal-dialog" style="position:absolute;z-index:5;right:20%;left:20%;">
          <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue;"> 
              <h4 class="modal-title" id="myModalLabel" style="color:blue;"><em>Appointment Set</em></h4>
            </div>
            <div class="modal-body">
              <table class = "table table-hover">
              <tr><td><em style = "color:blue;">Title</em></td><td>'.$_POST['Title'].'</td></tr>
              <tr><td><em style = "color:blue;">What : </em></td><td>'.$_POST['Description'].'</td></tr>
              <tr><td><em style = "color:blue;">Where : </em></td><td>'.$_POST['Venue'].'</td></tr>
              <tr><td><em style = "color:blue;">When : </em></td>
              <td>'.$day.','.$_SESSION['mstartime'].'-'.$_SESSION['mEndtime'].'</td></tr>
              </table>
            </div>
            <div class="modal-footer" style="background-color:lightblue;">
              <a href = "meeting.php" role = "button" class="btn btn-warning">Done!</a> 
            </div>
          </div>
        </div>  
      </html>';
      } 
      $sql="INSERT INTO meeting (Title, Venue, Description, Duration, MDate, Time, U_ID)
      VALUES ('$_POST[Title]','$_POST[Venue]','$_POST[Description]','$_POST[Duration]','$_POST[MDate]','$_SESSION[mstartime]', '1')";


      if (!mysql_query($con,$sql)) {
        die('Error: ' . mysql_error($con));
      } else {
        // echo "inserted!";
      }
    } else {
      // echo 'else here';
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

  <meta charset = "c">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen"> 
     
    <script src="../js/ajax.googleapis.com.ajax.libs.jquery.1.4.2.jquery.min.js"></script>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>   

    <style type="text/css">
    ::-webkit-scrollbar {width: 6px; height: 4px; background-color: white; }
    ::-webkit-scrollbar-thumb { background-color:lightblue; -webkit-border-radius: 1ex; }
    </style>
  </head>

 <body>
  <div class="row"><br>   
    <div class = "col-md-6 col-md-offset-3">
      <form class="form-horizontal" action = "meeting.php" method="post" enctype="multipart/form-data">
        <div class="panel panel-primary">
          <!-- style = "overflow:auto;height:650px;" -->
          <div class="panel-heading">
          <h3 class="panel-title">appointMe</h3>
        </div>
        <div class="panel-body">
           <div class = "control-group">
          <label class="control-label" for="meetingtitle">
            <h><em style="color:blue;">Title</em><h></label>

          <div class="controls">
            <input type="text" id="meetingtitle" value="" placeholder="" name="Title" class="form-control" required autofocus>
          </div>
        </div>

         <div class = "control-group">
          <label class="control-label" for="meetingdesc">
            <h><em style="color:blue;">Description</em><h></label>

          <div class="controls">
            <textarea id="meetingdesc" value="" placeholder="" name="Description" class="form-control" required></textarea>
          </div>
        </div>

          <div class = "control-group">
                  <label class="control-label" for="meetingvenue">
                    <h><em style="color:blue;">Venue</em><h></label>

                  <div class="controls">
                    <input type="text" id="meetingvenue" value="" placeholder="" name="Venue" class="form-control" required>
                  </div>
                </div>

            <div class = "control-group"> 
                  <div class="controls">
                    <div class="row"> 
                      <div class="col-xs-2">
                      <label class="control-label" for="duration"><h><em style="color:blue;">Duration</em><h></label>
                        <input type="text" class="form-control" placeholder="" name="Duration" required>
                      </div>
                      <div class="col-xs-3">
                      <label class="control-label" for="date"><h><em style="color:blue;">Unit</em><h></label>
                        <select type="text" class="form-control" name="Unit" required>
                          <option>minutes</option>
                          <option>hours</option>
                          <option>days</option> 
                          </select>

                      </div>
                      <div class="col-xs-4">
                      <label class="control-label" for="date"><h><em style="color:blue;">Date</em><h></label>
                        <input type="date" class="form-control" name="MDate" required>
                      </div>
                      <div class="col-xs-3"> 
                        <label class="control-label" for="time"><h><em style="color:blue;">Time</em><h></label>
                        <input type="time" class="form-control" name="Time" required>
                      </div> 
                      </div>

                    </div>
                  </div>
                </div> 
                <br>

            <div class = "control-group" style = "padding:2em;">
             <div class="panel-group" id="accordion">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h class="">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <i class="glyphicon glyphicon-plus"></i> Add attendee
                    </a>
                  </h>
                </div>  
                <div id="collapseOne" class="panel-collapse collapse in">
                  <div class="panel-body">
                  <div style = "height:100px;overflow:auto;" > 
                    <table class="table table-hover">
                    <?php
                      $sql = "SELECT * FROM user";
                      $result = mysql_query($sql);
                      while($row = mysql_fetch_array($result)) {
                        echo ' 
                              <tr class = "active"><td width = "10%"><input type="checkbox" name="attendees[]" value="'.$row['Username'].'"></td>
                              <td>'.$row['Username'].'</td></tr>
                             ';
                      }
                    ?> 
                     </table>
                    </div>
                  </div>
                </div>
              </div>  
            </div>
            </div>  

          <div class="modal-footer">
            <a href="index.html" class="btn btn-primary">Cancel</a>
            <button type = "submit" class="btn btn-success" name="SaveMeeting" style = "cursor: pointer">Save changes</button>
           </div>

            </div> 
        </div> 
  </div>

  </form>
  </div>
</div>
  

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>