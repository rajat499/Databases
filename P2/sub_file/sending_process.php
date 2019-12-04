<?php

	session_start();
	$con = mysqli_connect("krc353.encs.concordia.ca", "krc353_2", "qNbKfe", "krc353_2");
	if(isset($_SESSION['username']) and isset($_GET['user'])) {

		// First check the session username and get the user
		
		if(isset($_POST['text'])) {
			
			// Next check that the message is not empty
			
			if($_POST['text'] !='') {

				// Next we want to insert thisdata into the database
				
				$sender = $_SESSION['username'];
				$receiver = $_GET['user'];
				$message = $_POST['text'];
				//$date = date("Y-m-d h:i:sa"); 	
				
				$qrry = "INSERT INTO messages (sender,receiver,text)
				VALUES('$sender','$receiver', '$message')";
					
				$r = mysqli_query($con, $qrry);
				
				if($r) {
					
					// Message was sent successfully
					?>
						<div class="you-message">
							<a href="#">Me</a>
							<p><?php echo $message; ?></p>
						</div>
					<?php 
				} else {
					
					// There was an issue with the query
					echo $con->error."<br>";
					echo $qrry;
					echo "<script type='text/javascript'>
							alert('$con->error  $qrry');
							</script>";
					exit();
				}
				
			} else {
				// If nothing was written, tell user to write something first
				echo 'Please write something first';
			}
			
		} else {
			// If there was a problem with the text, tell the user there was something wrong with the text
			echo 'Problem with text';
		}
		
	} else {	
		// If not logged in, can not send a message
		// If user was not selected, can not send a message
		echo 'Please login or select a user to send a message';
		
	}

?>