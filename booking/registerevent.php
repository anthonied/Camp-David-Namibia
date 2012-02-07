<?php
include_once('include/session.php');

$result = $database->CheckAvailabilityForEvent(mysql_escape_string($_REQUEST[event]));
if($result > 0)
{
	switch($database->GetEventType($_REQUEST[event]))
	{
		case "1": // KIT
			$result = $session->RegisterForKIT(mysql_escape_string($_REQUEST[event]), mysql_escape_string($_REQUEST[selfdescription]), mysql_escape_string($_REQUEST[interests]), mysql_escape_string($_REQUEST[school]), mysql_escape_string($_REQUEST[classyear]), mysql_escape_string($_REQUEST[sport]));
			if($result)
			{
				$eventdetail = $database->GetEventDetails(mysql_escape_string($_REQUEST[event]));
				$mailer->SendEventRegistered($session->userdetail[firstname]." ".$session->userdetail[lastname],
				$session->email, "Knights in Training", $eventdetail[startdate], $eventdetail[enddate], $eventdetail[cost], $eventdetail[description]);
			}
			break;
		case "2": // B2F
			$result = $session->RegisterForB2F(mysql_escape_string($_REQUEST[event]), mysql_escape_string($_REQUEST[selfdescription]), mysql_escape_string($_REQUEST[interests]), mysql_escape_string($_REQUEST[maritalstatus]), mysql_escape_string($_REQUEST[occupation]));
			if($result)
			{
				$eventdetail = $database->GetEventDetails(mysql_escape_string($_REQUEST[event]));
				$mailer->SendEventRegistered($session->userdetail[firstname]." ".$session->userdetail[lastname],
				$session->email, "Battle to Fight", $eventdetail[startdate], $eventdetail[enddate], $eventdetail[cost], $eventdetail[description]);
			}
			break;
		default:
			$_SESSION['feedback'] = "Failed to register for the event";
			$_SESSION['feedbacktype'] = "error";
			$_SESSION['feedbacktitle'] = "Event Registration: ";
			$session->SafelyAbort($session->referrer);
			break;
	}
}
else {
	$_SESSION['feedback'] = "Failed to register for the event. Event is already fully booked.";
	$_SESSION['feedbacktype'] = "error";
	$_SESSION['feedbacktitle'] = "Event Registration: ";
	$session->SafelyAbort($session->referrer);
}

?>