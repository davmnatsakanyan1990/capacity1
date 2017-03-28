<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('userlist');
$objPagesLogin = $load->includeclasses('login');
$load->includeother('header_register');
$flag = 'false';
global $mail;
include('paypal.class.php'); 

$p = new paypal_class;             // initiate an instance of the class
  $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url
    $ipnarray = array();
  
//  echo '<pre>'; print_r($_REQUEST); echo '</pre>';

  if (isset($_POST['subscr_id']) && $_POST['subscr_id'] != '')  
  {
     // Payment has been recieved and IPN is verified.  This is where you
     // update your database to activate or process the order, or setup
     // the database with the user's order details, email an administrator,
     // etc.  You can access a slew of information via the ipn_data() array.
  
     // Check the paypal documentation for specifics on what information
     // is available in the IPN POST variables.  Basically, all the POST vars
     // which paypal sends, which we send back for validation, are now stored
     // in the ipn_data() array.
  
     // For this example, we'll just email ourselves ALL the data.
  
    $payment_status = 'complete';
    $order_id = $_POST["custom"];
    $ins = array(
      'payment_status' => $payment_status,
      'paypal_subscr_id' => $_POST['subscr_id'],
    );

    $res = $dclass->update('tbl_user_subscrib_detail',$ins," id = '".$order_id."'");
    $user = $dclass->select("user_id",'tbl_user_subscrib_detail'," AND id = ".$order_id);
    $us = array(
        'user_status' => 'active',
    );

    $dclass->update('tbl_user',$us," user_id = '".$user[0]['user_id']."'");

   /* $_SESSION['temp_user'] = $user[0]['user_id'];
    $objPagesLogin->LoginUserAfterpayment();
    $_SESSION['msg'] = 'payment_success';
    $_SESSION['msg-type']='alert-success';
    header("Location:".SITE_URL."userlist");   */      
   
  }
      
    
    
    
    
    //send_confirm_mail(); // to user
    //send_mail_admin($status_update); // to admin
  ?>