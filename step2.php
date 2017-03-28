<?php 
include('config/configuration.php');
 $objPages = $load->includeclasses('userlist');
 $objPagesLogin = $load->includeclasses('login');

$flag = 'false';
global $mail;

if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != '' ){
    $objPages->redirect("view");
}
/*rm added code for #32655 starts here*/
if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != '' && isset($_POST['registerlogin']) && $_POST['registerlogin'] != '')
{
    
$dataInsert  =  $objPages->updateDatastep2();
    if($dataInsert == '1' ){
      $objPagesLogin->LoginUser(); 
      $_SESSION['msg'] = 'reg';
      $objPages->redirect("userlist");
    }else {
      $_SESSION['msg-type']='alert-danger';
      $_SESSION['msg'] =  $dataInsert;
    }
}
/*rm added code for #32655 ends here*/
if(isset($_POST['register']) && $_POST['register'] != ''){

  if($_POST['script'] == 'add'){
    $dataInsert  =  $objPages->insertDatastep2();
    if($dataInsert == '1' ){
     // auto login user after create new profile 
      $_POST['username']=$_POST['email'];
      $objPagesLogin->LoginUser(); 
      $_SESSION['msg-type']='alert-success';
      $_SESSION['msg'] = 'reg';
      $objPages->redirect("stepthird");
    }else {
      $_SESSION['msg-type']='alert-danger';
      $_SESSION['msg'] =  $dataInsert;
    }
  }else{
    $dataInsert  =  $objPages->updateDatastep2();
    if($dataInsert == '1' ){
      $objPagesLogin->LoginUser(); 
      $_SESSION['msg'] = 'reg';
      $objPages->redirect("userlist");
    }else {
      $_SESSION['msg-type']='alert-danger';
      $_SESSION['msg'] =  $dataInsert;
    }
  }

}

$load->includeother('header_register');
?>
<div class="row profile">
    <div class="container">
      
        <?php 


        if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
            $userdetail = $dclass->select("email,user_comp_id","tbl_user"," AND user_id= '".base64_decode($_REQUEST['uid'])."' ");
            $compdetail = $dclass->select("company_name","tbl_user"," AND user_id= '".$userdetail[0]['user_comp_id']."' ");
            
            $uemail = $userdetail[0]['email'];
            $readonly = 'readonly="readonly"';
            $company_name = $compdetail[0]['company_name'];
            
        }else{ 
          $uemail = $_POST['email'];
          $readonly = '';
          $company_name = $_POST['company_name'];
          ?>


      <div class="col-sm-12"><ul class="number"><li class="selected-success"></li><li class="selected">2</li><li>3</li></ul></div>
      <?php } ?>

      
      <div class="col-sm-12 title">CREATE YOUR PROFILE</div>
    
     <form name="register_form" id="register_form" method="post" action="" enctype="multipart/form-data">

      <div class="col-sm-12 col-xs-12">
      <div class="col-sm-5 col-xs-3 label"><label>Email*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="email" <?php echo $readonly; ?> id="email" value="<?php echo $uemail; ?>" class="validate[required,custom[email]]" /></div>

      <div class="col-sm-5 col-xs-3 label"><label>Password*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="password" name="password" id="password" value="" class="validate[required]" /></div>
      
      <div class="col-sm-5 col-xs-3 label"><label>Name*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="fullname" id="fullname" value="<?php echo $_POST['fullname'] ?>" class="validate[required]" /></div>
      <?php if(!isset($_REQUEST['uid'])){ ?>
      <div class="col-sm-5 col-xs-3 label"><label>Job Title*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="job_title" id="job_title" value="<?php echo $_POST['job_title'] ?>" class="validate[required]"  /></div>
      <?php } ?>
      <div class="col-sm-5 col-xs-3 label"><label>Company Name*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="company_name" <?php echo $readonly; ?> id="company_name" value="<?php echo $company_name; ?>" class="validate[required]" /></div>
      <?php if(!isset($_REQUEST['uid'])){ ?>
      <div class="col-sm-5 col-xs-3 label"><label>Team*</label></div>
      <div class="col-sm-7 col-xs-9 textbox"><input type="text" name="department" id="department" value="<?php echo $_POST['department'] ?>" class="validate[required]" /></div>
      <?php } ?>
      <div class="col-sm-12 col-xs-12"><hr class="col-sm-5 col-xs-7"></hr></div>
      
      <div class="col-sm-5 col-xs-4 pic">
      <div id="imagePreview" class="img-circle"></div>
      <!--img class="img-circle" id="blah" src="images/prof-pic.png"  /-->
      
      </div>
      <div class="col-sm-7 col-xs-8 upload"><div class="upload1"><input type="file" name="user_avatar" id="imgInp" value="upload a photo +"></div></div>
      
      <div class="col-sm-12 col-xs-12 action">
      
      <?php 
        if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
          $script = 'edit';
        }else{
          $script = 'add';
        }
      ?>
      <?php if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){ ?>
        <input type="hidden" name="id" value="<?php echo base64_decode($_REQUEST['uid']); ?>">
      <?php }else{ ?>
        <a href="<?php echo SITE_URL; ?>stepfirst" class="btn-success" >BACK</a>
      <?php } ?>
        <input type="hidden" name="script" value="<?php echo $script; ?>">
        <?php if(isset($_REQUEST['uid'])){ ?>
      <input type="submit" name="registerlogin" class="btn-success" value="LOGIN"/>
      <?php }else{ ?>
        <input type="submit" name="register" class="btn-success" value="NEXT, GET YOUR TEAM ON BOARD"/>
       <?php }?></div>
      
      </div>
      </form>
      
    </div>
  </div>
  


  
</div>
<style type="text/css">
  .alert-success{float: left;width: 100%}
  #imagePreview {
    width: 62px;
    height: 62px;
    background-position: center center;
    background-size: cover;
    -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
    display: inline-block;
    background-image: url('images/prof-pic.png');
}
</style>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<script>
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, 1), 2);
};
  $(function() {
    $("#imgInp").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
        if(bytesToSize(files[0].size) > 2048){
          alert("This image is too large. File size limit is [2 MB]");
          return false;
        }
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
});

   /*function readURL(input) {
        if(input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
              if(input.files[0].type == 'image/jpeg' || input.files[0].type == 'image/png' || input.files[0].type == 'image/gif'){
                var mysrc = '<?php echo SITE_URL; ?>timthumb.php?src='+e.target.result+'&h=46&w=46&zc=1&q=100 ?>';
                $('#blah').attr('src', e.target.result);
                //$('#blah').attr('width','62px' );
                //$('#blah').attr('height','62px' );

              }else{
                alert("Please upload valid Image type.");
                return false;
              }
            }
            reader.readAsDataURL(input.files[0]);

        }
    }*/

   $(document).ready(function(){
      $("#register_form").validationEngine({validateNonVisibleFields: true});
      /*$("#imgInp").change(function(){

          readURL(this);
      });*/
   });
</script>

</body>
</body>
</html>
