<?php
require_once 'PHPMailer/class.phpmailer.php';
require_once 'PHPMailer/class.smtp.php';
require_once 'PHPMailer/class.pop3.php';
include_once 'constants.php';
class Mailer
{
	public $phpmailer;
	public function __construct()
	{
		$this->phpmailer = new PHPMailer();
		/*
		$this->phpmailer->IsSMTP();
		//$this->phpmailer->SMTPDebug = 2;
		$this->phpmailer->SMTPAuth = true;
		$this->phpmailer->SMTPSecure = "ssl";
		$this->phpmailer->Host = EMAIL_HOST;
		$this->phpmailer->Port = EMAIL_PORT;
		$this->phpmailer->Username = EMAIL_USERNAME;
		$this->phpmailer->Password = EMAIL_PASSWORD;*/
		$this->phpmailer->IsSendmail(); // telling the class to use SendMail transport
		$this->phpmailer->Priority = 1;
		$this->phpmailer->SetFrom(EMAIL_FROM_ADDR, EMAIL_FROM_NAME);
		$this->phpmailer->AddReplyTo(EMAIL_FROM_ADDR, EMAIL_FROM_NAME);
		$this->phpmailer->AltBody = "To view the message, please use an HTML compatible email viewer!";
	}
	public function __destruct()
	{
		unset($this->phpmailer);
	}
	public function SendRegistration($name, $email, $password)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">\r\n";
		$subject = "Campdavid Namibia - Welcome!";
		$body = "<html> <img src=\"images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."Welcome! You've just registered at Campdavid Namibia "
		."with the following information:<br /><br />"
		."Email: ".$email."<br />"
		."Password: ".$password."<br /><br />"
		."If you ever lose or forget your password, a new "
		."password will be generated for you and sent to this "
		."email address, if you would like to change your "
		."email address you can do so by going to the "
		."My Profile page after signing in.<br /><br />"
		."- Campdavid Namibia</html>";
		
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);
		$this->phpmailer->AddAttachment("images/mailheader.png");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}

	public function SendNewPassword($name, $email, $password)
	{

		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Your new password";
		$body = "<html> <img src=\"images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."We've sent you a new password at your "
		."request, you can use this new password with your "
		."username to log in to Campdavid Namibia.<br /><br />"
		."Email: ".$email."<br />"
		."New Password: ".$password."<br /><br />"
		."It is recommended that you change your password "
		."to something that is easier to remember, which "
		."can be done by going to the My Profile page "
		."after signing in.<br /><br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
public function SendNewEmail($name, $email, $newemail)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Your new email address";
		$body = "<html> <img src=\"images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."We've sent you an email to your new email address according to your "
		."request, you can use this new email address "
		."to log in to Campdavid Namibia.<br /><br />"
		."Email: ".$newemail."<br />"
		."It is recommended that you check this email address."
		."<br /><br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);
		$this->phpmailer->AddAttachment("images/mailheader.png");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
public function SendB2FEventRegistered($name, $email, $campname, $datestart, $dateend, $cost, $description)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Registered for event";
		$body = "<html> <img src=\"images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."You have successfully registered for a new event: <br/>"
		."Camp Name: $campname <br />"
		."Date: $datestart - $dateend <br/>"."Cost: N$ $cost <br/>"
		."Description: $description <br /><br />"
		."Please fill in the attached form and email the completed form and proof of payment to"
		." <a href=\"mailto:info@campdavid.co.na\">info@campdavid.co.na</a> as soon as possible."
		."<br /><br />Thank you<br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$this->phpmailer->AddAttachment("images/mailheader.png");
		$this->phpmailer->AddAttachment("attachements/battle2fightform.docx");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
public function SendKITEventRegistered($name, $email, $campname, $datestart, $dateend, $cost, $description)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Registered for event";
		$body = "<html> <img src=\"images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."You have successfully registered for a new event: <br/>"
		."Camp Name: $campname <br />"
		."Date: $datestart - $dateend <br/>"."Cost: N$ $cost <br/>"
		."Description: $description <br /><br />"
		."Please fill in the attached form and email the completed form and proof of payment to"
		." <a href=\"mailto:info@campdavid.co.na\">info@campdavid.co.na</a> as soon as possible."
		."<br /><br />Thank you<br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$this->phpmailer->AddAttachment("images/mailheader.png");
		$this->phpmailer->AddAttachment("attachements/battle2fightform.docx");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
public function SendA2LEventRegistered($name, $email, $campname, $datestart, $dateend, $cost, $description)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Registered for event";
		$body = "<html> <img src=\"images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."You have successfully registered for a new event: <br/>"
		."Camp Name: $campname <br />"
		."Date: $datestart - $dateend <br/>"."Cost: N$ $cost <br/>"
		."Description: $description <br /><br />"
		."Please fill in the attached form and email the completed form and proof of payment to"
		." <a href=\"mailto:info@campdavid.co.na\">info@campdavid.co.na</a> as soon as possible."
		."<br /><br />Thank you<br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$this->phpmailer->AddAttachment("images/mailheader.png");
		$this->phpmailer->AddAttachment("attachements/battle2fightform.docx");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
public function SendEventPayed($name, $email, $campname, $datestart, $dateend, $cost, $description)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Paid for event";
		$body = "<html> <img src=\"../images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."You have successfully paid for a new event: <br/>"
		."Camp Name: $campname"
		."<br />Date: $datestart - $dateend <br/>"."Cost: N$ $cost <br/>"
		."Description: $description <br /><br />"
		."Please follow the instructions of the attached document."
		."<br /><br />Thank you<br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$this->phpmailer->AddAttachment("../images/mailheader.png");
		$this->phpmailer->AddAttachment("../attachements/battle2fightinfo.docx");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
public function SendKITEventPayed($name, $email, $campname, $datestart, $dateend, $cost, $description)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Paid for event";
		$body = "<html> <img src=\"../images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."You have successfully paid for a new event: <br/>"
		."Camp Name: $campname"
		."<br />Date: $datestart - $dateend <br/>"."Cost: N$ $cost <br/>"
		."Description: $description <br /><br />"
		."Please follow the instructions of the attached document."
		."<br /><br />Thank you<br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$this->phpmailer->AddAttachment("../images/mailheader.png");
		$this->phpmailer->AddAttachment("../attachements/battle2fightinfo.docx");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
	public function SendB2FEventPayed($name, $email, $campname, $datestart, $dateend, $cost, $description)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Paid for event";
		$body = "<html> <img src=\"../images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."You have successfully paid for a new event: <br/>"
		."Camp Name: $campname"
		."<br />Date: $datestart - $dateend <br/>"."Cost: N$ $cost <br/>"
		."Description: $description <br /><br />"
		."Please follow the instructions of the attached document."
		."<br /><br />Thank you<br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$this->phpmailer->AddAttachment("../images/mailheader.png");
		$this->phpmailer->AddAttachment("../attachements/battle2fightinfo.docx");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
	
public function SendA2LEventPayed($name, $email, $campname, $datestart, $dateend, $cost, $description)
	{
		//$from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
		$subject = "Campdavid Namibia - Paid for event";
		$body = "<html> <img src=\"../images/mailheader.png\"></img><br />".$name.",<br /><br />"
		."You have successfully paid for a new event: <br/>"
		."Camp Name: $campname"
		."<br />Date: $datestart - $dateend <br/>"."Cost: N$ $cost <br/>"
		."Description: $description <br /><br />"
		."Please follow the instructions of the attached document."
		."<br /><br />Thank you<br />"
		."- Campdavid Namibia</html>";
		 
		$this->phpmailer->AddAddress($email, $name);
		$this->phpmailer->Subject = $subject;
		$this->phpmailer->MsgHTML($body);		
		$this->phpmailer->AddAttachment("../images/mailheader.png");
		$this->phpmailer->AddAttachment("../attachements/battle2fightinfo.docx");
		$result = $this->phpmailer->Send();
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAttachments();
		return $result;//mail($email,$subject,$body,$from);
	}
}

$mailer = new Mailer;
?>