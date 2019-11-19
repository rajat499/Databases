<!-- -------------------------------------------------------------
COMP353, Section F, Prof BC DESAI, Project-1
COMP353- Group 11
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40012133	Florin POENARIU f_poenar@encs.concordia.ca
40024628	Avnish PATEL av_pate@encs.concordia.ca
40036565	Sadia Anowara Smitha s_smitha@encs.concordia.ca
40150463	Rajat Jaiswal r_jais@encs.concordia.ca
------------------------------------------------------------- -->

<!-- Administrator Page on the webpage -->

<html>
	<head>
		<title> Administrator login </title>
		<style>
		body{
			/* text-align: center; */
		}
		</style>
	</head>
	<body>	 
        <b>System Administrator Login</b>
		<form action="./sysadmn.php" method="POST">
			Admin ID:   &emsp; <input type="text" name="user_id" id="userid" required="required"><br>
            Password: &emsp; <input type="password" name="pwd" id="pwd" required="required"><br>
			<input type="submit" value="Login" name="submit">
		</form>
		
		<div>
		<br>
        <b> Controller Login</b>
		<form action="./controller.php" method="POST">
			Controller ID:   &emsp; <input type="text" name="user_id" id="userid" required="required"><br>
            Password: &emsp; <input type="password" name="pwd" id="pwd" required="required"><br>
			<input type="submit" value="Login" name="submit">
		</form>
		</div>
	</body>
</html>
