<?php
include_once ("include/session.php");
if(isset($_REQUEST['form']) && $_REQUEST['form'] === "loginform" && !$session->loggedin){
	ProcessLogin();
}
else if($session->loggedin){
	ProcessLogout();
}
else {
	$session->SafelyAbort($session->referrer);
}

function ProcessLogin()
{
	global $session;

	$retval = $session->Login($_REQUEST['email'], $_REQUEST['password']);

	if($retval)
	{
		unset($_SESSION['feedback']);
		unset($_SESSION['feedbacktype']);
		unset($_SESSION['feedbacktitle']);
		$session->SafelyRedirect($session->referrer);
	}
	else
	{
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "Email and/or password invalid";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Login:";
		$session->SafelyAbort($session->referrer);
	}
}

function ProcessLogout()
{
	global $session;

	$retval = $session->Logout();

	if($retval)
	{
		unset($_SESSION['feedback']);
		unset($_SESSION['feedbacktype']);
		unset($_SESSION['feedbacktitle']);
		$session->SafelyRedirect(CAMPDAVID_HOME_URL);
	}
	else
	{
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "Failed to log out";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Logout:";
		$session->SafelyAbort($session->referrer);
	}
}
?>