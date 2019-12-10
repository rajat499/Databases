<!-- This checks inside inside of the database for users -->
<?php

	// connection.php is always required
	include("connection.php");

	if(isset($_POST['user'])) {
		
		$qrry = 'SELECT * FROM `users` WHERE `user_name`="'.$_POST['user'].'"';
		$r = mysqli_query($conn, $qrry);
		
		if($r) {
			
			if(mysqli_num_rows($r) > 0) {
				
				// If there's more than 0 rows that means the user is in the database
				while($row = mysqli_fetch_assoc($r)) {
					
					// So we want to fetch the username
					$user_name = $row['user_name'] ;
					
					// Shows the list of users
					echo '<option value="'.$user_name.'">';
				
				}
			}else{
					// In case there is no user found
					echo '<option value="no user">';
				}
			} else {

				// Problem with the query
				echo $qrry;
			}
		}	
	
?>