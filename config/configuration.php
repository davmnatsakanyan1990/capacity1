<?php
session_start();
ob_start("ob_gzhandler");
@ob_gzhandler();
error_reporting(E_ERROR);
define("TITLE","Administrative Control Panel");
define("TITLE1","Welcome Administrative Panel");	
ini_set("session.gc_maxlifetime", 604800);
date_default_timezone_set('Australia/Melbourne'); 

//Database Configuraion 
$glob['dbhost'] = 'localhost';
$glob['dbusername'] = 'root';
$glob['dbpassword'] = '';
$glob['dbdatabase'] = 'zealousys_capacity';
//Section  Wise Contant set Up 
if(strstr($_SERVER['PHP_SELF'],"admin"))
{

require_once('../init.php');
$stripe = array(
  'secret_key'      => 'sk_test_yOuLqPiODfTH3R1sbFdWiKOd',
  'publishable_key' => 'pk_test_c9nmTqxgGmmlXL2QLcia4vn6'
 );
\Stripe\Stripe::setApiKey($stripe['secret_key']);


	//  Set Defaults Directory
	define("MAIN_DIR","../");
	define("SEO_FRIENDLY",true);
	define("SITE_URL","http://opensource.zealousys.com/capacity/admin/");
	define("ACTUAL_LINK","http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
	// Directory Setup All   
	define("ADMINISTRATOR","admin/");
	//Includes All
	define("INC","inc/");
	define("OTHERPATH","inc/other/");
	define("CSSPATH","inc/css/");
	define("JSPATH","inc/js/");
	define("IMAGEPATH","inc/image/");
	define("DATABASE","database/");
	define("CONFIG","config/");
	define("CLASSES","classes/");
	define("AJAXCALL","inc/ajax_call/");
	define("UPLOAD","upload/");
	
	include("../classes/loader.php");
	include("../config/message.php");
	
	$load  =  new loader();	
	$gnrl = new general();
	$dclass = new database();


	include('../PHPMailer-master/PHPMailerAutoload.php');


	
}else{
//require_once('Stripe.php');
require_once('init.php');
$stripe = array(
  'secret_key'      => 'sk_test_yOuLqPiODfTH3R1sbFdWiKOd',
  'publishable_key' => 'pk_test_c9nmTqxgGmmlXL2QLcia4vn6'
  );
\Stripe\Stripe::setApiKey($stripe['secret_key']);

	//  Set Defaults Directory
	define("MAIN_DIR","./");
	define("SEO_FRIENDLY",true);
	// Directory Setup All   
	define("ADMINISTRATOR","http://opensource.zealousys.com/capacity/");
	define("ACTUAL_LINK","http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
	//Includes All
	define("INC","includes/");
	define("OTHERPATH","other/");
	define("CSSPATH","css/");
	define("JSPATH","js/");
	define("IMAGEPATH","assets/img/");
	define("DATABASE","database/");
	define("CONFIG","config/");
	define("CLASSES","classes/");
	
	define("AJAXCALL","ajax_call/");
	define("UPLOAD","upload/");

	define("SITE_URL","http://capacityapp.dev/");
	include("classes/loader.php");
	include("config/message.php");
	include('config/guidemsg.php');
	include('PHPMailer-master/PHPMailerAutoload.php');
	$load  =  new loader();	
	$gnrl = new general();
	$dclass = new database();
	//$SITE_MAINTENANCE = $dclass->select('l_values','tbl_setting',' AND v_name="SITE_MAINTENANCE"');
	//$SITE_MAINTENANCE = $gnrl->getSettings('SITE_MAINTENANCE');
	//echo $SITE_MAINTENANCE[0]['l_values'];
	/*if(strtolower($SITE_MAINTENANCE[0]['l_values']) == strtolower('yes')) {
		echo '<h2 style="text-align:center;float:left;width:100%;margin:150px 0 0 0;font-family:Comic Sans MS">Site is currently under maintenance.<br/> We will get back Soon.....</h2>';
		exit;
	}*/
}


$request_url =  $_SERVER['REQUEST_URI'];
$basefile = basename($_SERVER['PHP_SELF']);

	/*$mail = new PHPMailer;
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
	$mail->Password = "bzF%]c2_-i-S";
	//Set who the message is to be sent from
	$mail->setFrom('hello@capacityapp.net', 'Capacity');
*/
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
?>
