<?php  include("../config/configuration.php"); $load  =  new loader();	
	$objLogin = $load->includeclasses('login');	
	$label =  'index';
	$dclass = new database();
	$gnrl  =  new general;
//  Login  Process		
$syste_info =  $gnrl->getSettings();



if(isset($_POST['change']) && $_POST['change'] == 'Change' ){
	
			if(isset($_POST['eid']) && $_POST['eid'] != ''){
				$userData=$dclass->select('*','tbl_user',' AND user_type = "super_admin" AND email ="'.base64_decode(base64_decode($_POST['eid'])).'" ');
				
                                if(count($userData) > 0){
						$chArr = array('password'=>base64_encode($_POST['new_password']) );
						$dclass->update('tbl_user',$chArr,' user_id='.$userData[0]['user_id']);
                                                // delete token on update password
                                                $dclass->update('tbl_user',array('user_token'=>''),' user_id='.$userData[0]['user_id']);
						//$_GET['msg']='changePass';
						$gnrl->redirectTo('index.php?msg=chpass');
						
						
				}else{ 
					
					$_GET['msg']='emailnotexist';
				}
			}else{
				$_GET['msg']='chkLink';
			}
			

}

if($_REQUEST['token'] and $_REQUEST['token'] != '' and $_REQUEST['eid'] != '')
{
    $userData = $dclass->select('*','tbl_user',' AND user_token = "'.$_REQUEST['token'].'" AND email ="'.base64_decode(base64_decode($_REQUEST['eid'])).'" ');
     if(count($userData) == 0)
     {
        $_REQUEST['token'] = '';
        $gnrl->redirectTo('index.php?msg=invalidtoken');
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
<title>Online City Guide | Change Password</title>
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
 <link href="assets/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
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
<div class="logo"><img src="assets/img/logo.png" alt=""  height="70px"/> </div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
  <!-- BEGIN LOGIN FORM -->
  <form class="form-vertical login-form" id="change_pass" action="changePass.php" method="post">
  <input type="hidden" name="eid" value="<?php echo $_REQUEST['eid'] ?>" >
    <h3 class="form-title">Create Password</h3>
    <?php if(isset($_GET['msg'])){
		echo '<div class="alert alert-error">'.$mess[$_GET['msg']].'</div>';						}
	?>
    <div class="alert alert-error hide">
      <button class="close" data-dismiss="alert"></button>
      <span>Enter any username and password.</span> </div>
    <div class="control-group">
      <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
      <label class="control-label visible-ie8 visible-ie9 valiclrBold">New Password *</label>
      <div class="controls password-strength">
        <div class="input-icon left"> <i class="icon-lock"></i>
          <input type="password" class="validate[required,minSize[8],custom[minSpecialChars],custom[minNumberChars]]  m-wrap" name="new_password" placeholder="Password" id="password_strength">
           <span class="help-block" style="padding-left:5px;">Type a password to check its strength</span>
           <p class="passtagP">Password is case sensitive, must be at least 8 characters, and include at least 1 number and 1 special character (eg. @#$%^&*). </p>
        </div>
      </div>
    </div>
    
    
    
      
    
    
    
    <div class="control-group">
      <label class="control-label visible-ie8 visible-ie9 valiclrBold">Confirm Password *</label>
      <div class="controls">
        <div class="input-icon left"> <i class="icon-lock"></i>
         <input type="password" name="confirm_password" placeholder="Confirm Password"  class="validate[required,equals[password_strength]] m-wrap">
        </div>
      </div>
    </div>
    <div class="form-actions">
      
        <input type="submit" class="btn green pull-right" value="Change" name="change">
        <i class="m-icon-swapright m-icon-white"></i> </button>
    </div>
    
    
  </form>
  
  
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright"> <?php //echo date('Y');?> 2013 &copy; Online  City Guide. &nbsp; <?php echo $syste_info; ?> </div>
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
    
    	<script type="text/javascript" src="assets/scripts/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="assets/scripts/jquery.validationEngine-en.js"></script>
    


<script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="assets/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/date.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery.input-ip-address-control-1.0.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script src="assets/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
<script src="assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
<script src="assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js" type="text/javascript" ></script>
<script src="assets/plugins/bootstrap-switch/static/js/bootstrap-switch.js" type="text/javascript" ></script>
<script src="assets/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript" ></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/scripts/app.js" type="text/javascript"></script>
 <script src="assets/scripts/form-components.js"></script>  

<!-- END PAGE LEVEL SCRIPTS -->
<script>

		jQuery(document).ready(function($) {     
		  App.init();
		  FormComponents.init();
		 
		   $('#change_pass').validationEngine();
		});

	</script>
 
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>