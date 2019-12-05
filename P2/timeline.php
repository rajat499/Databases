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
    
    echo "Welcome to SCC System. You are a User."."<br>";
    echo "Your username is: ".$_SESSION['username']."<br>";
    
    echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
    echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
    echo "<button onclick='window.location.href=".'"MessagingSystem.php"'."'class='msg'>Messages</button>";
    echo "<button onclick='window.location.href=".'"timeline.php"'."'>Timeline</button>";
    echo "<button onclick='window.location.href=".'"edit_profile.php"'."'class='pro'>Edit Profile</button>";
    echo "<button onclick='window.location.href=".'"register_event.php"'."'class='reg'>Register Event</button><br><br>";

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
        echo "<form action='user_post.php' method='POST' enctype='multipart/form-data'>";
            echo "New Post:<br><textarea name='content' placeholder='Content' rows='6' cols='80'></textarea><br><br>";
            echo "Image: <input type='file' name='img'><br>";
            echo "<button type='submit' name='upload_post'>Post</button>";
        echo "</form>";
    }

    $sql = $conn->query("SELECT * from user_posts where user='$watch' ORDER BY time_stamp DESC");
    if(!$sql){
        echo "Error getting older posts<br>";
        exit();
    }
    echo "<h1>POSTS</h1><br>";
    // echo $sql->num_rows;
    while($post = $sql->fetch_assoc()){
        if($post["content"]=="" && $post["img"]=="")
            continue;
        echo "<div class='post'>";
            if($post["content"]!==""){
                echo $post["content"]."<br>";
            }
            if($post["img"]!==""){
                $path = $post["img"];
                echo "<img src='$path' height='400' width='450' alt='$path'><br>";
            }
            echo "Posted by You:". $post['user'] ."<br>";
            echo "<form action='view_post.php' method='POST'>";
                echo "<input type='hidden' name='type' value='user'>";
                echo "<button type='submit' name= 'view_post' value='".$post["postid"]."'>View this post</button>";
            echo "</form>";
        echo "</div>";
    }
    // echo $sql->num_rows;
    $sql = $conn->query("SELECT * from shared_posts where user='$watch' ORDER BY time_stamp DESC");
    if(!$sql){
        echo "Error getting older posts<br>";
        exit();
    }

    while($from = $sql->fetch_assoc()){
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
            if($post["content"]!==""){
                echo $post["content"]."<br>";
            }
            if($post["img"]!==""){
                $path = $post["img"];
                echo "<img src='$path' height='400' width='450' alt='$path'><br>";
            }
            echo "Shared $type Content . Originally Posted by:". $post['user'] ."<br>";
            if($from["user"] == $user){
                echo "<form action='' method='POST'>";
                    echo "<input type='hidden' name='type' value='$type'>";
                    echo "<button type='submit' name= 'delete_shared_post' value='".$from["id"]."'>Delete this post</button>";
                echo "</form>";
            }
            if($type=="user"){
                echo "<form action='view_post.php' method='POST'>";
                    echo "<input type='hidden' name='type' value='$type'>";
                    echo "<button type='submit' name= 'view_post' value='".$post["postid"]."'>View the original post</button>";
                echo "</form>";
            }
        echo "</div>";
    }
    
?>