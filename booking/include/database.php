<?php
include_once ("include/constants.php");
// Class to easily manage MySQL connection
class MySQLDB
{
	public $connection;	// The MySQL connection variable

	public function __construct(){
		$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS, false, MYSQL_CLIENT_COMPRESS | MYSQL_CLIENT_SSL) or die(mysql_error());
		mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
	}
	public function __destruct(){
		mysql_close($this->connection) or die(mysql_error());
	}
	public function ExecuteQuery($query){
		// NOTE: Maby remove the ping command
		mysql_ping($this->connection) or die(mysql_error()); // Keeps connection alive
		return mysql_query($query, $this->connection); // Return the query result
	}
	public function ConfirmLoginDetails($username, $password)
	{
		if(!get_magic_quotes_gpc()) {
			$username = addslashes($username);
		}
			
		$result = $this->ExecuteQuery("SELECT password FROM " .TABLE_USERS." WHERE email = '$username'" );
		if(!result || (mysql_numrows($result) < 1)){
			return USERNAME_INVALID;
		}

		$dbarray = mysql_fetch_array($result);
		$dbarray['password'] = stripslashes($dbarray['password']);
		$password = stripslashes($password);
			
		if($password == $dbarray['password']){
			return SUCCEEDED;
		}
		else
		return PASSWORD_INVALID;
	}
	public function AddNewUser($fname, $lname, $bdate, $email, $cellphone, $password)
	{
		$fname = mysql_escape_string($fname);
		$lname = mysql_escape_string($lname);
		
		if($bdate != NULL && $bdate != '')
		{						
			$bdate = date("Y-m-d", strtotime("$bdate"));
			$bdate = "'".mysql_escape_string($bdate)."'";
		}
		else
		{
			$bdate = "NULL";
		}
		
		$email = mysql_escape_string($email);
		$cellphone = mysql_escape_string($cellphone);
		$password = mysql_escape_string($password);
		$query = "INSERT INTO ".TABLE_USERS." (firstname, lastname, email, password,
		birthdate, cellphone) VALUES ('$fname', '$lname', '$email', '$password', $bdate, '$cellphone')";
		return $this->ExecuteQuery($query);
	}
	public function UpdateUser($email, $field, $value){

		if(!isset($field) || !isset($value))
		return true;
		$email = mysql_escape_string($email);
		if(is_array($field) && is_array($value)) {
			if(count($field) == count($value))

			{
				$numfields = count($field);
				$query = "UPDATE ".TABLE_USERS." SET";
				for($i = 0; $i < $numfields; $i++){
					$field[$i] = mysql_escape_string($field[$i]);
					$value[$i] = mysql_escape_string($value[$i]);
					if(isset($value[$i]) && $value[$i] != 'NULL' && $value[$i] != '')
					$query .= " $field[$i]='$value[$i]'";
					else
					$query .= " $field[$i]=NULL";
					if($i != $numfields-1)
					$query .= ",";
				}
				$query .= " WHERE email='$email'";

				return $this->ExecuteQuery($query);
			}
			else{
				return ARGUMENT_INVALID;
			}
		}
		else {
			$field = mysql_escape_string($field);
			$value = mysql_escape_string($value);

			if(isset($value) && $value != 'NULL' && $value != '')
			return $this->ExecuteQuery("UPDATE ".TABLE_USERS." SET $field='$value' WHERE email='$email'");
			else
			return $this->ExecuteQuery("UPDATE ".TABLE_USERS." SET $field=NULL WHERE email='$email'");
		}
	}

	public function UpdateEvent($idevents, $field, $value){

		if(!isset($field) || !isset($value))
		return true;
		$idevent = mysql_escape_string($idevent);
		if(is_array($field) && is_array($value)) {
			if(count($field) == count($value))

			{
				$numfields = count($field);
				$query = "UPDATE ".TABLE_EVENTS." SET";
				for($i = 0; $i < $numfields; $i++){
					$field[$i] = mysql_escape_string($field[$i]);
					$value[$i] = mysql_escape_string($value[$i]);
					if(isset($value[$i]) && $value[$i] != 'NULL' && $value[$i] != '')
					$query .= " $field[$i]='$value[$i]'";
					else
					$query .= " $field[$i]=NULL";
					if($i != $numfields-1)
					$query .= ",";
				}
				$query .= " WHERE idevents='$idevents'";

				return $this->ExecuteQuery($query);
			}
			else{
				return ARGUMENT_INVALID;
			}
		}
		else {
			$field = mysql_escape_string($field);
			$value = mysql_escape_string($value);

			if(isset($value) && $value != 'NULL' && $value != '')
			return $this->ExecuteQuery("UPDATE ".TABLE_EVENTS." SET $field='$value' WHERE idevents='$idevents'");
			else
			return $this->ExecuteQuery("UPDATE ".TABLE_EVENTS." SET $field=NULL WHERE idevents='$idevents'");
		}
	}

	public function GetUserInfo($email)
	{
		$email = mysql_escape_string($email);
		$result = $this->ExecuteQuery("SELECT * FROM ".TABLE_USERS." WHERE email='$email'");

		if(!$result || (mysql_numrows($result) < 1)){
			return NULL;
		}
			
		return mysql_fetch_array($result);
	}
	
