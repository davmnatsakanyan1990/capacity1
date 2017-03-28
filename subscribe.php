<?php 
include('config/configuration.php');
//echo  "get it"; die();
$objPages = $load->includeclasses('userlist');

$flag = 'false';
global $mail;
//include('paypal.class.php'); 

/*if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != '' ){
    $objPages->redirect("view");
}*/

  try {

    if (isset($_POST['stripeToken']) && $_POST['stripeToken'] != ''){
        if(isset($_POST['script']) && $_POST['script'] == 'add'){
         $condition = " AND  t1.email='".$_POST['email']."' AND t2.subscrib_type='paid' ";
          $id = $dclass->select('t1.user_id,t2.subscrib_type'," tbl_user t1 LEFT JOIN tbl_user_subscrib_detail t2 ON t1.user_id = t2.user_id",$condition);
          $numRows =  count($id);
          
          if($numRows > 0 ){
            $_SESSION['msg'] ='notavailable';
            $_SESSION['msg-type']='alert-danger';
            throw new Exception("That e-mail address already exists");
          }else{
                    
                  $condition = " AND  t1.email='".$_POST['email']."' AND t2.subscrib_type='free' ";
                  $chkfreeuser = $dclass->select('t1.user_id,t2.subscrib_type',"tbl_user t1 LEFT JOIN tbl_user_subscrib_detail t2 ON t1.user_id = t2.user_id",$condition);

                  if(count($chkfreeuser) > 0){
                    $id = $chkfreeuser[0]['user_id'];
                    $uname = explode(' ',$_POST['fullname']);
                    $ins = array(
                          "password" => base64_encode($_POST['password']),
                          "fname" => $uname[0],
                          "lname" => $uname[1],
                          "job_title" => $_POST['job_title'],
                          "user_status" => 'inactive',
                          "register_date" => date("Y-m-d H:i:s"),
                      );
                    $dclass->update('tbl_user',$ins," user_id = '".$id."'");
                    
                    $dclass->delete('tbl_user_subscrib_detail'," user_id = '".$id."'");

                    if($_FILES['user_avatar']['name'] != '')
                    {
                        //$ext = end(explode('.',$_FILES['user_avatar']['name']));
                        $filename=$id.'_'.$_FILES['user_avatar']['name'];
                        move_uploaded_file($_FILES['user_avatar']["tmp_name"],MAIN_DIR.UPLOAD.'/user/'.$filename);
                        $insimage = array('user_avatar'=>$filename);            
                        $dclass->update('tbl_user',$insimage," user_id = '".$id."'");

                    }
                  }else{
                    // code for insert user table
                    $uname = explode(' ',$_POST['fullname']);
                    $ins = array(
                          "username" => $_POST['email'],
                          "password" => base64_encode($_POST['password']),
                          "fname" => $uname[0],
                          "lname" => $uname[1],
                          "company_name"=>$_POST['company_name'],
                          "user_avatar" => '',
                          "user_dob" => '',
                          "city"=>'',
                          "state"=>'',
                          "address"=>'',
                          "job_title" => $_POST['job_title'],
                          "user_type" => 'company_user',
                          "r_id" => '2',
                          "email" => $_POST['email'],
                          "user_status" => 'inactive',
                          "user_comp_id" => '',
                          "register_date" => date("Y-m-d H:i:s")
                      );
                    $id = $dclass->insert('tbl_user',$ins);
                   
                    // code for insert user avatar
                    if($_FILES['user_avatar']['name'] != '')
                    {
                        //$ext = end(explode('.',$_FILES['user_avatar']['name']));
                        $filename=$id.'_'.$_FILES['user_avatar']['name'];
                        move_uploaded_file($_FILES['user_avatar']["tmp_name"],MAIN_DIR.UPLOAD.'/user/'.$filename);
                        $insimage = array('user_avatar'=>$filename);            
                        $dclass->update('tbl_user',$insimage," user_id = '".$id."'");

                    }

                    // code for default team
                    if(isset($_POST['department']) && $_POST['department'] != ''){
                    $teamArr = array(
                        'tm_title'=>$_POST['department'],
                        'company_user_id'=>$id,
                        'tm_status' =>'active'
                        );
                    $team_id = $dclass->insert('tbl_team',$teamArr);
                    $teamArr = array(
                        'tm_id'=>$team_id,
                        'user_id'=>$id,
                        );
                    $dclass->insert('tbl_team_detail',$teamArr);
                  }
                    // code for default working day time
                    $defaulttime = $dclass->select("*","tbl_working_day_time"," AND type = 'general'");
                    $timearr = array(
                      'company_user_id'=>$id,
                      'team_id'=>'',
                      'working_time'=>$defaulttime[0]['working_time'],
                      'working_days'=> $defaulttime[0]['working_days'],
                      'type'=>'perticular',
                    );
                    $dclass->insert("tbl_working_day_time",$timearr);

                    // code for default lunch hours
                    $defaultlunch = $dclass->select("*","tbl_lunch_hours"," AND type = 'general'");
                    $luncharr = array(
                      'company_user_id'=>$id,
                      'team_id'=>$team_id,
                      'lunch_hours'=>$defaultlunch[0]['lunch_hours'],
                      'show_in_calender'=> $defaultlunch[0]['show_in_calender'],
                      'type'=>'perticular',
                    );
                    $dclass->insert("tbl_lunch_hours",$luncharr);

                    //code for set default permission
                    $defaultpermission = $dclass->select("*","tbl_role_access"," AND company_user_id = '0'");
                    foreach($defaultpermission as $per){
                      $perarr = array(
                        'company_user_id'=>$id,
                        'r_id'=>$per['r_id'],
                        'v_name'=>$per['v_name'],
                        'l_value'=> $per['l_value'],
                      );
                      $dclass->insert("tbl_role_access",$perarr);
                    }

                  }

          
          $plan_detail = $dclass->select('*',"tbl_subscription_plan"," AND sub_id = '".$_POST['sub_id']."' ");
          $expiredate = date('Y-m-d', strtotime("+".$plan_detail[0]['sub_duration']." ".$plan_detail[0]['sub_duration_type'].""));
          $subArr = array(
              'user_id'=>$id,
              'sub_plan_id'=>$plan_detail[0]['sub_id'],
              'subscrib_date'=>date("Y-m-d"),
              'subscrib_price'=>$plan_detail[0]['sub_price'],
              'subscrib_type'=>'paid',
              'expire_date'=> $expiredate,
              'trial_expire_date'=>$expiredate,
              'payment_status'=>'pending',
            );
          $subscribe_id = $dclass->insert('tbl_user_subscrib_detail',$subArr);

          $subtitle = $plan_detail[0]['sub_title'];
          $amt = $plan_detail[0]['sub_price'];
          $period = $plan_detail[0]['sub_duration'];
          $period_type = $plan_detail[0]['sub_duration_type'];
          $pt = array(
              'day'=>'D',
              'week'=>'W',
              'month'=>'M',
              'year'=>'Y'
              );
            
            $params = array(
            "source"    => $_POST['stripeToken'], // obtained from Stripe.js
            "plan"      => $plan_detail[0]['plan_strip_id'],
            "email"     => $_POST['email'],
            "description" => $subscribe_id // company email
            );
 
            $error = "";
            $customer_id = '';


            $customer = \Stripe\Customer::create($params);
           
            $subscriptions_id = $customer->subscriptions->data[0]->id;
            $updt = array("paypal_subscr_id" => $customer->id,"stripe_subscription_id"=>$subscriptions_id);

            $dclass->update('tbl_user_subscrib_detail',$updt," id = '".$subscribe_id."'");
           
            $objPages->redirect(SITE_URL.'thanks');

          }
        }else if(isset($_POST['script']) && $_POST['script'] == 'edit'){
          $plan_detail = $dclass->select('*',"tbl_subscription_plan"," AND sub_id = '".$_REQUEST['sub_id']."' ");
          $expiredate = date('Y-m-d', strtotime("+".$plan_detail[0]['sub_duration']." ".$plan_detail[0]['sub_duration_type'].""));
            
          if(isset($_POST['u_id']) && $_POST['u_id'] != ''){
            $uid = $_POST['u_id'];
          }else{
            $uid = $_SESSION['company_id'];
          }
          $subscription_detail = $dclass->select('*',"tbl_user_subscrib_detail"," AND user_id = '".$uid."' ");
          
          $subArr = array(
              'sub_plan_id'=>$plan_detail[0]['sub_id'],
              'subscrib_date'=>date("Y-m-d"),
              'subscrib_price'=>$plan_detail[0]['sub_price'],
              'subscrib_type'=>'paid',
              'expire_date'=> $expiredate,
              'trial_expire_date'=>$expiredate,
              'payment_status'=>'pending',
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
           $customer_id = '';
           
           $params = array(
            "source"    => $_POST['stripeToken'], // obtained from Stripe.js
            "plan"      => $plan_detail[0]['plan_strip_id'],
            "email"     => $_POST['email'],
            "description" => $subscription_detail[0]['id'] // company email
            );

            $customer = \Stripe\Customer::create($params);
            $subscriptions_id = $customer->subscriptions->data[0]->id;
            
            $updt = array("paypal_subscr_id" => $customer->id,"stripe_subscription_id"=>$subscriptions_id);
            $dclass->update('tbl_user_subscrib_detail',$updt," id = '".$subscription_detail[0]['id']."'");


            //$objPages->redirect(SITE_URL.'setting');
            $_SESSION['msg'] = 'updatesubscription';
            $objPages->redirect(SITE_URL.'plan?upgrade=true');
        }
    }
    else {
      throw new Exception("The Stripe Token or customer was not generated correctly");
    }
  }
  catch (Exception $e) {
    $error = $e->getMessage();
  }

if(isset($_REQUEST['script']) && $_REQUEST['script'] == 'upgrade1123'){


          $plan_detail = $dclass->select('*',"tbl_subscription_plan"," AND sub_id = '".$_REQUEST['sub_id']."' ");
          $expiredate = date('Y-m-d', strtotime("+".$plan_detail[0]['sub_duration']." ".$plan_detail[0]['sub_duration_type'].""));
            
          if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
            $uid = base64_decode($_REQUEST['uid']);
          }else{
            $uid = $_SESSION['company_id'];
          }
          $subscription_detail = $dclass->select('*',"tbl_user_subscrib_detail"," AND user_id = '".$uid."' ");
          
          $subArr = array(
              'sub_plan_id'=>$plan_detail[0]['sub_id'],
              'subscrib_date'=>date("Y-m-d"),
              'subscrib_price'=>$plan_detail[0]['sub_price'],
              'subscrib_type'=>'paid',
              'expire_date'=> $expiredate,
              'trial_expire_date'=>$expiredate,
              'payment_status'=>'pending',
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
           $customer_id = '';
           
           $params = array(
            "source"    => $_POST['stripeToken'], // obtained from Stripe.js
            "plan"      => $plan_detail[0]['plan_strip_id'],
            "email"     => $_POST['email'],
            "description" => $subscribe_id // company email
            );

            $customer = \Stripe\Customer::create($params);
            $updt = array("paypal_subscr_id" => $customer->id);
            $dclass->update('tbl_user_subscrib_detail',$updt," id = '".$subscription_detail[0]['id']."'");
            

            $objPages->redirect(SITE_URL.'setting');

         /* $subscription = \Stripe\Subscription::retrieve($subscription_detail[0]['stripe_subscription_id']);
          $subscription->plan = $plan_detail[0]['sub_title'];
          $subscription->save();*/
  
 die();
}

$load->includeother('header_register');



?>          
<div class="row profile welcome">
    <div class="container">
      
      <div class="col-sm-12 title">Sign up and subscribe</div>
     <?php if(isset($_REQUEST['sub_id']) && $_REQUEST['sub_id'] != ''){ 
      $getPlandetail = $dclass->select("*","tbl_subscription_plan"," AND sub_id=".$_REQUEST['sub_id']);
      ?>
      <div class="col-sm-12 info"><?php echo $getPlandetail[0]['sub_title'] ?> subscription - US $ <?php echo $getPlandetail[0]['sub_price'] ?> a <?php echo $getPlandetail[0]['sub_duration_type'] ?>.</div>
      <p class="mb20">Thanks for choosing Capacity. You're on your way to starting your new monthly subscription. Just fill out the form below so we can create your profile and you'll be directed to the Stripe payment page. Once your payment is processed you'll have full access to Capacity to get started! There will be an automated tour of the program to show you the ropes when you first log in, and plenty of tool tips to help you out if you need them. It's great to have you with us, welcome aboard.</p>

      <?php } ?>
   <?php  
        if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
            $userdetail = $dclass->select("email,user_comp_id","tbl_user"," AND user_id= '".base64_decode($_REQUEST['uid'])."' ");
            $compdetail = $dclass->select("company_name","tbl_user"," AND user_id= '".base64_decode($_REQUEST['uid'])."' ");
            $uemail = $userdetail[0]['email'];
            $readonly = 'readonly="readonly"';
            $company_name = $compdetail[0]['company_name'];
        }else if(isset($_SESSION['company_id']) && $_SESSION['company_id'] != ''){
            $userdetail = $dclass->select("email,user_comp_id","tbl_user"," AND user_id= '".$_SESSION['company_id']."' ");
            $compdetail = $dclass->select("company_name","tbl_user"," AND user_id= '".$_SESSION['company_id']."' ");
            $uemail = $userdetail[0]['email'];
            $readonly = 'readonly="readonly"';
            $company_name = $compdetail[0]['company_name'];
        }else{ 
          $uemail = $_POST['email'];
          $readonly = '';
          $company_name = $_POST['company_name'];
          ?>
      <?php }


       ?>

     <form name="register_form" id="register_form" method="post" action="" enctype="multipart/form-data">

      <div class="col-sm-12 col-xs-12">
      
      <div class="col-sm-5 col-xs-3 label"><label>Email*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="email" id="email" value="<?php echo $uemail; ?>" <?php echo  $readonly; ?> class="validate[required,custom[email]]" /></div>
      <?php if(!isset($_REQUEST['uid'])){ ?>
      <div class="col-sm-5 col-xs-3 label"><label>Password*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="password" name="password" id="password" value="" class="validate[required]" /></div>
      
      <div class="col-sm-5 col-xs-3 label"><label>Name*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="fullname" id="fullname" value="<?php echo $_POST['fullname']; ?>" class="validate[required]" /></div>
      <div class="col-sm-5 col-xs-3 label"><label>Job Title*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="job_title" id="job_title" value="<?php echo $_POST['job_title']; ?>" class="validate[required]"  /></div>
      <?php } ?>
      <div class="col-sm-5 col-xs-3 label"><label>Company Name*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" <?php echo  $readonly; ?> name="company_name" id="company_name" value="<?php echo $company_name; ?>" class="validate[required]" /></div>
       <?php if(!isset($_REQUEST['uid'])){ ?>
      <div class="col-sm-5 col-xs-3 label"><label>Team*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="department" id="department" value="<?php echo $_POST['department']; ?>" class="validate[required]" /></div>
      <div class="col-sm-12 col-xs-12"><hr class="col-sm-5 col-xs-7"></hr></div>
      
      <div class="col-sm-5 col-xs-4 pic">
      <div id="imagePreview" class="img-circle"></div>
      <!--img class="img-circle" id="blah" src="images/prof-pic.png"  /-->
      </div>
      <div class="col-sm-7 col-xs-8 upload"><div class="upload1"><input type="file" name="user_avatar" id="imgInp" value="upload a photo +"></div></div>
      <?php } ?>
      <div class="col-sm-12 col-xs-12 action">
      
      <?php 
        if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
          $script = 'edit';
        }else{
          $script = 'add';
        }
      ?>
      <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                  data-key="<?php echo $stripe['publishable_key']; ?>"
                                  data-description="<?php echo $getPlandetail[0]['sub_title'] ?>"
                                  data-amount="<?php echo $getPlandetail[0]['sub_price'] ?>00"
                                  data-locale="auto"></script>

      <input type="hidden" name="sub_id" value="<?php echo $_REQUEST['sub_id']; ?>">
    <?php if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){ ?>
      <input type="hidden" name="u_id" value="<?php echo base64_decode($_REQUEST['uid']); ?>">    
    <?php } ?>
      
      <input type="hidden" name="script" value="<?php echo $script; ?>"> 
      <input type="button" id="register_btn" name="register" class="btn-success" value="Sign up for $<?php echo $getPlandetail[0]['sub_price'] ?> a <?php echo $getPlandetail[0]['sub_duration_type'] ?>"/></div>
      
      </div>
      <!--div class="col-sm-12 col-xs-12 center mt20"><img src="<?php echo SITE_URL; ?>/images/secure-payment-by-paypal2.jpg" width="13%"></div-->
      </form>
      
    </div>
  </div>
  


  
</div>
<style type="text/css">
  .alert-success{float: left;width: 100%}
  .center{text-align: center;}
  .mb20{margin-bottom: 20px;}
  .mt20{margin-top: 20px;}
  
  #imagePreview {
    width: 62px;
    height: 62px;
    background-position: center center;
    background-size: cover;
    -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
    display: inline-block;
    background-image: url('../images/prof-pic.png');
}
.stripe-button-el{display:none;}
</style>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<script>
   $(function() {
    $("#imgInp").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
        
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
            
            reader.onloadend = function(){ // set image data as background of div
                $("#imagePreview").css("background-image", "url("+this.result+")");
            }
        }else{
          alert("Please upload valid Image type.");
                return false;
        }
    });
    
    $("body").on("click","#register_btn",function(e){
        var validate =  $("#register_form").validationEngine('validate');
        if(validate){
          $(".stripe-button-el").trigger("click");
        }
    });
});



   $(document).ready(function(){
      $("#register_form").validationEngine({validateNonVisibleFields: true});
   });
</script>

</body>
</body>
</html>
