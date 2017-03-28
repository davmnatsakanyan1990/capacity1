<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('user');	
$objLogin = $load->includeclasses('login');
$load->includeother('header');
$flag = 'false';

$dclass = new database();
$gnrl  =  new general;
//  Login  Process		
$syste_info =  $gnrl->getSettings();

//if(!$gnrl->checkUserLogin()){
//	$gnrl->redirectTo("index.php?msg=logfirst");
//}



if(isset($_POST['submit']) && $_POST['submit'] == 'CHANGE'){
	
			if(isset($_POST['eid']) && $_POST['eid'] != ''){	
				$userData=$dclass->select('*','tbl_user',' AND user_type = "user" AND user_email ="'.base64_decode(base64_decode($_POST['eid'])).'" ');
				if(count($userData) > 0){
						$chArr = array('user_password'=> base64_encode($_POST['new_password']));
						$dclass->update('tbl_user',$chArr,' user_id='.$userData[0]['user_id']);
						//$_GET['msg']='changePass';
						$gnrl->redirectTo('changePassword/chpass');
				}else{ 
					$gnrl->redirectTo('changePassword/emailnotexist');
					//$_REQUEST['msg']='emailnotexist';
				}
			}else{
				
				$gnrl->redirectTo('changePassword/chkLink');
				//$_REQUEST['msg']='chkLink';
			}
			

}

?>

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
<div class="span12">CHANGE PASSWORD</div>
</div>
</div>
</div>

<div class="container">
<div class="content">



<div class="row">
<div class="span8 ">
<div id="forgot_panel">
			<div class="inner-container forgot-panel">
            <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != ''){ ?>
                    <div class="alert alert-block alert-error fade in">
                        <p><?php echo $mess[$_REQUEST['msg']]; ?></p>
                     </div>
            <?php } ?>
            
				<h3 class="m_title">CHANGE PASSWORD YOUR DETAILS?</h3>
				<form id="change_form" name="change_form" method="post">
	
    				<input type="hidden" name="eid" value="<?php echo $_REQUEST['eid'] ?>" >
                    <div class="control-group">
                        <label class="control-label valiclrBold" for="inputEmail">New Password</label>
                        <div class="controls">
                       <input type="password" class="vvalidate[required,minSize[8],custom[minSpecialChars],custom[minNumberChars]  m-wrap" name="new_password" placeholder="Password" id="password_strength">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label valiclrBold" for="inputEmail">Confirm Password</label>
                        <div class="controls">
                        <input type="password" name="confirm_password" placeholder="Confirm Password"  class="validate[required,equals[password_strength]] m-wrap">
                        <br/>
                        </div>
                    </div>
    
    
          <div class="control-group">
    <div class="controls">
     <input type="submit" class="btn btn-danger" id="recover" name="submit" value="CHANGE">
    </div>
    </div>
				</form>
				<div class="links"><a href="<?php echo SITE_URL ?>login.php" >AAH, WAIT, I REMEMBER NOW!</a></div>
			</div>
		</div>
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
	   $("#change_form").validationEngine();
	});
</script>



		   

	