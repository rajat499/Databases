<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca -->
<?php

	$conn = new mysqli("localhost", "root", "");

	if ($conn->connect_error) {
	    die("Connection to the MySQL server failed: " . $conn->connect_error);
	}

	$conn->select_db('krc353_2');
?>
