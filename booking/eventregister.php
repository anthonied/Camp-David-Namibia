<?php
include_once 'include/session.php';
?>



	<form action="registerevent.php" method="post" id="eventregisterform">
		<div id="eventregisterdialog" class="registerscreen">
			<input type="hidden" name="event" value="<?php echo $_REQUEST[eventid];?>" />
			<input type="hidden" name="form" value="eventregisterform" />
			<strong>You will be registered as:</strong>
			<div>
				<label>First Name:</label><strong><?php echo $session->userdetail[firstname];?></strong>
			</div>
			<div>
				<label>Last Name:</label><strong><?php echo $session->userdetail[lastname];?></strong>
			</div>
			<div>
				<label>Cellphone:</label><strong><?php echo $session->userdetail[cellphone];?></strong>
			</div>
			<div>
				<label>Email:</label><strong><?php echo $session->userdetail[email];?></strong>
			</div>
			<a href="#" id="changeprofile">Change Profile</a><br /><br />
			<?php
			if(!$session->RegisteredForEvent($_REQUEST[eventid]))
			{
				$eventtype = $database->GetEventType($_REQUEST[eventid]);
				
			?>
			Please provide the following additional information: 
			<?php
				if($eventtype == '1') // KIT
				{ 
			?>
			<div>
				<label>School: *</label><input type="text" name="school"
					class="ui-widget-content"/>
			</div>	
			<div>
				<label>Current Grade: *</label>
				<select name="classyear" class="combobox">
					<?php 
					for($i = 1; $i <= 12; $i++)
					{
						if($i == 10)
							print "<option value=\"$i\" selected=\"selected\">$i</option>";
						else 
							print "<option value=\"$i\">$i</option>";
					}
					?>
				</select>
			</div>			
			<div>
				<label>Sports:</label><input type="text" name="sport"
					class="ui-widget-content"/>
			</div>	
			<?php
				} elseif($eventtype == '2') // B2F
				{
			?>

			<div>
				<label>Marital Status: *</label>
				<select name="maritalstatus" class="combobox">
				<option value="Single" selected="selected">Single</option>
				<option value="Married">Married</option>
				<option value="Engaged">Engaged</option>				
				<option value="Divorced">Divorced</option>
				</select>
			</div>			
			<div>
				<label>Occupation: *</label><input type="text" name="occupation"
					class="ui-widget-content"/>
			</div>	
			<?php
				} elseif($eventtype == '3') // Adventure to live
				{
			?>

			<div>
				<label>Marital Status: *</label>
				<select name="maritalstatus" class="combobox">
				<option value="Single" selected="selected">Single</option>
				<option value="Married">Married</option>
				<option value="Engaged">Engaged</option>				
				<option value="Divorced">Divorced</option>
				</select>
			</div>			
			<div>
				<label>Occupation: *</label><input type="text" name="occupation"
					class="ui-widget-content"/>
			</div>	
			<?php
				} 
			?>
				<br />
				<div>
					<label style="float: none; width: 100%;">Interests and hobbies:</label><br />
					<textarea name="interests" rows="5" cols="40" class="ui-widget-content"></textarea>
				</div>	
				
				<div>
					<label style="float: none; width: 100%;">Describe yourself in a few words:</label><br />
					<textarea name="selfdescription" rows="5" cols="40" class="ui-widget-content"></textarea>
				</div>	
				<em>* Required fields</em> <br /> <em>Please check all your details</em><br /> <br /><input type="submit"
				value="Register" />
			<?php
				
			}
			else 
			{ 
			?>
				<em><strong>You are already registered for this event!</strong></em>
			<?php
			}
			?>
			
		</div>
	</form>


<script type="text/javascript">
$(document).ready(function(){

	$("#eventregisterdialog").dialog({
		autoOpen: true,
		resizable: true, 
		modal: true, 
		closeOnEscape: true,   
		draggable: true,
		minWidth: 0,
		minHeight: 0,
		title: "Register for an Event",
		width: "640",
		height: "640",
		//close: function () {$("#attendees").html("");},
		open: function() {
			$("#eventregisterdialog").dialog().parent().appendTo("form#eventregisterform");
		}
	});
	var options = {		
			dataType: "script",	  
			beforeSubmit: function(arr, $form, options) {
						return $("#eventregisterform").valid();
				     },   
			success:    function() { 
				$('[id^="eventregister"]').remove(); 
				$("#content").prepend('<div id="message-box"></div>');	
				$.get("include/messagebox.php?msgbox=1&msgtype=highlight&msgtitle=<?php echo rawurlencode("Event: ");?>&msgbody=<?php echo rawurlencode("You successfully registered for a new event. You will receive an email with details shortly.");?>", function(data) { $("#message-box").replaceWith(data);});
										
			} 
    }; 
	$("#eventregisterform").ajaxForm(options); 
	$("button, input:submit, .button").button();

	$("#eventregisterform").validate({
		rules : {
			occupation : "required",
			maritalstatus : "required",
			school: "required",
			classyear: "required"
		},
		messages : {
			occupation : "Please type in your current occupation",
			maritalstatus : "Please choose your marital status",
			school: "Please type in your school",
			classyear: "Please choose your current grade at school"
		}
	});

	$(".combobox").combobox();

	$("#changeprofile").click(function (){
		$("#eventregisterdialog").dialog("close");
		$("#tabcontrol").tabs("select", 2);
	});
});
</script>
