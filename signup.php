<?php
	include_once('connectDB.php');
	session_start();
	$firstname = $_POST['fname'];
	$lastname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$role = $_POST['role'];
	$designation = $_POST['desig'];

	$sql = "SELECT * FROM `user` WHERE `Email` = '$email';";
	$result = $conn->query($sql);
	$emailExist = False;
	if($result->num_rows > 0){
		$_SESSION['regisMes'] = "Email already exist";
		header('Location: index.php');
		$emailExist = True;
	}
	if(!$emailExist){
		$query = "INSERT INTO `user`(`Email`, `Password`, `FirstName`, `LastName`, `Role`, `Designation`)
			VALUES ('$email', '$password', '$firstname', '$lastname', '$role', '$designation')";

		if ($conn->query($query) === TRUE) {
		    echo "Registered Successfully";
		} else {
		    echo "Error: " . $query . "<br>" . $conn->error;
		}
	}
	header('Location: index.php');
?>
