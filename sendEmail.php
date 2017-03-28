<?php 
include('config/configuration.php');
$load  =  new loader();	
$dclass   =  new  database();
$gnrl =  new general;

$objPages = $load->includeclasses('user');	
$objLogin = $load->includeclasses('login');
$flag = 'false';
$msg = '';

$udetail = $dclass->select('*','tbl_user',' AND user_id ='.$_SESSION['user_id']);


if(isset($_POST['Send_to_friend']) && $_POST['Send_to_friend'] == 'Send'){
$html = '<div style="background:#fff; font-family:Arial; text-align:center;font-size:14px;padding:10px">
  <div style="background:#fff; width:600px; margin: 5px auto; border:10px solid #213857; padding:20px; text-align:left">
    <div style="float:left; width:400px; margin:0; padding:0 0 10px 0;" align="left"><strong style="color: #024a87;">Dear Friend,<br />
                Please<a href="'.SITE_URL.$_POST['req_url'].'"> Click here </a> for visit page.<br/></strong></div>
    <div style="float:right; width:151px; margin:-20px 5px 5px;" align="right"><img src="'.SITE_URL.'img/no_image.jpg" border="0" width="130" height="70" align="right"/></div>
	 <table border="0" cellpadding="4" cellspacing="10" style="width: 100%; border: 1px dashed #000; background: #fcfcd9;padding:10px;clear:both;border-collapse: inherit;" >
	<tbody>';
	
	
	
		$html .='<tr><td width="20%">Name</td><td width="80%" align="left">'.$_POST['name'].'</td></tr>';
		$html .='<tr><td width="20%">From Email</td><td width="80%" align="left">'.$_POST['email'].'</td></tr>';
		$html .='<tr><td width="20%">Message</td><td width="80%" align="left">'.$_POST['msge'].'</td></tr>';

foreach($_POST['pro_id'] as $val)
			{
				
				$Pdetail = $dclass->select('*','tbl_product',' AND product_id ='.$val);
				
				
		$html .='<tr><td width="20%">'.$Pdetail[0]['product_name'].'</td><td width="80%" align="left"><a target="_blank" href="'.SITE_URL.'product_detail.php?pro_id='.$val.'">
					<strong><span style="font-family: Verdana sans-serif; text-decoration: none; background-color:#213857; color: white; padding: 8px;">Click here</span></strong></td></tr>';		
				
		$html.='<tr >
					<td colspan="2"></td>
				</tr>';
			}
	
	
	$html.='</tbody>
</table>
  <p style="color:#666; margin:5px auto; font-size:11px;margin:0;padding:0 0 5px 0;">Copyright &copy; 2006-2011 OCG.com - All Rights Reserved.</p>
</div>';





				$email_template = $dclass->select('*','tbl_email_template',' AND email_template_title = "Send Friend"');
				$message  = str_replace(':::REQURL:::',' Please<a href="'.SITE_URL.$_POST['req_url'].'"> Click here </a> for visit page.',$email_template[0]['email_template_desc']);
				$message = str_replace(':::EMAIL:::',$_POST['email'],$message);
				$message = str_replace(':::NAME:::',$_POST['name'],$message);
				$message = str_replace(':::MSG:::',$_POST['msge'],$message);
				$prodetail = '';
				foreach($_POST['pro_id'] as $val)
				{
					
					$Pdetail = $dclass->select('*','tbl_product',' AND product_id ='.$val);
					
					
					$prodetail .='<p>'.$Pdetail[0]['product_name'].': <a target="_blank" style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'product_detail.php?pro_id='.$val.'">
						Click here</a></p>';		
					
				}
				$message = str_replace(':::PRODUCTLIST:::',$prodetail,$message);

				$subject  =   "Detail Of ocgweb.magneto.co.in";
			 	//$tomail = (',',$_POST['toemail']);
				$headers = "From: " . $_POST['email'] . "\r\n";
				$headers .= "Cc: " . $_POST['toemail'] . "  \r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";	
				mail($to,$subject,$message,$headers);								
				$msg = 'sentfriend';

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Online City Guide</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="images/favicon.png" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/basic.css" rel="stylesheet">
<link rel="stylesheet" href="css/template.css" type="text/css" />
<link rel="stylesheet" href="css/updates.css" type="text/css" />
<link rel="stylesheet" href="css/custom.css" type="text/css" />
<link rel="stylesheet" href="addons/superfish_responsive/superfish.css" type="text/css" />
<script src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="js/jquery.validationEngine.js"></script>
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
<script type="text/javascript">
$(document).ready(function(){
	$('#addTolistPop_form').validationEngine();
});
</script>

<style type="text/css">
.butnCss
{
	background-color: #E12121;
    color: white;
    padding: 6px;
    border-radius: 6px;
    width: 30%;
    margin-right:32px;
}

</style>

</head>

		<!--<div id="loading" style="display:none;text-align:center;padding-top:45px;"><img src="css/fancybox_loading.gif" alt="" /></div>-->

		<div id="login_panel" style="padding:0px;" >
        	<?php if($msg != ''){ ?>
				<div class="alert alert-block alert-error fade in">
                        <p><?php echo $mess[$msg]; ?></p>
                     </div>
			<?php } ?>	
				<?php //if(isset($_SESSION['user_id']) && $_SESSION['user_id']!= ''){ ?>
            
            <div class="inner-container">
				<h3 class="m_title">Send Email</h3>
				<form id="addTolistPop_form" name="addTolistPop_form" method="post" >
					 <input type="hidden" name="req_url" value="<?php echo $_REQUEST['req_url'] ?>"  />
                    <?php 
						
						if(isset($_REQUEST['identifyTag']) && $_REQUEST['identifyTag']== 'rightbar'){
							?>
                           <div style="max-height:160px;overflow:auto"> 
                          <?php   
							$user_viewd = $dclass->select("*","tbl_user_recently_view_place"," AND user_id=".$_SESSION['user_id']." ORDER BY datetime desc limit 0,10 ");
							foreach($user_viewd as $view){ 
					$view_pro_Detail = $dclass->select("t1.product_name,t1.city_id,t1.product_id,t2.city_name","tbl_product t1 LEFT JOIN tbl_city t2 ON t1.city_id=t2.city_id "," AND t1.product_id='".$view['product_id']."'");
					?>
			   			<p>
                             <input type="checkbox" name="pro_id[]" value="<?php echo $view_pro_Detail[0]['product_id'] ?>"  id="pro_id" class="validate[minCheckbox[1]]" checked="checked"/><?php echo ucfirst($view_pro_Detail[0]['product_name'])?>
                            
                            
                            
                            </p>
					<?php } ?>
						</div>
					<?php 	}?>
                
               <?php 
			   //	print_r($udetail);
				if(isset($_SESSION['user_id']) && $_SESSION['user_id']!='')
				{
					$uemail = $udetail[0]['user_email'];
					$uname = $udetail[0]['user_first_name']. " " .$udetail[0]['user_last_name'];
				}
				else
				{
					$uemail = '';
				}
			   
			   ?>
                
                <p><input type="text" name="name" id="name"  placeholder="Enter Your Name" value="<?php echo $uname;?>" class="validate[required]" ></p>
                <p> <input type="text" name="email" id="email" placeholder="Enter Your Email" value="<?php echo $uemail;?>" class="validate[required,custom[email]]" ></p>
                <p> <textarea name="toemail" id="toemail" placeholder="(e.g. bob@email.com)" class="validate[required]"></textarea>
				    <span class="help-block">(Enter multiple email addresses Separate with commas(,))</span>
			    </p>
                
                <p>
                	 <textarea placeholder="Message" name="msge" id="msge" class="validate[required]"></textarea>
                </p>
            
                <p style="margin-top:15px;">
              
                    <input type="submit" id="login_btn" name="Send_to_friend" class="btn btn-danger" value="Send">
                    <input type="button" id="login_btn" name="submit"   value="CANCEL" class="btn btn-danger" onclick="fancyclose()">
                </p>
                    
				</form>
   
			</div>
            
		</div>


<?php if($_POST['submit'] == 'CANCEL'){ ?>
<script type="text/javascript">
parent.jQuery.fancybox.close();
</script>
<?php } ?>
	
    
<?php if($msg != ''){ ?>
<script type="text/javascript">

setInterval(function(){parent.jQuery.fancybox.close();},2000);


</script>
<?php } ?>
    
    
    
<script type="text/javascript">

function fancyclose()
{
	parent.jQuery.fancybox.close();
}



$(document).ready(function() {
	
		$('#addTolistPop_form').submit(function() {
		//$('#loading').show(); 
		$('.login-panel').hide(); 
		return true; // allow regular form submission
	});
	
});
	</script>    