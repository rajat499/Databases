<?php
	session_start();
?>

<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca-->

<?php 
    
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

        if($img!=""){
            $file_tmp =$_FILES['img']['tmp_name'];
            $file_ext = strtolower(end(explode('.',$img)));
            $check = getimagesize($file_tmp);
            if($check==false){
                echo 
                "<script type='text/javascript'>
                    alert('File is not an image or Image is too large.');
                    window.location.href='timeline.php';
                </script>";
                exit();
            }
            $timestamp = time();
            $img = "uploads/".$user."_"."userimg_".$timestamp.".".$file_ext;
            move_uploaded_file($file_tmp,$img);
        }
        $sql = $conn->query("INSERT INTO user_posts(content, img, user) VALUES('$content','$img','$user')");
        if(!$sql){
            echo 
            "<script type='text/javascript'>
                alert('Error Connecting to Server. Try Posting again.');
                window.location.href='timeline.php';
            </script>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            alert('Posted Successfully');
            window.location.href='timeline.php';
        </script>";
        exit();
        
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