<?php
    session_start();
    echo "Welcome to SCC System. You are a System Administrator."."<br>";
    echo "Your username is: ".$_SESSION["username"]."<br>";
    echo "Your password is: ".$_SESSION["password"]."<br>";
?>