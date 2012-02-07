<?php
include_once 'session.php';
$idusers = $_REQUEST[users_idusers];
$idevents = $_REQUEST[events_idevents];
$query = "SELECT * FROM ".TABLE_ATTENDEES." WHERE users_idusers='$idusers' AND events_idevents='$idevents'";

	$result = $database->ExecuteQuery($query);
	$i = 0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$response->rows[$i]=$row;
		$i++;
	}
	
	$json = json_encode($response);
	
	echo $json;
?>