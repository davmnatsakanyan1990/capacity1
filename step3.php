<?php 
include('config/configuration.php');
 $objPages = $load->includeclasses('userlist');
 $objPagesLogin = $load->includeclasses('login');

$flag = 'false';
global $mail;

/*if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != '' ){
    $objPages->redirect("stepthird");
}*/

if(!isset($_SESSION['register_id']) || $_SESSION['register_id'] == ''){
    if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
      $objPages->redirect("stepsecond/".$_REQUEST['uid']);
    }else{
      $objPages->redirect("stepsecond");
    }
}

if(isset($_POST['step3']) && $_POST['step3'] != ''){
  //print_r($_POST);
    $dataInsert  =  $objPages->insertUserlist();
    // auto login user after create new profile 
    $objPagesLogin->LoginUser();
    
    if($dataInsert == '1' ){
      $objPages->redirect("userlist");
    }else{
      $_SESSION['msg1'] =  $dataInsert;
    }
}

$load->includeother('header_register');
?>
<div class="row invite">
    <div class="container">

     

     <form name="invite_form" id="invite_form" method="post" action="" enctype="multipart/form-data">       
        <div class="col-sm-12">
          <ul class="number">
            <li class="selected-success"></li>
            <li class="selected-success"></li>
            <li class="selected">3</li>
          </ul>
        </div>
        <div class="col-sm-12 title">INVITE YOUR TEAM</div>
        <div class="col-sm-12 col-xs-12">
          <div class="col-sm-12 col-xs-12 info">
            <div>Capacity is all about getting the most from your team, <br/>so you’re going to want to get them on board!<br/></div>
            <strong>You can invite up to 9 team members. </strong><br/>
            <div>Want more? <a href="<?php echo SITE_URL; ?>subscriptions">Upgrade Now</a></div>
          </div>
          <div class="col-sm-12 col-xs-12"><hr class="col-sm-5 col-xs-12"></hr></div>
          <div class="col-sm-12 col-xs-12 info">
          <div>
            <b>Enter your team’s email addresses <br/>(separated by a comma or each on a new line)</b> 
          </div>
      
          </div>
          <div class="col-sm-12 col-xs-12">
            <div class="col-sm-5 col-xs-12 textarea">
                <!-- <textarea name="userlist" id="userlist" class="validate[required]" ></textarea></div> -->
              <textarea name="userlist" id="userlist" ></textarea></div>
            </div>
          <div class="col-sm-12 col-xs-12 action">
            
            <a href="<?php echo SITE_URL; ?>userlist" class="btn-success" >Skip</a>
            <input type="submit" class="btn-success" name="step3" value="INVITE AND CONTINUE TO APP"/>

          </div>
        </div>
    </form>
  </div>
</div>
<style type="text/css">
  .alert-success{float: left;width: 100%}
</style>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<script>

   $(document).ready(function(){
      $("#invite_form").validationEngine({validateNonVisibleFields: true});
   });


</script>

</body>
</body>
</html>

