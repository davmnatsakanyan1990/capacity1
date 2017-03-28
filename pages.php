<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login'); 

$load->includeother('header_home');
$flag = 'false';
global $mail;


?>

   

  <?php $cmsfirst = $dclass->select("*","tbl_cms"," AND cms_id= '".$_REQUEST['page_id']."'");?>
  <!-- section 1 -->
  <?php $section1 = $dclass->select("*","tbl_section"," AND sec_id = ".$cmsfirst[0]['cms_id']);
    if($section1[0]['sec_bg_image'] != ''){
      $clssec1 = 'section1';
    }else{
      $clssec1 = '';
    }
  ?>
 <style type="text/css">
    .section1 {
      background-attachment: scroll;
      background-clip: border-box;
      background-color: transparent;
      background-image: url("<?php echo SITE_URL; ?>/upload/section/<?php echo $section1[0]['sec_bg_image'];?>");
      background-origin: padding-box;
      background-position: center bottom;
      background-repeat: no-repeat;
      background-size: cover;
      min-height: 510px;
  }
    </style>
  <div class="row know <?php echo $clssec1 ?>">
    <div class="container">
      <div class="col-sm-12 title"> <?php echo $section1[0]['sec_title']; ?> </div>
      <div><?php echo $cmsfirst[0]['cms_desc']; ?></div>
    </div>
  </div>
  
 <?php $load->includeother('footer_home'); ?>