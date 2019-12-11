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

    if($user !== "sysadmn"){
        echo "You don't have permissions to visit this page.<br>";
        exit();
    }

    
    echo "<b>Emails in the System</b><br>";
    $sql = $conn->query("SELECT * from system_emails ORDER BY time_stamp DESC");
    if(!$sql){
        echo "Error getting emails from the system. ".$conn->error."<br>";
        exit();
    }
    $col = array("user", "email", "txt");
    
    $count = $sql->num_rows;
    if($sql->num_rows>0){
        echo "<table>";
        echo "<tr> <th>UserID</th> <th>Email ID</th> <th>Content</th> <th>Delete Email</th> </tr>";
        while($row = $sql->fetch_assoc()){
            echo "<tr>";
                foreach($col as $att)
                    echo "<td>".$row[$att]."</td>";
                echo "<td><form action='' method='POST'>
                        <button type='submit' value='".$row["id"]."' name='remove_email'>Remove</button>
                        </form></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<h1>Toal number of emails in the system: $count</h1>";
    }
    else{
        echo "No emails in the system.<br>";
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove_email'])){
        delete_email($_POST['remove_email']);
    }

    function delete_email($id){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM system_emails WHERE id='$id'");
        if(!$sql){
            echo "Error in query delete emails: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            window.history.go(-1);
        </script>";
    }

?>