public function GetUserInfoAlt($idusers)
	{
		$email = mysql_escape_string($email);
		$result = $this->ExecuteQuery("SELECT * FROM ".TABLE_USERS." WHERE idusers='$idusers'");

		if(!$result || (mysql_numrows($result) < 1)){
			return NULL;
		}
			
		return mysql_fetch_array($result);
	}

	public function ConfirmUserEmail($email) {
		if(!get_magic_quotes_gpc()) {
			$email = addslashes($email);
		}

		$result = $this->ExecuteQuery("SELECT email FROM ".TABLE_USERS." WHERE email='$email'");
		if(!$result || (mysql_numrows($result) < 1)){
			return USERNAME_INVALID; //Indicates username failure
		}
		return SUCCEEDED;
	}

	public function EmailTaken($username){
		if(!get_magic_quotes_gpc()){
			$username = addslashes($username);
		}
		$query = "SELECT email FROM ".TABLE_USERS." WHERE email = '$username'";
		$result = $this->ExecuteQuery($query);
		return (mysql_numrows($result) > 0);
	}

	public function EditEvent($idevents, $description, $startdate, $enddate, $maxattendance, $cost, $partialpayments, $status, $camps_idcamps)
	{
		if(!$idevents)
		{
			return ARGUMENT_INVALID;
		}
		if($description != null && $description != '')
		{
			$fields[] = "description";
			$values[] = $description;
		}
		if($startdate != null && $startdate != '')
		{
			$fields[] = "startdate";
			$values[] = $startdate;
		}
		if($enddate != null && $enddate != '')
		{
			$fields[] = "enddate";
			$values[] = $enddate;
		}
		if($maxattendance != null && $maxattendance != '')
		{
			$fields[] = "maxattendance";
			$values[] = $maxattendance;
		}
		if($cost != null && $cost != '')
		{
			$fields[] = "cost";
			$values[] = $cost;
		}
		if($partialpayments != null && $partialpayments != '')
		{
			$fields[] = "partialpayments";
			$values[] = $partialpayments;
		}
		if($status != null && $status != '')
		{
			$fields[] = "status";
			$values[] = $status;
		}
		if($camps_idcamps != null && $camps_idcamps != '')
		{
			$fields[] = "camps_idcamps";
			$values[] = $camps_idcamps;
		}

		$this->UpdateEvent($idevents, $fields, $values);
	}

	public function AddEvent($description, $startdate, $enddate, $maxattendance, $cost, $partialpayments, $status, $camps_idcamps)
	{
		if($description != null && $description != '')
		{
			$fields[] = "description";
			$values[] = $description;
		}
		if($startdate != null && $startdate != '')
		{
			$fields[] = "startdate";
			$values[] = $startdate;
		}
		else
		{
			return ARGUMENT_INVALID;
		}
		if($enddate != null && $enddate != '')
		{
			$fields[] = "enddate";
			$values[] = $enddate;
		}
		else
		{
			return ARGUMENT_INVALID;
		}
		if($maxattendance != null && $maxattendance != '')
		{
			$fields[] = "maxattendance";
			$values[] = $maxattendance;
		}
		else
		{
			return ARGUMENT_INVALID;
		}
		if($cost != null && $cost != '')
		{
			$fields[] = "cost";
			$values[] = $cost;
		}
		else
		{
			return ARGUMENT_INVALID;
		}
		if($partialpayments != null && $partialpayments != '')
		{
			$fields[] = "partialpayments";
			$values[] = $partialpayments;
		}
		else
		{
			return ARGUMENT_INVALID;
		}
		if($status != null && $status != '')
		{
			$fields[] = "status";
			$values[] = $status;
		}
		else
		{
			return ARGUMENT_INVALID;
		}
		if($camps_idcamps != null && $camps_idcamps != '')
		{
			$fields[] = "camps_idcamps";
			$values[] = $camps_idcamps;
		}
		else
		{
			return ARGUMENT_INVALID;
		}

		$numfields = count($fields);
		$query = "INSERT INTO ".TABLE_EVENTS." (";
		for($i = 0; $i < $numfields; $i++){
			$field[$i] = mysql_escape_string($fields[$i]);
			$query .= $fields[$i];
			if($i != $numfields-1)
			$query .= ",";
		}

		$query .= ") VALUES(";
		for($i = 0; $i < $numfields; $i++){

			$value[$i] = mysql_escape_string($values[$i]);
			if(isset($values[$i]) && $values[$i] != 'NULL' && $values[$i] != '')
			$query .= " '$values[$i]'";
			else
			$query .= " NULL";
			if($i != $numfields-1)
			$query .= ",";
		}
		$query .= ")";

		echo $query;
		return $this->ExecuteQuery($query);
	}
	public function DeleteEvent($idevents, $idusers)
	{
		if(!$idevents || !$idusers)
		{
			return ARGUMENT_INVALID;
		}

		return $this->ExecuteQuery("DELETE FROM ".TABLE_ATTENDEES." WHERE events_idevents='$idevents' AND users_idusers='$idusers'");
	}
	public function GetEventType($idevents)
	{
		if(!$idevents || $idevents == '')
		{
			return ARGUMENT_INVALID;
		}

		$result = $this->ExecuteQuery("SELECT camps_idcamps FROM ".TABLE_EVENTS." WHERE idevents='$idevents'");
		$array = mysql_fetch_array($result, MYSQL_ASSOC);
		return $array["camps_idcamps"];
	}
	public function EditMyEvent($idevents, $idusers, $amountpayed, $payedinfull)
	{
		global $mailer;
		if(!$idevents || !$idusers)
		{
			return ARGUMENT_INVALID;
		}
		if($amountpayed != null && $amountpayed != '')
		{
			$fields[] = "amountpayed";
			$values[] = $amountpayed;
		}
		if($payedinfull != null && $payedinfull != '')
		{
			$fields[] = "payedinfull";
			$values[] = $payedinfull;
				
			if($payedinfull == '1')
			{
				if(!$this->GetServiceNumber($idevents, $idusers))
				$servicenumber = $this->GetServiceNumber($idevents, $idusers);
				if(!$servicenumber || $servicenumber = '' || $servicenumber = "NULL" || !isset($servicenumber))
					$servicenumber = $this->SetServiceNumber($idevents, $idusers);

				$fields[] = "servicenumber";
				$values[] = $servicenumber;
			}
		}

		$numfields = count($fields);
		$query = "UPDATE ".TABLE_ATTENDEES." SET";
		for($i = 0; $i < $numfields; $i++){
			$fields[$i] = mysql_escape_string($fields[$i]);
			$values[$i] = mysql_escape_string($values[$i]);
			if(isset($values[$i]) && $values[$i] != 'NULL' && $values[$i] != '')
			$query .= " $fields[$i]='$values[$i]'";
			else
			$query .= " $field[$i]=NULL";
			if($i != $numfields-1)
			$query .= ",";
		}
		$query .= " WHERE events_idevents='$idevents' AND users_idusers='$idusers'";

		return $this->ExecuteQuery($query);
	}
	public function GetEventDetails($idevents)
	{
		if(!$idevents || $idevents == '')
		{
			return ARGUMENT_INVALID;
		}

		$query = "SELECT * FROM ".TABLE_EVENTS." WHERE idevents='$idevents'";
		$result = $this->ExecuteQuery($query);

		return mysql_fetch_array($result, MYSQL_ASSOC);
	}
	public function GetActiveEvents()
	{
		$query = "SELECT * FROM ".TABLE_EVENTS." WHERE status='Active'";
		$result = $this->ExecuteQuery($query);

		$concat = array();
		while($array  = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$concat[] = $array;
		}
		return $concat;
	}
	public function GetNumAttendees($idevents)
	{
		if(!$idevents || $idevents == '')
		{
			return ARGUMENT_INVALID;
		}

		$query = "SELECT maxattendance FROM ".TABLE_EVENTS." WHERE idevents='$idevents'";
		$result = $this->ExecuteQuery($query);		
		$array  = mysql_fetch_array($result, MYSQL_ASSOC);

		$maxattendance = $array[maxattendance];
		
		$query = "SELECT COUNT(*) as count FROM ".TABLE_ATTENDEES." WHERE events_idevents='$idevents'";
		$result = $this->ExecuteQuery($query);		
		$array  = mysql_fetch_array($result, MYSQL_ASSOC);
		$count = $array[count];
		
		$array = array(maxattendance => $maxattendance, count => $count);
		return $array;
	}
	public function GetServiceNumber($idevents, $idusers)
	{
		if(!$idevents || $idevents == '')
		{
			return ARGUMENT_INVALID;
		}
		if(!$idusers || $idusers == '')
		{
			return ARGUMENT_INVALID;
		}
		
		$query = "SELECT servicenumber FROM ".TABLE_ATTENDEES." WHERE events_idevents='$idevents' AND users_idusers='$idusers'";
		$result = $this->ExecuteQuery($query);
		
		$array = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$sn = $array[servicenumber];
		return $sn;
		
	}
	public function SetServiceNumber($idevents, $idusers)
	{
		if(!$idevents || $idevents == '')
		{
			return ARGUMENT_INVALID;
		}
		if(!$idusers || $idusers == '')
		{
			return ARGUMENT_INVALID;
		}
		
		$query = "SELECT COUNT(*) AS count FROM ".TABLE_ATTENDEES." WHERE events_idevents='$idevents' AND payedinfull='1' AND servicenumber IS NOT NULL AND users_idusers!='$idusers'";

		$result = $this->ExecuteQuery($query);
		if(!$result)
		{
			$count = 0;
		}
		else
		{
			$count = mysql_fetch_array($result, MYSQL_ASSOC);
			$count = $count[count];
		}
			
		$date = getdate();
		$servicenumber = substr($date[year], 2, 2) . ($count+1);
		return $servicenumber;
	}
	public function CheckAvailabilityForEvent($idevents)
	{
		if(!$idevents || $idevents == '')
		{
			return ARGUMENT_INVALID;
		}
		$result = $this->GetNumAttendees($idevents);

		return (($result[maxattendance]) - ($result[count]));
	}
}

$database = new MySQLDB();