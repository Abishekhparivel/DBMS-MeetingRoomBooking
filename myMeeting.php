<?php
	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user']=='')
	{
		unset($_SESSION['sessionId']);
		unset($_SESSION['myMeeting']);
		header('Location: index.php');
	}
	if(!isset($_SESSION['myMeetings']) || $_SESSION['myMeetings'] === array() || !isset($_SESSION['sessionId']))
	{
		unset($_SESSION['sessionId']);
		unset($_SESSION['myMeetings']);
		header('Location: myMeetings.php');
	}
	include_once('connectDB.php');
	$slotId = $_SESSION['sessionId'];
	if(array_key_exists('addInvitee', $_POST))
	{
		$inviteeEmail = $_POST['inviteesAdd'];
		$findIdQuery = "SELECT `UserId` FROM `user` WHERE `Email` = '$inviteeEmail'";
		$findIdResult = $conn->query($findIdQuery);
		if($findIdResult->num_rows === 0)
		{
			echo "<script>alert('There is no user with the email you entered');</script>";
		}
		else
		{
			while($row = $findIdResult->fetch_assoc())
			{
				$inviteeId = $row['UserId'];
			}
			if($inviteeId === $_SESSION['user']['UserId'])
			{
				echo "<script>alert('You cant add your self');</script>";
			}
			else
			{
				$findInviteeQuery = "SELECT * FROM `invitee` WHERE `SlotId`='$slotId' AND `InviteeId` = '$inviteeId';";
				$findInviteeResult = $conn->query($findInviteeQuery);
				if($findInviteeResult->num_rows === 0)
				{
					$addInviteeQuery = "INSERT INTO `invitee`(`SlotId`, `InviteeId`, `InviteeAccepted`, `ManagerAccepted`) VALUES ('$slotId', '$inviteeId', 'WAITING', 'ACCEPTED')";
					if($conn->query($addInviteeQuery) === TRUE)
					{
						echo "<script>alert('Invitee has been requested');</script>";
					}
					else
					{
						echo "<script>alert('Invitee couldn't be requested, try again');</script>";
					}
				}
				else
				{
					while($row = $findInviteeResult->fetch_assoc())
					{
						$managerAccepted = $row['ManagerAccepted'];
						$inviteeAccepted = $row['InviteeAccepted'];
					}
					if($inviteeAccepted === 'DECLINED')
					{
						echo "<script>alert('Your request to add this user is already declined by the user');</script>";
					}
					else if($managerAccepted === 'ACCEPTED' && $inviteeAccepted === "WAITING")
					{
						echo "<script>alert('The invitee is already invited and waiting for his response');</script>";
					}
					else if($managerAccepted === 'ACCEPTED' && $inviteeAccepted === "ACCEPTED")
					{
						echo "<script>alert('The invitee is already attending the meeting');</script>";
					}
					else
					{
						$updateInviteeQuery = "UPDATE `invitee` SET `ManagerAccepted`='ACCEPTED' WHERE `SlotId`='$slotId' AND `InviteeId`='$inviteeId'";
						if ($conn->query($updateInviteeQuery) === TRUE)
						{
							if($inviteeAccepted)
							{
								echo "<script>alert('Invitee is requested and accepted');</script>";
							}
							else
							{
								echo "<script>alert('Invitee requested');</script>";
							}
						}
						else
						{
							echo "<script>alert('Invitee couldn't be requested, try again');<script>";
						}
					}
				}
			}
		}
	}
	if(array_key_exists('removeInvitee', $_POST))
	{
		$inviteeEmail = $_POST['inviteesRemove'];
		$findIdQuery = "SELECT `UserId` FROM `user` WHERE `Email` = '$inviteeEmail'";
		$findIdResult = $conn->query($findIdQuery);
		if($findIdResult->num_rows === 0)
		{
			echo "<script>alert('There is no user with the email you entered');</script>";
		}
		else
		{
			while($row = $findIdResult->fetch_assoc())
			{
				$inviteeId = $row['UserId'];
			}
			if($inviteeId === $_SESSION['user']['UserId'])
			{
				echo "<script>alert('You cant remove your self');</script>";
			}
			else
			{
				$findInviteeQuery = "SELECT * FROM `invitee` WHERE `SlotId`='$slotId' AND `InviteeId` = '$inviteeId';";
				$findInviteeResult = $conn->query($findInviteeQuery);
				if($findInviteeResult->num_rows === 0)
				{
					echo "<script>alert('The user you mentioned is not requested');</script>";
				}
				else
				{
					while($row = $findInviteeResult->fetch_assoc())
					{
						$managerAccepted = $row['ManagerAccepted'];
						$inviteeAccepted = $row['InviteeAccepted'];
					}
					if($managerAccepted === 'DECLINED' && $inviteeAccepted === 'DECLINED')
					{
						echo "<script>alert('The invitee is neither invited nor willing to attend ');</script>";
					}
					else if($managerAccepted === 'DECLINED' && $inviteeAccepted === 'ACCEPTED')
					{
						echo "<script>alert('The invitee is already declined for the meeting, but he is still willing to attend');</script>";
					}
					else if($managerAccepted === 'DECLINED' && $inviteeAccepted === 'WAITING')
					{
						echo "<script>alert('The invitee is already declined for the meeting, but no response from him');</script>";
					}
					else
					{
						$updateInviteeQuery = "UPDATE `invitee` SET `ManagerAccepted`='DECLINED' WHERE `SlotId`='$slotId' AND `InviteeId`='$inviteeId'";
						if ($conn->query($updateInviteeQuery) === TRUE)
						{
							if($inviteeAccepted === "ACCEPTED")
							{
								echo "<script>alert('Invitee is declined and but he is willing to attend');</script>";
							}
							else if($inviteeAccepted === "WAITING")
							{
								echo "<script>alert('Invitee didn't show response to the request, now the request is removed');</script>";
							}
							else
							{
								echo "<script>alert('Invitee was not willing to attend either, now the request is declined');</script>";
							}
						}
						else
						{
							echo "<script>alert('Invitee couldn't be removed, try again');<script>";
						}
					}
				}
			}
		}
	}
	if(array_key_exists('cancelSlot', $_POST))
	{
		$sql = "UPDATE `slot` SET `State`='CANCELLED' WHERE `SlotId`='$slotId'";
		if($conn->query($sql) === TRUE)
		{
			echo "<script>alert('Slot cancelled');</script>";
		}
		else
		{
			echo "<script>alert('Slot couldn't be cancelled, try again');</script>";
		}
	}
	$id = $_SESSION['sessionId'];
	$manager = $_SESSION['user']['UserId'];
	$sql = "SELECT * FROM `slot` WHERE `SlotId` = '$id' AND `Manager` = '$manager'";
	$result = $conn->query($sql);
	$slot = null;
	if($result->num_rows === 0)
	{
		unset($_SESSION['sessionId']);
		header('Location: myMeetings.php');
	}
	else if($result->num_rows === 1)
	{
		while($row = $result->fetch_assoc())
		{
			$startSlotSplit = explode(' ', $row['StartSlot'], 2);
			$endSlotSplit = explode(' ', $row['EndSlot'], 2);
			$manager= $row['Manager'];
			$date= date_format(date_create_from_format('Y-m-d', $startSlotSplit[0]), 'd-m-y');
			$slot=$startSlotSplit[1].'-'.$endSlotSplit[1];
			$shortDes=$row['ShortDescrip'];
			$longDes=$row['LongDescrip'];
			$state=$row['State'];
			$bookingTime =$row['BookingTime'];

		}
	}
