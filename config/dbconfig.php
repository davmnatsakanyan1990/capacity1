<?php
error_reporting(E_ERROR);
$glob['dbhost'] = 'localhost';
$glob['dbusername'] = 'root';
$glob['dbpassword'] = '';
$glob['dbdatabase'] = 'zealousys_capacity';
define("SITE_URL","http://capacityapp.dev/");
date_default_timezone_set('Australia/Melbourne'); 
ini_set("session.gc_maxlifetime", 604800);
include('../PHPMailer-master/PHPMailerAutoload.php');

   /* $mail = new PHPMailer;
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
	$mail->Host = 'mail.capacityapp.net';
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;
	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	
	$mail->IsHTML(true);
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "hello@capacityapp.net";
	//Password to use for SMTP authentication
	$mail->Password = "zG53-3q6g&{u";
	//Set who the message is to be sent from
	$mail->setFrom('hello@capacityapp.net', 'Capacity');*/
	$mail = new PHPMailer;
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;
	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	
	$mail->IsHTML(true);
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "ashish.panchal@zealousys.com";
	//Password to use for SMTP authentication
	$mail->Password = "J3njjhWj";
	//Set who the message is to be sent from
	$mail->setFrom('ashish.panchal@zealousys.com', 'Zealousys');

class sendmails{
	function commonmailfun($to,$reciver_name, $subject, $body){
	    $headers = "From: hello@capacityapp.net \r\n";
	    $headers .= "Reply-To: hello@capacityapp.net \r\n";	
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	    mail($to,$subject, $body, $headers,  '-fnoreply@capacityapp.net') or die('mail sending fail');	
	}

}

?>
