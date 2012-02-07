<?php
include_once 'session.php';
include_once 'messagebox.php';
if(isset($_SESSION['feedback']) && isset($_SESSION['feedbacktype']) && isset($_SESSION['feedbacktitle']))
{
	MessageBox($_SESSION['feedbacktype'], $_SESSION['feedback'], $_SESSION['feedbacktitle']);
	unset($_SESSION['feedback']);
	unset($_SESSION['feedbacktype']);
	unset($_SESSION['feedbacktitle']);			
}
else 
{
	MessageBox("error", "An unknown error occurred. Please check that your browser is up to date.", "Error: ");
}
?>