<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login');	

$load->includeother('header_home');
$flag = 'false';
global $mail;

?>
  <?php $testimonial = $dclass->select("*","tbl_testimonial"," AND tmn_status = '1' ORDER BY tmn_order desc limit 0,10");?>
  <!-- section 3 -->
  <?php $section3 = $dclass->select("*","tbl_section"," AND sec_id = 3");
  	if($section3[0]['sec_bg_image'] != ''){
  		$clssec3 = 'section3';
  	}else{
  		$clssec3 = '';
  	}
  ?>
 <style type="text/css">
  	.section3 {
	  	background-attachment: scroll;
	    background-clip: border-box;
	    background-color: transparent;
	    background-image: url("<?php echo SITE_URL; ?>/upload/section/<?php echo $section3[0]['sec_bg_image'];?>");
	    background-origin: padding-box;
	    background-position: center bottom;
	    background-repeat: no-repeat;
	    background-size: cover;
	    min-height: 510px;
	}
    </style>
  <div class="row testimonial <?php echo $clssec3 ?>">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section3[0]['sec_title']; ?></div>
      <div id="owl-example" class="owl-carousel">
        <?php foreach($testimonial as $tmn){ ?>

        	<div class="col-lg-12 client item"> 
        		<img style="width: 170px; height: 170px;" alt="image" src="<?php echo SITE_URL; ?>/upload/testimonial/<?php echo $tmn['tmn_img']; ?>" class="img-circle">
          	<h2 class="name"><?php echo $tmn['tmn_user']; ?></h2>
          	<p><?php echo $tmn['tmn_description']; ?></p>
          	<p class="quote">"</p>
        </div>
        <?php } ?>
      </div>
    <script type="text/javascript">
				$("#owl-example").owlCarousel({

    // Most important owl features
    items:3,
    singleItem : false,
    //Basic Speeds
    slideSpeed : 200,
    paginationSpeed : 800,
    rewindSpeed : 1000,

    //Autoplay
    autoPlay : true,
    stopOnHover : false,

    // Navigation
    navigation : false,
    navigationText : ["prev","next"],
    rewindNav : true,
    scrollPerPage : false,

    //Pagination
    pagination : true,
    paginationNumbers: false,

    // Responsive 
    responsive: true,
    responsiveRefreshRate : 200,
    responsiveBaseWidth: window,

    
  

   
})
  	</script> 
    </div>
  </div>
  </div>
 <?php $load->includeother('footer_home'); ?>