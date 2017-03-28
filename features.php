<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login');	

$load->includeother('header_home');
$flag = 'false';
global $mail;

?>
<?php $features = $dclass->select("*","tbl_feature"," AND ftr_status = '1' ORDER BY ftr_order asc limit 0,6");?> 
  <!-- section 5 -->
   <?php $section5 = $dclass->select("*","tbl_section"," AND sec_id = 5");
  	if($section5[0]['sec_bg_image'] != ''){
  		$clssec5 = 'section5';
  	}else{
  		$clssec5 = '';
  	}
  ?>
 <style type="text/css">
  	.section5 {
	  	background-attachment: scroll;
	    background-clip: border-box;
	    background-color: transparent;
	    background-image: url("<?php echo SITE_URL; ?>/upload/section/<?php echo $section5[0]['sec_bg_image'];?>");
	    background-origin: padding-box;
	    background-position: center bottom;
	    background-repeat: no-repeat;
	    background-size: cover;
	    min-height: 510px;
	}
    </style>
  <div class="row feature <?php echo $clssec5 ?>" id="section2">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section5[0]['sec_title']; ?></div>
  	<?php foreach($features as $ftr){ ?>	    
      <div class="col-md-4 col-sm-4 client item">
        <div class="img"><img alt="image" src="<?php echo SITE_URL ?>upload/feature/<?php echo $ftr['ftr_img']; ?>" ></div>
        <h2 class="heading"><?php echo $ftr['ftr_title']; ?></h2>
        <p><?php echo $ftr['ftr_description']; ?></p>
      </div>
    <?php } ?>  
    </div>
  </div>

 <?php $load->includeother('footer_home'); ?>