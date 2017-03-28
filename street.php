<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login');	

$load->includeother('header_home');
$flag = 'false';
global $mail;

?>
  <?php $words = $dclass->select("*","tbl_words_on_street"," AND wos_status = '1' ORDER BY wos_order desc limit 0,10");?> 
  <!-- section 4 -->
    <?php $section4 = $dclass->select("*","tbl_section"," AND sec_id = 4");
  	if($section4[0]['sec_bg_image'] != ''){
  		$clssec4 = 'section4';
  	}else{
  		$clssec4 = '';
  	}
  ?>
 <style type="text/css">
  	.section4 {
	  	background-attachment: scroll;
	    background-clip: border-box;
	    background-color: transparent;
	    background-image: url("<?php echo SITE_URL; ?>/upload/section/<?php echo $section4[0]['sec_bg_image'];?>");
	    background-origin: padding-box;
	    background-position: center bottom;
	    background-repeat: no-repeat;
	    background-size: cover;
	    min-height: 510px;
	}
    </style>
    <div class="row street <?php echo $clssec4 ?>">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section4[0]['sec_title']; ?></div>
      <div id="owl-example1" class="owl-carousel">
        <?php foreach($words as $wrd){  ?>
        <div class="col-lg-12 client item">
          <div class="img"><img src="<?php echo SITE_URL; ?>upload/words_on_street/<?php echo $wrd['wos_img']; ?>" height="56px" ></div>
          <h2 class="heading"><?php echo $wrd['wos_heading']; ?></h2>
          <p><?php echo $wrd['wos_description']; ?></p>
          <p class="quote">"</p>
        </div>
        <?php } ?>
      </div>


  <script type="text/javascript">
				
	$("#owl-example1").owlCarousel({

    // Most important owl features
    items:3,
    singleItem : false,
    autoPlay : true,
    stopOnHover : false,
    navigation : false,
    pagination : true,
    paginationNumbers: false,
	responsive: true,
    responsiveRefreshRate : 200,
    responsiveBaseWidth: window,
})

  </script> 
    </div>
  </div>
 <?php $load->includeother('footer_home'); ?>