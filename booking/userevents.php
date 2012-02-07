<?php
include_once 'include/session.php';
?>
<h1>Available Events</h1>
<?php
$events = $database->GetActiveEvents();

$i = 0;
foreach ($events as $event) {
	if(!$session->RegisteredForEvent($event[idevents]) && ($database->CheckAvailabilityForEvent($event[idevents]) > 0))
	{
		$i++;
		$campimage = '';
		switch($database->GetEventType($event[idevents]))
		{
			case "1":
				$campimage = "images/kitcamp.jpg";
				break;
			case "2":
				$campimage = "images/battletofight.jpg";
				break;
		}
		?>
<div
	class="eventdisplay ui-corner-all ui-widget-content ui-state-highlight">
	<img src="<?php echo $campimage ?>"></img>
	<h2>
		Date: <em><?php echo $event[startdate] . " to " . $event[enddate]?> </em>
	</h2>
	<h2>
		Description: <em><?php echo $event[description]?> </em>
	</h2>
	<h2>
		Cost: <em>N$ <?php echo $event[cost]?> </em>
	</h2>

	<button id="registerforevent<?php echo $event[idevents];?>" onclick="return registereventfunc('<?php echo $event[idevents];?>');">Register</button>
</div>
		<?php
	}	

}
	if($i == 0)
	{
	?>
	<div class="eventdisplay ui-corner-all ui-widget-content ui-state-highlight" style="text-align: center;">
		<h2>No events available.</h2>
	</div>
	<?php

	}
?>

<script type="text/javascript">
$(document).ready( function() {
	$("button, input:submit, .button").button();	
});

function registereventfunc(eventid)
{
	$('[id^="eventregister"]').remove();					
	$("#content").prepend('<div id="eventregister"></div>');	
	$.get("eventregister.php?eventid="+eventid, function(data) { $("#eventregister").replaceWith(data);});
}
</script>
