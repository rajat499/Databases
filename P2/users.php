<?php

$conn = new mysqli("krc353.encs.concordia.ca", "krc353_2", "qNbKfe");

if ($conn->connect_error) {
    die("Connection to the MySQL server failed: " . $conn->connect_error);
}

$conn->select_db('krc353_2');
$login_query = "SELECT * FROM users_info WHERE userid='$user_id' AND pass='$pwd'";

$result = $conn->query($login_query);
// $row = mysqli_fetch_array($result);
// echo "Result".$row;
if (!$result) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
$count=mysqli_num_rows($result);

$conn->close();
?>