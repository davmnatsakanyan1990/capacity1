<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login');	
$load->includeother('header_home');
$flag = 'false';
global $mail;
?>
<?php $faq = $dclass->select("*","tbl_faq"," AND faq_status = '1' ORDER BY faq_order asc limit 0,8");?> 
  <!-- section 7 -->
  <?php $section7 = $dclass->select("*","tbl_section"," AND sec_id = 7");
  	if($section7[0]['sec_bg_image'] != ''){
  		$clssec7 = 'section7';
  	}else{
  		$clssec7 = '';
  	}
  ?>
 <style type="text/css">
  	.section7 {
	  	background-attachment: scroll;
	    background-clip: border-box;
	    background-color: transparent;
	    background-image: url("<?php echo SITE_URL; ?>/upload/section/<?php echo $section7[0]['sec_bg_image'];?>");
	    background-origin: padding-box;
	    background-position: center bottom;
	    background-repeat: no-repeat;
	    background-size: cover;
	    min-height: 510px;
	}
    </style>
  <div class="row faq <?php echo $clssec7; ?>" id="section4">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section7[0]['sec_title']; ?></div>
      <?php
      	$cnt = 0;
       foreach($faq as $fq){ 
       		if($cnt % 2 == 0){
       			echo '<div class="clearfix"></div>';
       		}
       	?>
      <div class="col-lg-6 col-sm-6 client item">
        <h2 class="heading"><?php echo $fq['faq_question']; ?></h2>
        <p><?php echo $fq['faq_answer']; ?></p>
      </div>


      <?php 
      $cnt++;
      } ?>
     

    </div>
  </div>

 <?php $load->includeother('footer_home'); ?>