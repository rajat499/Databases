<?php
	session_start();
	if(isset($_SESSION["username"])){
		header("location:authenticate.php");
		exit();
	}
	// if(isset($_SESSION["logout"])){
	// 	echo $_SESSION["logout"];
	// }
?>

<!-- -------------------------------------------------------------
COMP353, Section F, Prof BC DESAI, Project-1
COMP353- Group 11
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40012133	Florin POENARIU f_poenar@encs.concordia.ca
40024628	Avnish PATEL av_pate@encs.concordia.ca
40036565	Sadia Anowara Smitha s_smitha@encs.concordia.ca
40150463	Rajat Jaiswal r_jais@encs.concordia.ca
------------------------------------------------------------- -->

<!-- Login Page on the webpage -->



<html>
	<head>
		<title> SCC System </title>
		<style>
		body{
			/* text-align: center; */
		}
		</style>
	</head>
	<body>	
        <!-- <div align="right"><a href="./admn.php" align="right">Administrator</a></div>  -->
        <b>User Login</b>
		<form action="./authenticate.php" method="POST">
			UserID:   &emsp; <input type="text" name="user_id" id="userid" required="required"><br>
            Password: &emsp; <input type="password" name="pwd" id="pwd" required="required"><br>
			<input type="submit" value="Login" name="submit">
		</form>
		
		<div>
		<br>
        <b> User Sign Up</b>
		<form action="register_user.php" method="POST">
			Name*:   &emsp; <input type="text" name="user_name"  required="required"><br>
			Choose a unique UserID*:   &emsp; <input type="text" name="user_id"  required="required"><br>
            Password*: &emsp; <input type="password" name="pwd"  required="required"><br>
            Email-id*: &emsp; <input type="text" name="email"  required="required"><br>
            Organization: &emsp; <input type="text" name="orgn" ><br>
			<input type="submit" value="Register" name="submit">
		</form>
		</div>
	</body>
</html>
