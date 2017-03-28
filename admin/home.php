<?php 
include("../config/configuration.php"); 

$load  =  new loader();	
	$objLogin = $load->includeclasses('home');	
	$label =  'home';
	$gnrl =  new general();
	$dclass = new database();
// check for login

if(!$gnrl->checkLogin()){
	$gnrl->redirectTo("index.php?msg=logfirst");
}

$user_access_data =  $dclass->select(' * ','tbl_user',' AND user_id = "'.$_SESSION['adminid'].'"');
$user_retrive = explode(':::',$user_access_data[0]['user_access']);


$load->includeother('header');
?>
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>

<!--<link href="assets/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>-->
<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/css/pages/tasks.css" rel="stylesheet" type="text/css" media="screen"/>
<?php
$load->includeother('left_sidebar');
?>
 <!-- BEGIN PAGE -->
  <div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3>Widget Settings</h3>
      </div>
      <div class="modal-body"> Widget settings form goes here </div>
    </div>
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
      <!-- BEGIN PAGE HEADER-->
      <div class="row-fluid">
      
      
        <?php // Error Messsages
						if(isset($_GET['msg'])){ 
							echo '<div style="text-align:center">'.$mess[$_GET['msg']].'</div>';						
						}
		?>
      
        <div class="span12">
          <!-- BEGIN STYLE CUSTOMIZER -->
          <div class="color-panel hidden-phone">
            <div class="color-mode-icons icon-color"></div>
            <div class="color-mode-icons icon-color-close"></div>
            <div class="color-mode">
              <p>THEME COLOR</p>
              <ul class="inline">
                <li class="color-black current color-default" data-style="default"></li>
                <li class="color-blue" data-style="blue"></li>
                <li class="color-brown" data-style="brown"></li>
                <li class="color-purple" data-style="purple"></li>
                <li class="color-grey" data-style="grey"></li>
                <li class="color-white color-light" data-style="light"></li>
              </ul>
              <label> <span>Layout</span>
                <select class="layout-option m-wrap small">
                  <option value="fluid" selected>Fluid</option>
                  <option value="boxed">Boxed</option>
                </select>
              </label>
              <label> <span>Header</span>
                <select class="header-option m-wrap small">
                  <option value="fixed" selected>Fixed</option>
                  <option value="default">Default</option>
                </select>
              </label>
              <label> <span>Sidebar</span>
                <select class="sidebar-option m-wrap small">
                  <option value="fixed">Fixed</option>
                  <option value="default" selected>Default</option>
                </select>
              </label>
              <label> <span>Footer</span>
                <select class="footer-option m-wrap small">
                  <option value="fixed">Fixed</option>
                  <option value="default" selected>Default</option>
                </select>
              </label>
            </div>
          </div>
          <!-- END BEGIN STYLE CUSTOMIZER -->
          <!-- BEGIN PAGE TITLE & BREADCRUMB-->
          <h3 class="page-title"> Dashboard <small>statistics and more</small> </h3>
          <ul class="breadcrumb">
            <li> <i class="icon-home"></i> <a href="home.php">Home</a> <i class="icon-angle-right"></i> </li>
            <li><a href="home.php">Dashboard</a></li>
          </ul>
          <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
      </div>
      <!-- END PAGE HEADER-->
      <div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
					<div class="row-fluid">
						<?php
						// code for product 
						$total_plan = $dclass->select("COUNT(*) as cnt","tbl_subscription_plan"," AND sub_status = 'active'") ;
						?>
                        <div class="span4 responsive" data-tablet="span6" data-desktop="span4">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="icon-globe"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php echo $total_plan[0]['cnt']; ?>
									</div>
									<div class="desc">Subscription plan</div>
								</div>
								<a class="more" href="subscription.php">
								View Detail <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<?php	// code for pending Review 
						$ttl_testimonial = $dclass->select("*"," tbl_testimonial"," AND tmn_status 	='1'") ;	?>
                        <div class="span4 responsive" data-tablet="span6  fix-offset" data-desktop="span4">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-comments"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo count($ttl_testimonial); ?></div>
									<div class="desc">Testimonials</div>
								</div>
								<a class="more" href="testimonial.php">
								View Detail <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<?php	// code for pending Review 
						$ttl_wos = $dclass->select("COUNT(*) as cnt","tbl_words_on_street"," AND wos_status = '1'") ;
						
						?>
                        <div class="span4 responsive" data-tablet="span6" data-desktop="span4">
							<div class="dashboard-stat yellow">
								<div class="visual">
									<i class="icon-bar-chart"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo $ttl_wos[0]['cnt']; ?></div>
									<div class="desc">Words on Street </div>
								</div>
								<a class="more" href="words_on_street.php">
								View Detail <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
					</div>
					<!-- END DASHBOARD STATS -->
					<div class="clearfix"></div>
                    
                    <div class="row-fluid">
						<?php
						// code for career Enquiry 
						$ttl_feature = $dclass->select("COUNT(*) as cnt","tbl_feature"," AND ftr_status='1'") ;
						?>
                        <div class="span4 responsive" data-tablet="span6" data-desktop="span4">
							<div class="dashboard-stat red">
								<div class="visual">
									<i class="icon-mail-forward"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php echo $ttl_feature[0]['cnt']; ?>
									</div>
									<div class="desc">                           
										Features
									</div>
								</div>
								<a class="more" href="feature.php">
								View Detail <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<?php
						// code for Active Banner
						$ttl_banner = $dclass->select('COUNT(*) as cnt',"tbl_banner"," AND banner_status = '1'"); 
						?>
                        <div class="span4 responsive" data-tablet="span6" data-desktop="span4">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="icon-eye-open"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo $ttl_banner[0]['cnt']; ?></div>
									<div class="desc">Active Banners</div>
								</div>
								<a class="more" href="banner.php">
								View Detail <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						<?php
						$total_faq = $dclass->select("COUNT(*) as cnt","tbl_faq"," AND faq_status = '1'") ;
						?>
                        <div class="span4 responsive" data-tablet="span6" data-desktop="span4">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="icon-globe"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php echo $total_faq[0]['cnt']; ?>
									</div>
									<div class="desc">Faq</div>
								</div>
								<a class="more" href="faq.php">
								View Detail <i class="m-icon-swapright m-icon-white"></i>
								</a>                 
							</div>
						</div>
						
					</div>
					<!-- END DASHBOARD STATS -->
					<div class="clearfix"></div>
					
				</div>
    </div>
    <!-- END PAGE CONTAINER-->
  </div>
  <!-- END PAGE -->
<?php $load->includeother('footer'); ?>  
<!-- BEGIN PAGE LEVEL PLUGINS -->

<script src="assets/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-daterangepicker/date.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>

<script src="assets/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/scripts/app.js" type="text/javascript"></script>
<script src="assets/scripts/index.js" type="text/javascript"></script>
<script src="assets/scripts/tasks.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function() {    
	   App.init(); // initlayout and core plugins
	   Index.init();
	   Index.initCalendar(); // init index page's custom scripts
	   Index.initDashboardDaterange();
	   Index.initIntro();
	   Tasks.initDashboardWidget();
	});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>