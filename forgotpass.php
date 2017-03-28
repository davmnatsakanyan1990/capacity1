<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('user');	
$objLogin = $load->includeclasses('login');
$load->includeother('header');
$flag = 'false';



$Fbcntnt = $dclass->select('l_values','tbl_setting','AND v_name="FB_CONTENT"');
				
				


if(isset($_POST['submit']) && $_POST['submit'] == 'SEND MY DETAILS!'){
	
	
if($_POST['email']  !=  ''  ){
				$userData=$dclass->select('*','tbl_user','AND user_email ="'.$_POST['email'].'"');
				if(count($userData) > 0){
					

				$email_template = $dclass->select('*','tbl_email_template',' AND email_template_title = "Forgotpass"');
				$message  = str_replace(':::NAME:::',ucfirst($userData[0]['user_first_name']).' ' .ucfirst($userData[0]['user_last_name']),$email_template[0]['email_template_desc']);
				
				$message = str_replace(':::LINK:::','<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'changePassword.php?eid='.base64_encode(base64_encode($_POST['email'])).'">'.SITE_URL.'changePassword.php?eid='.base64_encode(base64_encode($_POST['email'])).'</a>',$message);
			
			    $ip =  $_SERVER['REMOTE_ADDR']; 
				$message = str_replace(':::IPADDRS:::',$ip,$message);


					

				$upd_array = array("user_password_change"=>"1",);
				$dclass->update("tbl_user",$upd_array, "user_email = '". $userData[0]['user_email']."'");

					
				$subject  =   "Password Detail From Online City Guide Pvt Ltd.";
			 	$adminEMail = $dclass->select('l_values','tbl_setting','AND v_name="ADMIN_EMAIL"');
				
				$adminMail  =  $adminEMail[0]['l_values'];		   		
				$headers = "From: " . strip_tags($adminMail) . "\r\n";
				$headers .= "To: ". $userData[0]['user_email'] . "\r\n";
				//$headers .= "To: kartikgondalia@gmail.com \r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";	
				mail($adminMail,$subject,$message,$headers);
				
				//$objLogin->redirect('/index.php?msg=passsent');
				$_REQUEST['msg']='passsent';
				$flag = 'true';
				}else{
					
					//$objLogin->redirect('/index.php?msg=emailnotexist');
					$_REQUEST['msg']='emailnotexist';
				}
			
			}else{
				
			$_REQUEST['msg']='required';
		}
}?>

<?php

require 'facebook-php-sdk-master/src/facebook.php';

$config = array(
    'appId' => '1398099263775886',
    'secret' => '50d3d80ea08916b70adcf948098074ae',
    'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
  );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
//print_r($user);

if ($user_id) {  

	$user_profile = $facebook->api('/me','GET');
	
	$fbid = $user_profile['id'];  
	//$user_location = $facebook->api('/'.$fbid.'?fields=location');
	//$location = $user_location['location']['name'];
	//print_r($user_profile);
	//echo "HI::".$user_profile['location']['name'];
	$userData = $dclass->select('*','tbl_user',' AND user_email="'.$user_profile['email'].'"');
	if(count($userData) == 0 ){
		if($user_profile['birthday'] != ""){
			$birth_date_arr = explode("/",$user_profile['birthday']);
			$birth_date = $birth_date_arr[2]."-".$birth_date_arr[0]."-".$birth_date_arr[1];
		}else{
			$birth_date = NULL;
		}
		if($user_profile['location']['name'] != ""){
			$location_one = $user_profile['location']['name'];
			$location_arr = explode(", ", $location_one);
			$countryrData = $dclass->select('*','tbl_country',' AND country_name LIKE "'.trim($location_arr[1]).'"');
		}
		
		
		$CityData = $dclass->select('*','tbl_city',' AND city_name LIKE "'.trim($location_arr[0]).'"');
		if(count($CityData) == 0)
		{
			$ctins = array(
							"city_name" => $location_arr[0],
							"country_id" => $countryrData[0]['country_id'],
							"city_diaplay_front" => 'no',
							"city_status" => 'active'
							);
						$ctyId =  $dclass->insert('tbl_city',$ctins);
		}
		else
		{
				$ctyId = $CityData[0]['city_id'];
			
		}
		
		$ins = array(
	
							"user_email" => $user_profile['email'],
							"username" => $user_profile['username'],
							"user_password" => '',											
							"user_first_name" => $user_profile['first_name'],
							"user_gender" => ucfirst($user_profile['gender']),
							"user_last_name" => $user_profile['last_name'],
							"user_status" => 'active',
							"user_occupation"=>'',
							"user_city_id" => $ctyId,
							"user_country_id" => $countryrData[0]['country_id'],
							"user_state" => '',
							"user_description"=>$user_profile['user_about_me'],
							"user_dob" => $birth_date,
							"user_created_date"=>date('Y-m-d h:i:s'),
							"user_type"=>'user',
							);
				//print_r($ins);die();
				$id = $dclass->insert('tbl_user',$ins);
				$_SESSION['user_id'] = $id;
				$_SESSION['login_with'] = 'facebook';
			
			if($_REQUEST['redirect'] != ''){
				$objLogin->redirect($_REQUEST['redirect']);
			}else{
				$objLogin->redirect("index.php");
			}
	}else{
		$_SESSION['user_id'] = $userData[0]['user_id'];
			if($_SESSION['login_with'] == 'system'){
				if($_REQUEST['redirect'] != ''){
					$objLogin->redirect($_REQUEST['redirect']);
				}else{
					$objLogin->redirect("index.php");
				}
				
			}else{
				$objLogin->redirect("index.php");
			}
	}
	?>
      
    <?php } ?>



<style type="text/css">
label{
    font-size: 14px;
    font-weight: bold;
    line-height: 20px;
}
</style>

<div class="pageheading"> 
<div class="container">
<div class="row">
<div class="span12">Forgot Password</div>
</div>
</div>
</div>

<div class="container">
<div class="content">



<div class="row">
<div class="span4 ">
<div id="forgot_panel">
			<div class="inner-container forgot-panel">
            <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != ''){ ?>
                    <div class="alert alert-block alert-error fade in">
                        <p><?php echo $mess[$_REQUEST['msg']]; ?></p>
                     </div>
            <?php } ?>
            
				<h3 class="m_title">FORGOT YOUR DETAILS?</h3>
				<form id="forgot_form" name="forgot_form" method="post">
					<div class="control-group">
    <label class="control-label valiclrBold" for="inputEmail">Email</label>
    <div class="controls">
   <input type="text" id="forgot-email" name="email" class="inputbox validate[required,custom[email]]" placeholder="Email Address">
    <br/>
    </div>
    </div>
          <div class="control-group">
    <div class="controls">
     <input type="submit" class="btn btn-danger" id="recover" name="submit" value="SEND MY DETAILS!">
    </div>
    </div>
				</form>
				<div class="links"><a href="<?php echo SITE_URL ?>login.php" >AAH, WAIT, I REMEMBER NOW!</a></div>
			</div>
		</div>
 </div>       
