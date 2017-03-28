<?php 
	include("config/configuration.php");
	//include('config/guidemsg.php'); 
	$load  =  new loader();	
	$objPages = $load->includeclasses('setting');
	$label = "setting";
	$dclass   =  new  database();
	$gnrl =  new general;

$load->includeother('header');
	// check for login
if(!$gnrl->checkUserLogin()){
	$gnrl->redirectTo("index.php?msg=logfirst");
}
if($gnrl->checkpaymentstatus()){
  $gnrl->redirectTo("view");
}
//$gnrl->checkReminderRun();
function phpdateformat($date){
	global $dclass;
	$dtformat = $dclass->select("*","tbl_dateformate");
	$configformat = str_replace('yyyy','Y',$dtformat[0]['dtformate']);
	$configformat = str_replace('yy','y',$configformat);
	$configformat = str_replace('mm','m',$configformat);
	$configformat = str_replace('dd','d',$configformat);
	return	date($configformat, strtotime($date));
}

$dtformat = $dclass->select("*","tbl_dateformate"); 
?>
<style type="text/css">
	.ui-accordion-content-active{margin: 0 !important}
	.ui-state-default{margin: 0 !important}
</style>
<script type="text/javascript">
	$(document).ready(function() {
			$( "#accordion" ).accordion({ collapsible: true,
        heightStyle: "content"});
			$( "#accordion_sub" ).accordion({ collapsible: true,
        heightStyle: "content"});
	});
