<?php
    session_start();

    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }
//	echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";
   	//echo "<a href='authenticate.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>";
	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"controller.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
 
   if($user !== "controller"){
        echo "You don't have permissions to visit this page.<br>";
        exit();
    }
 	echo "Welcome to SCC System. You are a Controller."."<br>";
  	echo "Your username is: ".$_SESSION["username"]."<br>";
    	echo "Your password is: ".$_SESSION["password"]."<br>";

  	

	echo "<h1>Details of Charge According to Event Type in the System</h1><br>";

	$sql = $conn->query("SELECT * from charge_slab");
  	if(!$sql){
       		echo "Error getting details of charge_slab. ".$conn->error."<br>";
        exit();
  	}

	$eventname = $result['eventname'];

	$columns = array("id", "charge", "num_days", "num_posts","charge_post","charge_day"); 
	if($sql->num_rows>0){
       		echo "<table>";
        	echo "<tr> <th>ID</th> <th>Charge</th>  <th>Number of days</th> <th>NUmber of posts</th><th>Charge per post</th><th>Charge day</th></tr>";
       	 	while($row = $sql->fetch_assoc()){
            		echo "<tr>";
               		foreach($columns as $attr)
				echo "<td><input type='text' name='eventname' value='".$row[$attr]."'</td>";
               		 	echo "<td><form action='' method='POST'>
                       		 <button type='submit' value='edit_charge' name='edit_charge'>Edit Charge</button>
                        	</form></td>";
            			echo "</tr>";
        	}
       		 echo "</table>";
   	 }
    	else{
       		 echo "No Charges in the event.<br>";
    	}
 

	if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit_charge'])){ 
        	charge_event($id,$_POST['charge'], $_POST["num_days"], $_POST["num_posts"], $_POST["charge_post"],$_POST['charge_day']);
   	}

   	 function charge_event($id, $charge, $num_days, $num_posts, $charge_post, $charge_day){
       	 include("connection.php");
		$sql = "UPDATE charge_slab SET charge='$charge', num_days='$num_days', num_posts='$num_posts', charge_post='$charge_post', charge_day='$charge_day' WHERE id='$id'";
		if ($conn->query($sql) === TRUE) {
		    echo "Record updated successfully";
		} else {
		    echo "Error updating record: " . $conn->error;
		}   
		 echo 
			"<script type='text/javascript'>
			    window.location='controller.php';
			</script>"; 	
         }
        // $conn->close();

	$events = $conn->query("SELECT * from events_info");
    	if(!$events){
        	echo "Error getting details of events. ".$conn->error."<br>";
        exit();
    	}
	$col = array("eventid", "eventname","orgnType"); 
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
    <title>Events</title>
    </head>
</html>
