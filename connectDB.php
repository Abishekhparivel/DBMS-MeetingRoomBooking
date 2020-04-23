<?php
	$hostname = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "meetingroom";

	$conn = new mysqli($hostname, $username, $password, $dbname);

	if($conn -> connect_errno) {
		echo "Failed to connect to MySQL: ".$conn -> connect_error;
		exit();
	}
?>