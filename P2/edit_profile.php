<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    $sql = "SELECT * from  users_info where userid='$user'";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of the user:$user ".$conn->error."<br>";
        exit();
    }

    echo "<a href='users.php'>Go Back to Homepage</a>&emsp;&emsp;";
    echo "<a href='logout.php'>Logout</a><br><br>";

    $result = $result->fetch_assoc();
    echo "<b>Edit Your Profile</b><br>";
    echo "<form action='edit_profile.php' method='POST'>";
    echo "User ID: &emsp;$user<br>";
    $name = $result["username"];
    echo "Name: &emsp;<input type='text' name='name' value='$name'><br>";
    $pwd = $result["pass"];
    echo "Password: &emsp;<input type='text' name='pwd' value='$pwd'><br>";
    $email = $result["email"]; 
    echo "Email-id: &emsp; <input type='text' name='email'  value='$email'><br>";
    $orgn = $result["orgn"];
    echo "Organization: &emsp; <input type='text' name='orgn' value='$orgn'><br>";
    echo '<input type="submit" value="Edit" name="edit"><br>';
    echo "</form>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit'])){ 
        edit_profile($_SESSION["username"], $_POST['name'], $_POST["pwd"], $_POST["email"], $_POST["orgn"]);
    }

    function edit_profile($userid, $username, $pwd, $email, $orgn){
        include ("connection.php");
        $sql = $conn->query("UPDATE users_info SET username='$username', pass='$pwd', email='$email', orgn='$orgn' WHERE userid='$userid'");
        if(!$sql){
            echo "Error in query: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            alert('Updated info for userid: $userid');
            window.location='authenticate.php';
        </script>";
    }

?>


