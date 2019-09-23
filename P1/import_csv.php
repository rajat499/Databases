<!-- PHP File for importing a CSV file in MySQL Database -->
<?php
$servername = "krc353.encs.concordia.ca";

// Creating connection
$conn = new mysqli("krc353.encs.concordia.ca", "krc353_2", "qNbKfe");

// Checking connection
if ($conn->connect_error) {
    echo "Failed to connect to Database: " . $conn->connect_error."<br>";
}


$conn->select_db('krc353_2');

// Creating tables if doesn't exist 
$sql = "CREATE TABLE IF NOT EXISTS USERS (
    lastname VARCHAR(30),
    firstname VARCHAR(30),
    middle_name VARCHAR(30),
    userID VARCHAR(15) PRIMARY KEY NOT NULL,
    pass VARCHAR(15) NOT NULL
    )";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: " . $conn->error."<br>";
    }


$sql = "CREATE TABLE IF NOT EXISTS EVENTS (
    eventName VARCHAR(30),
    eventId INT(10) PRIMARY KEY NOT NULL,
    startDate DATE,
    endDate DATE,
    adminUserID VARCHAR(15)
    -- Foreign Key(adminUserID) REFERENCES USERS(userID)
    )";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: " . $conn->error."<br>";
    }


$sql = "CREATE TABLE IF NOT EXISTS ROLES (
    userID VARCHAR(15) NOT NULL,
    eventID INT(10) NOT NULL,
    PRIMARY KEY (userID, eventID)
    -- Foreign Key(userID) REFERENCES USERS(userID),
    -- Foreign Key(eventID) REFERENCES EVENTS(eventId)
    )";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: " . $conn->error."<br>";
    }

echo "<br>";  
echo "<br>";  

$section = 0; 

//open the csv file
if (($h = fopen($_POST["file_path"], "r")) !== FALSE) 
{
  //get each line as an array seperated by an "|"  
  while (($data = fgetcsv($h, 1000, "|")) !== FALSE) 
  {		
    //Each section is seperated by a value in the 0 index, we use this to increment the section number
    if ($data[0]!=""){
        $section++;
    }
    // Ignoring the lines of column titles. No need to store that in DB
    else if ((isset($data[4]) ? $data[4] : null) == "userID" || (isset($data[2]) ? $data[2] : null) == "EventID"){
        // do nothing
    }
    else if($section==1){   
        $lastname = !empty($data[1]) ? "'$data[1]'" : "NULL";
        $firstname = !empty($data[2]) ? "'$data[2]'" : "NULL";
        $middle_name = !empty($data[3]) ? "'$data[3]'" : "NULL";
        $userID = !empty($data[4]) ? "'$data[4]'" : "NULL";
        $pass = !empty($data[5]) ? "'$data[5]'" : "NULL";
        //check if there aren't any users already registered in the table with the id from the csv file
        $search_user_qry = "SELECT * FROM USERS WHERE USERS.userID = $userID";
        if($result = $conn->query($search_user_qry) ){
            if($result->num_rows == 0){
                $sql = "INSERT INTO USERS VALUES ($lastname, $firstname, $middle_name, $userID, $pass)";
                if ($conn->query($sql) != TRUE) {
                    echo "Error: " . $sql . " " . $conn->error."<br>";
                }
            }
        }
    }
  
    else if($section==2){
        $eventName = !empty($data[1]) ? "'$data[1]'" : "NULL";
        $eventID = !empty($data[2]) ? "'$data[2]'" : "NULL";
        $startDate = !empty($data[3]) ? "'$data[3]'" : "NULL";
        $endDate = !empty($data[4]) ? "'$data[4]'" : "NULL";
        $adminUserID = !empty($data[5]) ? "'$data[5]'" : "NULL";
        //check if there aren't any admins already registered in the table with the admin id from the csv file
        $search_admins_qry = "SELECT * FROM EVENTS WHERE EVENTS.adminUserID = $adminUserID";
        if($result = $conn->query($search_admins_qry) ){
            if($result->num_rows == 0){
                $sql = "INSERT INTO EVENTS VALUES ($eventName, $eventID, $startDate, $endDate, $adminUserID)";
                if ($conn->query($sql) != TRUE) {
                    echo "Error: " . $sql . " " . $conn->error."<br>";
                }
            }
        }
    }
    
    else if($section==3){
        $userID = !empty($data[1]) ? "'$data[1]'" : "NULL";
        $eventID = !empty($data[2]) ? "'$data[2]'" : "NULL";
        //check if there aren't any participants already registered in the table with the admin id from the csv file
        $search_participant_qry = "SELECT * FROM ROLES WHERE ROLES.userID = $userID AND ROLES.eventID = $eventID";
        if($result = $conn->query($search_participant_qry) ){
            if($result->num_rows == 0){
                    $sql = "INSERT INTO ROLES VALUES ($userID, $eventID)";
                    if ($conn->query($sql) != TRUE) {
                        echo "Error: " . $sql . " " . $conn->error."<br>";
                    }
            }
        }
    }
    
  }
  echo "<h3 align='center'>Done!! Successfully populated the database with the given CSV File: ".$_POST['file_path']."</h3>";
  fclose($h);
}
$conn->close();
?>

<html>
<head> 
    <title> Import CSV </title>
</head>
</html>
