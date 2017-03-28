<?php

// Configration 
  include("config/configuration.php");
  include("config/dbconfig.php");
  $load  =  new loader(); 
  $objPages = $load->includeclasses('userlist');
  $label = "userlist";
  $dclass   =  new  database();
  $gnrl =  new general;
  //$load->includeother('header');
global $mail;
  
$date = date('Y-m-d');

// Send project Reminder

$checkExpire = $dclass->select("t1.user_id,t1.email,t1.fname,t1.lname,t2.expire_date,t2.trial_expire_date","tbl_user t1 LEFT JOIN tbl_user_subscrib_detail t2 ON t1.user_id = t2.user_id"," AND (t2.expire_date <= '".$date."' OR t2.trial_expire_date <= '".$date."') AND t2.subscrib_type = 'free' AND t1.user_status = 'active' "); 

  foreach($checkExpire as $chk){
    //echo $chk['user_id'];
    $getChild = $dclass->select("*","tbl_user"," AND user_comp_id=".$chk['user_id']); 

      $us = array(
        'user_status' => 'inactive',
      );
      $dclass->update('tbl_user',$us," user_id = '".$chk['user_id']."'");
      
      foreach($getChild as $child){
        $us = array(
          'user_status' => 'inactive',
        );
        $dclass->update('tbl_user',$us," user_id = '".$child['user_id']."'");
      }
      
        $email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Free trial reminder"');
       
        $ilnk = '<a  href="'.SITE_URL.'subscriptions/'.base64_encode($chk['user_id']).'">Click here to subscribe</a>';
        $name = ucfirst($chk['fname']).' '.ucfirst($chk['lname']);
        $message = str_replace('{NAME}',$name,$email_template[0]['email_body']);
        
        $logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
        $message = str_replace('{LOGO}',$logo,$message);

        $message = str_replace('{DATE}',$chk['expire_date'],$message);
        $message = str_replace('{SUBSCRIPTIONLINK}',$ilnk,$message);
        
        
        $to = $chk['email'];
        $mail->Subject  =   $email_template[0]['email_subject'];
        $mail->ClearAddresses();
        $mail->addAddress($to);
        //$mail->addAddress('aspanchal86@gmail.com');
        $mail->msgHTML($message);
        $mail->send();
      
     // echo  $ilnk; echo '<br>'; 

  }


$checkErrorpr = $dclass->select("*","tbl_extra_subscription"," AND ex_date = '".$date."'");
foreach($checkErrorpr as $ck){
  $user = $dclass->select("user_id",'tbl_user_subscrib_detail'," AND id = ".$order_id);
  $userdetail = $dclass->select("*",'tbl_user'," AND id = ".$user[0]['user_id']);
       $us = array(
            'user_status' => 'inactive',
        );
        $dclass->update('tbl_user',$us," user_id = '".$user[0]['user_id']."'");

        $email_template = parent::select('*','tbl_email_template',' AND email_title = "Account on hold"');
        $link = '<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'">Here</a>';
        
        $name = ucfirst($userdetail[0]['fname']).' '.ucfirst($userdetail[0]['lname']);
        $message = str_replace('{NAME}',$name,$email_template[0]['email_body']);
        
        $logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
        $message = str_replace('{LOGO}',$logo,$message);

        $mail->Subject  =   $email_template[0]['email_subject'];
        $mail->addAddress($userdetail[0]['email']);
       // $mail->addAddress('aspanchl86@gmail.com');
        $mail->msgHTML($message);
        $mail->send();
} 

//echo "<script>setTimeout('self.close();',60000);</script>";
?>