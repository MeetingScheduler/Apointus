<!DOCTYPE html>
<html>
<?php
 	ob_start();
    session_start();
    include ("dbconfig.php");
    
    // echo "user_id: ".$_SESSION['user_id'];
    $user_id = $_SESSION['user_id'];
    $query = mysqli_query($con,"SELECT * FROM user WHERE User_id = '$user_id'");
    $user = mysqli_fetch_array($query);
    $_SESSION['username'] = $user['Username'];
    $_SESSION['email'] = $user['Email'];
    $_SESSION['fname'] = $user['Firstname'];
    $_SESSION['lname'] = $user['Lastname'];
    $_SESSION['contact_no'] = $user['contact_number'];
    $_SESSION['pp'] = $user['Profile_picture'];
    $query = mysqli_query($con,"SELECT * FROM meeting WHERE User_id = $user_id");
    $meetings = mysqli_fetch_array($query);
    // kani ra na if aku ge edit------------------------------------------------------------
    if (isset($_GET['resp'])) {
    	$response = $_GET['resp'];
    	list($resp,$n_id) = explode('-', $response);
   
    	$sql = "UPDATE notify SET Response = '$resp', HasResponded = 'YES' WHERE N_ID = '$n_id' AND User_id = '$_SESSION[user_id]'";
        if (!mysqli_query($con,$sql)) {
          die('Error: ' . mysqli_error($con));
        }
        $query = mysqli_query($con,"SELECT * FROM notification WHERE Event = 'response' AND Event_ID = '$n_id' AND User_id = '$_SESSION[user_id]'");
    	// $existing = mysqli_fetch_array($query);
        // echo 'numOfRows='.mysqli_num_rows($query);
    	if (mysqli_num_rows($query) == 0) {
    		$sql="INSERT INTO notification (Event, Event_ID, User_id)
      		VALUES ('response', $n_id, $_SESSION[user_id])";
		    if (!mysqli_query($con,$sql)) {
		       die('Error: ' . mysqli_error($con));
		    } else {
		       // echo "inserted!";
		    }
		    $id = mysqli_insert_id($con);
		    $query = mysqli_query($con,"SELECT * FROM notification WHERE N_ID = '$n_id'");
	    	$notif = mysqli_fetch_array($query);
		    $sql="INSERT INTO notify (N_ID, User_id, Response, HasResponded)
	              VALUES ('$id', '$notif[User_id]', 'ACCEPT', 'NO')";
	    
	        if (!mysqli_query($con,$sql)) {
	          die('Error: ' . mysqli_error($con));
	        } else {
	          // echo "inserted!";
	        }	
    	}
        
    } //until here-----------------------------------------------------------
    //add sched
    if (isset($_POST['add-schedule'])) {  
		$sql="INSERT INTO schedule (User_id, Subject, Day, StartTime, EndTime)
		VALUES ('$_SESSION[user_id]','$_POST[Subject]','$_POST[Day]','$_POST[StartTime]','$_POST[EndTime]')";
	
		if (!mysqli_query($con,$sql)) {
	  		die('Error: ' . mysqli_error($con)); 
	   	}
	} 

	//delete-sched  
    	if (isset($_GET['schedID'])) {
    		$sched_id = $_GET['schedID'];  
			mysqli_query($con,"DELETE FROM schedule WHERE Schedule_id='$sched_id'");
		}

	//edit sched 
		if (isset($_POST['saveUpdates'])) {
			$sql = "UPDATE schedule SET Day='$_POST[upDay]',Subject='$_POST[upSubject]', StartTime='$_POST[upStartTime]', EndTime='$_POST[upEndTime]'
	    	WHERE Schedule_id='$_POST[upSchedID]'";
			if (!mysqli_query($con,$sql)) {
				die('Error: ' . mysqli_error($con));
			} 
		}
	
    function getDateAlone($datetime) {
    	if ($datetime) {
	    	list($date,$time) = explode(" ", $datetime);
	    	return $date;
    	} else {
    		echo 'empty datetime';
    	}
    }

    function getStandardTime($datetime) {
    	if ($datetime) {
    		list($date,$milTime) = explode(" ", $datetime);
    		$stanTime = date('h:i A', strtotime($milTime));
    		return $stanTime;
    	} else {
    		echo 'empty datetime';
    	}
    }
