<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login');	

$flag = 'false';
global $mail;

if(isset($_SESSION['user_id']) && $_SESSION['user_id']!='')
{
	$objPages->redirect("view");
}

if(isset($_POST['change']) && $_POST['change'] == 'Change' ){
	
			if(isset($_POST['eid']) && $_POST['eid'] != ''){
				$userData=$dclass->select('*','tbl_user',' AND  email ="'.base64_decode(base64_decode($_POST['eid'])).'" ');
				
          if(count($userData) > 0){
						$chArr = array('password'=>base64_encode($_POST['new_password']) );
						$dclass->update('tbl_user',$chArr,' user_id='.$userData[0]['user_id']);
                                                // delete token on update password
            $dclass->update('tbl_user',array('user_token'=>''),' user_id='.$userData[0]['user_id']);
						//$_GET['msg']='changePass';
					$_SESSION['msg']='chpass';
          $_SESSION['msg-type']='alert-success';

            $gnrl->redirectTo(SITE_URL);
						
						
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
        $_SESSION['msg']='invalidtoken';
          $_SESSION['msg-type']='alert-danger';
        $gnrl->redirectTo(SITE_URL);
     }
}

/*if(isset($_POST['reset_pass'])){

	if($_POST['new_password'] != '' &&  $_POST['new_password'] == $_POST['conf-password']){
		$getid = $dclass->select("user_id","tbl_reset_password_request"," AND pwd_reset_hash = '".$_POST['token']."' ");
		$upPass = array("password" => base64_encode($_POST['new_password']));
		$dclass->update("tbl_user",$upPass," user_id = ".$getid[0]['user_id']);
		$dclass->delete("tbl_reset_password_request"," pwd_reset_hash = '".$_POST['token']."' ");

		$_REQUEST['msg']='changepass';
		$flag = 'true';
	}
}*/
$load->includeother('header_home');
?>


<div class="row welcome profile">
    <?php if(isset($_REQUEST['token']) && $_REQUEST['token'] != '' and $_REQUEST['eid'] != ''){ ?>
    <div class="container">
    <div class="col-sm-12 title">Reset Password</div>
    
    <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != ''){ ?>
              <div class="alert alert-danger"><?php echo $mess[$_REQUEST['msg']]; ?></div>
    <?php } ?>

      <form class="form-reset-pass" id="form-reset-pass" name="form-reset-pass" method="post" action="">
          <input type="hidden" name="eid" value="<?php echo $_REQUEST['eid'] ?>" >

      <div class="col-sm-12 col-xs-12">
      
     <div class="col-sm-5 col-xs-3 label"><label>New Password *</label></div>
      <div class="col-sm-7 col-xs-9 textbox">
        <input type="password" name="new_password" id="new_password" value="" class="validate[required]" />
      </div>

      <div class="col-sm-5 col-xs-3 label"><label>Confirm Password *</label></div>
      <div class="col-sm-7 col-xs-9 textbox">
      <input type="password" name="confirm_password" id="confirm_password" value="" class="validate[required,equals[new_password]]" />
      </div>
      
     
      
      
      <div class="col-sm-11 col-xs-9 action">
         <input type="submit" class="btn btn-success" value="Change" name="change">
        </div>
      </div>
      </form>
      
    </div>
    <?php }else{  ?>
   
      <?php if( $_REQUEST['msg'] =='changepass' && $flag == 'true'){ ?>
            <div class="col-sm-12 title">Reset Password</div>
            <div class="col-sm-12 col-xs-12">
                <div class="alert alert-danger"><?php echo $mess[$_REQUEST['msg']]; ?></div>
            </div>
        <?php }else{ ?>
            <div class="col-sm-12 title">Reset Password</div>
            <div class="col-sm-12 col-xs-12">
                <div class="alert alert-danger"><?php echo "token mismatch please try correct link"; ?></div>
            </div>

          <?php } ?>  

    <?php } ?>
</div>
  


  
</div>
<?php 
//$load->includeother('footer_home');
?>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
<script>
   $(document).ready(function(){
      $('#form-reset-pass').validationEngine();
   });
</script>
<style type="text/css">
  .alert-success{float: left;width: 100%}
  .profile, .welcome, .invite {
    min-height: 525px;

}
</style>

