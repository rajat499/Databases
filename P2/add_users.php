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
    
    $event = $_GET["event"];
    if($event==""){
        echo "Event Not Specified<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    $eventdetails = $conn->query("SELECT * from events_info where eventid='$event'");
    if(!$eventdetails){
        echo "Error getting details of events. ".$conn->error."<br>";
        exit();
    }
    $eventdetails = $eventdetails->fetch_assoc();

    if($user!==$eventdetails["eventmanager"] && $user!=="sysadmn"){
        echo "You have no rights to access this page.<br>";
        exit();
    }

    $file_name = "";
    
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES['input_file'])){
        if(isset($_FILES['input_file'])){
            $file_name = $_FILES['input_file']['name'];
            $file_size = $_FILES['input_file']['size'];
            $file_tmp =$_FILES['input_file']['tmp_name'];
            $file_ext = strtolower(end(explode('.',$file_name)));
            
            if($file_ext !== "csv"){
                echo "ONLY CSV FILES ALLLOWED <br>";
                exit();
            }

            move_uploaded_file($file_tmp,"uploads/".$file_name);
        }
        else{
            echo "FILE NOT SPECIFIED.<br>";
            exit();
        }
    }
    
    if($file_name==""){
        echo "FILE NAME NOT SPECIFIED.<br>";
        exit();
    }

    $file_name = "uploads/".$file_name;

    if(($f = fopen($file_name, "r")) !==FALSE){
        echo "Members Added<br>";
        echo "<table>";
        echo "<tr><th>Username</th> <th>Email</th> <th>Old or New</th></tr>";
        while(($line=fgetcsv($f,0,","))!==FALSE){
            echo "<tr>";
            if($line[0]==""){
                echo "Error in this line ".print_r($line)."<br>";
            }else{
                $username = trim($line[0]);
                $email = trim($line[1]);
                if($email==""){
                    echo "Email can't be empty in ".print_r($line)." <br>";
                    echo "</tr>";
                    continue;
                }
                $sql = $conn->query("select * from users_info where email='$email'");
                if($sql){
                    echo "<td>$username</td> <td>$email</td>";
                    if($sql->num_rows>0){
                        $sql = $sql->fetch_assoc();
                        $user = $sql["userid"];
                        $join_event = $conn->query("INSERT INTO participants VALUES('$user','$event')");
                        if(!$join_event){
                            echo "<td>$conn->error</td>";
                            echo "</tr>";
                            continue;
                        }
                        echo "<td>OLD</td>";
                    }
                    else{
                        $new_participant = $conn->query("INSERT INTO users_info(userid,username,pass,email) VALUES('$email','$username', 123, '$email')");
                        $text = "Hi $username!, Welcome to SCC System. You have been registered as a new user. Your login credentials are as follows: 
                                userid-$email and password-123. Please login using the same and change your credentials";
                        $sysemail = $conn->query("INSERT INTO system_emails(user,email,txt) VALUES('$email','$email','$text')");
                        if(!$sysemail){
                            echo "<td>$conn->error</td>";
                            echo "</tr>";
                            continue;
                        }
                        $join_event = $conn->query("INSERT INTO participants VALUES('$email','$event')");
                        if(!$join_event){
                            echo "<td>$conn->error</td>";
                            echo "</tr>";
                            continue;
                        }
                        echo "<td>NEW</td>";
                    }
                }else{
                    echo "<td>$conn->error</td>";
                    echo "</tr>";
                    continue;
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    else{
        echo "Likely the file doesn't exist.<br>";
        exit();
    }
?>