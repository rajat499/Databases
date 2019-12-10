<?php

	$conn = new mysqli("krc353.encs.concordia.ca", "krc353_2", "qNbKfe");

	if ($conn->connect_error) {
	    die("Connection to the MySQL server failed: " . $conn->connect_error);
	}

	$conn->select_db('krc353_2');
?>
