<?php 
require_once("../config/dbconfig.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
session_start();

function checkUserteam($userid){
	global $dclass;
	$data = $dclass->select("tm_id","tbl_team_detail"," AND user_id=".$userid);
	
	if(count($data) > 0){
		return 0;
	}else{
		return 1;
	}
}
function phpdateformat($date){
	global $dclass;
	$dtformat = $dclass->select("*","tbl_dateformate");

	$configformat = str_replace('yyyy','Y',$dtformat[0]['dtformate']);
	$configformat = str_replace('yy','y',$configformat);
	$configformat = str_replace('mm','m',$configformat);
	$configformat = str_replace('dd','d',$configformat);

	return	date($configformat, strtotime($date));

}

if(isset($_POST['tsk']) && $_POST['tsk'] != '' ){ 
	if($_POST['tsk'] == 'work_daytime_save'){



		$rowInserted = $dclass->select(' * ',' tbl_working_day_time',"  AND company_user_id =".$_SESSION['company_id']);
		$working_time = $_POST['working_start_time'].':::'.$_POST['working_end_time'];
		$working_day = implode(':::', $_POST['chk_day']);	

		$tm1 = date("H:i:s", strtotime($_POST['working_start_time']));
        $tm2 = date("H:i:s", strtotime($_POST['working_end_time']));
        
		if(count($rowInserted) > 0){
			$ins = array(
				"team_id"=>'',
				"company_user_id"=>$_SESSION['company_id'],
				"working_time"=>$working_time,
				"working_days"=>$working_day,
				"type"=>'perticular',
			);	
			$dclass->update("tbl_working_day_time",$ins," company_user_id = '".$_SESSION['company_id']."' ");
			echo 'edit';
		}else{
			$ins = array(
				"team_id"=>'',
				"company_user_id"=>$_SESSION['company_id'],
				"working_time"=>$working_time,
				"working_days"=>$working_day,
				"type"=>'perticular',
			);	
				$id = $dclass->insert("tbl_working_day_time",$ins);
			echo 'add';		
		}
			
			$company_user = $dclass->select("GROUP_CONCAT(user_id) as userids","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
			
			if($company_user[0]['userids'] != ''){
				$userids = $company_user[0]['userids'].','.$_SESSION['company_id'];
			}else{
				$userids = $_SESSION['company_id'];
			}

			
			$company_task = $dclass->select("t_id,t_start_datetime,t_end_datetime,t_title","tbl_task"," AND t_operator_id IN ($userids)");
			foreach($company_task as $ctask){
				
					$sttime = end(explode(' ',$ctask['t_start_datetime']));
					$edtime = end(explode(' ',$ctask['t_end_datetime']));
		
				if($ctask['t_start_datetime'] > date("Y-m-d")){
					$chday = date("w", strtotime($ctask['t_start_datetime']));
					//if(!in_array($chday,$_POST['chk_day'])){
					    if(strtotime($sttime) < strtotime($tm1) || strtotime($edtime) > strtotime($tm2)){
					  	  $uparr = array(
						  		"t_operator_id"=>0,
						  		"t_type"=>"queue"
						  	);
						  $dclass->update("tbl_task",$uparr," t_id = '".$ctask['t_id']."' ");
						}
					//}
				}else{
					$chday = date("w", strtotime($ctask['t_start_datetime']));
					// $chday condition uncomment if dont want to effect on workingday task of week 
					//if(!in_array($chday,$_POST['chk_day'])){
						if(strtotime($sttime) < strtotime($tm1) || strtotime($edtime) > strtotime($tm2)){ 
						  $dclass->delete("tbl_task"," t_id = '".$ctask['t_id']."' ");
						  $dclass->delete("tbl_user_notification"," task_id = '".$ctask['t_id']."' ");
						}
					//}
				}
			}
	
	
	}else if($_POST['tsk'] == 'work_daytime_get_data'){



			$getData = $dclass->select(' * ',' tbl_working_day_time',"  AND company_user_id =".$_SESSION['company_id']);
			$teamList = $dclass->select("*","tbl_team"," AND company_user_id = ".$_SESSION['company_id']); 
			$worktime = explode(':::',$getData[0]['working_time']); 
			$workday = explode(':::',$getData[0]['working_days']); 

			$html = '<div class="choose-time">
				    	<div class="tab-title">Choose Team</div>
				        <div class="form-group">
				            <select name="team_id" id="team_id" class="selectpicker validate[required]" data-live-search="true" title="Please select a lunch ...">
				                <option value="">Choose team</option>';
								 foreach($teamList as $tm){ 
								  	if($tm['tm_id'] == $_POST['team_id']){
								  		$selected = 'selected="selected"';
								  	}else{
								  		$selected = '';
								  	}

                                  	$html .= '<option value="'.$tm['tm_id'].'" '.$selected.' >'.$tm['tm_title'].'</option>';
                                  } 
                                $html .= '</select></div>
				    </div>
				    <div class="clr"></div>

				    	<div class="choose-working">
					    	<div class="tab-title">Choose Working Time</div>
					        <div class="full-box"><table><tr><td>
					        	<input type="text" value="'.$worktime[0].'" class="input-small validate[required]" name="working_start_time" id="working_start_time"  placeholder="Time">
					            </td><td><label>to</label>
					            <input type="text" value="'.$worktime[1].'" class="input-small validate[required]" name="working_end_time" id="working_end_time"  placeholder="Time">
					            </td></tr></table>
					        </div>
					    </div>		


					    <div class="choose-working-day">
				    	<div class="tab-title">Choose Working Days</div>';
				         $day = array(
                                			'0'=>'SUN',
                                			'1'=>'MON',
                                			'2'=>'TUE',
                                			'3'=>'WED',
                                			'4'=>'THU',
                                			'5'=>'FRI',
                                			'6'=>'SAT',
                                			); 
				        $html .= '<ul>';
				        	foreach($day as $key=>$val){ 
	                                if(in_array($key,$workday)){
	                              		$checked = 'checked="checked"';
	                              	}else{
	                              		$checked = '';
	                              	}
	                                 $html .= '<li>';
				            			$html .= $val;
						            	$html .= '<div class="checkbox">
						                    <input id="chk_'.$val.'" type="checkbox" '.$checked.' name="chk_day[]" value="'.$key.'">
						               		<label for="chk_'.$val.'"></label>
						                </div>    
						            </li>';
	                                  } 
				        $html .= '</ul>
				    </div>
				    <div class="clr"></div>
				    <div class="button-box">
				        <button class="save-btn" id="work_daytime_save" type="button">Save Changes</button>
				    </div>';

        	echo $html;
    
    
    
    }else if($_POST['tsk'] == 'holiday_save'){



    	 $start_Date = date("Y-m-d", strtotime($_POST['holi_start_date']));
    	  $end_Date = date("Y-m-d", strtotime($_POST['holi_end_date']));
		if(isset($_POST['holi_id']) && $_POST['holi_id'] != ''){
			$ins = array(
				"holi_title"=>$_POST['holi_title'],
				"holi_start_date"=>$start_Date,
				"holi_end_date"=>$end_Date
			);	
				$id = $dclass->update("tbl_holidays",$ins,' holi_id='.$_POST['holi_id']);
			//echo 'edit';		
		}else{
			
			$ins = array(
				"holi_title"=>$_POST['holi_title'],
				"holi_user_id"=>$_SESSION['company_id'],
				"holi_start_date"=>$start_Date,
				"holi_end_date"=>$end_Date,
				"holi_type"=>'perticuler',
				"holi_status"=>'active'
			);	
				$id = $dclass->insert("tbl_holidays",$ins);
			//echo 'add';		
		}
	
		$getHoliday = $dclass->select("*","tbl_holidays"," AND holi_user_id = '".$_SESSION['company_id']."' OR holi_type = 'general' ORDER BY holi_id desc");
		$html  = '<thead>
							    <tr>
							        <th>
							            <div class="checkbox">
						                    <input id="chk_all" type="checkbox" data-set="#sample_1_demo" class="holiday-checkall" name="chkall[]">
						               		<label for="chk_all"></label>
						                </div> 
						            </th>
					                <th>Title</th>
					                <th>Start date</th>
					                <th>End Date</th>
					                <th>Action</th>
							    </tr>
							</thead>
							<tbody>';
							    foreach($getHoliday as $rows){ 
						        	$start_date = phpdateformat($rows['holi_start_date']);
						        	$end_date = phpdateformat($rows['holi_end_date']);

							    $html  .= '<tr>
							        <td class="hidden-480">';
							             if($rows['holi_type'] != 'general'){ 
					                    $html  .= '<div class="checkbox">
						                    <input id="chk_'.$rows['holi_id'].'" type="checkbox" class="checkboxes_holi" name="chk[]" value="'.$rows['holi_id'].'">
						               		<label for="chk_'.$rows['holi_id'].'"></label>
						                </div>';
							                	
							             } 		
							       $html  .= '</td>
				                    <td class="hidden-480">'.$rows['holi_title'].'</td>
				                    <td class="hidden-480">'.$start_date.'</td>
				                    <td class="hidden-480">'.$end_date.'</td>
				                    <td class="hidden-480">';
					                  if($rows['holi_type'] != 'general'){  		
					                    $html .= '<span><a holi_id = "'.$rows['holi_id'].'"  class="holiday_edit_btn" href="javascript:void(0);" ><img src="images/edit-icon.png" alt="Edit"></a></span>
					                    <span> <a holi_id = "'.$rows['holi_id'].'"  class="holiday_delete_btn" href="javascript:void(0);" ><img src="images/delete-icon.png" alt="Edit"></a></span>';
					                   }   
					                $html .= '</td>
								</tr>';
							     } 
							            
							$html .= '</tbody>';
	echo $html;
	
	
	
	}else if($_POST['tsk'] == 'add_new_holiday'){




			$html = '<div class="holiday-title">
						    	<label class="input-label">Holiday Title</label>
						    	<input type="text" value="" name="holi_title" id="holi_title" class="input-box validate[required]">
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
						    <div class="button-box">';
						      $html .='<a href="javascript:void(0)" id="holiday_save" class="save-btn">SAVE CHANGES</a>
						        <a href="javascript:void(0)" id="holiday_cancel" class="save-btn">Cancel</a>
						    </div>';
        	echo $html;
    
    
    
    
    }else if($_POST['tsk'] == 'holiday_get_data'){



			if($_POST['holi_id'] != ''){
				$getData = $dclass->select(' * ',' tbl_holidays'," AND holi_id = '".$_POST['holi_id']."'" );
			
			}
	
				$start_date = phpdateformat($getData[0]['holi_start_date']);
				$end_date = phpdateformat($getData[0]['holi_end_date']);
			$html = '

			<div class="holiday-title">
						    	<label class="input-label">Holiday Title</label>
						    	<input type="text" value="'.$getData[0]['holi_title'].'" name="holi_title" id="holi_title" class="input-box validate[required]">
						    </div>
						    <div class="holiday-date">
						    	<label class="input-label">Start Date</label>
						        <input type="text" value="'.$start_date.'" class="input-small validate[required]" name="holi_start_date" id="holi_start_date" placeholder="">
						    </div>
						    <div class="holiday-to">to</div>
						    <div class="holiday-date">
						    	<label class="input-label">End Date</label>
						        <input type="text" value="'.$end_date.'" name="holi_end_date" id="holi_end_date" class="input-small validate[required]" placeholder="">
						    </div>
						    <div class="button-box">';
						    if($_POST['holi_id'] != ''){
											$html .='<input type="hidden" name="script" id="script" value="edit" > 
											<input type="hidden" name="holi_id" id="holi_id" value="'.$_POST['holi_id'].'" > ';		
							}

						        
						        $html .='<a href="javascript:void(0)" id="holiday_save" class="save-btn">SAVE CHANGES</a>
						        <a href="javascript:void(0)" id="holiday_cancel" class="save-btn">Cancel</a>
						    </div>';
        	echo $html;
    
    
    
    }else if($_POST['tsk'] == 'holiday_delete_single'){




			if($_POST['holi_id'] != ''){
				$dclass->delete("tbl_holidays",' holi_id='.$_POST['holi_id']);
			}
			
    	$getHoliday = $dclass->select("*","tbl_holidays"," AND holi_user_id = '".$_SESSION['company_id']."' OR holi_type = 'general' ORDER BY holi_id desc");
		$html  = '<thead>
							    <tr>
							        <th>
							            <div class="checkbox">
						                    <input id="chk_all" type="checkbox" data-set="#sample_1_demo" class="holiday-checkall" name="chkall[]">
						               		<label for="chk_all"></label>
						                </div> 
						            </th>
					                <th>Title</th>
					                <th>Start date</th>
					                <th>End Date</th>
					                <th>Action</th>
							    </tr>
							</thead>
							<tbody>';
							    foreach($getHoliday as $rows){ 
						        	//$start_date = date("d M y", strtotime($rows['holi_start_date']));
						        	//$end_date = date("d M y", strtotime($rows['holi_end_date']));

						        	$start_date = phpdateformat($rows['holi_start_date']);
									$end_date = phpdateformat($rows['holi_end_date']);
							    
							    $html  .= '<tr>
							        <td class="hidden-480">';
							             if($rows['holi_type'] != 'general'){ 
					                    $html  .= '<div class="checkbox">
						                    <input id="chk_'.$rows['holi_id'].'" type="checkbox" class="checkboxes_holi" name="chk[]" value="'.$rows['holi_id'].'">
						               		<label for="chk_'.$rows['holi_id'].'"></label>
						                </div>';
							                	
							             } 		
							       $html  .= '</td>
				                    <td class="hidden-480">'.$rows['holi_title'].'</td>
				                    <td class="hidden-480">'.$start_date.'</td>
				                    <td class="hidden-480">'.$end_date.'</td>
				                    <td class="hidden-480">';
					                  if($rows['holi_type'] != 'general'){  		
					                    $html .= '<span><a holi_id = "'.$rows['holi_id'].'"  class="holiday_edit_btn" href="javascript:void(0);" ><img src="images/edit-icon.png" alt="Edit"></a></span>
					                    <span> <a holi_id = "'.$rows['holi_id'].'"  class="holiday_delete_btn" href="javascript:void(0);" ><img src="images/delete-icon.png" alt="Edit"></a></span>';
					                   }   
					                $html .= '</td>
								</tr>';
							     } 
							            
							$html .= '</tbody>';
	echo $html;
    
    
    
    
    
    
    }else if($_POST['tsk'] == 'holiday_delete'){



		foreach($_POST['chk'] as $hid){
			$dclass->delete("tbl_holidays",' holi_id='.$hid);
		}
				 
			$getHoliday = $dclass->select("*","tbl_holidays"," AND holi_user_id = '".$_SESSION['company_id']."' OR holi_type = 'general' ORDER BY holi_id desc");
		$html  = '<thead>
							    <tr>
							        <th>
							            <div class="checkbox">
						                    <input id="chk_all" type="checkbox" data-set="#sample_1_demo" class="holiday-checkall" name="chkall[]">
						               		<label for="chk_all"></label>
						                </div> 
						            </th>
					                <th>Title</th>
					                <th>Start date</th>
					                <th>End Date</th>
					                <th>Action</th>
							    </tr>
							</thead>
							<tbody>';
							    foreach($getHoliday as $rows){ 
						        	

						        	$start_date = phpdateformat($rows['holi_start_date']);
									$end_date = phpdateformat($rows['holi_end_date']);
							    
							    $html  .= '<tr>
							        <td class="hidden-480">';
							             if($rows['holi_type'] != 'general'){ 
					                    $html  .= '<div class="checkbox">
						                    <input id="chk_'.$rows['holi_id'].'" type="checkbox" class="checkboxes_holi" name="chk[]" value="'.$rows['holi_id'].'">
						               		<label for="chk_'.$rows['holi_id'].'"></label>
						                </div>';
							                	
							             } 		
							       $html  .= '</td>
				                    <td class="hidden-480">'.$rows['holi_title'].'</td>
				                    <td class="hidden-480">'.$start_date.'</td>
				                    <td class="hidden-480">'.$end_date.'</td>
				                    <td class="hidden-480">';
					                  if($rows['holi_type'] != 'general'){  		
					                    $html .= '<span><a holi_id = "'.$rows['holi_id'].'"  class="holiday_edit_btn" href="javascript:void(0);" ><img src="images/edit-icon.png" alt="Edit"></a></span>
					                    <span> <a holi_id = "'.$rows['holi_id'].'"  class="holiday_delete_btn" href="javascript:void(0);" ><img src="images/delete-icon.png" alt="Edit"></a></span>';
					                   }   
					                $html .= '</td>
								</tr>';
							     } 
							            
							$html .= '</tbody>';
	echo $html;		
	
	
	
	}else if($_POST['tsk'] == 'add_new_team'){



		if($_SESSION['user_type'] == 'company_admin'){
           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']." OR user_id = '".$_SESSION['user_id']."' "); 
        }else{
           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
		}

        				   $html = '<div class="holiday-title">
						    	<label class="input-label">Team Title</label>
						    	<input type="text" name="tm_title" id="tm_title" class="input-box validate[required]">
						    </div>
						    <div class="holiday-date">
						    	<label class="input-label">Select User</label>
						          <div class="tab-left-box">
                                <select id="userids_setting" name="user_id[]" class="validate[required]"  multiple>';
                                foreach($userlist as $ui){
                                	if(checkUserteam($ui['user_id']) == 0){
                                		continue;
                                	}

                                 $html .= '<option value="'.$ui['user_id'].'"> '.$ui['fname'].' '.$ui['lname'].'</option>';
                                 } 
                                $html .= '</select>
                                </div>
						    </div>
						    
						    
						    <div class="button-box">
						        <a href="javascript:void(0)" id="team_save" class="save-btn">SAVE CHANGES</a>
						        <a href="javascript:void(0)" id="team_cancel" class="save-btn">Cancel</a>
						    </div>';

	    	echo $html;
    
    
    
    }else if($_POST['tsk'] == 'team_get_data'){



    		if($_POST['tm_id'] != ''){
				$getData = $teamDetail = $dclass->select("*","tbl_team"," AND tm_id=".$_POST['tm_id']);
			}
			$teamUser = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id=".$_POST['tm_id']);
    
            $uarray = explode(',',$teamUser[0]['uid']);

						if($_SESSION['user_type'] == 'company_admin'){
                           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']." OR user_id = '".$_SESSION['user_id']."' "); 
                        }else{
                           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
        				}

        				   $html = '<div class="holiday-title">
						    	<label class="input-label">Team Title</label>
						    	<input type="text" name="tm_title" id="tm_title" value="'.$getData[0]['tm_title'].'" class="input-box validate[required]">
						    </div>
						    <div class="holiday-date">
						    	<label class="input-label">Select User</label>
						          <div class="tab-left-box">
                                <select id="userids_setting" name="user_id[]" class="validate[required]"  multiple>';
                                foreach($userlist as $ui){ 
                                	if(in_array($ui['user_id'],$uarray)){
                                      $selected = "selected='selected'";
                                    }else{
                                      $selected = "";
                                        if(checkUserteam($ui['user_id']) == 0){
                                		  continue;
                                		}
                                    }
                                $html .= '<option value="'.$ui['user_id'].'" '.$selected.'> '.$ui['fname'].' '.$ui['lname'].'</option>';
                                 } 
                                $html .= '</select>
                                </div>
						    </div>
						    
						    
						    <div class="button-box">';
						    if($_POST['tm_id'] != ''){
											$html .='<input type="hidden" name="script" id="script" value="edit" > 
											<input type="hidden" name="tm_id" id="tm_id" value="'.$_POST['tm_id'].'" > ';		
							}
						        $html .= '<a href="javascript:void(0)" id="team_save" class="save-btn">SAVE CHANGES</a>
						        <a href="javascript:void(0)" id="team_cancel" class="save-btn">Cancel</a>
						    </div>';

	    	echo $html;
    
    
    
    }else if($_POST['tsk'] == 'team_save'){




		if(isset($_POST['script']) && $_POST['script'] == 'edit'){

          $ins = array(
                "tm_title" => $_POST['tm_title'],
                "company_user_id" => $_SESSION['company_id'],
              );
          $dclass->update('tbl_team',$ins," tm_id=".$_POST['tm_id']);

          $dclass->delete('tbl_team_detail'," tm_id = '".$_POST['tm_id']."'");
          foreach($_POST['user_id'] as $uid){
            $insDetail = array(
                "tm_id" => $_POST['tm_id'],
                "user_id" => $uid
              );
            $usid = $dclass->insert('tbl_team_detail',$insDetail);
          }

      
      }else{
          $ins = array(
                "tm_title" => $_POST['tm_title'],
                "company_user_id" => $_SESSION['company_id'],
                "tm_status" => 'active'                              
              );
          $id = $dclass->insert('tbl_team',$ins);

          foreach($_POST['user_id'] as $uid){
             $insDetail = array(
                "tm_id" => $id,
                "user_id" => $uid
             );
            $usid = $dclass->insert('tbl_team_detail',$insDetail);
          }
      }
	
		$getTeam = $dclass->select("*","tbl_team"," AND company_user_id = '".$_SESSION['company_id']."' ORDER BY tm_id desc"); 
		$html = '<thead>
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
				<tbody>';
				     foreach($getTeam as $rows){ 
				   $html .= '<tr>
				        <td class="hidden-480">
				           
		                    <div class="checkbox">
			                    <input id="chk_team_'.$rows['tm_id'].'" type="checkbox" class="checkboxes_team" name="chk_team[]" value="'.$rows['tm_id'].'">
			               		<label for="chk_team_'.$rows['tm_id'].'"></label>
			                </div>
				        </td>
	                    <td class="hidden-480">'.$rows['tm_title'].'</td>
	                    <td class="hidden-480">
		                   <span> <a tm_id = "'.$rows['tm_id'].'"  class="team_edit_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/edit-icon.png" alt="Edit"></a></span>
		                   <span> <a tm_id = "'.$rows['tm_id'].'"  class="team_delete_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/delete-icon.png" alt="Edit"></a></span>
		                </td>
					</tr>';
				     } 
				            
				$html .= '</tbody>';
	echo $html;
	
	
	
	
	
	
	
	}else if($_POST['tsk'] == 'team_delete_single'){




		if($_POST['tm_id'] != ''){
				$dclass->delete("tbl_team",' tm_id='.$_POST['tm_id']);
				$dclass->delete("tbl_team_detail",' tm_id='.$_POST['tm_id']);
		}
			
    	$getTeam = $dclass->select("*","tbl_team"," AND company_user_id = '".$_SESSION['company_id']."' ORDER BY tm_id desc"); 
		$html = '<thead>
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
				<tbody>';
				     foreach($getTeam as $rows){ 
				   $html .= '<tr>
				        <td class="hidden-480">
				           
		                    <div class="checkbox">
			                    <input id="chk_team_'.$rows['tm_id'].'" type="checkbox" class="checkboxes_team" name="chk_team[]" value="'.$rows['tm_id'].'">
			               		<label for="chk_team_'.$rows['tm_id'].'"></label>
			                </div>
				        </td>
	                    <td class="hidden-480">'.$rows['tm_title'].'</td>
	                    <td class="hidden-480">
		                   <span> <a tm_id = "'.$rows['tm_id'].'"  class="team_edit_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/edit-icon.png" alt="Edit"></a></span>
		                   <span> <a tm_id = "'.$rows['tm_id'].'"  class="team_delete_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/delete-icon.png" alt="Edit"></a></span>
		                </td>
					</tr>';
				     } 
				            
				$html .= '</tbody>';
	echo $html;
    
    
    
    
    
    }else if($_POST['tsk'] == 'team_delete'){




		foreach($_POST['chk_team'] as $hid){
			$dclass->delete("tbl_team",' tm_id='.$hid);
			$dclass->delete("tbl_team_detail",' tm_id='.$hid);
		}
				 
			$getTeam = $dclass->select("*","tbl_team"," AND company_user_id = '".$_SESSION['company_id']."' ORDER BY tm_id desc"); 
		$html = '<thead>
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
				<tbody>';
				     foreach($getTeam as $rows){ 
				   $html .= '<tr>
				        <td class="hidden-480">
				           
		                    <div class="checkbox">
			                    <input id="chk_team_'.$rows['tm_id'].'" type="checkbox" class="checkboxes_team" name="chk_team[]" value="'.$rows['tm_id'].'">
			               		<label for="chk_team_'.$rows['tm_id'].'"></label>
			                </div>
				        </td>
	                    <td class="hidden-480">'.$rows['tm_title'].'</td>
	                    <td class="hidden-480">
		                   <span> <a tm_id = "'.$rows['tm_id'].'"  class="team_edit_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/edit-icon.png" alt="Edit"></a></span>
		                   <span> <a tm_id = "'.$rows['tm_id'].'"  class="team_delete_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/delete-icon.png" alt="Edit"></a></span>
		                </td>
					</tr>';
				     } 
				            
				$html .= '</tbody>';
	echo $html;
	
	
	
	
	}else if($_POST['tsk'] == 'add_new_project'){




		if($_SESSION['user_type'] == 'company_admin'){
           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']." OR user_id = '".$_SESSION['user_id']."' "); 
        }else{
           $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
		}

        				   $html = '<div class="holiday-title">
						    	<label class="input-label">Project Title</label>
						    	<input type="text" name="pr_title" id="pr_title" class="input-box validate[required]">
						    </div>
						    <div class="holiday-date">
						    	<label class="input-label">Select User</label>
						          <div class="tab-left-box">
                                <select id="userids_setting" name="user_id[]" class="validate[required]"  multiple>';
                                foreach($userlist as $ui){
                                	if(checkUserteam($ui['user_id']) == 0){
                                		continue;
                                	}

                                 $html .= '<option value="'.$ui['user_id'].'"> '.$ui['fname'].' '.$ui['lname'].'</option>';
                                 } 
                                $html .= '</select>
                                </div>
						    </div>
						    
						    
						    <div class="button-box">
						        <a href="javascript:void(0)" id="team_save" class="save-btn">SAVE CHANGES</a>
						        <a href="javascript:void(0)" id="team_cancel" class="save-btn">Cancel</a>
						    </div>';

	    	echo $html;
    
    
    
    
    }else if($_POST['tsk'] == 'project_save'){






       $start_Date = date("Y-m-d", strtotime($_POST['pr_start_date']));
       $end_Date = date("Y-m-d", strtotime($_POST['pr_end_date']));
       
    if(isset($_POST['script']) && $_POST['script'] == 'edit'){

      
       
       $ins = array(
        "pr_title"=>$_POST['pr_title'],
        "pr_start_datetime"=>$start_Date,
        "pr_end_datetime"=>$end_Date,
        "pr_description"=>$_POST['pr_description'],
        "pr_color"=>$_POST['pr_color'],
        "pr_cl_id"=>$_POST['cl_id'],
      );

        $dclass->update("tbl_project",$ins," pr_id=".$_POST['project_id']);
      
          if(isset($_POST['pro_reminder']) && $_POST['pro_reminder'] == 'on'){
              
              if($_POST['pro_check_email'] == 'on'){
                $emstatus = 'on';
              }else{
                $emstatus = 'off';
              }

                $Remins = array(
                  "rem_status"=>$emstatus,
                  "rem_time"=>$_POST['pro_popup_time'],
                  "rem_time_period"=>$_POST['pro_reminder_type'],
                  "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
                );  
                $dclass->update("tbl_project_reminder",$Remins, ' pr_id ='.$_POST['project_id'].' AND rem_type="email"');

              if(isset($_POST['pro_check_popup']) && $_POST['pro_check_popup'] == 'on'){
                $popstatus = 'on';
              }else{
                $popstatus = 'off';
              }

                $Remins = array(
                  "rem_status"=>$popstatus,
                  "rem_time"=>$_POST['pro_popup_time'],
                  "rem_time_period"=>$_POST['pro_reminder_type'],
                  "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
                );  
               $dclass->update("tbl_project_reminder",$Remins, 'pr_id ='.$_POST['project_id'].' AND rem_type="popup" ');
              
          }else{
                $Remins = array(
                  "rem_status"=>'off',
                );  
                $dclass->update("tbl_project_reminder",$Remins, ' pr_id ='.$_POST['project_id'].' ');
          }
          $id = $_POST['project_id'];

      
      }else{
        
        $ins = array(
        "pr_title"=>$_POST['pr_title'],
        "pr_start_datetime"=>$start_Date,
        "pr_end_datetime"=>$end_Date,
        "pr_company_id"=>$_SESSION['company_id'],
        "pr_description"=>$_POST['pr_description'],
        "pr_cl_id"=>$_POST['cl_id'],
        "pr_color"=>$_POST['pr_color'],
        "pr_status"=>'active',
      );
        $id = $dclass->insert("tbl_project",$ins);  
        
         //if(isset($_POST['pro_reminder']) && $_POST['pro_reminder'] == 'on'){
            
            if(isset($_POST['pro_check_email']) && $_POST['pro_check_email'] == 'on'){
              $emstatus = 'on';
            }else{
              $emstatus = 'off';
            }

              $Remins = array(
                "pr_id"=>$id,
                "rem_status"=>$emstatus,
                "rem_type"=>'email',
                "rem_time"=>$_POST['pro_popup_time'],
                "rem_time_period"=>$_POST['pro_reminder_type'],
                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
              $dclass->insert("tbl_project_reminder",$Remins);

            if(isset($_POST['pro_check_popup']) && $_POST['pro_check_popup'] == 'on'){
              $popstatus = 'on';
            }else{
              $popstatus = 'off';
            }

              $Remins = array(
                "pr_id"=>$id,
                "rem_status"=>$popstatus,
                "rem_type"=>'popup',
                "rem_time"=>$_POST['pro_popup_time'],
                "rem_time_period"=>$_POST['pro_reminder_type'],
                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
              $dclass->insert("tbl_project_reminder",$Remins);

                $project_date_time =  date("Y-m-d H:i:s",strtotime($start_Date));
                    $reminder_datetime = date('Y-m-d H:i:s', strtotime($_POST['reminder_date']));

                    $hourdiff = round((strtotime($project_date_time) - strtotime($reminder_datetime))/3600, 0);
                    
                $noti_add = array(
                    'task_id'=>$id,
                    'task_event'=>'Project Reminder',
                    'task_title'=>$_POST['pr_title'],
                    'task_date'=>$start_Date,
                    'task_starttime'=>'',
                    'generate_datetime'=>date('Y-m-d H:i:s'),
                    'read_status'=>'0',
                     'description' => $hourdiff,
                    'user_id'=>$_SESSION['company_id'],
                );

                $dclass->insert("tbl_user_notification",$noti_add);

            
        //}
                $limit = $gnrl->checkmaxallowproject();
      }

      
      $getProject = $dclass->select("t1.*,t2.cl_company_name","tbl_project t1 LEFT JOIN  tbl_client t2 ON t1.pr_cl_id = t2.cl_id"," AND t1.pr_company_id = '".$_SESSION['company_id']."' ORDER BY t1.pr_id desc");
      $html = '<thead>
					<tr>
						<th>
							<div class="checkbox">
			                    <input id="chk_all_project" type="checkbox" data-set="#sample_1_demo_2" class="project-checkall" name="chkall_project[]">
			               		<label for="chk_all_project"></label>
			                </div> 
			            </th>
		                <th>Title</th>
		                <th>Action</th>
				    </tr>
				</thead>
				<tbody>';
				$i=0;
				     foreach($getProject as $rows){ 
				     	$i++;
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

				   $html .= '<tr>
				        <td class="hidden-480">
				           
		                    <div class="checkbox">
			                    <input id="chk_project_'.$rows['pr_id'].'" type="checkbox" class="checkboxes_project" name="chk_project[]" value="'.$rows['pr_id'].'">
			               		<label for="chk_project_'.$rows['pr_id'].'"></label>
			                </div>
				        </td>
	                    <td class="hidden-480">'.$gnrl->trunc_string($rows['pr_title'],20).'</td>
	                    <td class="hidden-480">'.$gnrl->trunc_string($rows['cl_company_name'],20).'</td>
	                    <td class="hidden-480">'.$prstdt.'</td>
	                    <td class="hidden-480">'.$preddt.'</td>
	                    <td class="hidden-480">
		                   <span> <a pr_id = "'.$rows['pr_id'].'"  class="project_edit_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/edit-icon.png" alt="Edit"></a></span>
		                   <span> <a pr_id = "'.$rows['pr_id'].'"  class="project_delete_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/delete-icon.png" alt="Edit"></a></span>
		                </td>
					</tr>';
				     } 
				            
				$html .= '</tbody>';
	//echo $html;   
  	  echo json_encode(array("htmldata"=>$html,"limit"=>$limit,"curnt"=>$i));
    
    
    }else if($_POST['tsk'] == 'project_delete_single'){



		if($_POST['pr_id'] != ''){
			$dclass->delete("tbl_project",' pr_id='.$_POST['pr_id']);
			$dclass->delete("tbl_project_reminder",' pr_id='.$_POST['pr_id']);
			$dclass->delete("tbl_task",'t_pr_id='.$_POST['pr_id']);
		}
			
    	 $getProject = $dclass->select("t1.*,t2.cl_company_name","tbl_project t1 LEFT JOIN  tbl_client t2 ON t1.pr_cl_id = t2.cl_id"," AND t1.pr_company_id = '".$_SESSION['company_id']."' ORDER BY t1.pr_id desc");
      $html = '<thead>
					<tr>
						<th>
							<div class="checkbox">
			                    <input id="chk_all_project" type="checkbox" data-set="#sample_1_demo_2" class="project-checkall" name="chkall_project[]">
			               		<label for="chk_all_project"></label>
			                </div> 
			            </th>
		                <th>Title</th>
		                <th>Action</th>
				    </tr>
				</thead>
				<tbody>';
				$i=0;
				     foreach($getProject as $rows){ 
				     	$i++;
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
				   $html .= '<tr>
				        <td class="hidden-480">
				           
		                    <div class="checkbox">
			                    <input id="chk_project_'.$rows['pr_id'].'" type="checkbox" class="checkboxes_project" name="chk_project[]" value="'.$rows['pr_id'].'">
			               		<label for="chk_project_'.$rows['pr_id'].'"></label>
			                </div>
				        </td>
	                    <td class="hidden-480">'.$gnrl->trunc_string($rows['pr_title'],20).'</td>
	                    <td class="hidden-480">'.$gnrl->trunc_string($rows['cl_company_name'],20).'</td>
	                    <td class="hidden-480">'.$prstdt.'</td>
	                    <td class="hidden-480">'.$preddt.'</td>
	                    <td class="hidden-480">
		                   <span> <a pr_id = "'.$rows['pr_id'].'"  class="project_edit_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/edit-icon.png" alt="Edit"></a></span>
		                   <span> <a pr_id = "'.$rows['pr_id'].'"  class="project_delete_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/delete-icon.png" alt="Edit"></a></span>
		                </td>
					</tr>';
				     } 
				            
				$html .= '</tbody>';
	//echo $html;

	//echo 1;
    $limit = $gnrl->checkmaxallowproject();
    $lms=$limit-1;
    echo json_encode(array("htmldata"=>$html,"limit"=>$lms,"curnt"=>$i));
    
    
    
    
    }else if($_POST['tsk'] == 'project_delete'){



    	$tltdelete=0;
		foreach($_POST['chk_project'] as $hid){
			$tltdelete++;
			$dclass->delete("tbl_project",' pr_id='.$hid);
			$dclass->delete("tbl_project_reminder",' pr_id='.$hid);
			$dclass->delete("tbl_task",'t_pr_id='.$hid);
		}
				 
			$getProject = $dclass->select("t1.*,t2.cl_company_name","tbl_project t1 LEFT JOIN  tbl_client t2 ON t1.pr_cl_id = t2.cl_id"," AND t1.pr_company_id = '".$_SESSION['company_id']."' ORDER BY t1.pr_id desc");
      $html = '<thead>
					<tr>
						<th>
							<div class="checkbox">
			                    <input id="chk_all_project" type="checkbox" data-set="#sample_1_demo_2" class="project-checkall" name="chkall_project[]">
			               		<label for="chk_all_project"></label>
			                </div> 
			            </th>
		                <th>Title</th>
		                <th>Action</th>
				    </tr>
				</thead>
				<tbody>';
				$i=0;
				     foreach($getProject as $rows){ 
				     	$i++;
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
				     	
				   $html .= '<tr>
				        <td class="hidden-480">
				           
		                    <div class="checkbox">
			                    <input id="chk_project_'.$rows['pr_id'].'" type="checkbox" class="checkboxes_project" name="chk_project[]" value="'.$rows['pr_id'].'">
			               		<label for="chk_project_'.$rows['pr_id'].'"></label>
			                </div>
				        </td>
	                    <td class="hidden-480">'.$gnrl->trunc_string($rows['pr_title'],20).'</td>
	                    <td class="hidden-480">'.$gnrl->trunc_string($rows['cl_company_name'],20).'</td>
	                    <td class="hidden-480">'.$prstdt.'</td>
	                    <td class="hidden-480">'.$preddt.'</td>
	                    <td class="hidden-480">
		                   <span> <a pr_id = "'.$rows['pr_id'].'"  class="project_edit_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/edit-icon.png" alt="Edit"></a></span>
		                   <span> <a pr_id = "'.$rows['pr_id'].'"  class="project_delete_btn" href="javascript:void(0);" ><img src="'.SITE_URL.'images/delete-icon.png" alt="Edit"></a></span>
		                </td>
					</tr>';
				     } 
				            
				$html .= '</tbody>';
	//echo $html;
	 $limit = $gnrl->checkmaxallowproject();
    $curentbfrdlete=$tltdelete+$i;
    echo json_encode(array("htmldata"=>$html,"limit"=>$limit,"curnt"=>$curentbfrdlete));
	
	
	
	}else if($_POST['tsk'] == 'lunchhour_save'){

    	$lunchhour = $_POST['lunch_start_time'].':::'.$_POST['lunch_end_time'];
		if(isset($_POST['show_in_calender']) && $_POST['show_in_calender'] == 'yes' ){
			$show = 'yes';
		}else{
			$show = 'no';
		}
		if(isset($_POST['lunch_id']) && $_POST['lunch_id'] != ''){
			$ins = array(
				"company_user_id"=>$_SESSION['company_id'],
				"team_id"=>$_POST['lunch_team_id'],
				"lunch_hours"=>$lunchhour,
				"show_in_calender"=>$show,
				"type"=>'perticular'
			);	
				$id = $dclass->update("tbl_lunch_hours",$ins,' id='.$_POST['lunch_id']);
			echo 'edit';		
		}else{
			
			$ins = array(
				"company_user_id"=>$_SESSION['company_id'],
				"team_id"=>$_POST['lunch_team_id'],
				"lunch_hours"=>$lunchhour,
				"show_in_calender"=>$show,
				"type"=>'perticular'
			);	
				$id = $dclass->insert("tbl_lunch_hours",$ins);

			echo 'add';		
		}
		
			$company_user = $dclass->select("GROUP_CONCAT(user_id) as userids","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
			
			if($company_user[0]['userids'] != ''){
				$userids = $company_user[0]['userids'].','.$_SESSION['company_id'];
			}else{
				$userids = $_SESSION['company_id'];
			}

			/*
			$company_task = $dclass->select("t_id,t_start_datetime,t_end_datetime,t_title","tbl_task"," AND t_operator_id IN ($userids)");
			foreach($company_task as $ctask){
				
					$sttime = end(explode(' ',$ctask['t_start_datetime']));
					$edtime = end(explode(' ',$ctask['t_end_datetime']));
		
				if($ctask['t_start_datetime'] > date("Y-m-d")){
					$chday = date("w", strtotime($ctask['t_start_datetime']));
					//if(!in_array($chday,$_POST['chk_day'])){
					    //if(strtotime($sttime) < strtotime($tm1) || strtotime($edtime) > strtotime($tm2)){
					  	  $uparr = array(
						  		"t_operator_id"=>0,
						  		"t_type"=>"queue"
						  	);
						  $dclass->update("tbl_task",$uparr," t_id = '".$ctask['t_id']."' ");
						//}
					//}
				}else{
					$chday = date("w", strtotime($ctask['t_start_datetime']));
					// $chday condition uncomment if dont want to effect on workingday task of week 
					//if(!in_array($chday,$_POST['chk_day'])){
						//if(strtotime($sttime) < strtotime($tm1) || strtotime($edtime) > strtotime($tm2)){ 
						  $dclass->delete("tbl_task"," t_id = '".$ctask['t_id']."' ");
						  $dclass->delete("tbl_user_notification"," task_id = '".$ctask['t_id']."' ");
						  
						//}
					//}
				}
			}
		*/
	
	
	}else if($_POST['tsk'] == 'save_permission'){



								$tasklist = array(
			  						"1" => 'TASK_ADD',
									"2" => 'TASK_UPDATE',
									"3" => 'TASK_DELETE',
									"4" => 'TASK_VIEW',
									"5" => 'USER_ADD',
									"6" => 'USER_UPDATE',
									"7" => 'USER_DELETE',
									"8" => 'USER_VIEW',
			  					); 
					
					$chkAcs = $dclass->select("*","tbl_role_access",'AND company_user_id = "'.$_SESSION['company_id'].'"');
					
					if(count($chkAcs) > 0){				
						$dclass->delete('tbl_role_access',' company_user_id ="'.$_SESSION['company_id'].'" ');
					}
					
					$role = $dclass->select("*","tbl_role","AND r_id NOT IN ('1','2') AND r_status='active'");

					foreach($tasklist as $tkey=>$tval){
						foreach($role as $rl){
							if(isset($_POST['check'][$tkey][$rl['r_id']])){
								$ins = array(
									"r_id"=>$rl['r_id'],
									"v_name"=>$tval,
									"l_value"=>$_POST['check'][$tkey][$rl['r_id']],
									"company_user_id"=>$_SESSION['company_id'],
								);
								//echo '<pre>'; print_r($ins); echo '</pre>';
								$dclass->insert('tbl_role_access',$ins);
								
							}else{
								continue;
							}
						}
					}
	
					die();
	
	
	
	
	
	
	
	
	
	
	}else if($_POST['tsk'] == 'save_profile'){



		if(isset($_POST['user_id']) && $_POST['user_id'] != ''){
			if(isset($_POST['company_name']) && $_POST['company_name'] != ''){
				$ins = array(
					"fname"=>$_POST['fname'],
					"lname"=>$_POST['lname'],
					"email"=>$_POST['email'],
					"company_name"=>$_POST['company_name']
				);
			}else{
				$ins = array(
					"fname"=>$_POST['fname'],
					"lname"=>$_POST['lname'],
					"email"=>$_POST['email']
				);	
			}
			
			 $dclass->update("tbl_user",$ins,' user_id='.$_POST['user_id']);
			 if($_FILES['user_avatar']['name'] != '')
					{
						$ext = end(explode('.',$_FILES['user_avatar']['name']));
						$filename=$_POST['user_id'].'_user.'.$ext;
						move_uploaded_file($_FILES["user_avatar"]["tmp_name"],'../upload/user/'.$filename);
						$insimage = array('user_avatar'=>$filename);						
						$dclass->update('tbl_user',$insimage," user_id = '".$_POST['user_id']."'");
					}

			echo 'edit';		
		}
	
	
	
	}else if($_POST['tsk'] == 'save_userguide'){


		
			if(isset($_POST['user_guide']) && $_POST['user_guide'] == 'yes'){
					
					$guidearr  = array(
						"user_id"=>$_SESSION['user_id'],
						"status"=>'on',
					);	
			
					$checkGuide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id=".$_SESSION['user_id']);
					
					if(count($checkGuide) > 0 ){
						$dclass->update('tbl_user_guide_prompt',$guidearr," user_id = '".$_SESSION['user_id']."'");
					}else{
						$dclass->insert('tbl_user_guide_prompt',$guidearr);
					}
					echo "User guides are now turned on.";
				}else{
					$guidearr  = array(
						"user_id"=>$_SESSION['user_id'],
						"status"=>'off',
					);	
			
					$checkGuide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id=".$_SESSION['user_id']);
					
					if(count($checkGuide) > 0 ){
						$dclass->update('tbl_user_guide_prompt',$guidearr," user_id = '".$_SESSION['user_id']."'");
					}else{
						$dclass->insert('tbl_user_guide_prompt',$guidearr);
					}
				
					echo "User guides are now turned off.";
				}

			
		
	
	
	
	
	
	
	
	
	}else if($_POST['tsk'] == 'change_password_task'){



            $userDetails = $dclass->select(' * ',' tbl_user'," AND user_id = '".$_SESSION['user_id']."'");
            
            // check old password is match with current password
            if(base64_decode($userDetails[0]['password']) != $_POST['old_pwd'])
            {
                echo 0;
            }
            else
            {
                // check new password and confirm password are same
                if($_POST['new_pwd'] == $_POST['confirm_pwd'])
                {
                    $ins = array("password"=>base64_encode($_POST['new_pwd']));
                   echo $dclass->update("tbl_user",$ins,'user_id = '.$_SESSION['user_id'] );
                }
            }
    
    
    
    }else if($_POST['tsk'] == 'get_lunch_hours'){



		$getlunchhr = $dclass->select("*","tbl_lunch_hours"," AND company_user_id = '".$_SESSION['company_id']."' AND team_id = '".$_POST['team_id']."' ");
		$lunchhour = explode(':::', $getlunchhr[0]['lunch_hours']);

    	?>

           
							<div class="choose-working">
						    	<div class="tab-title">Choose Lunch Hours</div>
						        <div class="full-box">
						        	<?php if(count($lunchhour) > 0){ ?>
	                               		<input type="hidden" name="lunch_id" id="lunch_id" value="<?php echo $getlunchhr[0]['id'] ?>" >
	                               	<?php } ?>
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
						        	<table><tr><td>
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
					    
            
        <?php
    
    
    
    
    
    }else if($_POST['tsk'] == 'user_guide_propmt_setting'){




    			if(isset($_POST['user_guide']) && $_POST['user_guide'] != ''){
					
					$guidearr  = array(
						"user_id"=>$_SESSION['user_id'],
						"status"=>'on',
					);	
			
					$checkGuide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id=".$_SESSION['user_id']);
					
					if(count($checkGuide) > 0 ){
						$dclass->update('tbl_user_guide_prompt',$guidearr," user_id = '".$_SESSION['user_id']."'");
					}else{
						$dclass->insert('tbl_user_guide_prompt',$guidearr);
					}
				}else{
					$guidearr  = array(
						"user_id"=>$_SESSION['user_id'],
						"status"=>'off',
					);	
			
					$checkGuide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id=".$_SESSION['user_id']);
					
					if(count($checkGuide) > 0 ){
						$dclass->update('tbl_user_guide_prompt',$guidearr," user_id = '".$_SESSION['user_id']."'");
					}else{
						$dclass->insert('tbl_user_guide_prompt',$guidearr);
					}
				}

					 		
		echo 'edit';	


    
    
    
    
    }else if($_POST['tsk'] == 'user_guide_propmt_setting_by_skip'){

    	if(isset($_POST['from']) && $_POST['from'] == 'skipbtn' ){
								
								$guidearr  = array(
									"user_id"=>$_SESSION['user_id'],
									"status"=>'off',
								);	
						
								$checkGuide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id=".$_SESSION['user_id']);
						
								if(count($checkGuide) > 0 ){
									$dclass->update('tbl_user_guide_prompt',$guidearr," user_id = '".$_SESSION['user_id']."'");
								}else{
									$dclass->insert('tbl_user_guide_prompt',$guidearr);
								}
						
					}
    
    }else if($_POST['tsk'] == 'undu_subscription'){

		if($_REQUEST['subscribe_id'] != ''){
			$dclass->delete("tbl_user_subscrib_detail","  id='".$_REQUEST['subscribe_id']."'");
		}
			
    }
	
}
?>