?>

<!DOCTYPE>
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
					<div class="navbar-brand">
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
							<br>
							<br>
						</div>
						<div class="row main-body" style="margin:0 auto;">
							<?php
								if(is_null($slot))
								{
									echo 'null is null';
								}
								else
								{
									if($state !== 'CANCELLED' && $state !== 'FINISHED')
									{
										echo "
											<div class =\"col-12 col-md-9\">
												<div class=\"row\">
													<label for=\"purpose\" class=\"col-md-2 \"><h5>Purpose</h5></label>
													<div class=\"col-md-10\">
														<p name=\"shortDes\" id=\"purpose\">$shortDes</p>
													</div>
												</div>
												<div class=\"row\">
													<label for=\"date\" class=\"col-md-2 \"><h5>Date of Meeting</h5></label>
													<div class=\"col-md-10\">
														<p name=\"date\" id=\"date\">$date</p>
													</div>
												</div>
												<div class=\"row\">
													<label for=\"slot\" class=\"col-md-2 \"><h5>Slot</h5></label>
													<div class=\"col-md-10\">
														<p name=\"slot\" id=\"slot\">$slot</p>
													</div>
												</div>
												<div class=\"row\">
													<label for=\"longDes\" class=\"col-md-2 \" ><h5>Description</h5></label>
													<div class=\"col-md-10\">
														<p name=\"longDes\" id=\"longDes\">$longDes</p>
													</div>
												</div>
												<div class=\"row\">
													<label for=\"state\" class=\"col-md-2 \" ><h5>Booking State</h5></label>
													<div class=\"col-md-10\">
														<p name=\"state\" id=\"state\">$state</p>
													</div>
												</div>
											</div>
											<div class =\"col-12 col-md-9\">
												<form method=\"post\">
													<div class=\"form-group row\">
														<label for=\"inviteesAdd\" class=\"col-md-2 col-form-label\"><h5>Add Invitee</h5></label>
														<div class=\"col-md-8\">
															<input type=\"text\" name=\"inviteesAdd\" class=\"form-control\" id=\"inviteesAdd\" placeholder=\"Mail of Person you want to invite\">
														</div>
														<div class=\"col-md-2\">
															<input type=\"submit\" class=\"button btn btn-success\" name=\"addInvitee\" value=\"Add Invitee\">
														</div>
													</div>
													<div class=\"form-group row\">
														<label for=\"inviteesRemove\" class=\"col-md-2 col-form-label\"><h5>Remove Invitee</h5></label>
														<div class=\"col-md-8\">
															<input type=\"text\" name=\"inviteesRemove\" class=\"form-control\" id=\"inviteesRemove\" placeholder=\"Mail of Person you want to decline meeting\">
														</div>
														<div class=\"col-md-2\">
															<input type=\"submit\" class=\"button btn btn-warning\" name=\"removeInvitee\" value=\"Remove Invitee\">
														</div>
													</div>
													<div class=\"form-group row\">
														<div class=\"offset-md-2 col-md-10\">
															<input type=\"submit\" class=\"button btn btn-danger\" name=\"cancelSlot\" value=\"Cancel Slot\">
														</div>
													</div>
												</form>
											</div>
										";
									}
									else
									{
										echo "Your meeting is cancelled by you.";
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
		<script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
		<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="node_modules/jquery/dist/jquery.min.js"></script>
		<script src="js/jquery-3.1.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>
		<script type="text/javascript">
		$(document).ready(function ()
		{
			$('#sidebarCollapse').on('click', function ()
			{
				$('#sidebar').toggleClass('active');
				$(this).toggleClass('active');
			});
		});
	</body>
</html>
