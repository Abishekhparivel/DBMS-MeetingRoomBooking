<?php
	session_start();
	if(!isset($_SESSION['user']) && $_SESSION["user"] == ""){
		header("Location: index.php");
	}
	if(isset($_POST["bookSlot"])){
			include_once("connectDB.php");
			$manager = $_SESSION['user']['UserId'];
			$shortDes = $_POST['shortDes'];
			$longDes = $_POST['longDes'];
			$startSlot = $_POST['date'] . ' ' . $_POST['startSlot'] . ':00';
			$endSlot = $_POST['date'] . ' ' . $_POST['endSlot'].':00';
			$state = "BOOKED";

			$findSql = "SELECT `SlotId` FROM `slot` WHERE ((`StartSlot` >= '$startSlot' and `StartSlot`<='$endSlot') or (`EndSlot`>='$startSlot' and `EndSlot`<='$endSlot') or (`EndSlot`<='$startSlot' and `EndSlot`>='$endSlot')) and `State` = 'BOOKED'";
			$findResult = $conn->query($findSql);
			if($findResult -> num_rows >0){
				$state = "WAITING";
			}

			$sql = "INSERT INTO `slot` (`Manager`, `StartSlot`, `EndSlot`, `ShortDescrip`, `LongDescrip`, `State`) VALUES ('$manager', '$startSlot', '$endSlot', '$shortDes', '$longDes', '$state')";
			if ($conn->query($sql) === TRUE) {
					if($state === 'BOOKED'){
			    	echo "<script>alert('Slot Booked.');</script>";
					}
					else {
						echo "<script>alert('There are other slots booked. So your slot is under waiting.');</script>";
					}
			} else {
			    echo "<script>alert('Error:  $conn->error');</script>";
			}
	}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 	<head>
 		<meta charset="utf-8">
 		<meta name = "viewport" content = "width=device-width, initial-scale=1,shrink-to-fit=no">

		<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="node_modules/bootstrap-social/bootstrap-social.css">
		<link rel = "stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style_all.css">
    <link rel="stylesheet" href="css/responsive.css">

 	</head>
 	<body>
		<div class="wrapper">
		    <!-- Sidebar Holder -->
		    <nav id="sidebar">
		        <div class="sidebar-header">
		            <div class="navbar-brand">
		                <!-- nav bar heading goes here, i guess -->
		            </div>
		        </div>
		        <ul class="list-unstyled components">
		            <li> <a href="myMeetings.php"><i class="fa fa-university" aria-hidden="true"></i> myMeetings</a> </li>
		            <li> <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i> Meetings Invited</a> </li>
		            <li> <a href="bookSlot.php"><i class="fa fa-thermometer-full" aria-hidden="true"></i> Book a Meeting</a> </li>
		            <li> <a href="#"><i class="fa fa-paper-plane" aria-hidden="true"></i> Search a Meeting</a> </li>
		            <li> <a href="#"><i class="fa fa-paper-plane" aria-hidden="true"></i> Invitations</a> </li>
		            <li> <a href="logout.php"><i class="fa fa-paper-plane" aria-hidden="true"></i> Logout</a> </li>
		        </ul>
		    </nav>
		    <!-- Page Content Holder -->
		    <div id="content">
		        <nav class="navbar navbar-default">
		            <div class="container-fluid">
		                <div class="navbar-header">
		                    <button type="button" id="sidebarCollapse" class="navbar-btn"> <span></span> <span></span> <span></span> </button>
		                </div>
		                <!-- the following div is for the stuff at the right corner -->
		                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		                    <ul class="nav navbar-nav navbar-right">
		                        <li><a href="#" class="text-danger">Here Some Project PSD Are Not My But Code Is Done By Me</a></li>
		                        <li><a href="#">Portfolio</a></li>
		                    </ul>
		                </div>
		            </div>
		        </nav>
		        <div class="portfolio">
		         <h2 class="title"><i class="fa fa-user" aria-hidden="true"></i>My Meeting</h2>
		            <hr>
		            <div class="row">
									<div class =main-body>
									 <br>
									 <br>
									</div>

									<div class="offset-1 row row-content main-body">
									 <div class = "col-12">
										 <h3>Enter the Slot Details</h3>
									 </div>
									 <div>
									 <br>
									 <br>
									 <br>
									 </div>
									 <div class ="col-12 col-md-9">
										 <form name = "bookSlot" method="post" action="bookSlot.php" onsubmit="return validate()">
											 <div class="form-group row">
												 <label for="purpose" class="col-md-2 col-form-label">Product Name</label>
												 <div class="col-md-10">
													 <input type="text" name="shortDes" class="form-control" id="shortDes" placeholder="Purpose of Meeting(Short Description)">
												 </div>
											 </div>
											 <div class="form-group row">
												 <label for="date" class="col-md-2 col-form-label">Date of Meeting</label>
												 <div class="col-md-10">
													 <input type="date" name="date" id="date">
												 </div>
											 </div>
											 <div class="form-group row">
												 <label for="startSlot" class="col-md-2 col-form-label">Start Slot</label>
												 <div class="col-md-10">
													 <select class="form-control" name="startSlot" id="startSlot">
														 <option>09:00</option>
														 <option>09:30</option>
														 <option>10:00</option>
														 <option>10:30</option>
														 <option>11:00</option>
														 <option>11:30</option>
														 <option>12:00</option>
														 <option>12:30</option>
														 <option>13:00</option>
														 <option>13:30</option>
														 <option>14:00</option>
														 <option>14:30</option>
														 <option>15:00</option>
														 <option>15:30</option>
														 <option>16:00</option>
														 <option>16:30</option>
														 <option>17:00</option>
														 <option>17:30</option>
														 <option>18:00</option>
													 </select>
												 </div>
													 </div>
													 <div class="form-group row">
														 <label for="endSlot" class="col-md-2 col-form-label">End Slot</label>
														 <div class="col-md-10">
															 <select class="form-control" name="endSlot" id="endSlot">
																 <option>09:00</option>
																 <option>09:30</option>
																 <option>10:00</option>
																 <option>10:30</option>
																 <option>11:00</option>
																 <option>11:30</option>
																 <option>12:00</option>
																 <option>12:30</option>
																 <option>13:00</option>
																 <option>13:30</option>
																 <option>14:00</option>
																 <option>14:30</option>
																 <option>15:00</option>
																 <option>15:30</option>
																 <option>16:00</option>
																 <option>16:30</option>
																 <option>17:00</option>
																 <option>17:30</option>
																 <option>18:00</option>
															 </select>
														 </div>
															 </div>
														 <div class="form-group row">
														 <label for="longDes" class="col-md-2 col-form-label" >Description</label>
														 <div class="col-md-10">
															 <textarea name="longDes" class="form-control" rows="6" placeholder="describe your slot" id="longDes"></textarea>
														 </div>
													 </div>
													 <div class="form-group row">
														 <div class="offset-md-2 col-md-10">
															 <input type="submit" class="btn btn-primary" name="bookSlot" value="Upload Details">
															 <input type="reset" class="btn btn-primary" name="reset" value="Reset">
														 </div>
													 </div>
												 </form>
											 </div>
										 </div>
		                <!-- removed some unnwanted shit from here -->
		            </div>
		        </div>
		    </div>
		</div>

 		<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
 		<script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
 		<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
 		<script src="node_modules/jquery/dist/jquery.min.js"></script>
 	<script language = "JavaScript" type="text/javascript">
 function validate()
 {
 		var shortDes = document.forms["bookSlot"]["shortDes"];
     var date = document.forms["bookSlot"]["date"];
     var startSlot = document.forms["bookSlot"]["startSlot"];
 		var endSlot = document.forms["bookSlot"]["endSlot"];
 		var longDes = document.forms["bookSlot"]["longDes"];

     if (shortDes.value == "")
     {
         window.alert("Please enter the purpose.");
         shortDes.focus();
         return false;
     }
     else if(shortDes.value.length > 200){
     	window.alert("Please a enter a purpose which less than length 200");
     	shortDes.focus();
     	return false;
     }

 		var dateEntered = new Date(date.value);
 		var currentDate = new Date();
 		if(dateEntered.getTime() <= currentDate.getTime()){
 			window.alert("Enter a valid future date");
 			date.focus();
 			return false;
 		}


     if (longDes.value == "")
     {
         window.alert("Please descrip your product.");
         longDes.focus();
         return false;
     }
     else if(longDes.value.length > 1000){
     	window.alert("max length of description is thousand. Please stick to it");
     	longDes.focus();
     	return false;
     }
     return true;

 }
 	</script>
 	</body>

 </html>
