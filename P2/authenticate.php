<?php

$conn = new mysqli("krc353.encs.concordia.ca", "krc353_2", "qNbKfe");

if ($conn->connect_error) {
    die("Connection to the MySQL server failed: " . $conn->connect_error);
}

$conn->select_db('krc353_2');
$user_id = $_POST["user_id"];
$pwd = $_POST["pwd"];
// echo "User ID:".$user_id."<br>";
// echo "Password:".$pwd."<br>";
$login_query = "SELECT * FROM users_info WHERE userid='$user_id' AND pass='$pwd'";

$result = $conn->query($login_query);

if (!$result) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

$count = $result->num_rows;
session_start();

if($count==1){
    $row = $result->fetch_assoc();
    if($pwd == $row["pass"]){
        $_SESSION['username']=$row["userid"];
        $_SESSION['password']=$row["pass"];

        // echo "Username: ".$_SESSION['username']."<br>";
        // echo "Password: ".$_SESSION['password']."<br>";
        
        if(($row["username"] == "System Administrator") && ($row["userid"] == "sysadmn")){
            header("location:sysadmn.php");
            exit();
        }
        else if(($row["username"] == "Controller") && ($row["userid"] == "controller")){
            header("location:controller.php");
            exit();
        }
        else{
            header("location:users.php");
            exit();
        }
    }
    else{
        echo 
        "<script type='text/javascript'>
            alert('Wrong Username or Password');
            window.location='login.php';
        </script>";
    }
}
else{
    echo 
    "<script type='text/javascript'>
        alert('Wrong Username or Password');
        window.location='login.php';
    </script>";
}

$conn->close();
?>