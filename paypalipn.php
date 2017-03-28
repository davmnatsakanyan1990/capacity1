<?php
// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 1);
// Set to 0 once you're ready to go live
define("USE_SANDBOX", 1);
define("LOG_FILE", "ipn.log");

//print_r($_POST); 

// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.

$raw_post_data = file_get_contents('php://input');
//echo $raw_post_data;

$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}

$txn = (object)$_POST;

//echo '<pre>'; print_r($myPost); echo '</pre>';
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	
	$req .= "&$key=$value";
}
// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data

if(USE_SANDBOX == true) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	//$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}
// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.
//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);
$res = curl_exec($ch);

if (curl_errno($ch) != 0) // cURL error
{
	if(DEBUG == true) {	
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;
}else{
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
		}
		curl_close($ch);
}

// Inspect IPN validation result and act accordingly
// Split response headers and payload, a better way for strcmp
$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));
//error_log(date('[Y-m-d H:i e] '). "Verified IPN Before: $txn  ". PHP_EOL, 3, LOG_FILE);
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= "From: support@capacity.com"; //Initialise email from which emails will be sent

include('config/configuration.php');
$objPages = $load->includeclasses('userlist');
$load->includeother('header_register');
global $mail;

if(strcmp ($res, "VERIFIED") == 0){

	// check whether the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment and mark item as paid.
	// assign posted variables to local variables
	//$item_name = $_POST['item_name'];
	//$item_number = $_POST['item_number'];
	//$payment_status = $_POST['payment_status'];
	//$payment_amount = $_POST['mc_gross'];
	//$payment_currency = $_POST['mc_currency'];
	//$txn_id = $_POST['txn_id'];
	//$receiver_email = $_POST['receiver_email'];
	//$payer_email = $_POST['payer_email'];
	//payment_status=Completed
	//payment_status=Pending
	


	//subscr_signup - a subscriber has signed up for the service
	//subscr_payment - a subscriber has paid for the service
	//subscr_failed - a subscriber tried to pay for the service but things didn't work out
	//subscr_cancelled - a subscriber cancelled a subscription
	//subscr_eot - a subscriber has reached the end of the subscription term
	//subscr_modify - a subscriber profile has been modified

	$order_id = $_POST["custom"];
	$user = $dclass->select("user_id,sub_plan_id",'tbl_user_subscrib_detail'," AND id = ".$order_id);
	$userdetail = $dclass->select("*",'tbl_user'," AND user_id = ".$user[0]['user_id']);
	
    if($txn->payment_status == 'Pending' || $txn->payment_status == 'Completed'){
		$paymentstatus = 'Completed';
	}else{
		$paymentstatus = $txn->payment_status;
	}

    if($txn->txn_type == 'subscr_signup' || $txn->txn_type == 'subscr_payment'){

		//if($_POST['payment_status'] == 'Completed'){
		    $plan_detail = $dclass->select('sub_duration,sub_duration_type',"tbl_subscription_plan"," AND sub_id = '".$user[0]['sub_plan_id']."' ");
			$expiredate = date('Y-m-d', strtotime("+".$plan_detail[0]['sub_duration']." ".$plan_detail[0]['sub_duration_type'].""));
			$noncurr = array('current'=> "0");
			$res = $dclass->update('tbl_user_subscrib_detail',$noncurr," user_id = '".$user[0]['user_id']."'");

		    $ins = array(
		      'payment_status' => 'Completed',
		      'paypal_subscr_id' => $txn->subscr_id,
		      'expire_date'=> $expiredate,
		      'current'=> "1"
		    );

		    $res = $dclass->update('tbl_user_subscrib_detail',$ins," id = '".$order_id."'");
		    
		    $us = array(
		        'user_status' => 'active',
		    );
		    $dclass->update('tbl_user',$us," user_id = '".$user[0]['user_id']."'");

		    $dclass->delete('tbl_extra_subscription'," subsrcibe_id = '".$order_id."'");
		    if($txn->txn_type == 'subscr_signup'){
	            $email_template = $dclass->select('*','tbl_email_template',' AND email_title = "User Confirmation"');
				$link = '<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'">Here</a>';
				
				$message = str_replace('{EMAIL}',$userdetail[0]['email'],$email_template[0]['email_body']);
				$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
	        	$message = str_replace('{LOGO}',$logo,$message);

				$message = str_replace('{PASSWORD}',base64_decode($userdetail[0]['password']),$message);
				$message = str_replace('{NAME}',$userdetail[0]['fname'].' '.$userdetail[0]['lname'],$message);
				$message = str_replace('{LINK}',$link,$message);
	            
				$mail->Subject = $email_template[0]['email_subject'];
			 	$mail->addAddress($userdetail[0]['email']);
			 	$mail->addAddress('aspanchl86@gmail.com');
			 	$mail->msgHTML($message);
				$mail->send();
			}

			//mail('aspanchal86@gmail.com',$email_template[0]['email_subject'],$message, $headers);	
		//}
	
	}else if($txn->txn_type == 'subscr_failed'){


			/*
			$ins = array(
		      'payment_status' => $paymentstatus,
		      'paypal_subscr_id' => $_POST['subscr_id'],
		    );

		    $rese = $dclass->update('tbl_user_subscrib_detail',$ins," id = '".$txn->custom."'");
		    $expiredate = date('Y-m-d', strtotime("+5 day"));
		    $exus = array(
		        'subsrcibe_id' => $txn->custom,
		        'ex_date' => $expiredate,
			);
		    $dclass->insert('tbl_extra_subscription',$exus);

	        $email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Error Processing Payment"');
			
			$message = str_replace('{NAME}',$userdetail[0]['fname'].' '.$userdetail[0]['lname'],$email_template[0]['email_body']);
			$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
        	$message = str_replace('{LOGO}',$logo,$message);
			$mail->Subject  =   $email_template[0]['email_subject'];
		 	$mail->addAddress($userdetail[0]['email']);
		 	$mail->msgHTML($message);
			$mail->send();*/
			//mail($userdetail[0]['email'],$email_template[0]['email_subject'],$message, $headers);
	
	
	}else if($txn->txn_type == 'subscr_eot'){
		   /* $expiredate = date('Y-m-d', strtotime("+5 day"));
		    $exus = array(
		        'subsrcibe_id' => $order_id,
		        'ex_date' => $expiredate,
			);
		    $dclass->insert('tbl_extra_subscription',$exus);*/
	}else if($txn->txn_type == 'subscr_cancel'){
		   $us = array(
		        'user_status' => 'inactive',
		    );
		    $dclass->update('tbl_user',$us," user_id = '".$user[0]['user_id']."'");
	}

	$ins = array(
		"str" => 'Verified->'.$txn->custom,
		"datetime" => date("Y-m-d H:i:s"),
	);
	$dclass->insert('tbl_ipn',$ins);

	error_log(date('[Y-m-d H:i e] '). " Verified IPN: $req  ". PHP_EOL, 3, LOG_FILE);
	
	
}else if(strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	// Add business logic here which deals with invalid IPN messages
	$ins = array(
		"str" => 'invalid'.$txn->custom,
		"datetime" => date("Y-m-d H:i:s"),
	);
	$id = $dclass->insert('tbl_ipn',$ins);
	error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	
}
?>