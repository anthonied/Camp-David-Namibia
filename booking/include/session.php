<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/www/users/campdnevhm/booking/');
include_once("constants.php");
include_once("database.php");
include_once("mailer.php");
class Session
{
	public $email;
	public $loggedin;
	public $userdetail;
	public $privelagelevel;
	public $url;
	public $referrer;
	public $ajaxrequest;

	public function __construct() {
		mt_srand($this->MakeSeed());
		$this->StartSession();
	}
	public function __destruct() {

	}

	public function StartSession()	{
		global $database;
		session_start();

		$this->loggedin = $this->CheckLogin();
		if(!$this->loggedin)
		{
			$this->privelagelevel = GUEST_LEVEL;
		}

		if(isset($_SESSION['url'])){
			$this->referrer = $_SESSION['url'];
		}else{
			$this->referrer = "/";
		}

		/* Set current url */
		$this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];

		$this->ajaxrequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	public function CheckLogin()
	{
		global $database;

		if(isset($_SESSION['email']))
		{
			if($database->ConfirmUserEmail($_SESSION['email']) != SUCCEEDED)
			{
				unset($_SESSION['email']);
				return false;
			}
			$this->userdetail = $database->GetUserInfo($_SESSION['email']);
			$this->email = $this->userdetail['email'];
			$this->privelagelevel = $this->userdetail['privelagelevel_idprivelagelevel'];

			return true;
		}
		else
		return false;
	}

	public function Login($email, $password) {
		global $database;
		if(!$email || strlen($email = trim($email)) == 0)
		{
			return false;
		}

		if(!$password || strlen($password = trim($password)) == 0)
		{
			return false;
		}
		$email = stripslashes($email);
		$result = $database->ConfirmLoginDetails($email, md5($password));
			
		if($result != SUCCEEDED)
		return false;

		$this->userdetail = $database->GetUserInfo($email);
		$this->email = $_SESSION['email'] = $this->userdetail['email'];
		$this->privelagelevel = $this->userdetail['privelagelevel_idprivelagelevel'];
			
		return true;
	}

	public function Logout() {
		global $database;

		unset($_SESSION['email']);

		$this->loggedin = false;
		$this->privelagelevel = GUEST_LEVEL;

		return true;
	}

	public function Register($fname, $lname, $cellphone,
	$bdate, $email, $password, $confpassword)
	{
		global $database, $mailer;

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
			if($database->EmailTaken($email)){
				return USERNAME_TAKEN;
			}
		}

		if(!$password || !$confpassword || $confpassword != $password){
			return PASSWORD_INVALID;
		}
		else{
			$password = stripslashes($password);
			if(strlen($password) < 4){
				return PASSWORD_INVALID;
			}
			else if(!eregi("^([0-9a-z])+$", ($password = trim($password)))){
				return PASSWORD_INVALID;
			}
		}

		if(!$cellphone)
		{
			return CELLPHONE_INVALID;
		}
		else {
			if(strlen($cellphone) < 10){
				return CELLPHONE_INVALID;
			}
		}

