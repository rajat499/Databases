<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

    echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";

    $sql = "SELECT * from  users_info where userid='$user'";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of the user:$user ".$conn->error."<br>";
        exit();
    }

    $result = $result->fetch_assoc();
    echo "<h1>Edit Your Profile</h1>";
    echo "<div class='form-container'>";
echo "<div class='wrapper'>";
echo"<table>";
    echo "<form action='edit_profile.php' method='POST'>";
    echo "<label>User ID:</label><input type='text' name='newuserid' value='$user'><br>";
    $name = $result["username"];
    echo "<label>Name:</label><input type='text' name='name' value='$name'><br>";
    $pwd = $result["pass"];
    echo "<label>Password</label><input type='text' name='pwd' value='$pwd'><br>";
    $email = $result["email"]; 
    echo "Email-ID: $email<br><br><br>";
    // $organizations = array("Non-Profit", "Family", "Community", "Other");
    // $orgn = $result["orgn"];
    // echo "<label>Organization: </label>";
    // echo "<select class='orgn' name='orgn'>";
    // echo "<option value='$orgn'>$orgn</option>";
    // foreach($organizations as $organization){
    //     if($organization !== $orgn){
    //         echo "<option value='$organization'>$organization</option>";
    //     }
    // }
    echo "</select></b><br><br><br>";
        
    echo '<input style="text-align:center; position: absolute; right: 6;" class="button" type="submit" value="Edit" name="edit"/>';
    echo "</form>";
echo"</table>";
echo"</div>";
    echo "</div>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit'])){ 
        edit_profile($_SESSION["username"],$_POST["newuserid"], $_POST['name'], $_POST["pwd"]);
    }

    function edit_profile($olduserid, $newuserid, $username, $pwd){
        include ("connection.php");
        $sql = $conn->query("UPDATE users_info SET userid='$newuserid', username='$username', pass='$pwd' WHERE userid='$olduserid'");
        if(!$sql){
            if($conn->error == "Duplicate entry '$newuserid' for key 'PRIMARY'"){
                echo "Error: UserID '".$newuserid."' is already taken. Try a new one.<br>";
            }else{
                echo "Error in query: ".$conn->error." <br>";
            }
            exit();
        }
        $_SESSION["username"] = $newuserid;
        echo 
        "<script type='text/javascript'>
            alert('Updated info for username: $username');
            window.location='authenticate.php';
        </script>";
    }

?>
<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/edit_profile.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit Your Profile</title>
    </head>
</html>

