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
  <div class="row subscription-section <?php echo $clssec6 ?>" id="section3">
    <div class="container">
      <div class="col-sm-12 title">SUBSCRIPTION OPTIONS</div>
      <div class="col-sm-12 small title1 subsc-text"><span>Choose from one of the three subscription packages below. We’ve tailored various levels of access to suit your business size and budget.</span></div>
      <?php $drtype = array(
              'day'=>'Day',
              'week'=>'WK',
              'month'=>'MO',
              'year'=>'YR',
          ); ?>
      <?php foreach($sub_plan as $pl){ ?>
        <div class="col-md-6 col-sm-12">
        <div class="subscription-head"><?php echo $pl['sub_title']; ?></div>
        <ul class="subscription-list">
            <?php if($pl['sub_available_user'] > 10000){
                $userval = 'UNLIMITED'; 
              }else{
                $userval = "1-".$pl['sub_available_user']; 
                } ?>
            <li><?php echo $userval; ?> USERS</li>
            <?php if($pl['sub_available_project'] > 10000){
                $projectval = 'UNLIMITED'; 
              }else{
                $projectval = "UP TO ".$pl['sub_available_project']; 
                } ?>
            <li><?php echo $projectval; ?> PROJECTS</li>
            <!--li>UP TO 100MB OF STORAGE</li-->
            <li>UNLIMITED TASKS</li>
            <li>FULL APP FEATURES</li>
        </ul>
        <?php if(isset($_REQUEST['script']) && $_REQUEST['script'] == '') ?>
        <div class="subscription-price">$<b><?php echo $pl['sub_price']; ?></b> / <?php echo $drtype[$pl['sub_duration_type']]; ?></div>
        <div class="subscription-btn-box">
          <div class="subscription-btn">
            <?php 

            if(isset($_REQUEST['script']) && $_REQUEST['script'] == 'upgrade'){
                     if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
                        $link = SITE_URL.'subscribes/'.$pl['sub_id'].'/upgrade/'.$_REQUEST['uid'];
                     }else{
                        $link = SITE_URL.'subscribes/'.$pl['sub_id'].'/upgrade';
                     }
              }else{
                if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
                        $link = SITE_URL.'subscribes/'.$pl['sub_id'].'/upgrade/'.$_REQUEST['uid'];
                     }else{
                        $link = SITE_URL.'subscribes/'.$pl['sub_id'];
                     }
                } ?>
            <a href="<?php echo $link; ?>">SUBSCRIBE NOW</a>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
    <div class="clr"></div>
  </div>

 <?php $load->includeother('footer_home'); ?>