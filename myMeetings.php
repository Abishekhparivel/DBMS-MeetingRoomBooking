<?php
	session_start();
	if(!isset($_SESSION['user'])){
		header('Location: index.php');
	}

	unset($_SESSION['sessionId']);

	function func($k){
		$_SESSION['sessionId'] = $k;
		header('Location: myMeeting.php');
	}

	include_once('connectDB.php');
	$manager = $_SESSION['user']['UserId'];
	$sql = "SELECT * FROM `slot` WHERE `Manager` = '$manager'";
	if($res = mysqli_query($conn,$sql)){

	}
	else{
		echo "query failed. Refresh the Page";
		exit();
			}
	$result = array();
	while($row = mysqli_fetch_array($res))
	{
		$startSlotSplit = explode(' ', $row['StartSlot'], 2);
		$endSlotSplit = explode(' ', $row['EndSlot'], 2);
			array_push($result, array(
			'id'=>$row['SlotId'],
			'manager'=> $row['Manager'],
			'date'=>$startSlotSplit[0],
			'startSlot'=>$startSlotSplit[1],
			'endSlot'=>$endSlotSplit[1],
			'shortDes'=>$row['ShortDescrip'],
			'longDes'=>$row['LongDescrip'],
			'state'=>$row['State'],
			'bookingTime'=>$row['BookingTime']
		));
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
		<link rel="stylesheet" href="index.css">
	</head>
	<body>
		<header>
			<div class = "container">
				<h1 style="font-weight: 20px">Meetings Booked By Me</h1>
			</div>
		</header>
		<div class=main-body>
			<br>
			<br>
		</div>
		<div class="row main-body align-items-center justify-content-between">
	<?php
	if($result == array())
	{
		echo "<h3 class = \"col-12 text-center\">No meetings are booked by you</h3>";
	}
	else
	{
		foreach($result as $row) {
			$shortDes = $row['shortDes'];
			$id = $row['id'];
			$date = date_format(date_create_from_format('Y-m-d', $row['date']), 'd-m-y');
			$slot = $row['startSlot']. '-'.$row['endSlot'];
			$longDes = $row['longDes'];
			$state = $row['state'];
			if(array_key_exists($id, $_POST)){
				func($id);
			}
			echo "<div class=\"col-sm-4 col-lg-3\">
					    <div class=\"card\"  style=\"max-width: 18rem;\">
					    	<div class=\"card-body\">
					        <h5 class=\"card-title\">$shortDes</h5>
									<p class=\"card-text\">Date: $date</p>
									<p class=\"card-text\">Slot: $slot</p>
									<p class=\"card-text\">Booking State: $state</p>
					        <p class=\"card-text\">$longDes</p>
									<form method=\"post\"><input class=\"btn btn-primary button\" type=\"submit\" name=\"$id\" value=\"View More\"></form>
					      </div>
					    </div>
					  </div>";

		}
	}
	?>
</div>
	<div class=main-body>
		<br>
		<br>
	</div>
		<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
		<script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
		<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="node_modules/jquery/dist/jquery.min.js"></script>
	</body>
</html>
