<?php 
include('config/configuration.php');
include('config/dbconfig.php');
global $mail;
$objPages = $load->includeclasses('login');	

$load->includeother('header');
$flag = 'false';


if(isset($_SESSION['user_id']) && $_SESSION['user_id']!='')
{
	$objPages->redirect("userlist");
}

if(isset($_POST['login'])){

$dataInsert  =  $objPages->LoginUser();

		if($dataInsert == '1' ){

                    if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                        $username=$_POST['username'];
                        $password=$_POST['password'];
                        setcookie("username", $username, time()+(10 * 365 * 24 * 60 * 60));
                        setcookie("password", $password, time()+(10 * 365 * 24 * 60 * 60));  /* expire in 1 hour */
                    } 
                        $objPages->redirect('view');
		}else if($dataInsert == '2' ){
				if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                        $username=$_POST['username'];
                        $password=$_POST['password'];
                        setcookie("username", $username, time()+(10 * 365 * 24 * 60 * 60));
                        setcookie("password", $password, time()+(10 * 365 * 24 * 60 * 60));  /* expire in 1 hour */
                }
				$objPages->redirect('userlist');
		}else {
			$_SESSION['msg-type']='alert-danger';
			$_SESSION['msg'] = $dataInsert;
			$objPages->redirect('home');
		}
}

if(isset($_POST['forgot'])){


if($_POST['email']  !=  ''  ){
				$userData=$dclass->select('*','tbl_user',' AND email ="'.$_POST['email'].'"');
				if(count($userData) > 0){
						if($userData[0]['user_status'] == 'active'){					
								$length = 10;
                                $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                                
                                // update tbl_user set token 
                                $dclass->update('tbl_user',array('user_token'=>$token),' email = "'.$_POST['email'].'"');
                                
								$email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Forgotpassword"');
								$message  = str_replace('{USERNAME}',ucfirst($userData[0]['fname']).' ' .ucfirst($userData[0]['lname']),$email_template[0]['email_body']);
								
								$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
            					$message = str_replace('{LOGO}',$logo,$message);

	                            $link = SITE_URL.'reset_password.php?token='.$token.'&eid='.base64_encode(base64_encode($_POST['email']));
				//$message = str_replace('{LINK}','<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'changePass.php?eid='.base64_encode(base64_encode($_POST['email'])).'">'.SITE_URL.'changePass.php?eid='.base64_encode(base64_encode($_POST['email'])).'</a>',$message);exit;
                                $message = str_replace('{LINK}','<a  style="color: #0000FF;text-decoration: underline;" href="'.$link.'">Here</a>',$message);
                                $message = str_replace('{STATIC_LINK}',$link,$message);
			
							   	/*$upd_array = array("user_password_change"=>"1",);
								$dclass->update("tbl_user",$upd_array, "user_email = '". $userData[0]['user_email']."'");*/
								
								$mail->Subject = $email_template[0]['email_subject'];
							 	$mail->addAddress($_POST['email']);
							 	$mail->addAddress('ashish.panchal@zealousys.com');
							 	$mail->msgHTML($message);
								$mail->send();
								$_SESSION['msg']='chkpasssemail';
								$_SESSION['msg-type']='alert-success';
								$flag = 'true';
								$objPages->redirect('home');
							}else{
								
								$_SESSION['msg']='tillinactive';
								$_SESSION['msg-type']='alert-danger';
								$objPages->redirect('home');
							}
				}else{
					
					$_SESSION['msg']='emailnotexist';
					$_SESSION['msg-type']='alert-danger';
					$objPages->redirect('home');
				}
			
			}else{
				
			$_SESSION['msg']='required';
			$_SESSION['msg-type']='alert-danger';
			$objPages->redirect('home');
		}


}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Traffic - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo SITE_URL; ?>assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo SITE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="<?php echo SITE_URL; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
      
	  	<div class="container">
	  		
		      <form class="form-login" id="form_login" name="form_login" method="post" action="">
		        <h2 class="form-login-heading">sign in now</h2>
                  <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){ ?>
          <div class="alert alert-success">
      <button data-dismiss="alert" class="close"></button>
      <?php  echo $mess[$_SESSION['msg']];
       $_SESSION['msg'] = '';
       ?>
            </div>
            <?php } ?> 
                 <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != ''){ ?>
		        	<div class="alert alert-danger"><?php echo $mess[$_REQUEST['msg']]; ?></div>
                <?php } ?>
		        <div class="login-wrap">
		            <input type="text" class="form-control validate[required]" name="username" id="username" placeholder="User ID" >
		            <br>
		            <input type="password" name="password" id="password" class="form-control validate[required]" placeholder="Password">
		            <label class="checkbox">
		                <span class="pull-right">
		                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>
		               </span>
		            </label>
		            <button class="btn btn-theme btn-block" name="login"  type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
		           
                    <hr>
		            
		            <!--div class="login-social-link centered">
		            <p>or you can sign in via your social network</p>
		                <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
		                <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
		            </div-->
		            <!--div class="registration">
		                Don't have an account yet?<br/>
		                <a class="" href="subscription.php">
		                    Create an account
		                </a>
		            </div-->
		
		        </div>
			</form>	
						          <!-- Modal -->
		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                    <form class="form-forgot" id="form_forgot" name="form_forgot" method="post" action="<?php echo SITE_URL.'login.php'; ?>">
                              <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Forgot Password ?</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>Enter your e-mail address below to reset your password.</p>
		                          <input type="text" name="email" id="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix validate[required,custom[email]]">
		
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" name="forgot" type="submit">Submit</button>
                                  
		                      </div>
                 			</form>             
		      		        </div>
		              </div>
		          </div>
		          <!-- modal -->
		
		      
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo SITE_URL; ?>assets/js/jquery.js"></script>
    <script src="<?php echo SITE_URL; ?>assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<script type="text/javascript" src="<?php echo SITE_URL; ?>assets/js/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
    <script>
        var jQuery = jQuery.noConflict();
		jQuery.backstretch("assets/img/login-bg.jpg", {speed: 500});
		jQuery(document).ready(function() {
            jQuery('#form_login').validationEngine();
			jQuery('#form_forgot').validationEngine();
			
        });
    </script>

    


  </body>
</html>
