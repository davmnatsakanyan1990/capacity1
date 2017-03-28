<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login');	

$load->includeother('header_home');
$flag = 'false';
global $mail;

?>
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
        <!--a href="<?php echo SITE_URL; ?>subscribes/<?php echo $pl['sub_id']; ?>"-->
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
     
        <div class="col-sm-12 link"><a href="<?php echo SITE_URL; ?>stepfirst" >GET STARTED - TRY IT FOR FREE</a></div>
    </div>
  </div>

 <?php $load->includeother('footer_home'); ?>