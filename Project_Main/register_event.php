<?php
    session_start();
?>

<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca

Integration of CSS by
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40036565	Sadia Anowara Smitha s_smitha@encs.concordia.ca -->

<?php     
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])){ 
        register_bank($_POST['accno'],$_POST['bankname'],$_POST['accname'],$_POST['address']);
        register_event($_POST['eventname'], $_POST["startdate"], $_POST["enddate"],$_POST["descr"], $_POST["orgn"], $_SESSION["username"], $_POST['accno'], $_POST['bankname']);
    }


    function register_event($eventname, $startdate, $enddate, $descr, $orgnType, $eventmanager, $debitaccno, $debitaccname){
        include("connection.php");
        $sql = $conn->query("SELECT * from charge_slab where id=1");
        $sql = $sql->fetch_assoc();
        $fee = $sql["charge"];
        $sql =     "INSERT INTO events_info(eventname, eventstatus, startdate, enddate, descr, orgnType, eventmanager, debitaccno, debitbankname, fee, charge_slab) VALUES 
                    ('$eventname', 1, '$startdate', '$enddate', '$descr', '$orgnType', '$eventmanager', $debitaccno, '$debitaccname', $fee, 1 )";
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
    <title>Register an Event</title>
    </head>
<body>
	<button onclick="window.location.href='authenticate.php'" class="btn"><i class="fa fa-home"></i></button> </b>
    <button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>;
	<button onclick="window.location.href='logout.php'" class="logout">Log out</button><br><br>
  <h1>Register an Event</h1>
  <div class="form-container">
    <form action="register_event.php" method="POST">
  	<div class='first'>
      <b>Event Details</b><br><br>
	    <input type="text" name="eventname" placeholder="Event Name" required="required"><br>
        <label>Start Date: </label><input type="date" name="startdate" placeholder="Start Date"  required="required"><br>
        <label>End Date: </label><input type="date" name="enddate"  placeholder="End Date" required="required"><br>
        <label>Description of the Event:</label><br><textarea name="descr" rows='6'></textarea><br>
        <br>
	<select class="orgn" name="orgn" placeholder="Select Organization Type" required>
                                        <option value="">Select Organization Type</option>
                                        <option value="Non-Profit">Non-Profit</option>
                                        <option value="Family">Family</option>
                                        <option value="Community">Community</option>
                                        <option value="Other">Other</option>
		</select></b><br><br><br>
	</div>
	<div class='second'>
        
           <b>Debit Details</b><br>
           <label>Account Number</label><input type="number" placeholder="8-Digit" name="accno" required="required" min="10000000" max="99999999"><br><br>
            <input type="text" placeholder="Account Name*" name="accname"  required="required"><br><br><br>
           <input type="text" placeholder=" Address" name="address"><br><br>
            <select class="bankname" placeholder="Bank Name*" name="bankname" required>
                                        <option value="">Select Bank Name</option>
                                        <option value="Royal Bank">Royal Bank</option>
                                        <option value="Federal Bank">Federal Bank</option>
                                        <option value="National Bank">National Bank</option>
                                        <option value="State Bank">State Bank</option>
                                        </select><br><br>        
	<br><br>

	<input class="button" type="submit" value="Register" name="register"/>

	</div>

     </form>
  </div>
</body>
</html>
