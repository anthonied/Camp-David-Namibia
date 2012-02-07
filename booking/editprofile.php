<?php
include_once 'include/session.php';

if(isset($_REQUEST['form']) && $_REQUEST['form'] === "editprofileform" && $session->loggedin){
	ProcessUpdate();
}
else {	
	$_SESSION['feedback'] = "An error occurred whilee updating your profile.";
	$_SESSION['feedbacktype'] = "error";
	$_SESSION['feedbacktitle'] = "Profile Update:";
	$session->SafelyAbort($session->referrer);
}

function ProcessUpdate()
{
	global $session;
	
	$retval = $session->EditAccount($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['cellphone'], 
		$_REQUEST['bdate'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['newpassword'], $_REQUEST['confpassword']);
		
	if($retval == SUCCEEDED)
	{
		unset($_SESSION['feedback']);
		unset($_SESSION['feedbacktype']);
		unset($_SESSION['feedbacktitle']);
		$session->SafelyRedirect($session->referrer);
	}
elseif ($retval == USERNAME_INVALID )
	{
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "Your email was invalid. Please type in a valid email address.";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Registration:";
		$session->SafelyAbort($session->referrer);
	}

	elseif ($retval == USERNAME_TAKEN)
	{
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "Your email address is already registered. Please type in a valid email address. Or use the 'Forgot Password' link below.";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Registration:";
		$session->SafelyAbort($session->referrer);
	}
	elseif ($retval == PASSWORD_INVALID)
	{
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "Your password was invalid. Please type in a valid password.";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Registration:";
		$session->SafelyAbort($session->referrer);
	}
	elseif ($retval == CELLPHONE_INVALID) {
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "Your cellphone number is invalid. Please type in a valid cellphone number.";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Registration:";
		$session->SafelyAbort($session->referrer);
	}
	else 
	{
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "An unknown error occurred. Please try again.";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Password Reset:";
		$session->SafelyAbort($session->referrer);
	}
}
?>