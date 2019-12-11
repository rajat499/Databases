<?php
session_start();
?>

<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca -->

<?php
include("connection.php");

$user_id = $_POST["user_id"];
$pwd = $_POST["pwd"];
if(isset($_SESSION["username"])){
    $user_id = $_SESSION["username"];
}
if(isset($_SESSION["password"])){
    $pwd = $_SESSION["password"];
}

$login_query = "SELECT * FROM users_info WHERE userid='$user_id' AND pass='$pwd'";

$result = $conn->query($login_query);

if (!$result) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

$count = $result->num_rows;

if($count==1){
    $row = $result->fetch_assoc();
    if($pwd == $row["pass"]){
        $_SESSION['username']=$row["userid"];
        $_SESSION['password']=$row["pass"];

        // echo "Username: ".$_SESSION['username']."<br>";
        // echo "Password: ".$_SESSION['password']."<br>";
        
        if(($row["username"] == "System Administrator") && ($row["userid"] == "sysadmn")){
            header("location:access_role.php");
            exit();
        }
        else if(($row["username"] == "Controller") && ($row["userid"] == "controller")){
            header("location:access_role.php");
            exit();
        }
        else{
            header("location:users.php");
            exit();
        }
    }
    else{
        unset($_SESSION["username"]);
        unset($_SESSION["password"]);
        echo 
        "<script type='text/javascript'>
            alert('Wrong Username or Password');
            window.location='login.php';
        </script>";
    }
}
else{
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
    echo 
    "<script type='text/javascript'>
        alert('Wrong Username or Password');
        window.location='login.php';
    </script>";
}

$conn->close();
?>