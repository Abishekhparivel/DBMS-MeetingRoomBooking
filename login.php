<?php
	include_once('connectDB.php');
	session_start();
	$email = $_POST['email'];
	$password = $_POST['password'];

	$query = "SELECT `Email`, `UserId`, `FirstName`, `LastName`, `Role`, `Designation` FROM `user` WHERE `Email`='$email'AND `Password`='$password'";

	$result = $conn->query($query);

	if($result->num_rows === 1){
		while($row = $result->fetch_assoc()){
			$_SESSION['user'] = $row;
		}
		unset($_SESSION['loginerror']);
	}
	else{
		$_SESSION['loginerror'] = "Invalid credentials";
		unset($_SESSION['user']);
	}

	header('Location: index.php');
?>
