<?php
//error_reporting(E_ALL | E_STRICT);

//ini_set('display_errors', true);
//ini_set('html_errors', true);
set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/www/users/campdnevhm/booking/');

include_once ("include/session.php");
//include_once ("include/mimetype.php");
include_once("include/messagebox.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Campdavid Namibia</title>
<meta charset="utf-8">
<link rel="shortcut icon" href="favicon.ico" />

<!-- Load jQuery UI Humanity theme -->
<!-- <link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/humanity/jquery-ui.css"
	rel="stylesheet" type="text/css" /> -->
	<link
	href="styles/jquery-ui-1.8.13.custom.css"
	rel="stylesheet" type="text/css" />


	<link
	href="styles/ui.jqgrid.css"
	rel="stylesheet" type="text/css" />

<!-- Load jQuery -->
<!--  <script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"
	type="text/javascript"></script>-->
	<script
	src="scripts/jquery-1.6.1.min.js"
	type="text/javascript"></script>
<!-- Load jQueryUI -->
<!--  <script
	src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"
	type="text/javascript"></script>-->
	<script
	src="scripts//jquery-ui-1.8.13.custom.min.js"
	type="text/javascript"></script>
<!--  <script
	src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"
	type="text/javascript"></script>-->
	
 <script
	src="scripts/jquery.validate.min.js"
	type="text/javascript"></script>
<!--  	<script
	src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/additional-methods.min.js"
	type="text/javascript"></script>-->
<script
	src="scripts/additional-methods.min.js"
	type="text/javascript"></script>
<script
	src="scripts/jquery.form-min.js"
	type="text/javascript"></script>
	<script src="scripts/grid.locale-en.js" type="text/javascript"></script>
	
<script
	src="scripts/jquery.jqGrid.min.js"
	type="text/javascript"></script>
<script
	src="scripts/jquery-combobox.js"
	type="text/javascript"></script>	
	
	<link href="styles/campdavidnamib.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
     
	$(document).ready(function(){
		$.validator.setDefaults({
			//submitHandler: function() { alert("submitted!"); },
			highlight: function(input) {
				$(input).addClass("ui-state-highlight");
			},
			unhighlight: function(input) {
				$(input).removeClass("ui-state-highlight");
			}
		});	

        $("#ajaxloader" ).dialog({
			autoOpen: false,
			resizable: false, 
			modal: true, 
			closeOnEscape: false,   
			draggable: false,
			minWidth: 0,
			minHeight: 0,
			width: "auto",
			height: "auto",
			open: function(event, ui) { $("#ajaxloader").dialog().parents(".ui-dialog").find(".ui-dialog-titlebar").remove(); 
			}
		});

        $.ajaxSetup ({   
            // Disable caching of AJAX responses    
            cache: false
            });
		$("#ajaxloader").ajaxStart(function(){	
			$(this).dialog("open");	
		});
		$("#ajaxloader").ajaxStop(function(){	
			$(this).dialog("close");	
		});
		$("#ajaxloader").ajaxError(function(e, jqxhr, settings, exception) {	
			$("#content").prepend('<div id="message-box"></div>');	
			$.get("include/getlasterror.php", function(data) { $("#message-box").replaceWith(data);});		
		});
		
		$("button, input:submit, .button").button();		

		$("#homelink").mouseover(function(){
			$(this).attr("src", "images/home_over.jpg");
		});
		$("#homelink").mouseout(function(){
			$(this).attr("src", "images/home_up.jpg");
		});
		
		<?php 		
		
		if(!$session->loggedin)
		{ 
			include 'scripts/loginregistration.js';
		}?>	

		$( "#tabcontrol" ).tabs({
			//cache: false,
			fx: {
				height: "toggle",
				opacity: "toggle",
				duration: "fast"
			},
			ajaxOptions: {
				type: "POST",
				//cache: false,
				dataType: "html",
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible. We apologize for any inconvenience.");
				}
			}
		});

		$("#profile").click(function (){
			$("#tabcontrol").tabs("select", 2);
		});
		$("#logout").click(function (){
			window.location = "login.php";
		});
	});
</script>
</head>
<body>
	<div class="page">
		<div id="header">
			<a class="link" href="<?php echo CAMPDAVID_HOME_URL ?>"> <img
				src="images/home_up.jpg" alt="Home" id="homelink" /> </a>
		</div>
		<div id="content" style="min-height: 332px">
		<?php
		if(isset($_SESSION['feedback']) && isset($_SESSION['feedbacktype']) && isset($_SESSION['feedbacktitle']))
		{
			MessageBox($_SESSION['feedbacktype'], $_SESSION['feedback'], $_SESSION['feedbacktitle']);
			unset($_SESSION['feedback']);
			unset($_SESSION['feedbacktype']);
			unset($_SESSION['feedbacktitle']);
			
		}
		if(isset($_SESSION['value_array']))
		{
			unset($_SESSION['value_array']);
		}
		if(!$session->loggedin)
		{
			include 'pages/loginregistration.php';
		}
		else 
		{			
		?>
			<div style="float: right; clear: both;">Logged in as <a href="#" id="profile"><?php echo $session->userdetail['firstname']." ".$session->userdetail['lastname']?></a></div>
			<div style="float: right; clear: both"><a href="#" class="button" id="logout">Logout</a></div>
			<br /><br />
			<div id="tabcontrol" style="margin-top: 3em;">
				<ul>				
					<li><a href="events.php" <?php if($session->IsAdministrator()) echo 'style="color: red;"';?>>Events</a></li>
					<?php if(!$session->IsAdministrator()){?>
					<li><a href="myevents.php">My Events</a></li>
					<?php }?>
					<li><a href="pages/profile.php">My Profile</a></li>
					<?php if($session->IsAdministrator()){?>
					<li><a href="users.php" style="color: red;">Users</a></li>
					<?php }?>
				</ul>
			</div>
		<?php
		} 
		?>
		</div>
	<div id="campdavidbanner"></div>
		<div id="footer">
			<a href="http://www.polard.com"><img class="polar"
				alt="Design by Polar Design Solutions" src="images/polar_logo.gif" />
			</a> <a href="http://www.solutionserver.co.za"><img class="solution"
				alt="Development and hosting by Solution Server"
				src="images/solution_server.gif" /> </a>
		</div>

		<div style="text-align: center;" id="ajaxloader">
			<img src="images/ajax-loader.gif" alt="Loading" />
		</div>
	</div>
</body>
</html>