<?php
include('config/configuration.php');
define("LOG_FILE", "stripe.log");
$objPages = $load->includeclasses('userlist');
global $mail;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$postdata = @file_get_contents("php://input");
	$event = json_decode($postdata);
		$customer_id = $event->data->object->customer;
		$customer = \Stripe\Customer::retrieve($customer_id);
		$invoice = \Stripe\Invoice::retrieve($event->data->object->id);

		//$invoice = Stripe_Invoice::retrieve('in_19H67dHgZNSL6haWQizzbD2O');
		$order_id = $customer->description;
		$user = $dclass->select("user_id,sub_plan_id",'tbl_user_subscrib_detail'," AND id = ".$order_id);
		$userdetail = $dclass->select("*",'tbl_user'," AND user_id = ".$user[0]['user_id']);
		$plan_detail = $dclass->select('sub_duration,sub_duration_type',"tbl_subscription_plan"," AND sub_id = '".$user[0]['sub_plan_id']."' ");
	
	error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $event->type".PHP_EOL, 3, LOG_FILE);
	if ($event->type == 'invoice.payment_succeeded') {

		
		// This is where we'd normally e-mail the invoice, but we'll just write out the invoice to a file instead.
		
			$expiredate = date('Y-m-d', strtotime("+".$plan_detail[0]['sub_duration']." ".$plan_detail[0]['sub_duration_type'].""));
			$noncurr = array('current'=> "0");
			$res = $dclass->update('tbl_user_subscrib_detail',$noncurr," user_id = '".$user[0]['user_id']."'");

		    $ins = array(
		      'payment_status' => 'Completed',
		      'expire_date'=> $expiredate,
		      'current'=> "1"
		    );

		    $res = $dclass->update('tbl_user_subscrib_detail',$ins," id = '".$order_id."'");
		    
		    $us = array(
		        'user_status' => 'active',
		    );
		    $dclass->update('tbl_user',$us," user_id = '".$user[0]['user_id']."'");

		    $dclass->delete('tbl_extra_subscription'," subsrcibe_id = '".$order_id."'");

			$from = "From: Oscar Wilde";
			$to = "To: ".$customer->email;
			$subject = "Subject: You have made a payment for another month of Wilde quotes";
			$body = "You have made a new payment for $".($invoice->total / 100.0).":\n\n";
			$body .= "subscription_id =>".$customer->description.":\n\n";

			foreach($invoice->lines->data as &$line) {
				if ($line->type == 'subscription') {
					$body .= "Subscription - ".$line->plan->name.": ".$line->amount."\n";
			    }
			    else if ($line->type == 'invoiceitem') {
			    	$body .= "Additional -".$line->description.": ".$line->amount;
			    }
			}

			//$email_file = fopen($customer_id."tttt.txt", 'a');
			$email = $from."\n".$to."\n".$subject."\n".$body;
			//fwrite($email_file, $email);
			$mail->addAddress('ashish.panchal@zealousys.com');
	        $mail->Subject = 'stripe testing webhook';
	        $mail->msgHTML($email);
	        $mail->send();
	
	}else if($event->type == 'invoice.payment_failed'){

			$ins = array(
		      'payment_status' => 'Pending',
		    );
		    //$rese = $dclass->update('tbl_user_subscrib_detail',$ins," id = '".$order_id."'");
		    $expiredate = date('Y-m-d', strtotime("+5 day"));
		    $exus = array(
		        'subsrcibe_id' => $order_id,
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
			$mail->send();
			//mail($userdetail[0]['email'],$email_template[0]['email_subject'],$message, $headers);
	
	
	}else if($event->type == 'customer.deleted'){
		   $us = array(
		        'user_status' => 'inactive',
		    );
		    $dclass->update('tbl_user',$us," user_id = '".$user[0]['user_id']."'");
	}
}