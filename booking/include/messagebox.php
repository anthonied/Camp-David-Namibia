<?php
function MessageBox($type, $message, $title)
{
	$style = '';	
	$icon = '';		
	switch ($type) {
		case "error":
			$style= "ui-state-error";	
			$icon = "ui-icon-alert";
			break;
		case "success":
			$style= "ui-state-success";	
			$icon = "ui-icon-circle-check";
			break;
		case "highlight":			
			$style= "ui-state-highlight";	
			$icon = "ui-icon-info";
			break;
		default:
			$style= "ui-state-highlight";	
			$icon = "ui-icon-info";
			break;
	}
	
	mt_srand(time());
	$randommsg = mt_rand(0,9999);
	
	$html = <<<MESSAGE
	<div class="$style ui-corner-all messagebox msg$randommsg"><span class="ui-icon $icon"></span> <strong>$title</strong>$message</div>
	<script> $(document).ready( function(){ setTimeout(" $('.msg$randommsg').fadeOut(2000);", 5000);});</script> 	 
MESSAGE;
	echo $html;
}

if(isset($_REQUEST['msgbox']))
{
	MessageBox($_REQUEST['msgtype'],$_REQUEST['msgbody'],$_REQUEST['msgtitle']);
}
?>