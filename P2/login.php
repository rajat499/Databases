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
		<input type="text" placeholder="Name" class="input topInput" required ="required" name="user_name"/>
		<input type="text" placeholder="Unique User Id" class="input input" required ="required" name="user_id" />
		<input type="password" placeholder="Password" class="input" required ="required" name="pwd"/>
		<input type="email" placeholder="Email" class="input input" required ="required" name="email" />
		<input type="text" placeholder="Organization" class="input" name="orgn"/>
		<input type="submit" value="Register" name="submit" class="input submitInput" />  
    <label for="loginToggle" class="text smallText centeredText">Already a User? <b>Log in</b></label>
  </form>
  
  <div class="credit">
    <a class="text smallText" href="https://www.meetup.com/" target="_blank">Video by <b>Meetup.com</b></a>
  </div>

<style>
html {
  width: 100%;
  height: 100%;
  margin: 0;
  font-family: Helvetica, Arial, sans-serif;
  overflow: hidden;
}

body {
  width: 100%;
  height: 100%;
  margin: 0;
  font-family: Helvetica, Arial, sans-serif;
  overflow: hidden;
}

.toggle {
  position: absolute;
  left: -100%;
}

.framed {
  position: absolute;
  top: 50%; left: 50%;
  width: 20rem;
  margin-left: -10rem;
}

.form {
  margin-top: -4.5em;
  transition: 1s ease-in-out;
}

.input {
  -moz-box-sizing: border-box;
       box-sizing: border-box;
  font-size: 1rem;
  line-height: 3rem;
  width: 100%; height: 3rem;
  color: #444;
  background-color: rgba(255,255,255,.9);
  border: 0;
  border-top: 1px solid rgba(255,255,255,0.7);
  padding: 0 1rem;
  font-family: 'Open Sans', sans-serif;
}

.input:focus {
  outline: 1.5px solid blue;
}
  
.topInput {
  border-radius: 0.5rem 0.5rem 0 0;
  border-top: 0;
}

.submitInput {
  background-color: hsla(14, 100%, 53%, 0.6);
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  border-top: 0;
  border-radius: 0 0 0.5rem 0.5rem;
  margin-bottom: 1rem;
}

.text {
  color: #fff;
  text-shadow: 0 1px 1px rgba(0,0,0,0.8);
  text-decoration: none;
}
  
.smallText {
  opacity: 0.85;
  font-size: 0.75rem;
  cursor: pointer;
}

.smallText:hover {
  opacity: 1;

}

.centeredText {
  display: block;
  text-align: center;
}

.credit {
  position: absolute;
  right: 1.125rem; bottom: 1.125rem;
}

#loginToggle:checked ~ .signupForm { left:200%; visibility:hidden; }
#signupToggle:checked ~ .loginForm { left:-100%; visibility:hidden; }

#myVideo {
  position: fixed;
  right: 0;
  bottom: 0;
  min-width: 100%; 
  min-height: 100%;
}
</style>

</html>