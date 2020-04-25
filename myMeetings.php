<?php
	session_start();
	if(!isset($_SESSION['user']))
	{
		header('Location: index.php');
	}
	unset($_SESSION['sessionId']);
	function func($k)
	{
		$_SESSION['sessionId'] = $k;
		header('Location: myMeeting.php');
	}
	include_once('connectDB.php');
	$manager = $_SESSION['user']['UserId'];
	$sql = "SELECT * FROM `slot` WHERE `Manager` = '$manager'";
	if($res = mysqli_query($conn,$sql)){}
	else
	{
		echo "query failed. Refresh the Page";
		exit();
	}
	$result = array();
	while($row = mysqli_fetch_array($res))
	{
		$startSlotSplit = explode(' ', $row['StartSlot'], 2);
		$endSlotSplit = explode(' ', $row['EndSlot'], 2);
		array_push(
			$result, array(
				'id'=>$row['SlotId'],
				'manager'=> $row['Manager'],
				'date'=>$startSlotSplit[0],
				'startSlot'=>$startSlotSplit[1],
				'endSlot'=>$endSlotSplit[1],
				'shortDes'=>$row['ShortDescrip'],
				'longDes'=>$row['LongDescrip'],
				'state'=>$row['State'],
				'bookingTime'=>$row['BookingTime']
			)
		);
	}
	$_SESSION['myMeetings'] = $result;
?>

<!DOCTYPE html>
<html>
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
		    <nav id="sidebar">
		        <div class="sidebar-header">
		            <div class="navbar-brand"></div>
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
		    <div id="content">
		        <nav class="navbar navbar-default">
		            <div class="container-fluid">
		                <div class="navbar-header">
		                    <button type="button" id="sidebarCollapse" class="navbar-btn"> <span></span> <span></span> <span></span> </button>
		                </div>
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
						<div class=main-body>
							<br>
							<br>
						</div>
						<div class="row main-body align-items-center  justify-content-between">
							<?php
								if($result == array())
								{
									echo "<h3 class = \"col-12 text-center\">No meetings are booked by you</h3>";
								}
								else
								{
									foreach($result as $row)
									{
										$shortDes = $row['shortDes'];
										$id = $row['id'];
										$date = date_format(date_create_from_format('Y-m-d', $row['date']), 'd-m-y');
										$slot = $row['startSlot']. '-'.$row['endSlot'];
										$longDes = $row['longDes'];
										$state = $row['state'];
										if(array_key_exists($id, $_POST))
										{
											func($id);
										}
										echo "
											<div class=\"col-sm-6 col-lg-4\">
												<div class=\"card\"  style=\"max-width: 18rem;min-width: 18rem;\">
													<div class=\"card-body\">
														<h4 class=\"card-title\">$shortDes</h4>
														<p class=\"card-text\">Date: $date</p>
														<p class=\"card-text\">Slot: $slot</p>
														<p class=\"card-text\">Booking State: $state</p>
														<p class=\"card-text\">$longDes</p>
														<form method=\"post\"><input class=\"btn btn-primary button\" type=\"submit\" name=\"$id\" value=\"View More\"></form>
													</div>
												</div>
											</div>
										";
									}
								}
							?>
						</div>
						<div class=main-body>
							<br>
							<br>
						</div>
		            </div>
		        </div>
		    </div>
		</div>
		<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
		<script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
		<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="node_modules/jquery/dist/jquery.min.js"></script>
	</body>
</html>
