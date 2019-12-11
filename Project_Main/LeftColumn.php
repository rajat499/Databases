
<!-- Worked on by
40012133	Florin POENARIU f_poenar@encs.concordia.ca -->

<!--- Create the New Message button -->
	<div style="cursor:pointer" onclick="document.getElementById('new-message').style.display='block'" class="white-back">
		<p id="new_message_button" align="center" ><br> New Message </p>
	</div>
`
<!-- Start the left column container division -->
<div id="left-col-container">

<?php 
	
	
	include("connection.php");
	
	// Querry for sender/receiver/time_stamp from messages
	$user = $_SESSION["username"];
	/*$qrry="SELECT DISTINCT sender, receiver, time_stamp
		FROM messages WHERE
		sender ='$user' UNION
		SELECT DISTINCT sender, receiver, time_stamp 
		FROM messages WHERE
		receiver='$user'
		ORDER BY time_stamp DESC";*/

	$qrry= "SELECT DISTINCT receiver, sender
		FROM messages WHERE
		sender = '$user' OR
		receiver= '$user'
		ORDER BY time_stamp DESC";
		
		
		$r = mysqli_query($conn, $qrry);
		
		if($r) {
			
			if(mysqli_num_rows($r) > 0) {
				
				// Place a counter at 0 and create an array to have all the added users
				$counter = 0;
				$added_user = array();
				while($row = mysqli_fetch_assoc($r)) {
					
					$sender = $row['sender'];
					$receiver = $row['receiver'];
					if($_SESSION['username'] == $sender) {

						// We want to add the receiver only once and to check if the user is in the array an

						if(in_array($receiver, $added_user)) {
							// Don't add the receiver because he is already in the list

						} else {
							// If he is not, add the receiver name in the left column
							?>
								<div class="leftColMessages">
									<img src="images/pikachu.jpg" class="image"/>
									<?php echo '<a style="float:left; margin-top: 15px; color:black;" href="?user='.$receiver.'">'.$receiver.'</a>'; ?>
								</div>	
								<?php
						
								// Make sure the receiver name is added inside of the array so that there is only one person in the left column
								$added_user = array($counter => $receiver);
								
								// We want to increment the counter again
								$counter++;
						
							}
						} elseif ($_SESSION['username'] == $sender) {
							
							// We want to add the sender, but only once too
							if(in_array($sender, $added_user)) {

								// Same as above, if sender is already added, don't add him again

							} else {

								// If the sender was not found, we want to add it in the left column

								?>
									<div class="leftColMessages">
										<img src="images/pikachu.jpg" class="image"/>
										<?php echo '<a style="float:right; color:black;" "href="?user='.$sender.'">'.$sender.'</a>'; ?>
									</div>	
								<?php

								// Since the sender name was added, we want it to be added inside of the array too
								$added_user = array($counter => $sender);

								// We want to increment the counter
								$counter++;
							
							}
						}
					}				
			} else {
				//  No Message was sent
				echo 'No user';
			}
		} else {
			// There was an error with the Querry, display error
			echo $conn->error;
			echo $qrry;
		}
?>
			
</div>
