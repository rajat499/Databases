
<div id="right-col-container">

	<div id="messages-container">
	
		<?php
			$con = mysqli_connect("krc353.encs.concordia.ca", "krc353_2", "qNbKfe", "krc353_2");
			$no_message = false;
		
			if(isset($_GET['user'])){
				
				$_GET['user'] = $_GET['user'];	
				
			} else {
				// The user variable is not in the url bar, so we want to add it there from \
				// the last message sent to user
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
			
			if($no_message == false) {
			
			$qrry = 'SELECT * FROM messages WHERE
				sender="'.$_SESSION['username'].'"
				AND receiver = "'.$_GET['user'].'"
			OR
				sender= "'.$_GET['user'].'"
				AND receiver = "'.$_SESSION['username'].'"
				';
			
			$r = mysqli_query($con, $qrry);
			
			if($r) {
				//query successful
				while($row = mysqli_fetch_assoc($r)) {
					$sender = $row['sender'];
					$receiver = $row['receiver'];
					$message = $row['text'];
					
					//check who is the sender of the message
					if($sender == $_SESSION['username']) {
						
						// If the message was sent by you, show it (in a special style)
						?>
							<div class="you-message">
								<a href="authenticate.php">You</a>
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
	<textarea class="textarea" id="text_message" placeholder="Write your message"></textarea>
	</form>
	
<!-- end of right-col-container -->
</div>

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
	
				//first remove the text from
				//text_message so
				
				$("#text_message").val("");
				
				//now add the data inside 
				//the message container
				document.getElementById("messages-container").innerHTML += data;
			}
			
			);
		});
		
		// if any button is clicked inside		
		// right-col-container
		
		$("#right-col-container").keypress(function(e) {
			//as we will submit the form with enter button so
			if (e.keyCode == 13 && !e.shiftKey) {
				//it means enter is clicked without shift key
				// so submit the form
				$("#message-form").submit();
			}
			
		});
		
		
	});

</script>

