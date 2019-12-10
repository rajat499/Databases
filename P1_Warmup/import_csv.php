<!-- -------------------------------------------------------------
COMP353, Section F, Prof BC DESAI, Project-1
COMP353- Group 11
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40012133	Florin POENARIU f_poenar@encs.concordia.ca
40024628	Avnish PATEL av_pate@encs.concordia.ca
40036565	Sadia Anowara SMITHA s_smitha@encs.concordia.ca
40150463	Rajat JAISWAL r_jais@encs.concordia.ca
------------------------------------------------------------- -->

<!-- PHP File for importing a CSV file in MySQL Database -->
<?php
$servername = "krc353.encs.concordia.ca";

$conn = new mysqli("krc353.encs.concordia.ca", "krc353_2", "qNbKfe");

if ($conn->connect_error) {
    echo "Failed to connect to Database: " . $conn->connect_error."<br>";
}


$conn->select_db('krc353_2');

$sql = "CREATE TABLE IF NOT EXISTS USERS (
    LastName VARCHAR(25),
    FirstName VARCHAR(25),
    MiddleName VARCHAR(25),
    UserID VARCHAR(15) PRIMARY KEY NOT NULL,
    Pass VARCHAR(15) NOT NULL
    )";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating table USERS: " . $conn->error."<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS EVENTS (
    EventName VARCHAR(25),
    EventID INT(10) PRIMARY KEY NOT NULL,
    StartDate DATE,
    EndDate DATE,
    AdminUserID VARCHAR(15),
    FOREIGN KEY(AdminUserID) REFERENCES USERS(UserID)
    )";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating table EVENTS: " . $conn->error."<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS ROLES (
    UserID VARCHAR(15) NOT NULL,
    EventID INT(10) NOT NULL,
    PRIMARY KEY (UserID, EventID),
    FOREIGN KEY(UserID) REFERENCES USERS(UserID),
    FOREIGN KEY(EventID) REFERENCES EVENTS(EventID)
    )";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating table ROLES: " . $conn->error."<br>";
}

echo "<br>";  
echo "<br>";  

$table_num = 0; 

if (($f = fopen($_POST["file_name"], "r")) !== FALSE) 
{
  while (($line = fgetcsv($f, 0, "|")) !== FALSE) 
  {		
    
    if ($line[0]!=""){
        $table_num++;
    }

    else if ((isset($line[4]) ? $line[4] : null) == "userID" || (isset($line[2]) ? $line[2] : null) == "EventID"){
    }

    else if($table_num==1){   
        
        $LastName = !empty($line[1]) ? "'$line[1]'" : "NULL";
        $FirstName = !empty($line[2]) ? "'$line[2]'" : "NULL";
        $MiddleName = !empty($line[3]) ? "'$line[3]'" : "NULL";
        $UserID = !empty($line[4]) ? "'$line[4]'" : "NULL";
        $Pass = !empty($line[5]) ? "'$line[5]'" : "NULL";

        $qry = "SELECT * FROM USERS WHERE USERS.UserID = $UserID";

        if($ans = $conn->query($qry) ){
            if($ans->num_rows == 0){
                $sql = "INSERT INTO USERS VALUES ($LastName, $FirstName, $MiddleName, $UserID, $Pass)";
                if ($conn->query($sql) != TRUE) {
                    echo "Error in query: " . $sql . ". " . $conn->error."<br>";
                }
            }
        }
    }
  
    else if($table_num==2){

        $EventName = !empty($line[1]) ? "'$line[1]'" : "NULL";
        $EventID = !empty($line[2]) ? "'$line[2]'" : "NULL";
        $StartDate = !empty($line[3]) ? "'$line[3]'" : "NULL";
        $EndDate = !empty($line[4]) ? "'$line[4]'" : "NULL";
        $AdminUserID = !empty($line[5]) ? "'$line[5]'" : "NULL";

        $qry = "SELECT * FROM EVENTS WHERE EVENTS.AdminUserID = $AdminUserID";

        if($ans = $conn->query($qry) ){
            if($ans->num_rows == 0){
                $sql = "INSERT INTO EVENTS VALUES ($EventName, $EventID, $StartDate, $EndDate, $AdminUserID)";
                if ($conn->query($sql) != TRUE) {
                    echo "Error in query: " . $sql . ". " . $conn->error."<br>";
                }
            }
        }
    }
    
    else if($table_num==3){

        $UserID = !empty($line[1]) ? "'$line[1]'" : "NULL";
        $EventID = !empty($line[2]) ? "'$line[2]'" : "NULL";

        $qry = "SELECT * FROM ROLES WHERE ROLES.UserID = $UserID AND ROLES.EventID = $EventID";

        if($ans = $conn->query($qry) ){
            if($ans->num_rows == 0){
                    $sql = "INSERT INTO ROLES VALUES ($UserID, $EventID)";
                    if ($conn->query($sql) != TRUE) {
                        echo "Error in query: " . $sql . " " . $conn->error."<br>";
                    }
            }
        }
    }
    
  }
  echo "<h3 align='center'>Done!! Successfully populated the database with the given CSV File: ".$_POST['file_name']."</h3>";
  fclose($f);
}
else
{
    echo "Couldn't Open the File.";
}
$conn->close();
?>

<html>
<head> 
    <title> Import CSV </title>
</head>
</html>
