<?php
    session_start();

    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }
	echo "<p style='font-family:Brush script mt; font-size:25px; color:white'>SCC System.<br> You are a Controller</p>";
//	echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";
   	//echo "<a href='authenticate.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>";
	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
 
   if($user !== "controller" && $user!=="sysadmn"){
        echo "You don't have permissions to visit this page.<br>";
        exit();
	}
	
	if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit_charge'])){ 
		charge_event($_POST['edit_charge'], $_POST['charge'], $_POST['num_days'], $_POST['num_posts'], $_POST['charge_post'],$_POST['charge_day']);
		// echo "(".$_POST['edit_charge']." ".$_POST['charge']." ". $_POST['num_days']." ". $_POST['num_posts']." ". $_POST['charge_post']." ". $_POST['charge_day'].")<br>";
   }

	function charge_event($id, $charge, $num_days, $num_posts, $charge_post, $charge_day){
		include("connection.php");
		$sql = "UPDATE charge_slab SET charge=$charge, num_days=$num_days, num_posts=$num_posts, charge_post=$charge_post, charge_day=$charge_day WHERE id='$id'";
		if ($conn->query($sql) !== TRUE) {
			echo "Error updating record: " . $conn->error;
			exit();
		}   	
	}


	echo "<h1>Details of Charge According to Event Type in the System</h1><br>";

	$sql = $conn->query("SELECT * from charge_slab");
  	if(!$sql){
       		echo "Error getting details of charge_slab. ".$conn->error."<br>";
        exit();
  	}

	$eventname = $result['eventname'];

	$columns = array("charge", "num_days", "num_posts","charge_post","charge_day"); 
	if($sql->num_rows>0){
       		echo "<table>";
        	echo "<tr> <th>Charge Type</th> <th>Charge</th>  <th>Number of days</th> <th>Number of posts</th><th>Charge per post</th><th>Charge day</th></tr>";
       	 	while($row = $sql->fetch_assoc()){
				echo "<tr><form action='' method='POST'>";
				if($row["id"]==1){
					echo "<td>Normal Charges</td>";
				}else if($row["id"]==2){
					echo "<td>Discounted Charges For Recurring Events</td>";
				}else{
					continue;
				}
				foreach($columns as $attr)
					echo "<td><input type='text' name='$attr' value='".$row[$attr]."'</td>";
				
				echo "<td>
						<button type='submit' value='".$row['id']."' name='edit_charge'>Edit Charge</button>
					</td>";
				echo "</form></tr>";
        	}
       		 echo "</table>";
   	 }
	else{
			echo "No Charges in the event.<br>";
	}

	$events = $conn->query("SELECT * from events_info");
    	if(!$events){
        	echo "Error getting details of events. ".$conn->error."<br>";
        exit();
    	}
	$col = array("eventid", "eventname","orgnType", "fee"); 
	echo "<br><br><br><br><h1>Events in the System</h1><br>";
   	if($events->num_rows>0){
        	echo "<table>";
        	echo "<tr> <th>Event ID</th> <th>Event Name</th>  <th>Organization</th> <th>Fees</th></tr>";
      	  while($row = $events->fetch_assoc()){
           	 foreach($col as $att)
             	 	echo "<td>".$row[$att]."</td>";
              		echo "</tr>";
       	   }
       		 echo "</table>";
    	}else{
        	echo "No events to display.<br>";
    	}   
?>
<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/controller.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Controller</title>
    </head>
</html>
