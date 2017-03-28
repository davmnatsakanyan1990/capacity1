<?php 
// Configration  
	include("config/configuration.php");
	//include('config/guidemsg.php'); 
	$load  =  new loader();	
	$objPages = $load->includeclasses('setting');
	$label = "setting";
	$dclass   =  new  database();
	$gnrl =  new general;

$load->includeother('header');
if(!$gnrl->checkUserLogin()){
	$gnrl->redirectTo("index.php?msg=logfirst");
}
// uncomment after client confirmation
if(!$gnrl->checkpaymentstatus()){
  $gnrl->redirectTo("plan");
}

if($_SESSION['user_type'] == 'employee'){
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
            <div id="accordion">
				<h3 <?php echo $step_11_admin; ?><?php echo $step_11_manager; ?>>Timings</h3>
				<div>
				<?php 
					$getData = $dclass->select(' * ',' tbl_working_day_time',"  AND company_user_id =".$_SESSION['company_id']);
					$worktime = explode(':::',$getData[0]['working_time']); 
					if(count($getData) > 0){
						$workday = explode(':::',$getData[0]['working_days']); 
					}else{
						$workday = array(1,2,3,4,5);
					}
				?>
					<form name="work_daytime" id="work_daytime" method="post" action="" enctype="multipart/form-data">
					<div class="Timing-box work_daytime_div">
					<div class="choose-working">
				    	<div class="tab-title">Choose Working Time</div>
				        <div class="full-box">
				        <?php
				        	if(count($getData) > 0){
				        		$sw_tm = $worktime[0];
				        		$ew_tm = $worktime[1];
				        	}else{
				        		$sw_tm = '9:00 AM';
				        		$ew_tm = '5:00 PM';
				        	} ?>
				             <table><tr><td>
                  				<input type="text" value="<?php echo $sw_tm; ?>" class="input-small timepicker validate[required] worktime" name="working_start_time" id="working_start_time"  placeholder="Time">
                  			</td><td>
                    			<label>to</label>
          
                    			<input type="text" value="<?php echo $ew_tm; ?>" class="input-small timepicker validate[required] worktime" name="working_end_time" id="working_end_time"  placeholder="Time">
		                    </td></tr></table>
				        </div>
				    </div>
				    <div class="choose-working-day">
				    	<div class="tab-title">Choose Working Days</div>
				        <?php $day = array(
                                			'0'=>'SUN',
                                			'1'=>'MON',
                                			'2'=>'TUE',
                                			'3'=>'WED',
                                			'4'=>'THU',
                                			'5'=>'FRI',
                                			'6'=>'SAT',
                                			); ?>
				        <ul>
				        	<?php foreach($day as $key=>$val){ 
				        		
				        		if(in_array($key,$workday)){
	                              		$checked = 'checked="checked"';
	                              	}else{
	                              		$checked = '';
	                              	}

				        		?>
	                            <li>
				            		<?php echo $val; ?>
						            <div class="checkbox">
						                    <input id="chk_<?php echo $val; ?>" type="checkbox" <?php echo $checked; ?> name="chk_day[]" value="<?php echo $key; ?>">
						               		<label for="chk_<?php echo $val; ?>"></label>
						                </div>    
						            </li>
	                                 <?php } ?>	
				        </ul>
				    </div>
				    <div class="clr"></div>
				    <div class="button-box">
				        <button class="save-btn" id="work_daytime_save" type="button">Save Changes</button>
				    </div>
				</div>
				<div class="clr"></div>
				</form>
				</div>
				
				<h3 <?php echo $step_12_manager; ?><?php echo $step_13_admin; ?>>Holidays</h3>
				<div>
				<div class="Holiday-box" >
					<div class="Holiday-add-box">
				        <button class="save-btn" id="add_holiday_btn" type="button">+ &nbsp;ADD Holiday</button>
						<button class="delete-btn" id="del_holiday_btn" type="button">X &nbsp;Delete</button>
				    </div>
				    <div class="clr20"></div>
				    <form name="holiday" id="holiday" method="post" action="" enctype="multipart/form-data">
				    	<div  id="add_new_vacation" style="display:none">
						
						    <div class="holiday-title">
						    	<label class="input-label">Holiday Title</label>
						    	<input type="text" name="holi_title" id="holi_title" class="input-box validate[required]">
						    </div>
						    <div class="holiday-date">
						    	<label class="input-label">Start Date</label>
						        <input type="text" value="" class="input-small validate[required]" name="holi_start_date" id="holi_start_date" placeholder="">
						    </div>
						    <div class="holiday-to">to</div>
						    <div class="holiday-date">
						    	<label class="input-label">End Date</label>
						        <input type="text" value="" name="holi_end_date" id="holi_end_date" class="input-small validate[required]" placeholder="">
						    </div>
						    <div class="button-box">
						        <a href="javascript:void(0)" id="holiday_save" class="save-btn">SAVE CHANGES</a>
						        <a href="javascript:void(0)" id="holiday_cancel" class="save-btn">Cancel</a>
						    </div>

						</div>
						<div class="clr20"></div>
						<div class="clr20"></div>
						<div class="clr5"></div>	
				    </form>
				    
				    <div class="clr"></div>
				    <?php $getHoliday = $dclass->select("*","tbl_holidays"," AND holi_user_id = '".$_SESSION['company_id']."' OR holi_type = 'general' ORDER BY holi_id desc"); ?>
					<form name="holiday_listing" id="holiday_listing" method="post" action="" enctype="multipart/form-data"> 
						<table id="sample_1_demo" class="table table-striped table-hover" cellspacing="0" width="100%">
							<thead>
							    <tr>
							        <th>
							            <div class="checkbox">
						                    <input id="chk_all" type="checkbox" data-set="#sample_1_demo" class="holiday-checkall" name="chkall[]">
						               		<label for="chk_all"></label>
						                </div> 
						            </th>
					                <th>Title</th>
					                <th>Start Date</th>
					                <th>End Date</th>
					                <th>Action</th>
							    </tr>
							</thead>
							<tbody>
							    <?php 
							    
							    foreach($getHoliday as $rows){ 
							    	
						        	//$start_date = date("y-m-d", strtotime($rows['holi_start_date']));
						        	$start_date = phpdateformat($rows['holi_start_date']);
						        	$end_date = phpdateformat($rows['holi_end_date']);
							    ?>
							    <tr>
							        <td class="hidden-480">
							            <?php if($rows['holi_type'] != 'general'){ ?>
					                    <div class="checkbox">
						                    <input id="chk_<?php echo $rows['holi_id']; ?>" type="checkbox" class="checkboxes_holi" name="chk[]" value="<?php echo $rows['holi_id']; ?>">
						               		<label for="chk_<?php echo $rows['holi_id']; ?>"></label>
						                </div>
							                	
							            <?php } ?>		
							        </td>
				                    <td class="hidden-480"><?php echo $rows['holi_title']; ?></td>
				                    <td class="hidden-480"><?php echo $start_date; ?></td>
				                    <td class="hidden-480"><?php echo $end_date; ?></td>
				                    <td class="hidden-480">
					                  <?php if($rows['holi_type'] != 'general'){ ?> 		
					                   <span> <a holi_id = '<?php echo $rows['holi_id'] ?>'  class="holiday_edit_btn" href="javascript:void(0);" ><img src="<?php echo SITE_URL; ?>images/edit-icon.png" alt="Edit"></a></span>
					                   <span> <a holi_id = '<?php echo $rows['holi_id'] ?>'  class="holiday_delete_btn" href="javascript:void(0);" ><img src="<?php echo SITE_URL; ?>images/delete-icon.png" alt="Edit"></a></span>
					                  <?php } ?>  
					                </td>
								</tr>
							    <?php } ?>
							            
							</tbody>
						</table>
					</form>

				</div>
				<div class="clr"></div>
</div>
				
				<h3 <?php echo $step_13_manager; ?><?php echo $step_14_admin; ?>>Lunch Hours</h3>
				<div>
					<?php 
						//$getlunchhr = $dclass->select("*","tbl_lunch_hours"," AND company_user_id = '".$_SESSION['company_id']."' ");
						//$lunchhour = explode(':::', $getlunchhr[0]['lunch_hours']);
					?>
				<div class="Lunch-box">
					<form name="lunch_hours" id="lunch_hours" method="post" action="" enctype="multipart/form-data">
						<?php $teamList = $dclass->select("*","tbl_team"," AND company_user_id = ".$_SESSION['company_id']);?> 
					<div class="choose-time">
				    	<div class="tab-title">Choose Team</div>
				        <div class="form-group">
				            <select name="lunch_team_id" id="lunch_team_id" class="selectpicker validate[required]" data-live-search="true" title="Please select a lunch ...">
				                <option value="">Choose Team</option>
								<?php foreach($teamList as $tm){ ?>
                                <option value="<?php echo $tm['tm_id']; ?>" ><?php echo $tm['tm_title']; ?></option>
                                <?php } ?>
				            </select>
				        </div>
				    </div>
				    <div class="clr"></div>
						<div class="lunchhours_detail">
							<div class="choose-working">
						    	<div class="tab-title">Choose Lunch Hours</div>
						        <div class="full-box">
						        	<?php if(count($lunchhour) > 0){ ?>
	                               		<input type="hidden" name="lunch_id" id="lunch_id" value="<?php echo $getlunchhr[0]['id'] ?>" >
	                               	<?php } ?>
						        	<table><tr><td>
						        	<?php if(isset($lunchhour[0]) && $lunchhour[0] != ''){
						        				$st_time = $lunchhour[0];
						        			}else{
						        				$st_time = '12:00 PM';
						        			}

						        			if(isset($lunchhour[1]) && $lunchhour[1] != ''){
						        				$ed_time = $lunchhour[1];
						        			}else{
						        				$ed_time = '1:00 PM';
						        			} ?>
						        	<input type="text" name="lunch_start_time" id="lunch_start_time" value="<?php echo $st_time; ?>" value="" class="input-small validate[required]" placeholder="Time">
						            </td><td>
						            <label>to</label>
						            <input type="text" name="lunch_end_time" id="lunch_end_time" value="<?php echo $ed_time; ?>" value="" class="input-small validate[required]" placeholder="Time">
						            </td></tr></table>
						        </div>
						    </div>
						    <div class="clr20"></div>
						    <div class="clr10"></div>
						    <div class="Lunch-check-box">
						    	<div class="checkbox">
						            <?php if($getlunchhr[0]['show_in_calender'] == 'yes'){
	                             		$chk = 'checked="checked"';
	                             	}else{
	                             		$chk = '';
	                             		} ?>
	                                <input type="checkbox" <?php echo $chk; ?> id="show_in_calender" name="show_in_calender" value="yes">
						            <label for="show_in_calender">Show Lunch Hours in Calendar.</label>
						        </div>
						    </div>
					    </div>
					    
					    <div class="button-box">
					        <a href="javascript:void(0)" id="lunchhour_save" class="save-btn">Save Changes</a>
					    </div>
				    </form>
				</div>
				<div class="clr"></div>
</div>

				<h3 <?php echo $step_12_admin; ?>>Company Permissions</h3>
				<div>
					<form name="permission" id="permission" method="post"  enctype="multipart/form-data">
                				<?php $tasklist = array(
			  						"1" => 'TASK_ADD',
									"2" => 'TASK_UPDATE',
									"3" => 'TASK_DELETE',
									"4" => 'TASK_VIEW',
									"5" => 'USER_ADD',
									"6" => 'USER_UPDATE',
									"7" => 'USER_DELETE',
									"8" => 'USER_VIEW',
			  						); 
									
									$role = $dclass->select("*","tbl_role","AND r_id NOT IN ('1','2') AND r_status='active'");	
				                $empnot = array('1','2','3','4');?>
				                <table class="table table-striped table-bordered table-hover" >
				                	<thead>
				                  <tr>
				                    <th class="Left  col-lg-3">Permission Name </th>
				                    <?php foreach($role as $rl){ ?>
				                  			<th class="center col-lg-1"><?php echo $rl['r_title'].'s'; ?></th>
				                    <?php } ?>        
				                    
				                  </tr>

				                  <?php 

				                $chkperSetvl = $dclass->select("*","tbl_role_access"," AND company_user_id = '".$_SESSION['company_id']."'");

				                if(count($chkperSetvl) > 0){
				                  foreach($tasklist as $tkey=>$tval){ ?>
				                  		<tr>
				                    		<td class="left col-lg-3"><?php echo ucfirst(strtolower(str_replace('_',' ',$tval))); ?></td>
				                        	<?php foreach($role as $rl){ 
											    
											    $check_peram = $dclass->select("*","tbl_role_access"," AND company_user_id = '".$_SESSION['company_id']."' AND r_id='".$rl['r_id']."' AND v_name = '".$tval."'");
												if(count($check_peram) > 0 && $check_peram[0]['l_value'] == 'yes' ){
													$checked = 'checked="checked"';
												}else{
													$checked = '';
												}
												if($rl['r_id'] == '4' && in_array($tkey, $empnot) ){
													$dis = 'disabled="disabled"';
												}else{
													$dis = '';
												}
											?>


				                  			<td class="center col-lg-1">
				                  			<div class="checkbox">
								                    <input id="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" <?php echo $checked; ?> <?php echo $dis; ?> type="checkbox" class="group-checkable" name="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" value="yes">
								               		<label for="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]"></label>
								            </div>

				                  			<!--input type="checkbox" <?php echo $checked; ?> id="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" name="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" value="yes" class="group-checkable form-control"-->
				                  			</td>
								  		<?php  } ?>
				                          </tr>  
								  <?php }
								}else{
								  foreach($tasklist as $tkey=>$tval){ ?>
				                  		<tr>
				                    		<td class="left col-lg-3"><?php echo ucfirst(strtolower(str_replace('_',' ',$tval))); ?></td>
				                        	<?php foreach($role as $rl){ 
											    
											    $check_peram = $dclass->select("*","tbl_role_access"," AND company_user_id = '0' AND r_id='".$rl['r_id']."' AND v_name = '".$tval."'");
												if(count($check_peram) > 0 && $check_peram[0]['l_value'] == 'yes' ){
													$checked = 'checked="checked"';
												}else{
													$checked = '';
												}
											?>


				                  			<td class="center col-lg-1">
				                  			<div class="checkbox">
								                    <input id="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" <?php echo $checked; ?> type="checkbox" class="group-checkable" name="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" value="yes">
								               		<label for="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]"></label>
								            </div>

				                  			<!--input type="checkbox" <?php echo $checked; ?> id="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" name="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" value="yes" class="group-checkable form-control"-->
				                  			</td>
								  		<?php  } ?>
				                          </tr>  
								  <?php }	
								}


								   ?>
				                  
				                </thead>
				                </table>
                
				                <div class="col-lg-12">
				                     <a href="javascript:void(0)" id="save_permission" class="save-btn pull-right">Save Changes</a>
				                </div>
				                <div class="clr"></div>
				            </form>
						</div>

				<h3>Account Admin Settings</h3>
				<div>
				<?php $getUserDetail = $dclass->select("*","tbl_user"," AND user_id=".$_SESSION['user_id']); ?>
				<form name="profile" id="profile" method="post" action="" enctype="multipart/form-data">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
					<input type="hidden" name="tsk" id="tsk" value="save_profile">
					<div class="Accoutn-setting">
				        <div class="Accoutn-box">
				          <div class="Accoutn-input-box">
				            <label class="Accoutn-label">Profile Picture</label>
				            <input type="file" name="user_avatar" id="user_avatar" class="filestyle" data-icon="false">
				            <?php if($getUserDetail[0]['user_avatar'] != ''){ 
				            	$user_image = SITE_URL.'upload/user/'.$getUserDetail[0]['user_avatar'];

				            	?>
                                <div class="Accoutn-pic">
                                <img src="<?php echo SITE_URL.'timthumb.php?src='.$user_image.'&h=46&w=46&zc=1&q=100' ?>"  />	
                                </div>
                            <?php } ?>
				          </div>
				          <div class="Accoutn-input-box">
				            <label class="Accoutn-label">First Name</label>
				            <input type="text" name="fname" id="fname" value="<?php echo $getUserDetail[0]['fname']; ?>" class="input-box validate[required]">
				          </div>
				          <div class="Accoutn-input-box">
				            <label class="Accoutn-label">Last Name</label>
				            <input type="text" name="lname" id="lname" value="<?php echo $getUserDetail[0]['lname']; ?>"  class="input-box validate[required]">
				          </div>
				          <?php if($_SESSION['user_type'] == 'company_admin'){ ?>
				         <div class="Accoutn-input-box">
				            <label class="Accoutn-label">Company Name</label>
				            <input type="text" name="company_name" id="company_name" value="<?php echo $getUserDetail[0]['company_name']; ?>" class="input-box validate[required]">
				         </div>
				        <?php } ?>
				          <div class="Accoutn-input-box">
				            <label class="Accoutn-label">Email Address</label>
				            <input type="text" name="email" id="email" value="<?php echo $getUserDetail[0]['email']; ?>" class="input-box validate[required,custom[email]]">
				            <a href="#" id="change_pwd_link"> Change Password</a> 
				          </div>
				       
				        

				        </div>
				        
				        <div class="button-box"> 
				        	
				        	<!--a href="javascript:void(0)" id="save_profile" class="save-btn">Save Changes</a-->
				        <button class="save-btn" id="save_profile" type="submit">Save Changes</button> 
				        </div>
				      </div>
				</form>      
				  <div class="clr"></div>
				</div>

				<h3>Display User Guides</h3>
				<div>
				<?php $getUserDetail = $dclass->select("*","tbl_user"," AND user_id=".$_SESSION['user_id']); ?>
				
					<div class="Accoutn-setting">
				        <?php $guide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id =".$_SESSION['user_id']);
				        		if(count($guide) > 0 && $guide[0]['status'] == 'on'){
				        			$checkedyes = 'checked="checked"';
				        			$checkedno = '';
				        		}else{
				        			$checkedno = 'checked="checked"';
				        			$checkedyes = '';
				        		}
				         ?>
				        <form name="user_gd" id="user_gd" method="post" action="" enctype="multipart/form-data">
				        <div class="Accoutn-input-box">
				            <label class="radio-top-lable">Show User Guide Prompt</label>
				            <div class="radio-btn">
    								<input type="radio" id="yes" name="user_guide" <?php echo $checkedyes; ?> value="yes">
    								<label for="yes">Yes</label>
    						</div>
    						<div class="radio-btn">
    								<input type="radio" id="no" name="user_guide" <?php echo $checkedno; ?> value="no">
    								<label for="no">No</label>
    						</div>
    					</div>
						</form>
				        <div class="button-box"> 
					    	<button class="save-btn" id="save_userguide" type="submit">Save Changes</button> 
				        </div>
				      </div>
				
				  <div class="clr"></div>
				</div>

				<h3>Team</h3>
				<div>
				<div class="Holiday-box">
					<div class="Holiday-add-box">
				        <button class="save-btn" id="add_team_btn" type="button">+ &nbsp;ADD Team</button>
						<button class="delete-btn" id="del_team_btn" type="button">X &nbsp;Delete</button>
				    </div>
				    <div class="clr20"></div>
				    <form name="team" id="team" method="post" action="" enctype="multipart/form-data">
				    	<div  id="add_new_team" style="display:none">
						<?php 
						 if($_SESSION['user_type'] == 'company_admin'){
                           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']." OR user_id = '".$_SESSION['user_id']."' "); 
                         }else{
                           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
                         } ?>
						    <div class="holiday-title">
						    	<label class="input-label">Team Title</label>
						    	<input type="text" name="holi_title" id="holi_title" class="input-box validate[required]">
						    </div>
						    <div class="holiday-date">
						    	<label class="input-label">Select User</label>
						          <div class="tab-left-box">
                                <select id="userids_setting" name="user_id[]" class="validate[required]"  multiple>
                                <?php foreach($userlist as $ui){ ?>
                                <option value="<?php echo $ui['user_id']; ?>"> <?php echo $ui['fname'].' '.$ui['lname']; ?></option>
                                <?php } ?>
                                </select>
                                </div>
						    </div>
						    
						    
						    <div class="button-box">
						        <a href="javascript:void(0)" id="team_save" class="save-btn">SAVE CHANGES</a>
						        <a href="javascript:void(0)" id="team_cancel" class="save-btn">Cancel</a>
						    </div>

						</div>
						<div class="clr20"></div>
						<div class="clr20"></div>
						<div class="clr5"></div>	
				    </form>
				    
				    <div class="clr"></div>
				    <?php $getTeam = $dclass->select("*","tbl_team"," AND company_user_id = '".$_SESSION['company_id']."' ORDER BY tm_id desc"); ?>
					<form name="team_listing" id="team_listing" method="post" action="" enctype="multipart/form-data"> 
						<table id="sample_1_demo_1" class="table table-striped table-hover" cellspacing="0" width="100%">
							<thead>
							    <tr>
							        <th>
							            <div class="checkbox">
						                    <input id="chk_all_team" type="checkbox" data-set="#sample_1_demo_1" class="team-checkall" name="chkall_team[]">
						               		<label for="chk_all_team"></label>
						                </div> 
						            </th>
					                <th>Title</th>
					                <th>Action</th>
							    </tr>
							</thead>
							<tbody>
							    <?php foreach($getTeam as $rows){ ?>
							    <tr>
							        <td class="hidden-480">
							           
					                    <div class="checkbox">
						                    <input id="chk_team_<?php echo $rows['tm_id']; ?>" type="checkbox" class="checkboxes_team" name="chk_team[]" value="<?php echo $rows['tm_id']; ?>">
						               		<label for="chk_team_<?php echo $rows['tm_id']; ?>"></label>
						                </div>
							        </td>
				                    <td class="hidden-480"><?php echo $rows['tm_title']; ?></td>
				                    <td class="hidden-480">
					                  
					                   <span> <a tm_id = '<?php echo $rows['tm_id'] ?>'  class="team_edit_btn" href="javascript:void(0);" ><img src="<?php echo SITE_URL; ?>images/edit-icon.png" alt="Edit"></a></span>
					                   <span> <a tm_id = '<?php echo $rows['tm_id'] ?>'  class="team_delete_btn" href="javascript:void(0);" ><img src="<?php echo SITE_URL; ?>images/delete-icon.png" alt="Edit"></a></span>
					                  
					                </td>
								</tr>
							    <?php } ?>
							            
							</tbody>
						</table>
					</form>

				</div>
				<div class="clr"></div>
</div>

				<h3>Project</h3>
				<div>
				<div class="Holiday-box">
					<div class="Holiday-add-box">
					<?php if( $gnrl->checkmaxallowproject() > $gnrl->gettotalcompanyproject() || $gnrl->checkmaxallowproject() == ''){ ?>
                        <button class="save-btn" id="add_project_btn" type="button">+ &nbsp;ADD Project</button>
                     <?php }else{ ?>
                     <p>You have now reached the maximum number of <?php echo $gnrl->gettotalcompanyproject(); ?> projects. To add any new projects you must delete one or more old projects first.</p>
				     <?php } ?>   
						<button class="delete-btn" id="del_project_btn" type="button">X &nbsp;Delete</button>
				    </div>
				    <div class="clr20"></div>
				    <form name="project" id="project" method="post" action="" enctype="multipart/form-data">
				    	<div  id="add_new_project" style="display:none"></div>
					</form>
				    
				    <div class="clr"></div>
				    <?php $getProject = $dclass->select("t1.*,t2.cl_company_name","tbl_project t1 LEFT JOIN  tbl_client t2 ON t1.pr_cl_id = t2.cl_id"," AND t1.pr_company_id = '".$_SESSION['company_id']."' ORDER BY t1.pr_id desc"); ?>
					<form name="project_listing" id="project_listing" method="post" action="" enctype="multipart/form-data"> 
						<table id="sample_1_demo_2" class="table table-striped table-hover" cellspacing="0" width="100%">
							<thead>
							    <tr>
							        <th>
							            <div class="checkbox">
						                    <input id="chk_all_project" type="checkbox" data-set="#sample_1_demo_2" class="project-checkall" name="chkall_project[]">
						               		<label for="chk_all_project"></label>
						                </div> 
						            </th>
					                <th >Title</th>
					                <th>Company</th>
					                <th>Start date</th>
					                <th>End date</th>
					                <th>Action</th>
							    </tr>
							</thead>
							<tbody>
							    <?php foreach($getProject as $rows){
							    	if( $rows['pr_start_datetime'] == '1970-01-01 00:00:00' || $rows['pr_start_datetime'] == '1969-12-31 00:00:00') { 
							     		$prstdt = '';
							     	}else{ 
							     		$prstdt = phpdateformat($rows['pr_start_datetime']);
							     	}
							     	if( $rows['pr_end_datetime'] == '1970-01-01 00:00:00' || $rows['pr_end_datetime'] == '1969-12-31 00:00:00') { 
							     		$preddt = '';
							     	}else{ 
							     		$preddt = phpdateformat($rows['pr_end_datetime']);
							     	}
							     ?>
							    <tr>
							        <td class="hidden-480">
							           
					                    <div class="checkbox">
						                    <input id="chk_project_<?php echo $rows['pr_id']; ?>" type="checkbox" class="checkboxes_project" name="chk_project[]" value="<?php echo $rows['pr_id']; ?>">
						               		<label for="chk_project_<?php echo $rows['pr_id']; ?>"></label>
						                </div>
							        </td>
				                    <td class="hidden-480" width="36%" style="padding: 10px 0px 10px 10px !important;" title='<?php echo $rows['pr_title']; ?>'><?php echo $gnrl->trunc_string($rows['pr_title'],70); ?></td>
				                    <td class="hidden-480"><?php echo $gnrl->trunc_string($rows['cl_company_name'],20); ?></td>
				                    <td class="hidden-480"><?php echo $prstdt; ?></td>
				                    <td class="hidden-480"><?php echo $preddt; ?></td>
				                    <td class="hidden-480">
					                  
					                   <span> <a pr_id = '<?php echo $rows['pr_id'] ?>'  class="project_edit_btn" href="javascript:void(0);" ><img src="<?php echo SITE_URL; ?>images/edit-icon.png" alt="Edit"></a></span>
					                   <span> <a pr_id = '<?php echo $rows['pr_id'] ?>'  class="project_delete_btn" href="javascript:void(0);" ><img src="<?php echo SITE_URL; ?>images/delete-icon.png" alt="Edit"></a></span>
					                  
					                </td>
								</tr>
							    <?php } ?>
							            
							</tbody>
						</table>
					</form>

				</div>
				<div class="clr"></div>
</div>
			
				<h3 <?php echo $step_15_admin; ?>>Subscription Plan</h3>
				<div>
				<?php $getPlandetail = $dclass->select("t1.*,t2.sub_title,t2.sub_price","tbl_user_subscrib_detail t1 LEFT JOIN tbl_subscription_plan t2 ON t1.sub_plan_id = t2.sub_id"," AND t1.user_id=".$_SESSION['company_id']." AND (payment_status='Completed' AND current=1) order by t1.id desc limit 0,1");
						if( $getPlandetail[0]['subscrib_type'] == 'free'){
							$subtitle = 'Free Trial';
						}else{
							$subtitle = $getPlandetail[0]['sub_title'];
						}
				 ?>
				
					<table class="table table-striped table-bordered table-hover" >
				            <thead>
				                <tr>
				                    <th class="center hidden-480 span3">Current Plan </th>
				                    <th class="center hidden-480 span3">Price </th>
				                    <th class="center hidden-480 span3">Expires On</th>
				                </tr>
				                  	<tr>
				                  		<td class="center hidden-480 span3"><?php echo $subtitle; ?></td>
				                  		<td class="center hidden-480 span3">USD <?php echo $getPlandetail[0]['subscrib_price']; ?></td>
				                  		<td class="center hidden-480 span3"><?php echo phpdateformat($getPlandetail[0]['expire_date']); ?></td>
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
				<h3>Payments</h3>
				<?php

				$payments = $dclass->select("*", "tbl_user_subscrib_detail", " AND user_id =".$_SESSION['user_id']." AND subscrib_type='paid'");
				

				?>
				<div>
					<table class="table table-striped table-bordered table-hover" >
						<thead>
						<tr>
							<th class="center hidden-480 span3">Transaction ID</th>
							<th class="center hidden-480 span3">Date </th>
							<th class="center hidden-480 span3">Amount</th>
							<th class="center hidden-480 span3">Payment Status</th>
							<th class="center hidden-480 span3">Comment</th>
						</tr>
						<?php
						foreach ($payments as $payment){ ?>
						<tr>
							<td class="center hidden-480 span3"></td>
							<td class="center hidden-480 span3"><?php echo $payment['subscrib_date'] ?></td>
							<td class="center hidden-480 span3">USD <?php echo $payment['subscrib_price'] ?></td>
							<td class="center hidden-480 span3"><?php echo $payment['payment_status'] ?></td>
							<td class="center hidden-480 span3"><a href="extract/<?php echo $payment['id']; ?>"><button class="btn btn-xs export"><i class="fa fa-download" aria-hidden="true"></i> Export</button></a></td>
						</tr>
						<?php } ?>
						</thead>
					</table>
				</div>
			</div>
        </div>
        <?php if($_SESSION['user_id'] == $_SESSION['company_id']){
				    if($getPlandetail[0]['subscrib_type'] == 'paid'){
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
				            
				            if($pl['sub_price'] <= $getPlandetail[0]['sub_price']){
				             	$link = 'javascript:void(0)';
				             	$cld = 'grayout'; 
				             	?>
				             	 <li> <a href="<?php echo $link; ?>" class="save-btn pull-right <?php echo $cld; ?>" type="button">Subscribe Now</a></li>
				             	<?php
				            }else{
				            	$link = SITE_URL.'upgrades/'.$pl['sub_id'].'/upgrade'; 
				            	$cld = ''; 
				            	?>
				            	 <li><a href="<?php echo $link; ?>"  onclick="return confirm('Are you sure you want upgrade your plan')" class="save-btn pull-right <?php echo $cld; ?>" type="button">Subscribe Now</a></li>
				        <?php } ?>
				           
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

<div class="modal bs-example-modal-lg" id="change_password_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog task-detail">
    <div class="modal-content user-box">
        <form name="change_password" id="change_password" method="post" action="" enctype="multipart/form-data">
            <div class="task-title">Change Password</div>
            <div class="task-form">
                <div class="task-form-left">    
                    <div class="tab-title">Old Password</div>
                    <div class="full-box">
                            <input type="password" value="" class="input-box validate[required]" name="old_pwd" id="old_pwd"  placeholder="Current Password">
                    </div>
                </div>
                <div class="clr"></div>
                <div class="task-form-left">
                    <div class="tab-title">New Password</div>
                    <div class="full-box">
                            <input type="password" value="" class="input-box validate[required]" name="new_pwd" id="new_pwd"  placeholder="New Password">
                    </div>
                </div>
                <div class="clr"></div>
                <div class="task-form-left">
                    <div class="tab-title">Confirm Password</div>
                    <div class="full-box">
                            <input type="password" value="" class="input-box validate[required,equals[new_pwd]]" name="confirm_pwd" id="confirm_pwd"  placeholder="Confirm Password">
                    </div>
                </div>
                <div class="clr"></div>
                <div class="button-box">
                    <button type="button" id="save_pwd" class="save-btn">Change Password</button>
                    <button type="button" id="cancel" class="delete-btn">Cancel</button>
                <div class="clr"></div>
                </div>
        </form>
            
        </div>
            <div class="clr"></div>
     </div>
    </div>
</div>
<?php $load->includeother('footer');?>


<style type="text/css">
.bootstrap-timepicker-widget{opacity: 1.0 !important;}
#add_new_project .full-box {width: 40%;}	
#add_new_project .tab-right-box .full-box {width: 100%;}
#add_new_project .pro_reminder_box {margin-top: 13px;}
#add_new_project .reminder-box{color: #383838}
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
			
			var showproject = "<?php echo $_SESSION['showproject']; ?>";
			if(showproject=='yes')
			{

				var collapseItem = localStorage.getItem('collapseItem'); 
			    if (collapseItem) {
			       $('#accordion').find(collapseItem).trigger('click');
			    }
			}
			$('#accordion').on('click','#changePlanbtn', function(){
				$(".change_planclass").toggle();
			});

			
			var startDate = new Date('01/01/2012');
			var FromEndDate = new Date();
			var ToEndDate = new Date();
			ToEndDate.setDate(ToEndDate.getDate()+365);

			$('#timepicker1, #timepicker2').timepicker({
				minuteStep: 5,
				showInputs: false,
				disableFocus: true
			});
			
			$('#sample_1_demo').DataTable({
	          "bLengthChange": false,
	          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
			});
			$('#sample_1_demo_1').DataTable({
	          "bLengthChange": false,
	          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
			});

			$('#sample_1_demo_2').DataTable({
	          "bLengthChange": false,
	          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
			});
			
			$('#working_start_time').timepicker({
				minuteStep: 30,
				showInputs: false,
				disableFocus: true,
				modalBackdrop: true,

			});
			$('#working_end_time').timepicker({
				minuteStep: 30,
				showInputs: false,
				disableFocus: true,
				modalBackdrop: true
			});
			$('#lunch_start_time, #lunch_end_time').timepicker({
				minuteStep: 30,
				showInputs: false,
				disableFocus: true,
				modalBackdrop: true,
			});
    
		    $(document).on('click','.ttable', function(){
			$('.bootstrap-timepicker-widget').addClass("other_class");
		});

			// form validate start
			$('#work_daytime').validationEngine({validateNonVisibleFields: true,scroll: false});
			$('#holiday').validationEngine({validateNonVisibleFields: true,scroll: false});
			$('#lunch_hours').validationEngine({validateNonVisibleFields: true,scroll: false});
			$('#profile').validationEngine({validateNonVisibleFields: true,scroll: false});
			// save work daytime 
			$('#accordion').on('click','#work_daytime_save', function(){
				
				var validateWork = $('#work_daytime').validationEngine('validate');	
				if(validateWork  ==  true  ||   validateWork  ==  'true' ||  validateWork ==  'TRUE'){

					var start_date = new Date('2015/05/15 '+$("#working_start_time").val());
					var end_date = new Date('2015/05/15 '+$("#working_end_time").val());
					
					if(new Date(start_date) <= new Date(end_date)){

						var tsk = 'work_daytime_save';
						$.ajax({
							type:"post",
							url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
							data:$("#work_daytime").serialize()+'&tsk='+tsk,
							beforeSend: function() {
						        $('#ui-id-2').block({ 
						               //message: '<img src="<?php echo SITE_URL; ?>/images/loader/loading.gif" width="100px"  />',  
						               message: '<div id="loaderImage" style="margin:-9% 40%"></div>',
						        });
						        new imageLoader(cImageSrc, 'startAnimation()');
						    },
							success:function(data){							
								 
								if($.trim(data) == 'add' ){	
									$(".msgDiv").removeClass("hide");	
									$(".msgDiv").html("Work hours updated Successfully.");	
								}else if($.trim(data) == 'edit' ){
									$(".msgDiv").removeClass("hide");	
									$(".msgDiv").html("Work hours edited Successfully.");	
								}else{
									$(".msgDiv").removeClass("hide");	
									$(".msgDiv").html("Please Try Again.");	
								}
								stopAnimation();
								$('#ui-id-2').unblock();
								 
								//setInterval(function(){ window.location.reload(); },2000);
							}
						 });
					}else{
						$("#working_end_time").val('');
						$('#working_end_time').validationEngine('showPrompt', 'End time should be greater then start time.', 'fail');
					}
				}
			});
			//get html on change of team
			$('#accordion').on('change','#team_id', function(){
					
					var team_id = $(this).val();
					var tsk = 'work_daytime_get_data';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:{tsk:tsk,team_id:team_id},
						success:function(data){							
							$(".work_daytime_div").removeClass("hide");	
							$(".work_daytime_div").html(data);
							$('.selectpicker').selectpicker();
							$('#working_start_time, #working_end_time').timepicker({
									minuteStep: 30,
									showInputs: false,
									disableFocus: true
							});	

							$('#work_daytime').validationEngine({validateNonVisibleFields: true,scroll: false});	
						}
					 });
			});
			// get team wise lunch hours
			$('#accordion').on('change','#lunch_team_id', function(){
					
					var team_id = $(this).val();
					var tsk = 'get_lunch_hours';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:{tsk:tsk,team_id:team_id},
						success:function(data){							
							$(".lunchhours_detail").removeClass("hide");	
							$(".lunchhours_detail").html(data);
							
							$('#lunch_start_time, #lunch_end_time').timepicker({
									minuteStep: 30,
									showInputs: false,
									disableFocus: true
							});	

							$('#lunch_hours').validationEngine({validateNonVisibleFields: true,scroll: false});	
						}
					 });
			});
			//get blank form of new holiday
		$('#accordion').on('click','#add_holiday_btn', function(){
				var tsk = 'add_new_holiday';
				$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:{tsk:tsk},
						success:function(data){							
							$("#add_new_vacation").show();
							$("#add_new_vacation").html(data);
                                                        $('#holi_title').focus();
							$('#holiday').validationEngine();
							$('#holi_start_date').datepicker({
				                      weekStart: 1,
				                      startDate: '01/01/2012',
				                      format:'dd M yy',
				                      autoclose: true
				                  }).on('changeDate', function(selected){
				                          startDate = new Date(selected.date.valueOf());
				                          startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
				                          $('#holi_end_date').datepicker('setStartDate', startDate);
				                   }); 
				                  $('#holi_end_date').datepicker({
				                          weekStart: 1,
				                          startDate: startDate,
				                          endDate: ToEndDate,
				                          format:'dd M yy',
				                          autoclose: true
				                      }).on('changeDate', function(selected){
				                          FromEndDate = new Date(selected.date.valueOf());
				                          FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
				                          $('#holi_start_date').datepicker('setEndDate', FromEndDate);
				                  });
						}
					 });
			});
		$('#accordion').on('click','#holiday_cancel', function(){
			$("#add_new_vacation").hide();	
		});
		$('#accordion').on('click','.holiday_delete_btn', function(){
			if(confirm("Are you sure to delete this?")  ==  true){	
			var holi_id = $(this).attr('holi_id');
				var tsk = 'holiday_delete_single';
			$.ajax({
					type:"post",
					url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
					data:{tsk:tsk,holi_id:holi_id},
					success:function(data){							
						$(".msgDiv").removeClass("hide");	
						$(".msgDiv").html("Holiday/s deleted successfully.");
						$("#sample_1_demo").html(data);	
							$('#sample_1_demo').dataTable().fnDestroy();
							$('#sample_1_demo').DataTable({
					          "bLengthChange": false,
					          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
							});
						//setInterval(function(){ window.location.reload(); },2000);	
					}
				 });	
			}
		});
		$('#accordion').on('click','.holiday_edit_btn', function(){
					var holi_id = $(this).attr('holi_id');
					var tsk = 'holiday_get_data';
					$.ajax({
						type:"post",
						url:"ajax_call/ajax_setting.php",
						data:{tsk:tsk,holi_id:holi_id},
						success:function(data){							
							$("#add_new_vacation").show();
							$("#add_new_vacation").html(data);	
							$('#holiday').validationEngine();
						
			 				$('#holi_start_date').datepicker({
				                      
				                      weekStart: 1,
				                      startDate: '01/01/2012',
				                      format:'dd M yy',
				                      autoclose: true
				                  }).on('changeDate', function(selected){
				                          startDate = new Date(selected.date.valueOf());
				                          startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
				                          $('#holi_end_date').datepicker('setStartDate', startDate);
				                   }); 
				                  $('#holi_end_date').datepicker({
				                          
				                          weekStart: 1,
				                          startDate: startDate,
				                          endDate: ToEndDate,
				                          format:'dd M yy',
				                          autoclose: true
				                      }).on('changeDate', function(selected){
				                          FromEndDate = new Date(selected.date.valueOf());
				                          FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
				                          $('#holi_start_date').datepicker('setEndDate', FromEndDate);
				                  });

						}
					 });
			});
		// save holidya 
		$('#accordion').on('click','#holiday_save', function(){
				var validateholi = $('#holiday').validationEngine('validate');	
				if(validateholi  ==  true  ||   validateholi  ==  'true' ||  validateholi ==  'TRUE'){	
					var tsk = 'holiday_save';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:$("#holiday").serialize()+'&tsk='+tsk,
						beforeSend: function() {
					        $('#ui-id-4').block({ 
					            message: '<div id="loaderImage" style="margin:-20% 40%"></div>', 
					        });
					         new imageLoader(cImageSrc, 'startAnimation()');
					    },
						success:function(data){							
							//if($.trim(data) == 'add' ){	
								$(".msgDiv").removeClass("hide");	
								$(".msgDiv").html("Holiday/s updated successfully.");	
								
								$("#sample_1_demo").html(data);	
								$('#sample_1_demo').dataTable().fnDestroy();
								$('#sample_1_demo').DataTable({
						          "bLengthChange": false,
						          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
								});
								$("#add_new_vacation").hide();
								stopAnimation();
								$('#ui-id-4').unblock();
								 
							//setInterval(function(){ window.location.reload(); },2000);
						}
					 });
				}
			});
	
		$('#accordion').on('change','.holiday-checkall', function(){
             if(this.checked){
	            $(".checkboxes_holi").each(function(){
	                this.checked=true;
	            })             
	        }else{
	            $(".checkboxes_holi").each(function(){
	                this.checked=false;
	            })             
	        }
   	 });
	    $('#accordion').on('change','.checkboxes_holi', function(){	
    
        if (!$(this).is(":checked")){
            $(".holiday-checkall").prop("checked", false);
        }else{
            var flag = 0;
            $(".checkboxes_holi").each(function(){
                if(!this.checked)
                flag=1;
            })             
            if(flag == 0){ $(".holiday-checkall").prop("checked", true);}
        }
    });
		// delete holidya 
		$('#accordion').on('click','#del_holiday_btn', function(){
				if ($("input[type='checkbox'][name='chk[]']:checked").length > 0){
				
					if(confirm("Are you sure to delete this?")  ==  true){	
					var tsk = 'holiday_delete';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:$("#holiday_listing").serialize()+'&tsk='+tsk,
						
						success:function(data){							
							$(".msgDiv").removeClass("hide");	
							$(".msgDiv").html("Holiday/s deleted successfully.");
							$("#sample_1_demo").html(data);	
								$('#sample_1_demo').dataTable().fnDestroy();
								$('#sample_1_demo').DataTable({
						          "bLengthChange": false,
						          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
								});
							//setInterval(function(){ window.location.reload(); },1000);
						}
					 });
				}
				}else{
					alert("Please select atleast one record");	
				}

			});
		


		// add new team	
		$('#accordion').on('click','#add_team_btn', function(){
				var tsk = 'add_new_team';
				$.ajax({
					type:"post",
					url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
					data:{tsk:tsk},
					success:function(data){							
						$("#add_new_team").show();
						$("#add_new_team").html(data);
                        //$('#holi_title').focus();
                        $( "#userids_setting" ).select2({ placeholder: "Search team members", width: '100%'});
                 		$(".select2-no-results").hide();
						$('#team').validationEngine();
					}
				});
			});
		// team cancel
		$('#accordion').on('click','#team_cancel', function(){
			$("#add_new_team").hide();	
		});
		//team save
		$('#accordion').on('click','#team_save', function(){
				var validateholi = $('#team').validationEngine('validate');	
				if(validateholi  ==  true  ||   validateholi  ==  'true' ||  validateholi ==  'TRUE'){	
					var tsk = 'team_save';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:$("#team").serialize()+'&tsk='+tsk,
						beforeSend: function() {
					        $('#ui-id-14').block({ 
					               message: '<div id="loaderImage" style="margin:-17% 35%"></div>',
					        });
					        new imageLoader(cImageSrc, 'startAnimation()');
					    },
						success:function(data){							
							//if($.trim(data) == 'add' ){	
								$(".msgDiv").removeClass("hide");	
								$(".msgDiv").html("Team added successfully.");	
								
								$("#sample_1_demo_1").html(data);	
								$('#sample_1_demo_1').dataTable().fnDestroy();
								$('#sample_1_demo_1').DataTable({
						          "bLengthChange": false,
						          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
								});
								$("#add_new_team").hide();
								stopAnimation();	
								$('#ui-id-14').unblock();
								 
							//setInterval(function(){ window.location.reload(); },2000);
						}
					 });
				}
			});
		// team edit
		$('#accordion').on('click','.team_edit_btn', function(){
					var tm_id = $(this).attr('tm_id');
					var tsk = 'team_get_data';
					$.ajax({
						type:"post",
						url:"ajax_call/ajax_setting.php",
						data:{tsk:tsk,tm_id:tm_id},
						success:function(data){							
							$("#add_new_team").show();
							$("#add_new_team").html(data);	
							$('#team').validationEngine();
							$( "#userids_setting" ).select2({ placeholder: "Search team members", width: '100%'});
                 			$(".select2-no-results").hide();
						}
					 });
			});
		//team delete
		$('#accordion').on('click','.team_delete_btn', function(){
			if(confirm("Are you sure to delete this?")  ==  true){	
			var tm_id = $(this).attr('tm_id');
				var tsk = 'team_delete_single';
			$.ajax({
					type:"post",
					url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
					data:{tsk:tsk,tm_id:tm_id},
					success:function(data){							
						$(".msgDiv").removeClass("hide");	
						$(".msgDiv").html("Team deleted successfully.");
						
						$("#sample_1_demo_1").html(data);	
							$('#sample_1_demo_1').dataTable().fnDestroy();	
							$('#sample_1_demo_1').DataTable({
					          "bLengthChange": false,
					          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
							});
						//setInterval(function(){ window.location.reload(); },2000);	
					}
				 });	
			}
		});

		// delete team 
		$('#accordion').on('click','#del_team_btn', function(){
				if ($("input[type='checkbox'][name='chk_team[]']:checked").length > 0){
				
					if(confirm("Are you sure to delete this?")  ==  true){	
					var tsk = 'team_delete';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:$("#team_listing").serialize()+'&tsk='+tsk,
						
						success:function(data){							
							$(".msgDiv").removeClass("hide");	
							$(".msgDiv").html("Team deleted successfully.");
							$("#sample_1_demo_1").html(data);	
								$('#sample_1_demo_1').dataTable().fnDestroy();
								$('#sample_1_demo_1').DataTable({
						          "bLengthChange": false,
						          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
								});
							//setInterval(function(){ window.location.reload(); },1000);
						}
					 });
				}
				}else{
					alert("Please select atleast one record");	
				}

			});

		$('#accordion').on('change','.team-checkall', function(){
             if(this.checked){
	            $(".checkboxes_team").each(function(){
	                this.checked=true;
	            })             
	        }else{
	            $(".checkboxes_team").each(function(){
	                this.checked=false;
	            })             
	        }
   	 });
	    $('#accordion').on('change','.checkboxes_team', function(){	
    
        if (!$(this).is(":checked")){
            $(".team-checkall").prop("checked", false);
        }else{
            var flag = 0;
            $(".checkboxes_team").each(function(){
                if(!this.checked)
                flag=1;
            })             
            if(flag == 0){ $(".team-checkall").prop("checked", true);}
        }
    });

		
	    // add new project	
		$('#accordion').on('click','#add_project_btn', function(){
				 var tsk = 'add_new_project_form';
		          $.ajax({
		            type:"post",
		            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
		            data:{tsk:tsk},
		              	success:function(data){             
		                $("#add_new_project").show();
		                $("#add_new_project").html(data);
		                $('#pr_title').focus();
		                 $('select[name="pr_color"]').simplecolorpicker({theme: 'fontawesome'});
		                 $('#reminder_date').datetimepicker({
		                      format:'dd M yy h:i:h',
		                      autoclose: true
		                });
		                $('#pr_start_date').datepicker({
		    
		    weekStart: 1,
		    format:'dd M yy',
		    //format:"<?php echo $dtformat[0]['dtformate'];  ?>",
		    autoclose: true
		}).on('changeDate', function(selected){
		        startDate = new Date(selected.date.valueOf());
		        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
		        $('#pr_end_date').datepicker('setStartDate', startDate);
		 }); 
		                $('#pr_end_date').datepicker({
		        
		        weekStart: 1,
		        startDate: startDate,
		        endDate: ToEndDate,
		        format:'dd M yy',
		        autoclose: true
		    }).on('changeDate', function(selected){
		        FromEndDate = new Date(selected.date.valueOf());
		        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
		        $('#pr_start_date').datepicker('setEndDate', FromEndDate);
		});
		                $('.selectpicker').selectpicker();
		                $('#project').validationEngine({validateNonVisibleFields: true});
		            	}
		           });
		});
			
		$('#accordion').on('click','.mycheckbox_pro', function(){
		      if($('.mycheckbox_pro').is(':checked') == true){
		          $(".pro_reminder_box").removeClass('hide');
		      }else{
		          $(".pro_reminder_box").addClass('hide');
		      }
	    });
		// save project
		$('#accordion').on('click','#project_save', function(){
       
       
		      var validateActionPro = $('#project').validationEngine('validate'); 
		      if(validateActionPro  ==  true  ||   validateActionPro  ==  'true' ||  validateActionPro ==  'TRUE'){
		        var tsk = 'project_save';
		        var clid = $('#client_id').val();
		       $(this).attr('disabled','disabled');
		        $.ajax({
		            type:"post",
		            url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
		            data:$("#project").serialize() + "&tsk="+tsk+"&clid="+clid,
		            dataType: "json",
		               success:function(data){   
		               $(".msgDiv").removeClass("hide");
		               $(".msgDiv").text("Project saved successfully.");
		               $("#sample_1_demo_2").html(data.htmldata);	
						$('#sample_1_demo_2').dataTable().fnDestroy();
						$('#sample_1_demo_2').DataTable({
				          "bLengthChange": false,
				          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
						});
						$("#add_new_project").hide();

						if(data.curnt==data.limit)
						{
							localStorage.setItem('collapseItem', '#ui-id-15');
							<?php  $_SESSION['showproject']='yes'; 
							$_SESSION['CREATED'] = time(); ?>
							location.reload();
						}
		            }
		           });
		     }
   		});
		//project cancel
		$('#accordion').on('click','#project_cancel', function(){
			$("#add_new_project").hide();	
		});
		//project edit
		$('#accordion').on('click','.project_edit_btn', function(){
	      var project_id = $(this).attr('pr_id');
	      var tsk = 'get_project_detail';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{project_id:project_id,tsk:tsk},
              success:function(data){             
                $("#add_new_project").show();
                $("#add_new_project").html(data);
                $('select[name="pr_color"]').simplecolorpicker({theme: 'fontawesome'});
                $('#reminder_date').datetimepicker({
                      format:'dd M yy h:i:s',
                      autoclose: true
                });
                $('#pr_start_date').datepicker({
    
                    weekStart: 1,
                    startDate: '01/01/2012',
                    format:'dd M yy',
                    autoclose: true
                }).on('changeDate', function(selected){
                        startDate = new Date(selected.date.valueOf());
                        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                        $('#pr_end_date').datepicker('setStartDate', startDate);
                 }); 
                $('#pr_end_date').datepicker({
                        
                        weekStart: 1,
                        startDate: startDate,
                        endDate: ToEndDate,
                        format:'dd M yy',
                        autoclose: true
                    }).on('changeDate', function(selected){
                        FromEndDate = new Date(selected.date.valueOf());
                        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                        $('#pr_start_date').datepicker('setEndDate', FromEndDate);
                });
                $('.selectpicker').selectpicker();
                $('#project').validationEngine({validateNonVisibleFields: true});
            }
           });
      
    });
		//project delete
		$('#accordion').on('click','.project_delete_btn', function(){
			if(confirm("Are you sure you would like to continue?")  ==  true){	
			var pr_id = $(this).attr('pr_id');
				var tsk = 'project_delete_single';
			$.ajax({
					type:"post",
					url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
					data:{tsk:tsk,pr_id:pr_id},
					dataType: "json",
					success:function(data){			

						$(".msgDiv").removeClass("hide");	
						$(".msgDiv").html("Project deleted successfully.");
						
						$("#sample_1_demo_2").html(data.htmldata);	
							$('#sample_1_demo_2').dataTable().fnDestroy();	
							$('#sample_1_demo_2').DataTable({
					          "bLengthChange": false,
					          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
							});
							if(data.limit==data.curnt)
							{
								
								localStorage.setItem('collapseItem', '#ui-id-15');
								<?php  $_SESSION['showproject']='yes';
								$_SESSION['CREATED'] = time(); ?>
								location.reload();
								setInterval(function(){ window.location.reload(); },1000);
							}
							//Ajit
						//setInterval(function(){ window.location.reload(); },1000);	
					}
				 });	
			}
		});
		// delete team 
		$('#accordion').on('click','#del_project_btn', function(){
				if ($("input[type='checkbox'][name='chk_project[]']:checked").length > 0){
				
					if(confirm("Are you sure you would like to continue?")  ==  true){	
					var tsk = 'project_delete';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:$("#project_listing").serialize()+'&tsk='+tsk,
						dataType: "json",
						success:function(data){							
							$(".msgDiv").removeClass("hide");	
							$(".msgDiv").html("Project deleted successfully.");
							$("#sample_1_demo_2").html(data.htmldata);	
								$('#sample_1_demo_2').dataTable().fnDestroy();
								$('#sample_1_demo_2').DataTable({
						          "bLengthChange": false,
						          aoColumnDefs: [{ bSortable: false,aTargets: [ 0,-1 ]}]
								});
								if(data.limit==data.curnt)
									{
										<?php $_SESSION['showproject']='yes'; 
										$_SESSION['CREATED'] = time();
										?>
										localStorage.setItem('collapseItem', '#ui-id-15');
										location.reload();
									}
							//setInterval(function(){ window.location.reload(); },1000);
						}
					 });
				}
				}else{
					alert("Please select atleast one record");	
				}

			});
		$('#accordion').on('change','.project-checkall', function(){
             if(this.checked){
	            $(".checkboxes_project").each(function(){
	                this.checked=true;
	            })             
	        }else{
	            $(".checkboxes_project").each(function(){
	                this.checked=false;
	            })             
	        }
   	 });
	    $('#accordion').on('change','.checkboxes_project', function(){	
    
        if (!$(this).is(":checked")){
            $(".project-checkall").prop("checked", false);
        }else{
            var flag = 0;
            $(".checkboxes_project").each(function(){
                if(!this.checked)
                flag=1;
            })             
            if(flag == 0){ $(".project-checkall").prop("checked", true);}
        }
    });
		//lunch hour save	    
   			$('#accordion').on('click','#lunchhour_save', function(){
					
				var validatehour = $('#lunch_hours').validationEngine('validate');	
				if(validatehour  ==  true  ||   validatehour  ==  'true' ||  validatehour ==  'TRUE'){	
					var start_date = new Date('2015/05/15 '+$("#lunch_start_time").val());
					var end_date = new Date('2015/05/15 '+$("#lunch_end_time").val());
					if(new Date(start_date) <= new Date(end_date)){
						/* All the future tasks of company will be sent to Queue, */
						var conf = confirm("Are you sure?")
						if(conf){
							var tsk = 'lunchhour_save';
							$.ajax({
								type:"post",
								url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
								data:$("#lunch_hours").serialize()+'&tsk='+tsk,
								beforeSend: function() {
							        $('#ui-id-6').block({ 
							             message: '<div id="loaderImage" style="margin:-9% 42%"></div>', 
							        });
							        new imageLoader(cImageSrc, 'startAnimation()');
							    },
								success:function(data){							
									
									if($.trim(data) == 'add' ){	
										$(".msgDiv").removeClass("hide");	
										$(".msgDiv").html("Lunch hours updated Successfully.");	
										//$('#client_name').validationEngine('showPrompt','Client name Already Exist', 'pass');
										//$('#client_name').val('');
									}else if($.trim(data) == 'edit' ){
										$(".msgDiv").removeClass("hide");	
										$(".msgDiv").html("Lunch hours updated Successfully.");	
									}else{
										$(".msgDiv").removeClass("hide");	
										$(".msgDiv").html("Please Try Again.");	
									}
									stopAnimation();
									$('#ui-id-6').unblock();
								}
							 });
						}
					}else{
						$("#lunch_end_time").val('');
						$('#lunch_end_time').validationEngine('showPrompt', 'End time should be greater then start time.', 'fail');
					}
				}
			});
			// save permittion 
			$('#accordion').on('click','#save_permission', function(){
					var tsk = 'save_permission';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:$("#permission").serialize()+'&tsk='+tsk,
						beforeSend: function() {
					        $('#ui-id-8').block({ 
					            message: '<div id="loaderImage" style="margin:-9% 42%"></div>',
					        });
					        new imageLoader(cImageSrc, 'startAnimation()');
					    },
						success:function(data){							
								$(".msgDiv").removeClass("hide");	
								$(".msgDiv").html("Permission updated Successfully.");	
								stopAnimation();
								$('#ui-id-8').unblock();
							
						}
					 });
			});

			$('#accordion').on('click','#save_userguide', function(){
					
					var tsk = 'save_userguide';
					$.ajax({
						type:"post",
						url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
						data:$("#user_gd").serialize()+'&tsk='+tsk,
						beforeSend: function() {
					        $('#ui-id-12').block({ 
					            message: '<div id="loaderImage" style="margin:-9% 42%"></div>', 
					        });
					        new imageLoader(cImageSrc, 'startAnimation()');
					    },
						success:function(data){							
								$(".msgDiv").removeClass("hide");	
								$(".msgDiv").html(data);	
								stopAnimation();
								$('#ui-id-12').unblock();
							
						}
					 });
			});
			$('#profile').on('submit',(function(e) {
			    var validateprofile = $('#profile').validationEngine('validate');	
				if(validateprofile  ==  true  ||   validateprofile  ==  'true' ||  validateprofile ==  'TRUE'){	
			        e.preventDefault();
			        var formData = new FormData(this);
			        var tsk = 'save_profile';	
			        $.ajax({
			            type:'POST',
			            url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
			            data:formData,
			            cache:false,
			            contentType: false,
			            processData: false,
			            beforeSend: function() {
					        $('#ui-id-10').block({ 
					            message: '<div id="loaderImage" style="margin:-9% 42%"></div>',
					        });
					        new imageLoader(cImageSrc, 'startAnimation()');
					    },
			            success:function(data){
			            	$(".msgDiv").removeClass("hide");	
							$(".msgDiv").html("Profile saved successfully.");
							stopAnimation();
							$('#ui-id-10').unblock();
							//setInterval(function(){ window.location.reload(); },2000);	    
			            },
			            
			        });
			    }
			    }));
            // open popup on change password link from settings
            $('#setting-main').on('click','#change_pwd_link',function(){
                $('#change_password_modal').modal('show');
                
            });
            // close popup on cancel
            $('#change_password_modal').on('click','#cancel',function(){
                $('#change_password_modal').modal('hide');
                setInterval(function(){ window.location.reload(); },500);
            });
            // change password successfully if match current password and new password and confirm password are same.
            $('#change_password_modal').on('click','#save_pwd',function(){
                var validateAction = $('#change_password').validationEngine('validate'); 
                if(validateAction  ==  true  ||   validateAction  ==  'true' ||  validateAction ==  'TRUE'){
                    var tsk = 'change_password_task';
                    var uid = '';
                    $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
                            data:$("#change_password").serialize() + "&tsk="+tsk,
                            success:function(data){          
                              if(data == 0)
                              {
                                  $('#old_pwd').validationEngine('showPrompt', 'Please enter correct old password.', 'pass');
                                  $('#old_pwd').val('');
                                  return false;
                              }else{
                                    $(".msgDiv").removeClass('hide');
                                    $(".msgDiv").html("Password successfully updated.");
                                    $("#change_password_modal").modal('hide');
                                    //setInterval(function(){ window.location.reload(); },2000);
                              }
                              
                            }
                           });
                }
            });

        	hasha = window.location.hash;
            if(hasha == '#project'){
            	$("#ui-id-2").css("display","none");
            	$("#ui-id-16").css("display","block");
            }

        });
    </script> 
 <?php $guide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id =".$_SESSION['user_id']);
