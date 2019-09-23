<!-- PHP File for displaying the tables -->
<?php

$conn = new mysqli("krc353.encs.concordia.ca", "krc353_2", "qNbKfe");
if ($conn->connect_error) {
    die("Connection to the MySQL server failed: " . $conn->connect_error);
}

$conn->select_db('krc353_2');
$table = $_GET["table"];

$name_query = $conn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'krc353_2' AND TABLE_NAME = '$table'");
while($row = $name_query->fetch_assoc()){
    $name_column[] = $row;
}
$column_names = array_column($name_column, 'COLUMN_NAME');

?>

<html>

    <head> 
        <title> Information </title>
        <style>
        table { 
            border-collapse: collapse; 
        } 
        th { 
            background-color:green; 
            Color:white; 
        } 
        th, td { 
            width:150px; 
            text-align:center; 
            border:1px solid black; 
            padding:5px 
            
        }
        body{
            text-align: center; 
        }
        </style>
    </head>

    <body>
        <h1><?php echo $table; ?></h1>
        <table align="center">
        <tr>
            <?php 
            foreach($column_names as $name)
                echo "<th>" . $name . "</th>";
            ?>
        </tr>
        <?php
            $sql = "SELECT * FROM $table";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach($column_names as $name)
                        echo "<td>" . $row[$name] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "No rows in the table.";
            }
            ?>
        </table>
    </body>

</html>
