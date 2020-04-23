<?php
	session_start();
	if(isset($_SESSION['user']) && $_SESSION['user'] !== ''){
		header('Location: summa.php');
	}
	else if(isset($_SESSION['loginerror']) && $_SESSION['loginerror'] !== ''){
		$loginerror = $_SESSION['loginerror'];
		echo "<script>alert('Login Error: $loginerror');</script>";
		unset($_SESSION['loginerror']);
	}
	else if(isset($_SESSION['regisMes'])){
		if($_SESSION['regisMes'] === "Success"){
			echo '<script>alert("Registration Successful");</script>';
		}
		else {
			$regisMes = $_SESSION['regisMes'];
			echo "<script>alert('Registration Error: $regisMes');</script>";
		}
		unset($_SESSION['regisMes']);
	}
?>



<!DOCTYPE html>
<html>
<head>
	<title>Meeting Room Booking</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1">
	<!-- Style -->
	<link href="css/main.css" rel='stylesheet' type='text/css'>
	<!-- Font Awesome -->
	<link href="css/font-awesome.css" rel="stylesheet">
</head>

	<div class="popup" data-popup="popup-signin">
	    <div class="popup-inner">
	    	<div class="popup-header">
	    		<h1>SIGN IN</h1>
	    	</div>
	    	<div class="popup-content">
	    		<h4>Welcome! Sign in To Procede</h4>
	    		<form action="login.php" method="post">
					<li>
						<i class="fa fa-envelope"></i><input type="text" class="text" placeholder="Example@email.com" name="email">
					</li>
					<li>
						<i class="fa fa-lock"></i><input name="password" type="password" placeholder="Password">
					</li>
					<div>
						<input type="submit" value="SIGN IN">
					</div>
				</form>
	    	</div>
	        <div class="popup-footer">
				<a href="#" class="account"></a>
				<a href="#" class="action" data-popup-open="popup-signup" data-popup-close="popup-signin">Need to sign up?</a>
	        </div>
	    </div>
	</div>

	<div class="popup" data-popup="popup-signup">
	    <div class="popup-inner">
	    	<div class="popup-header">
	    		<h1>SIGN UP</h1>
	    	</div>
	    	<div class="popup-content">
	    		<h4>Hi, Welcome to Sign up form!</h4>
	    		<form action="signup.php" method="post">
	    			<li>
						<i class="fa fa-user"></i><input type="text" class="text" placeholder="Your First Name" name="fname">
					</li>
					<li>
						<i class="fa fa-user"></i><input type="text" class="text" placeholder="Your Last Name" name="lname">
					</li>
					<li>
						<i class="fa fa-envelope"></i><input type="text" class="text" placeholder="Example@email.com" name="email">
					</li>
					<li>
						<i class="fa fa-lock"></i><input type="password" placeholder="Password" name="password">
					</li>
					<li>
						<i class="fa fa-envelope"></i><input type="text" class="text" placeholder="Student ot Faculty or Staff" name="role">
					</li>
					<li>
						<i class="fa fa-envelope"></i><input type="text" class="text" placeholder="My Designation" name="desig">
					</li>
					<div>
						<input type="submit" value="SIGN UP">
					</div>
				</form>
	    	</div>
	        <div class="popup-footer">
				<a href="#" class="account"></a>
				<a href="#" class="action" data-popup-open="popup-signin" data-popup-close="popup-signup">Already have account?</a>
	        </div>
	    </div>
	</div>



	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript">

		$('[data-popup="popup-signin"]').fadeIn(350);
		$(function() {

		    $('[data-popup-open]').on('click', function(e)  {
		        var targeted_popup_class = $(this).attr('data-popup-open');
		        $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
		        e.preventDefault();
		    });

		    $('[data-popup-close]').on('click', function(e)  {
		        var targeted_popup_class = $(this).attr('data-popup-close');
		        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
		        e.preventDefault();
		    });
		    //
		});
	</script>

</html>
