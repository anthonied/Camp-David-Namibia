$("#registerlink").click(function() {
	$("#registerdialog").dialog("open").parent().appendTo("form#registerform");
});
$("#forgotlink").click(function() {
	$("#forgotpassworddialog").dialog("open").parent().appendTo("form#forgotpasswordform");
});
$("#bdate").datepicker({
	changeYear : true,
	changeMonth : true,
	yearRange : "1900:c",
	dateFormat : "yy-mm-dd"
});
$(".date-widget").datepicker({
	changeYear : true,
	changeMonth : true,
	yearRange : "1900:c",
	dateFormat : "yy-mm-dd"
});
$("#registerdialog").dialog({
	autoOpen : false,
	resizable : false,
	modal : true,
	closeOnEscape : true,
	draggable : true,
	minWidth : 0,
	minHeight : 0,
	width : 600,
	buttons : {
		Submit : function() {
			if ($("#registerform").valid())
			{
				$("#ajaxloader").dialog("open");
				document.forms.registerform.submit();
			}
		}
	}
});
$("#forgotpassworddialog").dialog({
	autoOpen : false,
	resizable : false,
	modal : true,
	closeOnEscape : true,
	draggable : true,
	minWidth : 0,
	minHeight : 0,
	width : 600,
	buttons : {
		Submit : function() {
			
			if ($("#forgotpasswordform").valid())
			{
				$("#ajaxloader").dialog("open");
				document.forms.forgotpasswordform.submit();
			}
		}
	}
});
$("#registerform").validate({
	rules : {
		fname : "required",
		lname : "required",
		bdate : {
			required : false,
			dateISO : true
		},
		cellphone : {
			required : true,
			minlength : 5
		},
		password : {
			required : true,
			minlength : 5
		},
		confpassword : {
			required : true,
			minlength : 5,
			equalTo : "#registerpassword"
		},
		email : {
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
		confpassword : {
			required : "Please provide a password",
			minlength : "Your password must be at least 5 characters long",
			equalTo : "Please enter the same password as above"
		},
		email : "Please enter your email address"
	}
});
$("#forgotpasswordform").validate({
	rules : {		
		email : {
			required : true,
			email : true
		}
	},
	messages : {
		email : "Please enter your registered email address"
	}
});