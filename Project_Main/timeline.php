<?php

    session_start();
        
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

    $watch = $_GET["watch"];
    if($watch==""){
        $watch = $user;
    }
        echo "<link rel='stylesheet' type='text/css' href='css/timeline.css' />";
    
    echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
    echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button>";
    echo "<button onclick='window.location.href=".'"MessagingSystem.php"'."'class='msg'><i class='fa fa-comments'></i></button>";


    echo "<em style='font-family:Brush script mt; font-size:25px; color:white'>SCC System<br>You are ".$_SESSION['username'].". This is $watch's timeline.</em><br><br><br>";


    echo "<button class='timeline' onclick='window.location.href=".'"timeline.php"'."'>Timeline</button>";
    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['delete_shared_post']))){
        $id = $_POST['delete_shared_post'];
        $q = $conn->query("DELETE from shared_posts where id=$id");
        if(!$q){
            echo $conn->error." Couldn't Delete Post from shared posts.<br>";
            exit();
        }

        echo 
        "<script type='text/javascript'>
            alert('Post deleted successfully.');
            window.location='timeline.php';
        </script>";
    }
        
    
    
    if($watch==$user){
            echo "<div class='commenttextarea'>";
        echo "<form action='user_post.php' method='POST' enctype='multipart/form-data'>";
            echo "New Post:<br><textarea name='content' class='comment' placeholder='Content' rows='6' cols='80'></textarea><br><br>";
            echo "Image:<br><input id='inp' type='file' name='img'></input><br>";
            echo "<button class='postpost' type='submit' name='upload_post'>Post</button>";
        echo "</form><br></div>";
    }

    $sql = $conn->query("SELECT * from user_posts where user='$watch' ORDER BY time_stamp DESC");
    if(!$sql){
        echo "Error getting older posts<br>";
        exit();
    }
    echo "<p class='headers'>POSTS</p><br>";
    // echo $sql->num_rows;
    while($post = $sql->fetch_assoc()){
echo"<div class='postbox'>";
        if($post["content"]=="" && $post["img"]=="")
            continue;
        echo "<div class='post'>";
            if($post["img"]!==""){
                $path = $post["img"];
                echo "<img src='$path' height='400' width='450' alt='$path'><br>";
            }
            if($post["content"]!==""){
                echo "<small style='color:white; font-size:16px'>".$post["content"]."</small><br>";
            }
            echo "<form action='view_post.php' method='POST'>";
                echo "<input type='hidden' name='type' value='user'><br><br>";
                echo "<button class='button2' type='submit' name= 'view_post' value='".$post["postid"]."'>View</button>";
            echo "</form>";
        echo "</div>";
echo"</div>";
echo"<br><br><br>";
    }
    // echo $sql->num_rows;
    $sql = $conn->query("SELECT * from shared_posts where user='$watch' ORDER BY time_stamp DESC");
    if(!$sql){
        echo "Error getting older posts<br>";
        exit();
    }

    while($from = $sql->fetch_assoc()){
echo"<div class='postbox'>";
        $type = $from["post_type"];
        $post = "";
        if($type=="user"){
            $postid = $from["user_postid"];
            $post = "SELECT * from user_posts where postid=$postid";
        }
        else if($type=="event"){
            $postid = $from["event_postid"];
            $post = "SELECT * from event_posts where postid=$postid";
        }
        else if($type=="group"){
            $postid = $from["group_postid"];
            $post = "SELECT * from group_posts where postid=$postid";
        }

        $post = $conn->query($post);
        if(!$post){
            echo "Error getting older post<br> ".$conn->error;
            exit();
        }
        $post = $post->fetch_assoc();

        if($post["content"]=="" && $post["img"]=="")
            continue;
        echo "<div class='post'>";
            if($post["img"]!==""){
                $path = $post["img"];
                echo "<img src='$path' height='400' width='450' alt='$path'><br><br><br>";
            }
            if($post["content"]!==""){
                echo "<small style='color:white; font-size:16px'>".$post["content"]."</small><br>";
            }
            $postedby = $post['user'];
            echo "<small style='color:white; font-size:12px'>Shared $type Content. Originally Posted by </small><a href='timeline.php?watch=$postedby'><b style='font-size:12px; color:blue'>".$postedby."</b></a><br>";
            if($from["user"] == $user){
                echo "<form action='' method='POST'>";
                    echo "<input type='hidden' name='type' value='$type'>";
                    echo "<button class='button2' type='submit' name= 'delete_shared_post' value='".$from["id"]."'><i class='fa fa-trash'></i></button>";
               echo "</form>";
           }
            if($type=="user"){
		    echo"    ";
             echo "<form action='view_post.php' method='POST'>";
                   echo "<input type='hidden' name='type' value='$type'>";
                    echo "<button class='button2' type='submit' name= 'view_post' value='".$post["postid"]."'>View the original post</button>";
                echo "</form>";
            }
        echo "</div>";
echo"</div>";
echo"<br><br><br>";
    }
    
?>

<html>
    <head>
        <title>Timeline</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <head>
</html>