<div class="span4">
  <?php
	if($user_id && $_SESSION['user_id'] != "") {

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {

        $user_profile = $facebook->api('/me','GET');
        //echo "Name: " . $user_profile['name'];

      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
		$par['scope'] = "user_birthday,user_about_me,user_status,user_location,email,user_hometown";
        $login_url = $facebook->getLoginUrl($par); 
        echo '<a href="' . $login_url . '"><img src="images/fbsignin.png"></a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
    } else {

      // No user, print a link for the user to login
	  $par['scope'] = "user_birthday,user_about_me,user_status,user_location,email,user_hometown";
      $login_url = $facebook->getLoginUrl($par);
      echo '<a href="' . $login_url . '"><img src="images/fbsignin.png"></a>';

    } 
	?>

  <br/>
  <br/>
  <br/>
  <p><?php echo $Fbcntnt[0]['l_values'];?></p>
<p>capacity.? <span class="reghere"><a class="regcolr" href="<?php echo SITE_URL; ?>register.php">Register Now!</a></span></p>
</div>
<!-------------------side-bar----------------->
<?php $load->includeother('index_right_sidebar');?>
<!-------------------side-bar-end---------------->
</div>
</div>
</div>
<?php $load->includeother('footer');?>
<script src="<?php echo SITE_URL; ?>admin/assets/scripts/jquery.validationEngine.js"></script>
<script src="<?php echo SITE_URL; ?>admin/assets/scripts/jquery.validationEngine-en.js"></script>  
<script>
	jQuery(document).ready(function($) {       
	   $("#forgot_form").validationEngine();
	});
</script>