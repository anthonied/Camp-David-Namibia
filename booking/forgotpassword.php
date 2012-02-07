<?php
include_once ("include/session.php");
if(isset($_REQUEST['form']) && $_REQUEST['form'] === "forgotpasswordform" && !$session->loggedin){
	$retvalue = ProcessForgotPassword();

	if($retvalue == SUCCEEDED)
	{
		$_SESSION['feedback'] = "You will receive an email with your temporary password within a few moments. Please change it as soon as possible";
		$_SESSION['feedbacktype'] = "higlight";
		$_SESSION['feedbacktitle'] = "Password Reset:";
		$session->SafelyRedirect($session->referrer);
	}
	elseif($retvalue == USERNAME_INVALID)
	{
		$_SESSION['value_array'] = $_REQUEST;
		$_SESSION['feedback'] = "Your email was invalid. Please type in a valid email address.";
		$_SESSION['feedbacktype'] = "error";
		$_SESSION['feedbacktitle'] = "Password Reset:";
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
else {
	$session->SafelyRedirect($session->referrer);
}

function ProcessForgotPassword()
{
	global $database, $session, $mailer;

	$email = $_REQUEST['email'];
	if(!$email || strlen($email = trim($email)) == 0){
		return USERNAME_INVALID;
	}
	else {
		$email = stripslashes($email);
		if(strlen($email) < 5){
			return USERNAME_INVALID;
		}
		else if(strlen($email) > 30){
			return USERNAME_INVALID;
		}
		$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
		."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
		."\.([a-z]{2,}){1}$";
		if(!eregi($regex,$email)){
			return USERNAME_INVALID;
		}
		/* Check if username is already in use */
		if(!$database->EmailTaken($email)){
			return USERNAME_INVALID;
		}
	}

	$newpass = $session->GenerateRandStr(8);

	$usrinfo = $database->GetUserInfo($email);

	/* Attempt to send the email with new password */
	if($mailer->SendNewPassword($usrinfo['firstname']." ".$usrinfo['lastname'],$email,$newpass)){
		/* Email sent, update database */
		$database->UpdateUser($email, "password", md5($newpass));
		return SUCCEEDED;
	}
	/* Email failure, do not change password */
	else{
		return DATABASE_ERROR;
	}
}
?>