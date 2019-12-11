<?php
	session_start();
	include("connection.php");
?>
<!-- Worked on by
40012133	Florin POENARIU f_poenar@encs.concordia.ca -->
<?php
	
	if(isset($_POST['send'])) {
		$sender = $_SESSION['username'];
		$receiver = $_POST['user_name'];
		$message = $_POST['message'];
		//$date = date("Y-m-d h:i:sa");
		
		$qrry = 'INSERT INTO messages (sender,receiver,text)
			VALUES("'.$sender.'","'.$receiver.'", "'.$message.'")';
			
		$row = mysqli_query($conn, $qrry);
		if($row) {
			// Message has been sent
			//header("location:index.php?user=".$receiver);
			echo 
			"<script type='text/javascript'>
				window.location='MessagingSystem.php';
			</script>";
			//exit();
		} else {
			// There is a problem with the query
			echo $qrry;
		}
		
	}

?>

<div id="new-message">

	<p class="message-headers">New Message</p>
	
	<p class="message-background">
		
		<form align="center" method="post">
			
			<input type="text" list="user" onkeyup="CheckInDatabase()" 
					class="user-name-input" placeholder="User Name" 
					name="user_name" id="user_name" />
			
			<!-- this datalist will show available user -->
			
			<datalist id="user"></datalist>
			<br><br>
			
			<textarea class="message-input" name="message" placeholder="Write your message"></textarea><br><br>
			
			<button type="submit" value="Send" id="send" name="send">Send</button>
			
			<button id="send" onclick="document.getElementById('new-message').style.display='none'">Cancel</button>
		</form>
	</p>
	<p class="message-footer"></p>
	<!-- end of new-message -->
</div>

<script src="jquery-3.4.1.min.js"></script>
	
<script>

	//it will disable the send button with refresh page as well
	document.getElementById("send").disabled = true;

	function CheckInDatabase() {
		
		var user_name = document.getElementById("user_name").value;
		
		// send this user_name to another file CheckInDatabase.php 
		$.post("CheckInDatabase.php",
		
		{
			user: user_name
		},
			//we will receive this data from CheckInDatabase.php file
			function(data, status) {
				if(data == '<option value="no user">') {
					
					//if user is registered send button will work
					document.getElementById("send").disabled = true;
				} else {
					//send button will not work
					document.getElementById("send").disabled  = false;
				}
			
			document.getElementById('user').innerHTML = data;
			}
				
		);
	}

</script>
