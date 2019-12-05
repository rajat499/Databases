
<div id="right-col-container">

	<div id="messages-container">
	
		<?php
		
			// We create a variable in case there is no message/there is a messsage
			$no_message = false;
		
			if(isset($_GET['user'])){
				
				$_GET['user'] = $_GET['user'];	
				
			} else {
				// The user variable is not in the url bar, so we want to add it there from
				// the last message that was sent to a user
				$qrry=' SELECT sender, receiver FROM messages
					WHERE sender = "'.$_SESSION['username'].'"
					or receiver = "'.$_SESSION['username'].'"
					ORDER BY time_stamp DESC LIMIT 1';
					
				$r = mysqli_query($con, $qrry);
				
				if($r) {

					if(mysqli_num_rows($r) > 0 ) {
						
						while($row = mysqli_fetch_assoc($r)) {
							// Set the sender as the sender from the row
							$sender = $row['sender'];
							// Set the receiver as the receiver from the row
							$receiver = $row['receiver'];
							
							if($_SESSION['username'] == $sender) {
								// Make user the receiver
								$_GET['user'] = $receiver;
								
							} else {
								// Make the user the sender
								$_GET['user'] = $sender;
							}
							
						}
						
					} else {
						// There were no message sent from yourself
						echo 'No messages from you';
						$no_message = true;
						
					}
					
				} else {
					// There was an issue with the query
					echo $qrry;
				}
			}
			// In case there was a message
			if($no_message == false) {
			
			// make a query of the messages
			$qrry = 'SELECT * FROM messages WHERE
				sender="'.$_SESSION['username'].'"
				AND receiver = "'.$_GET['user'].'"
			OR
				sender= "'.$_GET['user'].'"
				AND receiver = "'.$_SESSION['username'].'"
				';
			
			$r = mysqli_query($con, $qrry);
			?>

			<?php
			if($r) {

				// If the query was successful then continue
				while($row = mysqli_fetch_assoc($r)) {
					// Set the row with sender, receiver and text to variables
					$sender = $row['sender'];
					$receiver = $row['receiver'];
					$message = $row['text'];
					// Check who is the sender of the message
					if($sender == $_SESSION['username']) {
						
						// If the message was sent by you, show it (in a special style)
						?>

							<div class="you-message">
								<a href="#">You</a>
								<p><?php echo $message; ?></p>
							</div>
						<?php 
						
					} else {
						// If the message was sent by someone else, show it (in a special style)
						?>

							<div class="sender-message">
								<a href="#"><?php echo $sender;?> </a>
								<p><?php echo $message;?></p>
							</div>
						<?php
					}
				
				}	
			}
			else {
				// There was a problem with the query
				echo $qrry;
				}
			}
		?>
	

	<!-- end of messages container -->
	</div>

	<form method="post" id="message-form">
	Sending Message to <?php echo $receiver?>
	<textarea class="textarea" id="text_message" placeholder="Write your message"></textarea>
	</form>
	
<!-- end of right-col-container -->
</div>

<!-- Script that is needed for this program -->
<script src="sub_file/jquery-3.4.1.min.js"></script>

<script>

	// If we want to be able to send message pressing enter
	$("document").ready(function(event) {
		
		// Check first if the form is submitted
		
		$("#right-col-container").on('submit', '#message-form', function() {

			// First place the value that is found in the text area into a variable
			var text_message = $("#text_message").val();
			
			// Next we want the data to be sent to the sending_process.php file
			$.post("sub_file/sending_process.php?user=<?php echo $_GET['user'];?>",
			{
				
				text: text_message,
				
			},
				// In exchange for that, we get this function
				function(data, status) {

				// We want to remove the text from text_message
				$("#text_message").val("");
				
				// So that afterwards we add the data inside of the messages container
				document.getElementById("messages-container").innerHTML += data;
			}
			
			);
		});
		
		// If there was any button that weas clicked inside of the right-col-container
		$("#right-col-container").keypress(function(e) {

			// We want to be able to submit a message by pressing the enter button
			// and only with the enter button without the shiftkey pressed
			if (e.keyCode == 13 && !e.shiftKey) {

				// Submit the form pressing the enter key
				$("#message-form").submit();
			}	
		});
	});

</script>