		if($database->AddNewUser($fname, $lname, $bdate, $email, $cellphone, md5($password)))
		{
			$mailer->SendRegistration($fname." ".$lname, $email, $password);

			return SUCCEEDED;
		}
		else {
			return DATABASE_ERROR;
		}
	}
	public function IsAdministrator(){
		return ($this->privelagelevel == ADMIN_LEVEL);
	}

	public function MakeSeed()
	{
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	}

	public function GenerateRandID(){
		return md5($this->GenerateRandStr(16));
	}

	public function GenerateRandStr($length){
		$randstr = "";
		for($i=0; $i < $length; $i++){
			$randnum = mt_rand(0,61);
			if($randnum < 10){
				$randstr .= chr($randnum+48);
			}else if($randnum < 36){
				$randstr .= chr($randnum+55);
			}else{
				$randstr .= chr($randnum+61);
			}
		}
		return $randstr;
	}

	public function EditAccount($fname, $lname, $cellphone,
	$bdate, $email, $password, $newpassword, $confpassword)
	{
		global $database, $mailer;
		$emailchanged = false;
		$passwordchanged = false;
		if($fname != $this->userdetail['firstname'])
		{
			$fields[] = "firstname";
			$values[] = $fname;
		}
		if($lname != $this->userdetail['lastname'])
		{
			$fields[] = "lastname";
			$values[] = $lname;
		}
		if($cellphone != $this->userdetail['cellphone'])
		{
			if(!$cellphone)
			{
				return CELLPHONE_INVALID;
			}
			else {
				if(strlen($cellphone) < 10){
					return CELLPHONE_INVALID;
				}
			}
			$fields[] = "cellphone";
			$values[] = $cellphone;
		}

		if($bdate != $this->userdetail['birthdate'])
		{
			if($bdate != NULL && $bdate != '')
			{
				$bdate = date("Y-m-d", strtotime("$bdate"));
				$bdate = mysql_escape_string($bdate);
			}
			else
			{
				$bdate = "NULL";
			}
			$fields[] = "birthdate";
			$values[] = $bdate;
		}
		if($email != $this->userdetail['email'])
		{
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
				if($database->EmailTaken($email)){
					return USERNAME_TAKEN;
				}
			}
			$fields[] = "email";
			$values[] = $email;
			$emailchanged = true;
		}
		if(isset($password) && strlen($password))
		{
			if(md5($password) != $this->userdetail['password'])
			return PASSWORD_INVALID;
			if(!$newpassword || !$confpassword || $confpassword != $newpassword){
				return PASSWORD_INVALID;
			}
			else{
				$newpassword = stripslashes($newpassword);
				if(strlen($newpassword) < 4){
					return PASSWORD_INVALID;
				}
				else if(!eregi("^([0-9a-z])+$", ($newpassword = trim($newpassword)))){
					return PASSWORD_INVALID;
				}
			}

			$fields[] = "password";
			$values[] = md5($newpassword);

			$passwordchanged = true;
		}

		$result = $database->UpdateUser($this->email, $fields, $values);

		if($result)
		{
			if($emailchanged)
			{
				$mailer->SendNewEmail($fname." ".$lname, $email, $email);
				$mailer->SendNewEmail($fname." ".$lname, $this->email, $email);

				$this->email = $_SESSION['email'] = $email;
			}
			if($passwordchanged && $emailchanged)
			{
				$mailer->SendNewPassword($fname." ".$lname, $email, $newpassword);
				$mailer->SendNewPassword($fname." ".$lname, $this->email, $newpassword);
			}
			elseif ($passwordchanged)
			{
				$mailer->SendNewPassword($fname." ".$lname, $this->email, $newpassword);
			}

			return SUCCEEDED;
		}

		return DATABASE_ERROR;
	}
	
	public function SafelyRedirect($location)
	{
		// Don't redirect if ajax request
		if(!$this->ajaxrequest)
		{
			if(!isset($location) || $location == '')
			{
				header("Location: ".$this->referrer); // Redirect to referrer

			}
			else
			{
					
				header("Location: ".$location); // Redirect to location
					
			}
		}
	}

	public function SafelyAbort($location)
	{
		if(!$this->ajaxrequest)
		{
			$this->SafelyRedirect($location);
		}
		else {
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
			header('Retry-After: 60');
		}
	}
	
	public function RegisteredForEvent($idevents){
		global $database;
		if(!$idevents || $idevents == '')
		{
			return ARGUMENT_INVALID;
		}
		$idusers = $this->userdetail[idusers];
		$query = "SELECT events_idevents, users_idusers FROM ".TABLE_ATTENDEES." WHERE events_idevents='$idevents' AND users_idusers='$idusers'";
		$result = $database->ExecuteQuery($query);
		$retval = mysql_num_rows($result);
		return $retval > 0;
	}
	
	public function RegisterForKIT($idevents, $selfdescription, $interests, $school, $classyear, $sport)
	{
		global $database, $mailer;
		
		if(!$idevents)
			return ARGUMENT_INVALID;
		$idusers = $this->userdetail[idusers];
		$query = "INSERT INTO ".TABLE_ATTENDEES." (events_idevents, users_idusers, selfdescription, interests, school, classyear, sport) VALUES ('$idevents', '$idusers', '$selfdescription', '$interests', '$school', '$classyear', '$sport')";
		$result = $database->ExecuteQuery($query);
		return $result;
	}
	
	public function RegisterForB2F($idevents, $selfdescription, $interests, $maritalstatus, $occupation)
	{
		global $database, $mailer;
		
		if(!$idevents)
			return ARGUMENT_INVALID;
		$idusers = $this->userdetail[idusers];
		$query = "INSERT INTO ".TABLE_ATTENDEES." (events_idevents, users_idusers, selfdescription, interests, maritalstatus, occupation) VALUES ('$idevents', '$idusers', '$selfdescription', '$interests', '$maritalstatus', '$occupation')";
		$result = $database->ExecuteQuery($query);
		return $result;
	}

}

$session = new Session();
?>