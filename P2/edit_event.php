<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";
    echo "<a href='users.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>";
    $event = $_GET["event"];
    if($event==""){
        echo "Event Not Specified<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    $sql = $conn->query("SELECT * from events_info where eventid='$event'");
    if(!$sql){
        echo "Error getting details of events. ".$conn->error."<br>";
        exit();
    }

    $result = $sql->fetch_assoc();
    if($result["eventmanager"] !== $user){
        echo "You are not a manager of this event.<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    echo "<b>Edit the Details</b><br>";
    echo "<form action='' method='POST'>";
    $eventname = $result['eventname'];
    echo "Event Name:   &emsp; <input type='text' name='eventname' value='$eventname'><br>";
    $startdate = $result["startdate"];
    echo "Start Date: &emsp;<input type='date' name='startdate' value='$startdate'><br>";
    $enddate = $result["enddate"];
    echo "Start Date: &emsp;<input type='date' name='enddate' value='$enddate'><br>";
    $organizations = array("Non-Profit Organization", "Family", "Community", "Other");
    echo "Organization Type: &emsp; <select name='orgn'>";
    $orgn = $result["orgnType"];
    echo "<option value='$orgn'>$orgn</option>";
    foreach($organizations as $organization){
        if($organization !== $orgn){
            echo "<option value='$organization'>$organization</option>";
        }
    }
    echo "</select><br><br>";

    echo "<b> Debit Details of the event:</b><br>";
    $accno = $result["debitaccno"];
    echo "Account Number: &emsp; <input type='number' value='$accno' name='accno' min='10000000' max='99999999'><br>";
    $bankname = $result["debitbankname"]; 
    $bankdetails = $conn->query("SELECT * from acc_details where accno=$accno and bankname='$bankname'");
    $bankdetails = $bankdetails->fetch_assoc();
    $accname = $bankdetails["accname"];
    echo "Account Name: &emsp; <input type='text' value='$accname' name='accname'><br>";
    $banks = array("ABC", "RBC", "XYZ", "TDBC");
    echo "Bank Name: &emsp; <select name='bankname'>";
    echo "<option value='$bankname'>$bankname</option>";
    foreach($banks as $bank){
        if($bank !== $bankname){
            echo "<option value='$bank'>$bank</option>";
        }
    }
    echo "</select><br>";
    $address = $bankdetails["address"];
    echo "Address:&emsp; <input type='text' name='address' value='$address'><br>";
    echo "<input type='submit' value='Edit Details' name='edit_details'>";
    echo "</form>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit_details'])){ 
        register_bank($_POST['accno'],$_POST['bankname'],$_POST['accname'],$_POST['address']);
        edit_event($event, $_POST['eventname'], $_POST["startdate"], $_POST["enddate"], $_POST["orgn"], $_POST['accno'], $_POST['bankname']);
    }

    function edit_event($eventid, $eventname, $startdate, $enddate, $orgnType, $debitaccno, $debitbankname){
        include("connection.php");
        $sql = $conn->query("UPDATE events_info SET eventname='$eventname', startdate='$startdate', enddate='$enddate', orgnType='$orgnType', debitaccno='$debitaccno', debitbankname='$debitbankname' WHERE eventid='$eventid'");
        if(!$sql){
            echo "Error in query: ".$conn->error." <br>";
            exit();
        }
        echo
        "<script type='text/javascript'>
            alert('Updated info for the the event: $eventname');
            window.location='manage_event.php?event=$eventid';
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
