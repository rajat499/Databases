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
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>

<video autoplay muted loop id="myVideo">
  <source src="https://www.meetup.com/mu_static/en-US/video.dddafbfe.mp4" type="video/mp4">
</video>

<div class="content">
  <input type="radio" checked id="loginToggle" name="toggle" class="toggle" />
  <input type="radio" id="signupToggle" name="toggle" class="toggle" />
  <form class="form loginForm framed" action ="./authenticate.php" method="POST">
	<h1 style="color:white;text-align:center;font-family: serif;">SCC</h1></b>
	<p style="color:white;text-align:center;font-family: serif;">Share, Contribute, Comment System</p>
		<input type="text" placeholder="User ID" class="input topInput" required ="required" name= "user_id" id="userid"/>
		<input type="password" placeholder="Password" class="input" required ="required" name= "pwd" id="pwd"/>
		<input type="submit" value="Log in" name="submit" class="input submitInput" />  
    <label for="signupToggle" class="text smallText centeredText">New User? <b>Sign up</b></label>
  </form>
  
  <form class="form signupForm framed"action ="./register_user.php" method="POST">
		<input type="text" placeholder="Name*" class="input topInput" required ="required" name="user_name"/>
		<input type="text" placeholder="Unique User Id*" class="input input" required ="required" name="user_id" />
		<input type="password" placeholder="Password*" class="input" required ="required" name="pwd"/>
		<input type="email" placeholder="Email*" class="input input" required ="required" name="email" />
		<input type="text" placeholder="Organization" class="input" name="orgn"/>
		<input type="submit" value="Register" name="submit" class="input submitInput" />  
    <label for="loginToggle" class="text smallText centeredText">Already a User? <b>Log in</b></label>
  </form>
  
  <div class="credit">
    <a class="text smallText" href="https://www.meetup.com/" target="_blank">Video by <b>Meetup.com</b></a>
  </div>

</html>