?>

<head>
	<title> Appointus </title>
	<meta charset = "c">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <script src="js/jquery.js"></script>
     <script src="js/bootstrap.js"></script> 

	<link rel="stylesheet" type="text/css" href="css/profile.css">
	
	<link href="fullcalendar/fullcalendar.css" rel="stylesheet">
	<!-- <link href="fullcalendar/fullcalendar.print.css" rel="stylesheet">   -->
    <script src="js/ajax.googleapis.com.ajax.libs.jquery.1.4.2.jquery.min.js"></script>
	<script src="lib/jquery.min.js"></script>
	<script src="lib/jquery-ui.custom.min.js"></script>
	<script src="fullcalendar/fullcalendar.min.js"></script>
	
	<script>

	 $(document).ready(function() {
	  var date = new Date();
	  var d = date.getDate();
	  var m = date.getMonth();
	  var y = date.getFullYear();

	  var calendar = $('#calendar').fullCalendar({
	   editable: true,
	   header: {
	    left: 'prev,next today',
	    center: 'title',
	    right: 'month,agendaWeek,agendaDay'
	   },
	   
	   events: "fullcalendar/events.php", 
	   // Convert the allDay from string to boolean
	   eventRender: function(event, element, view) {
	    if (event.allDay === 'true') {
	     event.allDay = true;
	    } else {
	     event.allDay = false;
	    }
	   },
	   selectable: false,
	   selectHelper: false,
	   select: function(start, end, allDay) {
	   var title = prompt('Event Title:');
	   var url = prompt('Type Event url, if exits:');
	   if (title) {
	   var start = $.fullCalendar.formatDate(start, "yyyy-MM-dd HH:mm:ss");
	   var end = $.fullCalendar.formatDate(end, "yyyy-MM-dd HH:mm:ss");
	   $.ajax({
	   url: 'fullcalendar/add_events.php',
	   data: 'title='+ title+'&start='+ start +'&end='+ end +'&url='+ url ,
	   type: "POST",
	   success: function(json) {
	   alert('Added Successfully');
	   }
	   });
	   calendar.fullCalendar('renderEvent',
	   {
	   title: title,
	   start: start,
	   end: end,
	   allDay: allDay
	   },
	   true // make the event "stick"
	   );
	   }
	   calendar.fullCalendar('unselect');
	   },
	   
	   editable: false,
	   eventDrop: function(event, delta) {
	   var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
	   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
	   $.ajax({
	   url: 'fullcalendar/update_events.php',
	   data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
	   type: "POST",
	   success: function(json) {
	    alert("Updated Successfully");
	   }
	   });
	   },
	   eventResize: function(event) {
	   var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
	   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
	   $.ajax({
	    url: 'fullcalendar/update_events.php',
	    data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
	    type: "POST",
	    success: function(json) {
	     alert("Updated Successfully");
	    }
	   });


	}
	   
	  });
	  
	 });

	</script>

	<style>
	 ::-webkit-scrollbar {width: 5px; height: 0px; background-color: #d3d3d3; }
    ::-webkit-scrollbar-thumb { background-color:#006699; -webkit-border-radius: 0.5ex; }
	</style>
</head>
<body>
	<div id = "wrapper">
		<div id = "header">
			<img id = "logo" src="img/appointus-final-logo.png"/>

			<div id = "account-greeting">
				<img src="<?php echo 'img/' . $_SESSION['pp']; ?>" maxwidth= "100px" id="profile-picture">

				<div class="dropdown" id = "settings">
						<a data-toggle="dropdown" href="#"> <h1 id = "profile-name"><?php echo $_SESSION['username']; ?></h1> </a>
						<ul class="dropdown-menu" role="menu" id = "dropmenu">
						<li><a href="myprofile.php">Profile</a></li>
						<li><a href="#sked" data-toggle= "modal">Schedule</a></li>
						<li role="presentation" class="divider"></li>
						<li><a href="index.php?name=logout" name = "logout"
							onclick="alert('Are you sure you want to log out?')">Log-Out</a>
						 <?php
						   if(isset($_POST['logout'])) {
								session_unset();
								session_destroy();
								header("Location: index.php");
							}?></li>
						</ul>
					</div>
		
			</div> <!--end of account-greeting -->
		</div> <!-- end of header -->

		<div id = "divider">
		</div> 

		<div id = "content-container">
			<div id = "left-content-container">
				
				<div id = "">
					<div id="content"> 
					<ul id="tabs" class="nav nav-tabs nav-block pull-right" data-tabs="tabs" >
			        <li><a href="meeting.php">
			        	<i class="glyphicon glyphicon-briefcase white"></i> appointMe</a></li> 
			    	</ul>
					</div>					
				</div> <!-- end of appointments-container -->

				<div id = "calendar" style="padding: 1% 4% 10% 2%;"> 
				</div> <!-- end of calendar -->
			</div> <!-- end of left-container -->	

			<div id = "right-content-container"> 
				<div id="content"style="height:100%;">
			    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs" style="position:fixed;width:100%;background-color:#333333;color:white;">
			        <li  class="active"><a href="#ntf" data-toggle="tab">
			        	<i class="glyphicon glyphicon-bell white"></i> Notifications</a></li>
			        <li><a href="#up" data-toggle="tab">
			        	<i class="glyphicon glyphicon-pushpin white"></i> MeetBoard</a></li>
			   		 <li><a href="#mb" data-toggle="tab">	
			        	<i class="glyphicon glyphicon-dashboard white"></i> Rings</a></li> 
			       </ul>
		    <div id="my-tab-content" class="tab-content" style="overflow:auto;"> <!-- padding: 1% 4% 1%; -->
		    	<br><br>

		        <div class="tab-pane" id="mb">
		        	<div style = "padding: 5% 5% 0%;overflow:auto;height:500px;" >
		        	<form action= "profile.php" method="POST">
		        	<a href="ring.php" name="createRing" type="submit"><i class="glyphicon glyphicon-plus"></i> Create Ring</a>
		        	</form>
		        	<hr style="border:1px solid #006699;">
		        	<div class="list-group">
		            <?php
		            // echo 'session id= '.$_SESSION['user_id'];
		            $query = mysqli_query($con,"SELECT * from membership WHERE User_id = '$_SESSION[user_id]'");
		            $noRing = true;
		            while ($mem = mysqli_fetch_array($query)) {
		            	$query1 = mysqli_query($con,"SELECT * from ring WHERE R_ID = '$mem[R_ID]'");
		            	$ring = mysqli_fetch_array($query1);
		            	echo'<div class="dropdown" >
						<a data-toggle="dropdown" href="#" class="list-group-item">'.$ring['Ringname'].'</a>
						<ul class="dropdown-menu" role="menu" id = "dropmenu" style="width:350px;">'; 
						
		            	$query2 = mysqli_query($con,"SELECT * from membership WHERE R_ID = '$ring[R_ID]'"); 
		            	while($mem1 = mysqli_fetch_array($query2)) {
		            		if ($mem1['User_id'] != $_SESSION['user_id']) {
		            			$query3 = mysqli_query($con,"SELECT * from user WHERE User_id = '$mem1[User_id]'");
			            		$member = mysqli_fetch_array($query3);
			            		echo '
								<li><a>'.$member['Firstname'].' '.$member['Lastname'].'</a></li>';
		            		}
		            	}
		            	echo '
		            		<li role="presentation" class="divider"></li>
		            		<li><a href="edit_ring.php?ring_id=' . $mem['R_ID'] . '"><i class="glyphicon glyphicon-user"></i>  Add Members</a></li>
		            		</ul></div>';
		            	$noRing = false;
		            }
		            if ($noRing == true) {
						echo '<table><tr><td align="center"><a>No Rings.</a></td></tr></table>';
					}
		            ?>
		        	</div>
		        	</div>
		        </div> 

		        <div class="tab-pane active" id="ntf" > 
		        	<div style="position:fixed;overflow:auto;height:540px;">  
		        	<div class="list-group" > 
		        	<?php
					    $query = mysqli_query($con,"SELECT * from notify WHERE User_id = '$_SESSION[user_id]' ORDER BY Count_ID DESC");
					    $noNotif = true;
					    while ($result = mysqli_fetch_array($query)) {//each user's notif
						    $query2 = mysqli_query($con,"SELECT * from notification WHERE N_ID = '$result[N_ID]'");
						    $result2 = mysqli_fetch_array($query2);//notif details
						    $query3 = mysqli_query($con,"SELECT * from user WHERE User_id = '$result2[User_id]'");
						    $result3 = mysqli_fetch_array($query3);//ni notify, ni hemu sa meeting, ni respond

						    if ($result2['Event'] == "meeting") { 
						    	$query4 = mysqli_query($con,"SELECT * from meeting WHERE M_ID = '$result2[Event_ID]'");
						    	$result4 = mysqli_fetch_array($query4); 
						    	echo '
						    	<div class="dropdown">';
						    	if ($result['HasResponded'] == "NO") {
						    		echo '<a data-toggle="dropdown" href="#" class="list-group-item" style="background-color:lightblue;">';
						    	} else {
						    		echo '<a data-toggle="dropdown" href="#" class="list-group-item">';
						    	}
						    	echo '
						    	<i class="glyphicon glyphicon-envelope"></i> 
								'.$result3['Username'].' invited you for '.$result4['Title'].' on '.getDateAlone($result4['Datetime']).', '.getStandardTime($result4['Datetime']).'</a>
								<ul class="dropdown-menu details" role="menu" id = "dropmenu" style="width:300px;border:1px solid gray;">';
							    if ($result['HasResponded'] == "NO") {
							    	echo ' 
							    	<li style="padding: 10px 10px 0px;">
							    	<p><b>'.$result4['Title'].'</b></p>
							    	<table class="table table-hover">  
							    	<tr><td> Agenda: </td><td>'.$result4['Description'].'</td></tr>
							    	<tr><td> Venue: </td><td>'.$result4['Venue'].'</td></tr>
							    	<tr><td> Date&Time: </td><td>'.getDateAlone($result4['Datetime']).', '.getStandardTime($result4['Datetime']).'</td></tr>
							    	<tr><td><a href="profile.php?resp=DECLINE-'.$result['N_ID'].'" class="btn btn-block btn-notdark btn-sm" name="decline">
							    	<i class="glyphicon glyphicon-thumbs-down"></i> Decline</a></td>
								    <td><a href="profile.php?resp=ACCEPT-'.$result['N_ID'].'" class="btn btn-block btn-dark btn-sm" name="accept">
								    <i class="glyphicon glyphicon-thumbs-up"></i> Accept</a></td></tr>
								    </table></li>';
								} else {
									if ($result['Response'] == "ACCEPT") {
										echo ' 
								    	<li style="padding: 10px 10px 0px;">
								    	<p><b>'.$result4['Title'].'</b></p>
								    	<table class="table table-hover">  
								    	<tr><td> Agenda: </td><td>'.$result4['Description'].'</td></tr>
								    	<tr><td> Venue: </td><td>'.$result4['Venue'].'</td></tr>
								    	<tr><td> Date&Time: </td><td>'.getDateAlone($result4['Datetime']).', '.getStandardTime($result4['Datetime']).'</td></tr>';
										echo '<li><table><tr><td style="padding: 4px 50px 0px;"><a>You already accepted.</a></td></tr></table></li>';
									} else {
										echo '<li><table><tr><td style="padding: 4px 60px 0px;"><a>You already declined.</a></td></tr></table></li>';
									}
							    }
							    echo'</ul></div>';
						    } else if ($result2['Event'] == "response") {
						    	$query4 = mysqli_query($con,"SELECT * from notification WHERE N_ID = '$result2[Event_ID]'");
						    	$result4 = mysqli_fetch_array($query4);//gi respondan na notification
						    	$query5 = mysqli_query($con,"SELECT * from meeting WHERE M_ID = '$result4[Event_ID]'");
						    	$result5 = mysqli_fetch_array($query5);//meeting details 

						    	$query6 = mysqli_query($con,"SELECT * from notify WHERE N_ID = '$result2[Event_ID]' AND User_id = '$result2[User_id]'");
						    	$result6 = mysqli_fetch_array($query6);//who responded and notification responded
							    $query7 = mysqli_query($con,"SELECT * from user WHERE User_id = '$result6[User_id]'");
							    $result7 = mysqli_fetch_array($query7);//who responded details
					    		
								if ($result6['HasResponded'] == "YES") {
									echo '<a href="#" class="list-group-item">';
									if ($result6['Response'] == "ACCEPT") {
										echo '<i class="glyphicon glyphicon-thumbs-up"></i> '.$result7['Username'].' is coming for '.$result5['Title'].' on '.getDateAlone($result5['Datetime']).', '.getStandardTime($result5['Datetime']);
									} else {
										echo '<i class="glyphicon glyphicon-thumbs-down"></i> '.$result7['Username'].' is not coming for '.$result5['Title'].' on '.getDateAlone($result5['Datetime']).', '.getStandardTime($result5['Datetime']);
									}
								echo '</a>';
							    } 
						    	
						    } else if ($result2['Event'] == "membership"){
						    	$query4 = mysqli_query($con,"SELECT * from ring WHERE R_ID = '$result2[Event_ID]'");
						    	$ring = mysqli_fetch_array($query4);//ring details
						    	$query5 = mysqli_query($con,"SELECT * from user WHERE User_id = '$ring[User_id]'");
						    	$user = mysqli_fetch_array($query5);//user details
						    	echo '<a href="#" class="list-group-item">';
						    	echo '<i class="glyphicon glyphicon-thumbs-up"></i> '.$user['Username'].' added you to the ring '.$ring['Ringname'].'.';
						    	echo '</a>';
						    } else {
						    	echo 'unidentified event';
						    }
						   	$noNotif = false;
						}
						if ($noNotif == true) {
							echo '<table><tr><td align="center"><a>No notifications.</a></td></tr></table>';
						}
		        	?> 
		        	</div> 
		        </div>
		        </div>
		        <div class="tab-pane" id="up"> 
		        	<div style="position:fixed;overflow:auto;height:100%;">  
		        	<div class="list-group">
		        	<?php
					    $query = mysqli_query($con,"SELECT * from attendance WHERE User_id = '$_SESSION[user_id]' ORDER BY A_ID DESC");
					    $noUpcom = true;
						while ($result = mysqli_fetch_array($query)) {
							$query2 = mysqli_query($con,"SELECT * from meeting WHERE M_ID = '$result[M_ID]'");
						    $meeting = mysqli_fetch_array($query2);
						    if ($meeting['User_id'] == $_SESSION['user_id']) {
						    	// echo '<a href="#" class="list-group-item">
								echo '<div class="dropdown">  
						    		<a data-toggle="dropdown" href="#" class="list-group-item">	
									<i class="glyphicon glyphicon-pushpin"></i>
									'.$meeting['Title'].' on '.getDateAlone($meeting['Datetime']).', '.getStandardTime($meeting['Datetime']).' at '.$meeting['Venue'].'</a>
									<ul class="dropdown-menu details" role="menu" id = "dropmenu" style="width:300px;border:1px solid gray;">';

						    	echo '<li style="padding: 10px 10px 0px;">
								    	<p><b>'.$meeting['Title'].'</b></p>
								    	<table class="table table-hover">  
								    	<tr><td> Agenda: </td><td>'.$meeting['Description'].'</td></tr>
								    	<tr><td> Venue: </td><td>'.$meeting['Venue'].'</td></tr>
								    	<tr><td> Date&Time: </td><td>'.getDateAlone($meeting['Datetime']).', '.getStandardTime($meeting['Datetime']).'</td></tr></table>';
							
								echo'</li></ul></div>';
								if ($meeting['IsAddedToCalendar'] == 'NO') {
						    		$conn=mysqli_connect("localhost","root","","fullcalendar");
									// Check connection
									if (mysqli_connect_errno()) {
									  echo "Failed to connect to MySQL: " . mysqli_connect_error();
									}
									mysqli_query($conn,"INSERT INTO evenement (title, start, User_id)
									VALUES ('$meeting[Title]', '$meeting[Datetime]', '$_SESSION[user_id]')");

									mysqli_close($conn); 
									$sql = "UPDATE meeting SET IsAddedToCalendar = 'YES' WHERE M_ID = '$meeting[M_ID]'";
							        if (!mysqli_query($con,$sql)) {
							          die('Error: ' . mysqli_error($con));
							        }
							    }
						    } else {
						    	$query3 = mysqli_query($con,"SELECT * from notification WHERE Event = 'meeting' and Event_ID = '$meeting[M_ID]'");
								$notif = mysqli_fetch_array($query3);
						    	$query4 = mysqli_query($con,"SELECT * from notify WHERE N_ID = '$notif[N_ID]' and User_id = '$_SESSION[user_id]'");
						    	$resp = mysqli_fetch_array($query4);

						    	if ($resp['HasResponded'] == "YES" && $resp['Response'] == "ACCEPT") {
						    		// echo '<a href="" class="list-group-item">
								    echo '<div class="dropdown">  
							    		<a data-toggle="dropdown" href="#" class="list-group-item">	
							    		<i class="glyphicon glyphicon-pushpin"></i>
									   	'.$meeting['Title'].' on '.getDateAlone($meeting['Datetime']).', '.getStandardTime($meeting['Datetime']).' at '.$meeting['Venue'].'</a>
								   		<ul class="dropdown-menu details" role="menu" id = "dropmenu" style="width:300px;border:1px solid gray;">';

						    		echo '<li style="padding: 10px 10px 0px;">
								    	<p><b>'.$meeting['Title'].'</b></p>
								    	<table class="table table-hover">  
								    	<tr><td> Agenda: </td><td>'.$meeting['Description'].'</td></tr>
								    	<tr><td> Venue: </td><td>'.$meeting['Venue'].'</td></tr>
								    	<tr><td> Date&Time: </td><td>'.getDateAlone($meeting['Datetime']).', '.getStandardTime($meeting['Datetime']).'</td></tr></table>';

						    		echo'</li></ul></div>'; 
						    		
								   	$conn=mysqli_connect("localhost","root","","fullcalendar");
										// Check connection
									if (mysqli_connect_errno()) {
									  echo "Failed to connect to MySQL: " . mysqli_connect_error();
									}
									$sql = "SELECT * FROM evenement WHERE title = '$meeting[Title]'";
							        $result = mysqli_query($conn,$sql);
							        $isAddedByYou = false;
							        while($row = mysqli_fetch_array($result)) {
							        	if ($row['User_id'] == $_SESSION['user_id']) {
							        		$isAddedByYou = true;
							        		break;
							        	}
							        }
								   	if ($isAddedByYou == false) {
										mysqli_query($conn,"INSERT INTO evenement (title, start, User_id)
										VALUES ('$meeting[Title]', '$meeting[Datetime]', '$_SESSION[user_id]')");

										mysqli_close($conn); 
										$sql = "UPDATE meeting SET IsAddedToCalendar = 'YES' WHERE M_ID = '$meeting[M_ID]'";
								        if (!mysqli_query($con,$sql)) {
								          die('Error: ' . mysqli_error($con));
								        }
								    }
						    	}
						    	
						    } 	
						   	 $noUpcom = false; 

						}
						if ( $noUpcom == true) {
							echo '<table><tr><td align="center"><a>No upcomings.</a></td></tr></table>';
						}
		        	?> 
		        	</div> 
		        </div>
		    </div>
		</div> 
		</div>		
			</div> 
	</div> <!--end of wrapper -->


	<!-- Modal -->
		<div class="modal fade" id="sked" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="font-family:Century Gothic;">
		  		<div class="modal-header">
		  			<button type="button" class="btn btn-default pull-right" href="#edit-sked" data-toggle = "modal" >Add Schedule</button> 
		    		<h class="modal-title" id="myModalLabel"  style="font-size:20px;"><b>My Schedule</b></h>
		    		</div>
		  		<div class="modal-body up" id = "prof-content" style="overflow:auto;height:400px;"> 
		  		<form class="form-horizontal" action = "profile.php" method="post" enctype="multipart/form-data" >
		   			<table class= "table table-hover" width="100%" >  
		   				<tr class="success">
		   					<th></th>  
		   					<th>Subject</th> 
		   					<th>Day</th> 
		   					<th>Start Time</th>
		   					<th>End Time</th>
		   				</tr> 
<!-- 		   			<?php 
			   			$conn=mysqli_connect("localhost","root","","fullcalendar");
			   			if (mysqli_connect_errno()) {
							echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
			   			$schedule_list = mysqli_query($con,"SELECT * from schedule WHERE User_id = '$_SESSION[user_id]'");
			   			$schedule = mysqli_fetch_array($schedule_list);
	
						mysqli_query($conn,"INSERT INTO evenement (title, start, User_id)
						VALUES ('$schedule[Subject]', '$schedule[Datetime]', '$_SESSION[user_id]')");

						mysqli_close($conn); 
						$sql = "UPDATE meeting SET IsAddedToCalendar = 'YES' WHERE M_ID = '$meeting[M_ID]'";
						if (!mysqli_query($con,$sql)) {
							die('Error: ' . mysqli_error($con));
						}
						

		   			?> -->
		   			<?php
		   			$query = mysqli_query($con,"SELECT * from schedule WHERE User_id = '$_SESSION[user_id]'"); 
		   			while ($result = mysqli_fetch_array($query)) { 
		   				$startTime = date("g:iA", strtotime("$result[StartTime]"));
		   				$endTime = date("g:iA", strtotime("$result[EndTime]"));
		   				echo '<tr> 
		   					<td>
		   					<a href= "profile.php?schedID='.$result['Schedule_id'].'" name="delete-schedule" class="btn btn-default btn-sm pull-left">
		   					<i class="glyphicon glyphicon-trash"></i></a>
		   					<div class="dropdown" id = "settings">
	   						<button class ="btn btn-default btn-sm pull-left" data-toggle="dropdown">
	   						<i class="glyphicon glyphicon-wrench"></i></button>
	   						<ul class="dropdown-menu" role="menu">
	   						<form action= "profile.php" method="POST">
	   						<li class="dont"><input type="text" name="upSubject" value="'.$result['Subject'].'" class="form-control">
	   						<li class="dont"><select type="text" class="form-control" name="upDay">';
	   						if ($result['Day'] == 'Sunday') {
	   							echo '<option selected>Sunday</option>
					                <option>Monday</option>
					                <option>Tuesday</option>
					                <option>Wednesday</option>
					                <option>Thursday</option>
					                <option>Friday</option>
					                <option>Saturday</option>';
	   						} else if ($result['Day'] == 'Monday') {
	   							echo '<option >Sunday</option>
					                <option selected>Monday</option>
					                <option>Tuesday</option>
					                <option>Wednesday</option>
					                <option>Thursday</option>
					                <option>Friday</option>
					                <option>Saturday</option>';
	   						} else if ($result['Day'] == 'Tuesday') {
	   							echo '<option>Sunday</option>
					                <option>Monday</option>
					                <option selected>Tuesday</option>
					                <option>Wednesday</option>
					                <option>Thursday</option>
					                <option>Friday</option>
					                <option>Saturday</option>';
	   						} else if ($result['Day'] == 'Wednesday') {
	   							echo '<option>Sunday</option>
					                <option>Monday</option>
					                <option>Tuesday</option>
					                <option selected>Wednesday</option>
					                <option>Thursday</option>
					                <option>Friday</option>
					                <option>Saturday</option>';
	   						} else if ($result['Day'] == 'Thursday') {
	   							echo '<option>Sunday</option>
					                <option>Monday</option>
					                <option>Tuesday</option>
					                <option>Wednesday</option>
					                <option selected>Thursday</option>
					                <option>Friday</option>
					                <option>Saturday</option>';
	   						} else if ($result['Day'] == 'Friday') {
	   							echo '<option>Sunday</option>
					                <option>Monday</option>
					                <option>Tuesday</option>
					                <option>Wednesday</option>
					                <option>Thursday</option>
					                <option selected>Friday</option>
					                <option>Saturday</option>';
	   						} else if ($result['Day'] == 'Saturday') {
	   							echo '<option>Sunday</option>
					                <option>Monday</option>
					                <option>Tuesday</option>
					                <option>Wednesday</option>
					                <option>Thursday</option>
					                <option>Friday</option>
					                <option selected>Saturday</option>';
	   						}
	   					echo ' </select> </li>
	   						<li class="dont"><input type="time" name="upStartTime" value="'.$result['StartTime'].'" class="form-control"></li>
	   						<li class="dont"><input type="time" name="upEndTime" value="'.$result['EndTime'].'" class="form-control"></li>
	   						<li class="dont" style="display:none;"><input type="text" name="upSchedID" value="'.$result['Schedule_id'].'" class="form-control"></li>
	   						<li role="presentation" class="divider"></li>
	            			<li class="dont">
	            			<button type="submit" class="btn btn-default btn-block" name="saveUpdates">Save Changes</button></li>															    			
		  					</form>
		  					</ul>
	   						</div> 
		   					</td> 
	   						<td>'.$result['Subject'].'</td> 
		   					<td>'.$result['Day'].'</td> 
		   					<td>'.$startTime.'</td>
		   					<td>'.$endTime.'</td>
		   					</tr>';
		   			} 
		   			?>	
		   			</table>
					</form> 
		  		</div> 
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="edit-sked" tabindex="-5" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content"> 
  		<div class="modal-body" id = "prof-content" stye="font-family:Century Gothic;"> 
  			<form class="form-horizontal" action = "profile.php" method="post" enctype="multipart/form-data">
  				<div class="row"> 
  					<div class="col-md-6 col-md-offset-3"> 
  				<hr style="border-top:1px solid #006699;">
  				Subject :
  				<input type="text" name="Subject" class="form-control">
  				Schedule Day : <select type="text" class="form-control" name = "Day">
                <option>Sunday</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option> 
                </select> <br>
					Start Time : 
					<input type="time" name="StartTime" class="form-control"> </br>
					End Time :
					<input type="time" name="EndTime" class="form-control">
					<hr style="border-top:1px solid #006699;">
					<button type="button" class="btn btn-default" data-toggle="modal" href = "#sked" data-dismiss="modal" style="margin-left:15%;">Cancel</button>
					<button type="submit" class="btn btn-default" name="add-schedule">Add Schedule</button>
  				</div>
  				</div> 
  			</form>

  		</div> 
		</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<script type="text/javascript">  
  $('.dropdown-menu li.dont').click(function(e) {
    e.stopPropagation();
	});
</script>
</body>
</html>
