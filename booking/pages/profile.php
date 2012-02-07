<?php
include_once '../include/session.php';
?>



<form action="editprofile.php"
	method="post" id="editprofileform">
	<div class="registerscreen">
		<input type="hidden" name="form" value="editprofileform" />
		<div>
			<label>Name: *</label><input type="text" name="fname"
				class="ui-widget-content"
				value="<?php echo $session->userdetail['firstname'];?>"/>
		</div>
		<div>
			<label>Lastname: *</label><input type="text" name="lname"
				class="ui-widget-content"
				value="<?php echo $session->userdetail['lastname'];?>"/>
		</div>
		<div>
			<label>Cellphone: *</label><input type="text"
				name="cellphone" class="ui-widget-content"
				value="<?php echo $session->userdetail['cellphone'];?>"/>
		</div>
		<div>
			<label>Birthdate:</label><input type="text" name="bdate"
				class="ui-widget-content date-widget"
				value="<?php echo $session->userdetail['birthdate'];?>"/>
		</div>

		<br />
		<div>
			<label>Email: *</label><input type="text" name="email"
				class="ui-widget-content"
				value="<?php echo $session->userdetail['email'];?>"/>
		</div>
		<div>
			<label>Current Password:</label><input
				type="password" name="password" class="ui-widget-content" value=""/>
		</div>
		<div>
			<label>New Password:</label><input
				type="password" name="newpassword" class="ui-widget-content"
				value=""/>
		</div>
		<div>
			<label>Confirm Password:</label><input
				type="password" name="confpassword" id="confpassword"
				class="ui-widget-content" value=""/>
		</div>
		<em>* Required fields</em> <br /> <br /> <input type="submit"
			value="Submit" />
	</div>
</form>

<script type="text/javascript">
$(document).ready(function(){
	var options = {		
			dataType: "script",	  
			beforeSubmit: function(arr, $form, options) {
						return $("#editprofileform").valid();
				     },   
			success:    function() {  
				$("#content").prepend('<div id="message-box"></div>');	
				$.get("include/messagebox.php?msgbox=1&msgtype=highlight&msgtitle=<?php echo rawurlencode("Profile: ");?>&msgbody=<?php echo rawurlencode("Profile successfully updated.");?>", function(data) { $("#message-box").replaceWith(data);});						
			} 
    }; 
	$("#editprofileform").ajaxForm(options); 
	$("button, input:submit, .button").button();

	$(".date-widget").datepicker({
		changeYear : true,
		changeMonth : true,
		yearRange : "1900:c",
		dateFormat : "yy-mm-dd"
	});

	$("#editprofileform").validate({
		rules : {
			fname : "required",
			lname : "required",
			bdate : {
				required : false,
				dateISO : true
			},
			cellphone : {
				required : true,
				number : true,
				minlength : 10
			},
			password : {
				required : false,
				minlength : 5
			},
			newpassword : {
				required : false,
				minlength : 5
			},
			confpassword : {
				required : false,
				minlength : 5,
				equalTo : "[name=newpassword]"
			},
			registeremail : {
				required : true,
				email : true
			}
		},
		messages : {
			fname : "Please enter your firstname",
			lname : "Please enter your lastname",
			cellphone : {
				required : "Please enter a valid cellphone number",
				minlength : "Format: 0123456789"
			},
			bdate : "Please enter a valid date: yyyy-mm-dd",
			password : {
				required : "Please provide a password",
				minlength : "Your password must be at least 5 characters long"
			},
			newpassword : {
				required : "Please provide a password",
				minlength : "Your password must be at least 5 characters long"
			},
			confpassword : {
				required : "Please provide a password",
				minlength : "Your password must be at least 5 characters long",
				equalTo : "Please enter the same password as above"
			},
			email : "Please enter your email address"
		}
	});
});
</script>