</script>
	
	

	<section id="setting-main">
    	<div class="setting-box">
    		<!--div class="alert alert-success hide" id="msgDiv"><button data-dismiss="alert" class="close"></button></div--> 
            <div id="accordion">
				
				<h3>Subscription Plan</h3>
				<?php $getPlandetail = $dclass->select("t1.*,t2.sub_title,t2.sub_price","tbl_user_subscrib_detail t1 LEFT JOIN tbl_subscription_plan t2 ON t1.sub_plan_id = t2.sub_id"," AND t1.user_id=".$_SESSION['company_id']." AND (payment_status='Completed' AND current = 1) order by t1.id desc");
						if( $getPlandetail[0]['subscrib_type'] == 'free'){
							$subtitle = 'Free Trial';
						}else{
							$subtitle = $getPlandetail[0]['sub_title'];
						}

				 ?>
				<div>
				<?php if($_REQUEST['upgrade'] == 'true'){
					echo "<span class='pending_msg'>Payment process will take a while. Please refresh the page after few minutes.</span>";

					}else{
						echo "<span class='pending_msg'>Your payment is still pending. To complete it click on the green subscription button below.</span>";

					} 


					?>
					<?php if($getPlandetail[0]['payment_status'] == '' && $getPlandetail[0]['subscrib_type'] == 'free'){
							$pay_Status = 'Completed';
						}else{
							$pay_Status = $getPlandetail[0]['payment_status'];
						} ?>
					
					<br/>
					<table class="table table-striped table-bordered table-hover" >
				            <thead>
				                <tr>
				                    <th class="center hidden-480 span3">Current Plan </th>
				                    <th class="center hidden-480 span3">Price </th>
				                    <!--th class="center hidden-480 span3">Payment Status</th-->
				                    <th class="center hidden-480 span3">Expires On</th>
				                </tr>
				                  	<tr>
				                  		<td class="center hidden-480 span3"><?php if($subtitle != ''){ echo  $subtitle;}else{ echo 'None';}  ?></td>
				                  		<td class="center hidden-480 span3"> <?php if($getPlandetail[0]['subscrib_price'] != ''){ echo 'USD '.$getPlandetail[0]['subscrib_price']; }else{ echo "None";} ?></td>
				                  		<!--td class="center hidden-480 span3"><?php echo $pay_Status; ?></td-->
				                  		<td class="center hidden-480 span3"><?php if($getPlandetail[0]['expire_date'] != '') {echo phpdateformat($getPlandetail[0]['expire_date']);}else{ echo 'None';} ?></td>
				                    </tr>  
				                </thead>
				                </table>
				                <?php if($_SESSION['user_id'] == $_SESSION['company_id']){


				               if($getPlandetail[0]['subscrib_type'] == 'paid'){
				                 ?>
				                <div class="col-lg-12">
				                    <!--a href="<?php echo SITE_URL; ?>subscriptions?script=upgrade" class="save-btn pull-right" type="button">Change Subscription plan</a-->
				                    <a href="javascript:void(0);" id="changePlanbtn" class="save-btn pull-right" type="button">Change Subscription plan</a>
				                </div>
				                <?php }else{ ?>
				                <div class="col-lg-12">
				                    <a href="<?php echo SITE_URL; ?>subscriptions/<?php echo base64_encode($_SESSION['user_id']); ?>" class="save-btn pull-right" type="button">Subscribe Now</a>
				                    
				                </div>
				                <?php	
				                	}
				                 } ?>
				            <div class="clr"></div>    
				</div>
			</div>
        </div>
        <?php if($_SESSION['user_id'] == $_SESSION['company_id']){
				    if($getPlandetail[0]['subscrib_type'] == 'paid'){
		                if($getPlandetail[0]['payment_status'] != 'Completed'){
		                	$getPrevPlandetail = $dclass->select("t1.*,t2.sub_title,t2.sub_price","tbl_user_subscrib_detail t1 LEFT JOIN tbl_subscription_plan t2 ON t1.sub_plan_id = t2.sub_id"," AND t1.payment_status = 'Completed' AND t1.user_id=".$_SESSION['company_id']."  order by t1.id desc");
		                	if(count($getPrevPlandetail) > 0){
		                		$checkPrise = $getPrevPlandetail[0]['sub_price'];
		                	}else{
		                		$checkPrise = $getPlandetail[0]['sub_price'];
		                	}
		                }else{
		                	$checkPrise = $getPlandetail[0]['sub_price'];
		                }

				                 ?>
        <div class="setting-box change_planclass" style="display:none">
	        <div id="accordion_sub">
					<?php
					$drtype = array(
              'day'=>'Day',
              'week'=>'WK',
              'month'=>'MO',
              'year'=>'YR',
          );
					$sub_plan = $dclass->select("*","tbl_subscription_plan"," AND sub_status = 'active' ORDER BY sub_price asc");
					 ?>  
					<?php foreach($sub_plan as $pl){ ?>
					<h3><?php echo $pl['sub_title']; ?></h3>
					<div>
						
							<ul class="subscription-list">
				            <li class="rs">PRICE $<?php echo $pl['sub_price']; ?>/<?php echo $drtype[$pl['sub_duration_type']]; ?></li>
				            <li>1-<?php echo $pl['sub_available_user']; ?> USERS</li>
				            <li>UP TO <?php echo $pl['sub_available_project']; ?> PROJECTS</li>
				            <li>UNLIMITED TASKS</li>
				            <li>FULL APP FEATURES</li>
				            <?php 
				            if($pl['sub_price'] <= $checkPrise){
				             	$link = 'javascript:void(0)';
				             	$cld = 'grayout'; 
				            }else{
				            	$link = SITE_URL.'upgrades/'.$pl['sub_id'].'/upgrade'; 
				            	$cld = ''; 
				            } ?>
				            <li> <a href="<?php echo $link; ?>" class="save-btn pull-right <?php echo $cld; ?>" type="button">Subscribe Now</a></li>
				        </ul>
				        <div class="clr"></div>	
				   </div> 
					<?php } ?>
			</div>
		</div>
		 <?php
				                	}
				} ?>		
    </section>
      <!-- /MAIN CONTENT -->
<!--main content end-->

<?php $load->includeother('footer');?>


<style type="text/css">
.bootstrap-timepicker-widget{opacity: 1.0 !important;}
#add_new_project .full-box {width: 40%;}	
#add_new_project .tab-right-box .full-box {width: 100%;}
#add_new_project .pro_reminder_box {margin-top: 13px;}
#add_new_project .reminder-box{color: #383838}
.setting-box {
    margin: 0 auto;
    width: 85%;
}
.pending_msg {
    color: red;
    margin-top: -7px;
    position: absolute;
}
</style>
<script type="text/javascript" src="js_ui/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js_ui/jquery.blockUI.js"></script>
<style>
.bootstrap-timepicker-widget{z-index:1151 !important;}
</style>
<script>

    	$(document).ready(function() {
			//TableManaged.init();
			//$( "#accordion" ).accordion();
			$('#accordion').on('click','#changePlanbtn', function(){
				$(".change_planclass").toggle();
			});
			$('#accordion').on('click','#undusubscripttion', function(){
					
					var subscribe_id = $(this).attr('subscribe_id');
					var tsk = 'undu_subscription';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:{tsk:tsk,subscribe_id:subscribe_id},
						success:function(data){							
							window.location.reload();
						}
					 });
			});

		});
    </script> 
