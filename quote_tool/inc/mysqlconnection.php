<?php

	$servername = "10.169.0.152";
	$username = "rusticho_elnm";
	$password = "s4lpwr32ln93soyw";
	$dbname = "rusticho_elnm";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
 ?>