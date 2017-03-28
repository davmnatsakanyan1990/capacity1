<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('userlist');
$load->includeother('header_register');
$flag = 'false';
global $mail;

if(isset($_REQUEST['script']) && $_REQUEST['script'] == 'upgrade'){
  
        $plan_detail = $dclass->select('*',"tbl_subscription_plan"," AND sub_id = '".$_REQUEST['sub_id']."' ");

          $expiredate = date('Y-m-d', strtotime("+".$plan_detail[0]['sub_duration']." ".$plan_detail[0]['sub_duration_type'].""));
            
          if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
            $uid = base64_decode($_REQUEST['uid']);
          }else{
            $uid = $_SESSION['company_id'];
          }
          $subscription_detail = $dclass->select('*',"tbl_user_subscrib_detail"," AND user_id = '".$uid."' ");
          if($subscription_detail[0]['stripe_subscription_id'] != ''){
            $us = array('user_status' => 'active',);
            $dclass->update('tbl_user',$us," user_id = '".$uid."'");

            $subArr = array(
                'sub_plan_id'=>$plan_detail[0]['sub_id'],
                'subscrib_date'=>date("Y-m-d"),
                'subscrib_price'=>$plan_detail[0]['sub_price'],
                'subscrib_type'=>'paid',
                'expire_date'=> $expiredate,
                'trial_expire_date'=>$expiredate,
                //'payment_status'=>'pending',
            );

            $dclass->update('tbl_user_subscrib_detail',$subArr," id = '".$subscription_detail[0]['id']."'");
          
          //$subscribe_id = $dclass->insert('tbl_user_subscrib_detail',$subArr);

            $amt = $plan_detail[0]['sub_price'];
            $subtitle = $plan_detail[0]['sub_title'];
            $period = $plan_detail[0]['sub_duration'];
            $period_type = $plan_detail[0]['sub_duration_type'];
            $pt = array(
                'day'=>'D',
                'week'=>'W',
                'month'=>'M',
                'year'=>'Y'
            );

            $userdetail = $dclass->select(' * ','tbl_user'," AND user_id=".$uid);
            //code ended for user insert
          
          $subscription = \Stripe\Subscription::retrieve($subscription_detail[0]['stripe_subscription_id']);
          $subscription->plan = $plan_detail[0]['plan_strip_id'];
          $subscription->save();
          
          $_SESSION['msg'] = 'updatesubscription';
          $objPages->redirect(SITE_URL.'setting?upgrade=true');
 die();
      }else{
        $objPages->redirect(SITE_URL.'subscriptions/'.base64_encode($_SESSION['user_id']));
      }
}


?>
  
</div>
</body>
</html>
