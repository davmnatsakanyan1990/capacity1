<?php  include("../config/configuration.php"); $load  =  new loader();	
	$objLogin = $load->includeclasses('login');	
	$label =  'index';
	$dclass = new database();
	$gnrl  =  new general;
        global $mail;
//  Login  Process		
$syste_info =  $gnrl->getSettings();

if($_SESSION['adminid'] == ''){
		if(isset($_REQUEST['username']) && $_REQUEST['username']!=''){
			
			 $flagLogin  = $objLogin->LoginAdmin(); 
			
			if($flagLogin == '1'){
					$objLogin->redirect("home.php");				
			}else{		
				$_REQUEST['msg1'] =$flagLogin;	
			}	
	}else{
		$_REQUEST['msg1'] =$flagLogin;
	}
}else{
	$objLogin->redirect("home.php");				
}





if(isset($_POST['forgot_pass']) && $_POST['forgot_pass'] == 'yes' ){
	
if($_POST['email']  !=  ''  ){
				$userData=$dclass->select('*','tbl_user',' AND user_type = "super_admin" AND email ="'.$_POST['email'].'"');
				if(count($userData) > 0){
				
                                // generate random token 
                                $length = 10;
                                $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                                
                                // update tbl_user set token 
                                $dclass->update('tbl_user',array('user_token'=>$token),' email = "'.$_POST['email'].'"');
                                
				$email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Forgotpassword"');
				$message  = str_replace('{USERNAME}',ucfirst($userData[0]['fname']).' ' .ucfirst($userData[0]['lname']),$email_template[0]['email_body']);
				
                                $link = 'http://'.SITE_URL.'changePass.php?token='.$token.'&eid='.base64_encode(base64_encode($_POST['email']));
				//$message = str_replace('{LINK}','<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'changePass.php?eid='.base64_encode(base64_encode($_POST['email'])).'">'.SITE_URL.'changePass.php?eid='.base64_encode(base64_encode($_POST['email'])).'</a>',$message);exit;
                                $message = str_replace('{LINK}','<a  style="color: #0000FF;text-decoration: underline;" href="'.$link.'">Here</a>',$message);
                                $message = str_replace('{STATIC_LINK}',$link,$message);
			                          $logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
                                $message = str_replace('{LOGO}',$logo,$message); 
			   	/*$upd_array = array("user_password_change"=>"1",);
				$dclass->update("tbl_user",$upd_array, "user_email = '". $userData[0]['user_email']."'");*/
                                
                                
                                $mail->Subject  =   $email_template[0]['email_title'];
			 	$mail->addAddress($_POST['email']);
				$mail->msgHTML($message);
				$mail->send();
				
                                $_REQUEST['msg']='chkpasssemail';
				$flag = 'true';
				}else{
					
					$_REQUEST['msg']='emailnotexist';
				}
			
			}else{
				
			$_REQUEST['msg']='required';
		}

}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title> | Login Page</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2_metro.css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/css/pages/login.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo"><img src="../images/logo.png" alt=""  height="70px"/> </div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
  <!-- BEGIN LOGIN FORM -->
  <form class="form-vertical login-form" action="" method="post">
    <h3 class="form-title">Sign in to your account</h3>
   
   
   
    <?php 
	
	
	if(!isset($_REQUEST['msg1'])&& $_REQUEST['msg1']==''){
		if(isset($_REQUEST['msg'])&& $_REQUEST['msg']!=''){
			echo '<div class="alert alert-error">'.$mess[$_REQUEST['msg']].'</div>';}
	}
		
	if(isset($_REQUEST['msg1'])&& $_REQUEST['msg1']!=''){
	echo '<div class="alert alert-error">'.$mess[$_REQUEST['msg1']].'</div>';}
		
	?>
    
  
    <div class="control-group">
      <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
      <label class="control-label visible-ie8 visible-ie9">Username</label>
      <div class="controls">
        <div class="input-icon left"> <i class="icon-user"></i>
          <input class="m-wrap placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" id="username" name="username"/>
        </div>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label visible-ie8 visible-ie9">Password</label>
      <div class="controls">
        <div class="input-icon left"> <i class="icon-lock"></i>
          <input class="m-wrap placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" id="password" name="password"/>
        </div>
      </div>
    </div>
    <div class="form-actions">
      
      <label class="checkbox">
        <h5><!--input type="checkbox" name="remember" value="1"/-->
        
        <?php if($_COOKIE['intro_show']=='1'){
				$checkcon = "checked";
			}else{
				$checkcon = "";
				}?>
        
        <input type="checkbox" value="remember-me" <?php echo $checkcon;?>  id="remember_me">
        Remember me </h5></label>
        <input type="submit" class="btn green pull-right" value="Sign In" id="Login" name="Login">
        <i class="m-icon-swapright m-icon-white"></i> </button>
    </div>
    <div class="forget-password">
      <h5>Forgot your password ?</h5>
      <p> 
      Please click <a href="javascript:;"  id="forget-password">here</a> to reset your password
       </p>
    </div>
    
  </form>
  <!-- END LOGIN FORM -->
  <!-- BEGIN FORGOT PASSWORD FORM -->
  <form class="form-vertical forget-form" action="" method="post">
    <h3 >Forget Password ?</h3>
    <p>Enter your e-mail address below to reset your password.</p>
    <div class="control-group">
      <div class="controls">
        <div class="input-icon left"> <i class="icon-envelope"></i>
          <input class="m-wrap placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" />
          <input type="hidden" name="forgot_pass" value="yes" />
        </div>
      </div>
    </div>
    <div class="form-actions">
      <button type="button" id="back-btn" class="btn"> <i class="m-icon-swapleft"></i> Back </button>
      
        <input type="submit" class="btn green pull-right" id="Forgot" name="Forgot" value="Send" >
    </div>
  </form>
  <!-- END FORGOT PASSWORD FORM -->
  
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright"> <?php //echo date('Y');?> 2015 &copy; Capacity. &nbsp; <?php echo $syste_info; ?> </div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<script src="assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
<!--[if lt IE 9]>

	<script src="assets/plugins/excanvas.min.js"></script>

	<script src="assets/plugins/respond.min.js"></script>  

	<![endif]-->
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/scripts/app.js" type="text/javascript"></script>
<script src="assets/scripts/login.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>

		jQuery(document).ready(function() {     
		  App.init();
		  Login.init();
		});

	</script>
  
        <script>
            $(function() {
 
                if (localStorage.chkbx && localStorage.chkbx != '') {
                    $('#remember_me').attr('checked', 'checked');
                    $('#username').val(localStorage.usrname);
                    $('#password').val(localStorage.pass);
                } else {
                    $('#remember_me').removeAttr('checked');
                    $('#username').val('');
                    $('#password').val('');
                }
 
                $('#remember_me').click(function() {
 
                    if ($('#remember_me').is(':checked')) {
                        // save username and password
                        localStorage.usrname = $('#username').val();
                        localStorage.pass = $('#password').val();
                        localStorage.chkbx = $('#remember_me').val();
                    } else {
                        localStorage.usrname = '';
                        localStorage.pass = '';
                        localStorage.chkbx = '';
                    }
                });
            });
 
        </script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>