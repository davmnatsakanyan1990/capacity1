<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login');	

$load->includeother('header_home');
$flag = 'false';
global $mail;

?>


<div class="row slider">
  
    
  <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){ ?>
      <div class="alert alert-success">
        <button data-dismiss="alert" class="close"></button>
      <?php  echo $mess[$_SESSION['msg']];
          $_SESSION['msg'] = '';?>
      </div>
      <div class="clr"></div>
  <?php } ?>    


      <!-- Wrapper for slides -->
<?php $banner = $dclass->select("*","tbl_banner"," AND banner_status = '1' ORDER BY banner_order desc limit 0,3");?>     
      <div data-ride="carousel" class="carousel slide" id="myCarousel"> 
      <div class="carousel-inner">
       <a class="label label-danger"  href="<?php echo SITE_URL; ?>stepfirst">GET STARTED - TRY IT FOR FREE</a>
        <?php
        $cn = 1;
         foreach($banner as $bnr){
         		if($cn == 1){
         			$cls = 'active';
         		}else{
         			$cls = '';
         		}
          ?>
        	<div class="item <?php echo $cls ?>" id="slide<?php echo $cn; ?>" style="background-image:url('<?php echo SITE_URL; ?>upload/banner/<?php echo $bnr['banner_img']; ?>'); background-repeat:no-repeat; bavkground-size:cover; background-attachment:scroll; background-position:center center;"> 
          <!--img src="<?php echo SITE_URL; ?>upload/banner/<?php echo $bnr['banner_img']; ?>"-->
          <div class="carousel-caption">
            <h3><a href="#"><?php echo $bnr['banner_title']; ?></a></h3>
            <p><?php echo strip_tags($bnr['banner_description']); ?></p>
           
          </div>
        </div>
        <?php 
        $cn++;
        } ?>
        
      </div>
      <!-- End Carousel Inner -->
     
      
       <div class="slide-nav">
        <div class="col-md-2 col-sm-1 col-xs-1"></div>
        <div class="col-md-8 col-sm-10 col-xs-10 sliderwhite-bg">
            <ul class="nav nav-pills nav-justified">
          <?php  
            $i = 0;
            foreach($banner as $bnr){ 
                if($i == 0){
                    $cl = 'active';
                }else{
                    $cl = '';
                }
                ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" class="col-sm-4 col-xs-4 <?php echo $cl; ?>"><!--<div class="arrow"></div>--><a href="#"><?php echo $bnr['banner_title']; ?></a></li>
            <?php 
                $i++;
            } ?>
          </ul>
        </div>
         <div class="col-md-2 col-sm-1 col-xs-1"></div>
         </div>
      
    
    </div>
    <!-- End Carousel --> 
  </div>
  
  <?php $cmsfirst = $dclass->select("*","tbl_cms"," AND cms_publish = 1 ORDER BY cms_display_order asc");?>
  <!-- section 1 -->
  <?php $section1 = $dclass->select("*","tbl_section"," AND sec_id = 1");
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
  <!-- section 2 -->
  <?php $section2 = $dclass->select("*","tbl_section"," AND sec_id = 2");
  	if($section1[0]['sec_bg_image'] != ''){
  		$clssec2 = 'section2';
  	}else{
  		$clssec2 = '';
  	}
  ?>
 <style type="text/css">
  	.section2 {
	  	background-attachment: scroll;
	    background-clip: border-box;
	    background-color: transparent;
	    background-image: url("<?php echo SITE_URL; ?>/upload/section/<?php echo $section2[0]['sec_bg_image'];?>");
	    background-origin: padding-box;
	    background-position: center bottom;
	    background-repeat: no-repeat;
	    background-size: cover;
	    min-height: 510px;
	}
    </style>
  <div class="row capacity <?php echo $clssec2 ?>" id="section1">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section2[0]['sec_title']; ?></div>
      <div><?php echo $cmsfirst[1]['cms_desc']; ?></div>
    </div>
  </div>
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
    <?php if($testimonial!=NULL){ ?>
  <div class="row testimonial <?php echo $clssec3 ?>">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section3[0]['sec_title']; ?></div>
      <div id="owl-example" class="owl-carousel">
      <div class="col-lg-12 client item" >
        <?php 

        $cnt =0;

        foreach($testimonial as $tmn){ 
          if($cnt%3==0 && $cnt != 0 ){ echo '</div><div  class="col-lg-12 client item">';}
          ?>

        	 <div class="col-lg-4" >
        		<img style="width: 170px; height: 170px;" alt="image" src="<?php echo SITE_URL; ?>/upload/testimonial/<?php echo $tmn['tmn_img']; ?>" class="img-circle">
          	<h2 class="name"><?php echo $tmn['tmn_user']; ?></h2>
          	<p><?php echo $tmn['tmn_description']; ?></p>
          	<p class="quote">"</p>        
            </div>
        <?php $cnt++; } ?>
        
        </div>
      </div>
    <script type="text/javascript">
				$("#owl-example").owlCarousel({
    // Most important owl features    
    singleItem : true,
    //Basic Speeds
    slideSpeed : 200,
    paginationSpeed : 800,
    rewindSpeed : 1000,
    transitionStyle : "fade",

    //Autoplay
    autoPlay : 4000,
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
    <?php } ?>
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
    <?php if($words!=NULL){ ?>
    <div class="row street <?php echo $clssec4 ?>">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section4[0]['sec_title']; ?></div>
      <div id="owl-example1" class="owl-carousel">
      <div  class="col-lg-12 client item">
        <?php 
        $cnm = 0;
        foreach($words as $wrd){  
          if($cnm%3==0 && $cnm != 0 ){ echo '</div><div  class="col-lg-12 client item">';}
          ?>
        <div class="col-lg-4">
          <div class="img"><img src="<?php echo SITE_URL; ?>upload/words_on_street/<?php echo $wrd['wos_img']; ?>" height="56px" ></div>
          <h2 class="heading"><?php echo $wrd['wos_heading']; ?></h2>
          <p><?php echo $wrd['wos_description']; ?></p>
          <p class="quote">"</p>
        </div>
        <?php $cnm++; } ?>


      </div>
      </div>

  <script type="text/javascript">
				
	$("#owl-example1").owlCarousel({

    // Most important owl features
    
    // Most important owl features    
    singleItem : true,
    //Basic Speeds
    slideSpeed : 200,
    paginationSpeed : 800,
    rewindSpeed : 1000,
    transitionStyle : "fade",

    //Autoplay
    autoPlay : 6000,
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
    <?php } ?>
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
  	<?php 
$i = 1; 
    foreach($features as $ftr){ ?>	    
      <div class="col-md-4 col-sm-4 client item">
        <div class="img"><img alt="image" src="<?php echo SITE_URL ?>upload/feature/<?php echo $ftr['ftr_img']; ?>" ></div>
        <h2 class="heading"><?php echo $ftr['ftr_title']; ?></h2>
        <p><?php echo $ftr['ftr_description']; ?></p>
      </div>
      <?php if($i == 3){ ?>
        <div class="clr"></div>
      <?php } ?>
    <?php 
    $i++;
    } ?>  
    </div>
  </div>
  <!-- section 6 -->
  <?php $section6 = $dclass->select("*","tbl_section"," AND sec_id = 6");
  	if($section6[0]['sec_bg_image'] != ''){
  		$clssec6 = 'section6';
  	}else{
  		$clssec6 = '';
  	}
  ?>
 <style type="text/css">
  	.section6 {
	  	background-attachment: scroll;
	    background-clip: border-box;
	    background-color: transparent;
	    background-image: url("<?php echo SITE_URL; ?>/upload/section/<?php echo $section6[0]['sec_bg_image'];?>");
	    background-origin: padding-box;
	    background-position: center bottom;
	    background-repeat: no-repeat;
	    background-size: cover;
	    min-height: 510px;
	}
    </style>

  <?php $sub_plan = $dclass->select("*","tbl_subscription_plan"," AND sub_status = 'active' "); ?>  
  <div class="row pricing <?php echo $clssec6 ?>" id="section3">
    <div class="container">
      <div class="col-sm-12 title"><?php echo $section6[0]['sec_title']; ?></div>
      <div class="col-sm-12 small title">Free 14-day trial. No credit card required.</div>
      <?php foreach($sub_plan as $pl){ 

        ?>
      <div class="col-sm-4">
        <?php $drtype = array(
              'day'=>'Day',
              'week'=>'WK',
              'month'=>'MO',
              'year'=>'YR',
          ); ?>
        <a href="<?php echo SITE_URL; ?>subscriptions">
        <div class="box">
          <div class="col-sm-12 title"><?php echo $pl['sub_title']; ?></div>
          <div class="col-sm-12 small title"><?php echo $pl['sub_description']; ?></div>
          <div class="col-sm-12 detail">
            <div class="rs"><sup>$</sup><?php echo $pl['sub_price']; ?><sub>/<?php echo $drtype[$pl['sub_duration_type']]; ?></sub></div>
          </div>
        </div></a>
      </div>
      <?php } ?>
      <!--div class="col-sm-4">
        <div class="box">
          <div class="col-sm-12 title">studio</div>
          <div class="col-sm-12 small title">5-15 users</div>
          <div class="col-sm-12 detail">
            <div class="rs"><sup>$</sup>30<sub>/MO</sub></div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="box">
          <div class="col-sm-12 title">enterprise</div>
          <div class="col-sm-12 small title">unlimited users</div>
          <div class="col-sm-12 detail">
            <div class="rs"><sup>$</sup>45<sub>/MO</sub></div>
          </div>
        </div>
      </div-->

        <div class="col-sm-12 link"><a href="<?php echo SITE_URL; ?>stepfirst" >GET STARTED - TRY IT FOR FREE</a></div>
    </div>
  </div>
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
    <?php if($faq!=NULL){ ?>
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
    <?php } ?>
<style type="text/css">
  .alert{margin-bottom: 0px !important;}
</style>
 <?php $load->includeother('footer_home'); ?>
