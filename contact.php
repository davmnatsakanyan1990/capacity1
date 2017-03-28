<?php 
include('config/configuration.php');
$flag = 'false';
global $mail;

/*$_SESSION['user_id'] = '';
unset($_SESSION['user_id']);
setcookie("username", '', time() - 300);
setcookie("password", '', time() - 300); 
session_destroy();*/

if(isset($_POST['send']) && $_POST['send'] == 'SEND'){

         $email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Contact us"');
         $message  = str_replace('{NAME}',ucfirst($_POST['fullname']),$email_template[0]['email_body']);
         
         $logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
         $message = str_replace('{LOGO}',$logo,$message); 

         $message = str_replace('{EMAIL}',$_POST['email'],$message);
         $message = str_replace('{MASSAGE}',$_POST['massage'],$message);
        



        $mail->Subject  =   $email_template[0]['email_title'];
        
        $adminmail = $dclass->select('*','tbl_setting',' AND v_name = "ADMIN_EMAIL"');
        //print_r($adminmail);
        
        $mail->addAddress($adminmail[0]['l_values']);
        //$mail->addAddress('jyecalder@gmail.com');
        
        
          

        $mail->msgHTML($message);
        $mail->send();
        $_SESSION['msg-type']='alert-success';
        $_SESSION['msg']='inqsent';

}
$load->includeother('header_home');
?>

<div class="row welcome profile">
    <div class="container">
    <div class="col-sm-12 title">Contact Us</div>
      <div class="col-sm-12 info">We'd love to hear from you! Whether it's an enquiry,a <br/>Suggestion, or just some feedback on capacity App, please feel free to drop<br/> 
      us a message and we will get back to you.</div>
    
     <form name="contact_form" id="contact_form" method="post" action="" enctype="multipart/form-data">

      <div class="col-sm-12 col-xs-12">
      
     <div class="col-sm-5 col-xs-3 label"><label>Name*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="fullname" id="fullname" value="" class="validate[required]" /></div>

      <div class="col-sm-5 col-xs-3 label"><label>Email*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="email" id="email" value="" class="validate[required,custom[email]]" /></div>
      
      <div class="col-sm-5 col-xs-3 label"><label>Message*</label></div>
      <div class="col-sm-7 col-xs-9 textbox">
              <textarea name="massage" id="massage" class="validate[required]" rows="9" cols="32"> </textarea>
      </div>
      
      
      <div class="col-sm-11 col-xs-9 action">
        <input type="submit" name="send" class="btn-success" value="SEND"/></div>
      </div>
      </form>
      
    </div>
  </div>
  


  
</div>
<?php 
$load->includeother('footer_home');
?>
<style type="text/css">
  .alert-success{float: left;width: 100%}
</style>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<script>
   $(document).ready(function(){
      $("#contact_form").validationEngine({validateNonVisibleFields: true});
   });
</script>

</body>
</body>
</html>