if(count($guide) == 0  || $guide[0]['status'] == 'on'){ ?>

<script type="text/javascript">
 setTimeout(function(){
	 if (RegExp('multipage', 'gi').test(window.location.search)) {
	        introJs().start();
	 }
	 $(".introjs-tooltip").on("click",".introjs-prevbutton",function(){
            var chkds = $(".introjs-helperNumberLayer").text();
            if(chkds == '11'){
                window.location.href = 'view';
            } 
     });
 },1000);


 </script>
 		<?php if($_SESSION['user_type'] == 'manager'){ ?>
 		<script>
				setTimeout(function(){
				$(".introjs-tooltip").on("click",".introjs-prevbutton",function(){
			            var chkds = $(".introjs-helperNumberLayer").text();
			            if(chkds == '11'){
			                window.location.href = 'view';
			            } 
			     });
			 },1000);
 		</script>	
 		<?php }else{ ?>
 			<script>
				setTimeout(function(){
				$(".introjs-tooltip").on("click",".introjs-prevbutton",function(){
			            var chkds = $(".introjs-helperNumberLayer").text();
			            if(chkds == '11'){
			                window.location.href = 'view';
			            } 
			     });
			 },1000);
 		</script>
 		<?php } ?>	
 <?php } 

if (!isset($_SESSION['CREATED'])) {
       $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 10) {
        $_SESSION['showproject']='';
         unset($_SESSION['showproject']);
        $_SESSION['CREATED'] = time();  // update creation time
    }

 ?>