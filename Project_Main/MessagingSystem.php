<?php

	// starts  a session
	session_start();
	//header("Refresh:3");
	
	// Checks if user was set else it brings you to the login
	if(isset($_SESSION['username'])) {
	
?>
<!-- Worked on by
40012133	Florin POENARIU f_poenar@encs.concordia.ca -->

<!doctype html>

<html>
<!-- Header and stylesheet for the file, used from register.event stylesheet too -->
<head>
	<link rel="stylesheet" type="text/css" href="css/register_event.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Messages</title>
</head>
<!-- Logout and go to home page buttons -->
<button onclick="window.location.href='authenticate.php'" class="btn"><i class="fa fa-home"></i></button> </b>
<button onclick="window.location.href='logout.php'" class="logout">Log out</button><br><br>


<style>

	/* All the css for the messaging system is in here */
	<?php require_once("css/messaging.css"); ?>

</style>

<body>

	<?php require_once("NewMessage.php"); ?>

	<!--For every individual section of the instant messanger (left column, right column, and 
		the top section) a seperate file was created to keep everything more organized and cleaner -->
			
	<div id="container">
	
		<div id="menu"> 
		<?php 

			// Display username at the top
			echo $_SESSION['username'];

		?>
		</div>
		
		<!-- Left Column, where you can find the name of all the users contacted -->
		<div id="left-col">		
			<?php require_once("LeftColumn.php"); ?>
		</div>
		
		<!-- Right Column, where you can find the  chat log/conversations between yourself and others -->
		<div id="right-col">
			<?php require_once("RightColumn.php"); ?>
		</div>
		
	</div>

</body>

</html>

<?php		
	} /*else {
		header("location:/InstantMessaging/login.php");
		exit();
	}*/
	echo $con->error."<br>";
?> 
