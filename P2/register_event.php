<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])){ 
        register_bank($_POST['accno'],$_POST['bankname'],$_POST['accname'],$_POST['address']);
        register_event($_POST['eventname'], $_POST["startdate"], $_POST["enddate"], $_POST["orgn"], $_SESSION["username"], $_POST['accno'], $_POST['bankname']);
    }


    function register_event($eventname, $startdate, $enddate, $orgnType, $eventmanager, $debitaccno, $debitaccname){
        include("connection.php");
        $sql =     "INSERT INTO events_info(eventname, eventstatus, startdate, enddate, orgnType, eventmanager, debitaccno, debitbankname) VALUES 
                    ('$eventname', 1, '$startdate', '$enddate', '$orgnType', '$eventmanager', $debitaccno, '$debitaccname' )";
        $result = $conn->query($sql);
        if(!$result){
            echo "Error in query: ".$conn->error." <br>";
            exit();
        }
        echo
        "<script type='text/javascript'>
            alert('Registered the event: $eventname');
            window.location='authenticate.php';
        </script>";
    }

    function register_bank($accno, $bankname, $accname, $address){
        include("connection.php");
         $sql = $conn->query("SELECT * from acc_details where accno=$accno and bankname='$bankname'");
         if($sql->num_rows==0){
            $sql = $conn->query("INSERT INTO acc_details VALUES($accno,'$bankname', '$accname', '$address')");
            if(!$sql){
                echo "Error in query acc_details: ".$conn->error." $accno <br>";
                exit();
            }
         }
         else{
            $sql = "";
            echo "alert('Address is $address')";
            if($address=="")
                $sql = "UPDATE acc_details SET accname='$accname' WHERE accno=$accno AND bankname='$bankname'";  
            else
               $sql = "UPDATE acc_details SET accname='$accname', address='$address' WHERE accno=$accno AND bankname='$bankname'";
                  
            $result = $conn->query($sql);
            if(!$result){
            echo "Error in query: ".$conn->error." <br>";
            exit();
            }
         }
    }
?>

<html>
    <head>
    <title> Register an event</title>
    </head>
    <body>
        <a href='users.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>
        <b> Register a New Event</b>
        <form action="register_event.php" method="POST">
            Event Name*:   &emsp; <input type="text" name="eventname"  required="required"><br>
			Start Date*:   &emsp; <input type="date" name="startdate"  required="required"><br>
            End Date*: &emsp; <input type="date" name="enddate"  required="required"><br>
            Organization Type*: &emsp; <select name="orgn" required>
                                        <option value="">None</option>
                                        <option value="Non-Profit Organization">Non-Profit Organization</option>
                                        <option value="Family">Family</option>
                                        <option value="Community">Community</option>
                                        <option value="Other">Other</option>
                                        </select><br><br>

            <b> Debit Details of the event:</b><br>
            Account Number*: &emsp; <input type="number" placeholder="8-Digit" name="accno" required="required" min="10000000" max="99999999"><br>
            Account Name*: &emsp; <input type="text" name="accname"  required="required"><br>
            Bank Name*: &emsp; <select name="bankname" required>
                                        <option value="">None</option>
                                        <option value="ABC">ABC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="XYZ">XYZ</option>
                                        <option value="TDBC">TDBC</option>
                                        </select><br>
            Address:   &emsp; <input type="text" name="address"><br>
            <input type="submit" value="Register" name="register">
        </form>
    </body>
</html>