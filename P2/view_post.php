<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"users.php"'."' class='btn'><i class='fa fa-home'></i></button>";
    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
    
    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['delete_post']))){
        $type = $_POST["type"];
        $postid = $_POST["delete_post"];
        $q = "";
        if($type=="event"){
            $q = "DELETE FROM event_posts where postid=$postid";
        }
        else if($type=="user"){
            $q = "DELETE FROM user_posts where postid=$postid";
        }
        else if($type=="group"){    
            $q = "DELETE FROM group_posts where postid=$postid";
        }
        else{
            echo "Couldn't Delete Post. Unrecognized Post Type.";
            exit();
        }

        $q = $conn->query($q);
        if(!$q){
            echo $conn->error." Couldn't Delete Post.<br>";
            exit();
        }

        echo 
        "<script type='text/javascript'>
            alert('Post deleted successfully.');
            window.location='users.php';
        </script>";
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['share_post']))){
        $type = $_POST["type"];
        $postid = $_POST["share_post"];
        $q = "";
        if($type=="event"){
            $q = "INSERT INTO shared_posts(user, post_type, event_postid) VALUES('$user', '$type', $postid)";
        }
        else if($type=="user"){
            $q = "INSERT INTO shared_posts(user, post_type, user_postid) VALUES('$user', '$type', $postid)";
        }
        else if($type=="group"){    
            $q = "INSERT INTO shared_posts(user, post_type, group_postid) VALUES('$user', '$type', $postid)";
        }
        else{
            echo "Couldn't Share Post. Unrecognized Post Type.";
            exit();
        }

        $q = $conn->query($q);
        if(!$q){
            echo $conn->error." Couldn't Share Post.<br>";
            exit();
        }

        echo 
        "<script type='text/javascript'>
            alert('Post Shared successfully.');
            window.location='timeline.php';
        </script>";
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['post_comment']))){
        $type = $_POST["type"];
        $content = $_POST["comment"];
        $postid = $_POST["post_comment"];
        $q = "";
        if($type=="event"){
            $q = "INSERT INTO comments(content, user, post_type, event_postid) VALUES('$content', '$user', '$type', $postid)";
        }
        else if($type=="user"){
            $q = "INSERT INTO comments(content, user, post_type, user_postid) VALUES('$content', '$user', '$type', $postid)";
        }
        else if($type=="group"){    
            $q = "INSERT INTO comments(content, user, post_type, group_postid) VALUES('$content', '$user', '$type', $postid)";
        }
        else{
            echo "Couldn't Post Comment. Unrecognized Post Type.";
            exit();
        }

        $q = $conn->query($q);
        if(!$q){
            echo $conn->error." Couldn't Insert Comment.<br>";
            exit();
        }
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['delete_comment']))){
        $comment_id = $_POST["delete_comment"];
        $q = $conn->query("DELETE FROM comments where commentid = $comment_id");
        if(!$q){
            echo $conn->error." Couldn't Delete Comment.<br>";
            exit();
        }
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['view_post']))){
        $postid = $_POST["view_post"];
        if(!isset($_POST["view_post"])){
            echo "No Post Found.";
            exit();
        }
        $type = $_POST["type"];
        $q1 = "";
        $q2 = "";

        if($type=="event"){
            $q1 = "SELECT * from event_posts where postid=$postid";
            $q2 = "SELECT * from comments where post_type='event' AND event_postid=$postid ORDER BY time_stamp DESC";
        }
        else if($type=="user"){
            $q1 = "SELECT * from user_posts where postid=$postid";
            $q2 = "SELECT * from comments where post_type='user' AND user_postid=$postid ORDER BY time_stamp DESC";
        }
        else if($type=="group"){
            $q1 = "SELECT * from group_posts where postid=$postid";
            $q2 = "SELECT * from comments where post_type='group' AND group_postid=$postid ORDER BY time_stamp DESC";
        }
        else{
            echo "Unrecognized Post Type.";
            exit();
        }

        $post = $conn->query($q1);
        if(!$post){
            echo "Error getting the post";
            exit();
        }
        echo "<h1>POST</h1>";
        $post = $post->fetch_assoc();
        echo "<div class = 'post'>";
            if($post["content"]!==""){
                echo $post["content"]."<br>";
            }
            if($post["img"]!==""){
                $path = $post["img"];
                echo "<img src='$path' height='400' width='450' alt='$path'><br>";
            }
            echo "Posted by:". $post['user'] ."<br>";
            if($user==$post['user']){
                echo "<form action='' method='POST'>";
                    echo "<input type='hidden' name='type' value='$type'>";
                    echo "<input type='hidden' name='view_post' value='$postid'>";
                    echo "<button type='submit' name= 'delete_post' value='$postid'>Delete this post</button>";
                echo "</form>";
            }
            echo "<form action='' method='POST'>";
                echo "<input type='hidden' name='type' value='$type'>";
                echo "<input type='hidden' name='view_post' value='$postid'>";
                echo "<button type='submit' name= 'share_post' value='$postid'>Share this post</button>";
            echo "</form>";
        echo "</div>";

        $comments = $conn->query($q2);
        if(!$comments){
            echo "Error getting the post";
            exit();
        }
        echo "<h1>Comment Box</h1>";
        echo "<form action='' method='POST'>";
            echo "<br><textarea name='comment' placeholder='New Comment' rows='4' cols='50' required></textarea><br><br>";
            echo "<input type='hidden' name='type' value='$type'>";
            echo "<input type='hidden' name='view_post' value='$postid'>";
            echo "<button type='submit' name='post_comment' value='$postid'>Post Comment</button>";
        echo "</form>";
        echo "<div class = 'comment-box'>";
            while($comment = $comments->fetch_assoc()){
                echo "<div class='comment'>";
                    if($comment["content"]!==""){
                        echo $comment["content"]."<br>";
                    }
                    echo "Posted by:". $comment['user'] ."<br>";
                    if($user==$comment['user']){
                        echo "<form action='' method='POST'>";
                            echo "<input type='hidden' name='type' value='$type'>";
                            echo "<input type='hidden' name='view_post' value='$postid'>";
                            echo "<button type='submit' name= 'delete_comment' value='".$comment["commentid"]."'>Delete this Comment</button>";
                        echo "</form>";
                    }
                echo "</div>";
            }
        echo "</div>";

    }
    else{
        echo 
        "<script type='text/javascript'>
            alert('Likely an Illegal Access');
            window.history.go(-1);
        </script>";
        exit();
    }
?>