<?php
    session_start();
    include("connection.php");

    $_userid = $_POST["user_id"];
    $_username = $_POST["user_name"];
    $_pass = $_POST["pwd"];
    $_email = $_POST["email"];
    $_orgn = $_POST["orgn"];

    $sql = "INSERT INTO users_info VALUES('$_userid', '$_username', '$_pass', '$_email')";

    if($conn->query($sql) !== TRUE){
        if($conn->error == "Duplicate entry '$_userid' for key 'PRIMARY'"){
            echo "Error: UserID '".$_userid."' is already taken. Try a new one.<br>";
        }
        else if($conn->error == "Duplicate entry '$_email' for key 'email'"){
            echo "Error: Email '$_email' is already registered with us. Try a new one.<br>";
        }
        else{
            echo "One of the entry is too long. Keep in mind:<br>UserID should be less than 15 characters<br>";
            echo "Your name should be less than 255 characters<br>";
            echo "Password should be less than 16 characters<br>";
            echo "Email-id should be less than 30 characters<br>Organization name to be less than 255 characters<br>";
        }
    }
    else{
        echo "Congratulations! You have been registered.<br>";
        echo "Your UserID is: ".$_userid."<br>";
        echo "Please use the same to login into our system.<br>";
    }
    include("login.php");
?>