<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }
    echo "<link rel='stylesheet' type='text/css' href='css/edit_event.css' />";

   // echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";

	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";

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
    if($result["eventmanager"] !== $user && $user!=="sysadmn"){
        echo "You are not a manager of this event.<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    echo "<h1>Edit Event Details</h1>";
    echo "<div class='form-container'>";
        echo "<form action='' method='POST'>";
    echo"<div class='first'>";
    echo "<b>Event Details</b><br><br>";
    $eventname = $result['eventname'];
    echo "Event Name: &emsp;<input type='text' name='eventname' value='$eventname'><br>";
    $startdate = $result["startdate"];
    echo "Start Date: &emsp;<input type='date' name='startdate' value='$startdate'><br>";
    $enddate = $result["enddate"];
    echo "End Date: &emsp;<input type='date' name='enddate' value='$enddate'><br>";
    $descr = $result["descr"];
    echo "Description of the Event:<br><textarea name='descr' rows='4'>$descr</textarea><br><br>";
    $organizations = array("Non-Profit", "Family", "Community", "Other");
    echo "Organization Type: &emsp;<br><select class='orgn' placeholder='Select Organization Type' name='orgn'>";
    $orgn = $result["orgnType"];
    echo "<option value='$orgn'>$orgn</option>";
    foreach($organizations as $organization){
        if($organization !== $orgn){
            echo "<option value='$organization'>$organization</option>";
        }
    }
    echo "</select><br>";
	echo'</div>';

	echo"<div class='second'>";
    echo "<b>Debit Details</b><br><br>";
    $accno = $result["debitaccno"];
    echo "Account Number: &emsp; <input type='number' value='$accno' name='accno' min='10000000' max='99999999'><br>";
    $bankname = $result["debitbankname"]; 
    $bankdetails = $conn->query("SELECT * from acc_details where accno=$accno and bankname='$bankname'");
    $bankdetails = $bankdetails->fetch_assoc();
    $accname = $bankdetails["accname"];
    echo "Account Name: &emsp; <input type='text' value='$accname' name='accname'><br><br>";
    $address = $bankdetails["address"];
    echo "Address:&emsp; <input type='text' name='address' value='$address'><br>";
    $banks = array("Royal Bank", "Federal Bank", "National Bank", "State Bank");
    echo "Bank Name: &emsp;<br><select class='bankname' name='bankname'>";
    echo "<option value='$bankname'>$bankname</option>";
    foreach($banks as $bank){
        if($bank !== $bankname){
            echo "<option value='$bank'>$bank</option>";
        }
    }
    echo "</select><br><br><br>";
    



        echo"<input style='text-align:center; position: absolute;' class='button' type='submit' value='Edit Details' name='edit_details'/>";
	echo "</div>";

echo "</form>";
echo "</div>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit_details'])){ 
        register_bank($_POST['accno'],$_POST['bankname'],$_POST['accname'],$_POST['address']);
        edit_event($event, $_POST['eventname'],$_POST["descr"], $_POST["startdate"], $_POST["enddate"], $_POST["orgn"], $_POST['accno'], $_POST['bankname']);
    }

    function edit_event($eventid, $eventname,$descr, $startdate, $enddate, $orgnType, $debitaccno, $debitbankname){
        include("connection.php");
        $sql = $conn->query("UPDATE events_info SET eventname='$eventname', descr='$descr', startdate='$startdate', enddate='$enddate', orgnType='$orgnType', debitaccno='$debitaccno', debitbankname='$debitbankname' WHERE eventid='$eventid'");
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
	<link rel="stylesheet" type="text/css" href="css/edit_event.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit Event</title>
    </head>
</html>
