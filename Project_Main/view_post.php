<?php
	session_start();
?>

<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca

Integration of CSS by
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40036565	Sadia Anowara Smitha s_smitha@encs.concordia.ca
40012133	Florin POENARIU f_poenar@encs.concordia.ca -->

<?php 
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['delete_post']))){
        $type = $_POST["type"];
        $postid = $_POST["delete_post"];
        $q = "";
        $img = "";
        if($type=="event"){
            $img = "SELECT img from event_posts where postid=$postid";
            $q = "DELETE FROM event_posts where postid=$postid";
        }
        else if($type=="user"){
            $img = "SELECT img from user_posts where postid=$postid";
            $q = "DELETE FROM user_posts where postid=$postid";
        }
        else if($type=="group"){
            $img = "SELECT img from group_posts where postid=$postid";    
            $q = "DELETE FROM group_posts where postid=$postid";
        }
        else{
            echo "Couldn't Delete Post. Unrecognized Post Type.";
            exit();
        }
        // Delete Image from Server
        $img = $conn->query($img);
        if(!$img){
            echo $conn->error." Couldn't Find Image.<br>";
            exit();
        }
        $img = $img->fetch_assoc();
        $img = $img["img"];
        if($img!==""){
            if(!unlink($img))
                echo "Couldn't delete image".$img;
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

    echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button>";
    echo "<em style='font-family:Brush script mt; font-size:25px; color:white'>SCC System. You are $user</em><br><br><br>";
    
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
            $q2 = "SELECT * from comments where post_type='event' AND event_postid=$postid ORDER BY time_stamp ASC";
            echo "<em style='font-family:Brush script mt; font-size:25px; color:white'>This is an event post</em><br><br><br>";
        }
        else if($type=="user"){
            $q1 = "SELECT * from user_posts where postid=$postid";
            $q2 = "SELECT * from comments where post_type='user' AND user_postid=$postid ORDER BY time_stamp ASC";
            echo "<em style='font-family:Brush script mt; font-size:25px; color:white'>This is a user post</em><br><br><br>";
        }
        else if($type=="group"){
            $q1 = "SELECT * from group_posts where postid=$postid";
            $q2 = "SELECT * from comments where post_type='group' AND group_postid=$postid ORDER BY time_stamp ASC";
            echo "<em style='font-family:Brush script mt; font-size:25px; color:white'>This is a group post</em><br><br><br>";
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

        $post = $post->fetch_assoc();
        echo "<div class = 'post'>";
            if($post["content"]!==""){
                echo "<b style='color:blue'>".$post['user'] .": </b>";
                echo "<small style='color:white; font-size:16px'>".$post["content"]."</small><br><br>";
            }
            if($post["img"]!==""){
                $path = $post["img"];
                echo "<img src='$path' height='750' width='800' alt='$path'><br>";
                echo "<b style='color:blue'>Posted by ".$post['user'] ." </b>";
            }
            
           if($user==$post['user']){
                echo "<form action='' method='POST'>";
                    echo "<input type='hidden' name='type' value='$type'>";
                    echo "<input type='hidden' name='view_post' value='$postid'>";
                    echo "<br><button type='submit' name= 'delete_post' value='$postid' class='delpost'><i class='fa fa-trash'></i></button>";
                    echo "<button type='submit' name= 'share_post' value='$postid'class='share'><i class='fa fa-share-alt'></i></button>";
               echo "</form>";
            }
            else{
                echo "<form action='' method='POST'>";
                   echo "<input type='hidden' name='type' value='$type'>";
                   echo "<input type='hidden' name='view_post' value='$postid'>";
                    echo "<button type='submit' name= 'share_post' value='$postid'class='share'><i class='fa fa-share-alt'></i></button>";
                echo "</form>";
            }
	
        echo "</div>";

        $comments = $conn->query($q2);
        if(!$comments){
            echo "Error getting the post";
            exit();
        }
        echo "<div class='commenttextarea'>";
        echo "<h3>Comment Box</h3>";
        echo "<form action='' method='POST'>";
            echo "<textarea class='commentbox' name='comment' placeholder='New Comment' rows='4' cols='50' required></textarea>";
            echo "<input type='hidden' name='type' value='$type'>";
            echo "<input type='hidden' name='view_post' value='$postid'>";
            echo "<br><button type='submit' name='post_comment' value='$postid'class='postpost'>Post</button></div>";

        echo "</form>";
        echo "<div class= 'commentstitle'><br>--------------------------------------------COMMENTS SECTION--------------------------------------------<br><br></div>";
        echo "<div class = 'comment-box'>";
            while($comment = $comments->fetch_assoc()){
                echo "<div class='comment'>";
                echo "<div class='commentsection'><img src='images/pikachu.jpg' class='image'/></div>";  
                    if($comment["content"]!==""){
                        echo "<div id='titlecomment'><b>".$comment['user']."   Commented </b></div>";
                        echo "<br> <div id='comments'>";
                        echo $comment["content"]."<br></div>";
                        
                    }
                    
                    if($user==$comment['user']){
                        echo "<form action='' method='POST'>";
 
                        echo "<div class='commentsection'><input type='hidden' name='type' value='$type'></div>";
                        echo "<div class='commentsection'><input type='hidden' name='view_post' value='$postid'></div>";
                        echo "<div class='commentsection'><button type='submit' name= 'delete_comment' value='".$comment["commentid"]."'class='delcom'><i class='fa fa-trash'></i></button></div>";
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

<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/view_post.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>View a Post</title>
    </head>
</html>
