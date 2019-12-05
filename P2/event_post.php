<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['upload_post']))){
        $content=$_POST["content"];
        $img = "";
        if(isset($_FILES['img'])){
            $img=$_FILES['img']['name'];
        }
        if($content=="" && $img==""){
            echo 
            "<script type='text/javascript'>
                alert('No Content Specified for the post.');
                window.history.go(-1);
            </script>";
            exit();
        }
        if(!isset($_POST['event'])){
            echo 
            "<script type='text/javascript'>
                alert('Likely an Illegal Access.');
                window.history.go(-1);
            </script>";
            exit();
        }
        else{
            $event = $_POST["event"];
            if($img!=""){
                $file_tmp =$_FILES['img']['tmp_name'];
                $file_ext = strtolower(end(explode('.',$img)));
                $check = getimagesize($file_tmp);
                if($check==false){
                    echo 
                    "<script type='text/javascript'>
                        alert('File is not an image or Image is too large.');
                        window.location.href='events.php?event=$event';
                    </script>";
                    exit();
                }
                $timestamp = time();
                $img = "uploads/".$user."_".$event."_"."eventimg_".$timestamp.".".$file_ext;
                move_uploaded_file($file_tmp,$img);
            }
            $sql = $conn->query("INSERT INTO event_posts(content, img, user, event) VALUES('$content','$img','$user',$event)");
            if(!$sql){
                echo 
                "<script type='text/javascript'>
                    alert('Error Connecting to Server. Try Posting again.');
                    window.location.href='events.php?event=$event';
                </script>";
                exit();
            }else{
                echo 
                "<script type='text/javascript'>
                    alert('Posted Successfully');
                    window.location.href='events.php?event=$event';
                </script>";
                exit();
            }
        }
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