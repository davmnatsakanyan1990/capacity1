<?php 
require_once("../config/dbconfig.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
session_start();
function checkLunch($start='',$end='',$team_id){
 return 0;
 global $dclass;
    $lunchtime = $dclass->select("*","tbl_lunch_hours"," AND team_id = '".$team_id."' AND company_user_id = '".$_SESSION['company_id']."' ");
    if(count($lunchtime) > 0 ){
        if($lunchtime[0]['show_in_calender'] == 'yes'){      
          $time = explode(':::',$lunchtime[0]['lunch_hours']);
          $start_a = strtotime($start);
          $end_a = strtotime($end); 
           $tm1 = date("H:i:s", strtotime($time[0]));
           $tm2 = date("H:i:s", strtotime($time[1]));
          $start_b = strtotime($tm1);
          $end_b = strtotime($tm2); 

          if(($start_a < $end_b) && ($end_a > $start_b)){
            return 1;
          }else{
            return 0;  
          }
        }else{
          return 0;
        }
    }else{
      $start_a = strtotime($start);
      $end_a = strtotime($end); 
          $tm1 = date("H:i:s", strtotime('12:00 PM'));
          $tm2 = date("H:i:s", strtotime('1:00 PM'));
          $start_b = strtotime($tm1);
          $end_b = strtotime($tm2); 

          if(($start_a < $end_b) && ($end_a > $start_b)){
            return 1;
          }else{
            return 0;  
          }
    }
}
function getTimeDetail($team_id=''){
 global $dclass;
  if(isset($team_id) && $team_id != ''){
    $teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id = '".$_SESSION['company_id']."' ");
    if(count($teamWorkDay) > 0){
      $time_temp = $teamWorkDay[0]['working_time'];
    }else{
      $teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND type = 'general'");
      $time_temp = $teamWorkDay[0]['working_time'];
    }
  }else{
    $teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND type = 'general'");
    $time_temp = $teamWorkDay[0]['working_time'];
  }
  $time = explode(':::',$time_temp);
    $time_s = date("H:i:s", strtotime($time[0]));
    $time_e = date("H:i:s", strtotime($time[1]));
    $td = array($time_s,$time_e);
  return $td;
}
function checkholiday($checkdate){
 global $dclass;
 $holidays = $dclass->select("holi_id","tbl_holidays"," 
    AND holi_user_id=".$_SESSION['company_id']." 
    AND '".$checkdate."' BETWEEN holi_start_date AND holi_end_date ");
  if(count($holidays) > 0){
    return 1;
  }else{
    return 0;
  }
}
function getLunchDuration($team_id){
 return 0;
 global $dclass;
 $lunchhr = $dclass->select("lunch_hours,show_in_calender","tbl_lunch_hours"," AND team_id = '".$team_id."' AND company_user_id=".$_SESSION['company_id']."");
 if(count($lunchhr) > 0){
    if($lunchhr[0]['show_in_calender'] == 'yes'){
        $workingtm = $dclass->select("working_time","tbl_working_day_time","  AND company_user_id=".$_SESSION['company_id']."");
        $worktm = explode(':::',$workingtm[0]['working_time']);
          $wk1 = strtotime(date("H:i:s", strtotime($worktm[0])));
          $wk2 = strtotime(date("H:i:s", strtotime($worktm[1])));
          $lunch = explode(':::',$lunchhr[0]['lunch_hours']);
          $tm1 = strtotime(date("H:i:s", strtotime($lunch[0])));
          $tm2 = strtotime(date("H:i:s", strtotime($lunch[1])));
       
          if( $wk1 <= $tm1 && $tm1 <= $wk2 && $wk1 <= $tm2 && $tm2 <= $wk2 ){
            $temp_tmdr = $tm2 - $tm1;
            $tmdr = $temp_tmdr/3600;
            //return $tmdr; 
			return 0;
          }else{
            return 0;
          }
    }else{
      return 0;
    }
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
  return  date($configformat, strtotime($date));
}

if($_POST['task'] == 'popup_edit_task'){

  if($_SESSION['user_type'] == 'employee'){
    $readonly = 'readonly';
    $disable = 'disabled';
  }else{
    $readonly = '';
    $disable = '';
  }
  if(isset($_POST['ttype']) && $_POST['ttype'] == 'queue'){
    $taskDetail = $dclass->select("* ","tbl_task"," AND t_id= ".$_POST['task_id']);
  }else{
    $taskDetail = $dclass->select("task.*,CONCAT(u.fname,' ', u.lname) AS operator_name ","tbl_task as task inner join tbl_user as u on task.t_operator_id = u.user_id"," AND task.t_id= ".$_POST['task_id']);  
  }

 $html = ' <form name="popup_edit_task" id="popup_edit_task" method="post" action="" enctype="multipart/form-data">
 <div class="modal-dialog task-detail">
    <div class="modal-content task-box">
      <div class="task-title">Task Details</div>
      <div class="task-form">
        <div class="task-form-left">
            <label class="task-label">Task Name *<span>:</span></label>
            <input type="text" class="input-box validate[required]" name="t_title" '.$readonly.' value="'.$taskDetail[0]['t_title'].'">
        </div>
        <div class="task-form-right">
            <label class="task-label">Description<span>:</span></label>
            <textarea placeholder="Description" class="tab-textarea mytextarea" '.$readonly.' id="t_description" name="t_description">'.$taskDetail[0]['t_description'].'</textarea>
        </div>
        <div class="clr"></div>'; 
        $team = $dclass->select("*","tbl_team"," ANd company_user_id=".$_SESSION['company_id']); 
        $html .='<div class="task-form-left">';
         $html .='<label class="task-label">Select Team *<span>:</span></label>
            <div class="form-group">
             <select id="t_team_id" name="t_team_id" '.$disable.' class="selectpicker validate[required]" data-live-search="true">
                <option value="">Choose Team</option>';
                foreach($team as $tm){ 
                    if($taskDetail[0]['t_team_id'] == $tm['tm_id']){ 
                        $selected = 'selected="selected"';
                    }else{
                        $selected = '';
                    }
                        $html .= "<option value='".$tm['tm_id']."' ".$selected."  >".$tm['tm_title']."</option>";
                     } 

                    $html .= '</select>   </div> '; 
            $html .='</div><div class="task-form-right">';
         
         $getOperator = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id = ".$taskDetail[0]['t_team_id']);
         $operator = $dclass->select("user_id,fname,lname","tbl_user"," AND user_id IN (".$getOperator[0]['uid'].") ");
            $html .='<label class="task-label">Operator *<span>:</span></label>
            <div class="form-group operators">
                <select id="t_operatorss" name="t_operators" '.$disable.' class="selectpicker validate[required]" data-live-search="true" title="Choose Operator">
                    <option value="">Choose Operator</option>';
                    foreach($operator as $ui){
                      if($taskDetail[0]['t_operator_id'] == $ui['user_id']){
                        $selected = "selected='selected'";
                      }else{
                        $selected = "";
                      }
                        $html .='<option value="'.$ui['user_id'].'" '.$selected.' >'.$ui['fname'].' '.$ui['lname'].'</option>'; 
                     }

                $html .='</select></div>';
            $html .='
        </div>';
        $start_time = date("h:i:s A", strtotime($taskDetail[0]['t_start_datetime']));
        $start_date = date("d M y", strtotime($taskDetail[0]['t_start_datetime']));
       
        if($taskDetail[0]['t_expected_deadline_date'] != '0000-00-00' && $taskDetail[0]['t_expected_deadline_date'] != ''){
          $deadline_date = date("d M y", strtotime($taskDetail[0]['t_expected_deadline_date']));
        }else{
          $deadline_date = '';
        }

        $html .='<div class="clr"></div>';
        $html .='<div class="task-form-left">
            <label class="task-label">Start time <span>:</span></label>
            <input type="text" value="'.$start_time.'" id="t_start_time_edit" name="t_start_time" readonly class="tab-input-small validate[required]" placeholder="Time">
        </div>
        <div class="task-form-right">
            <label class="task-label">Start Date<span>:</span></label>
            <input type="text" placeholder="Start Date" value="'.$start_date.'" class="tab-input-small last-fild validate[required]" name="t_start_date" id="t_start_date_edit">
        </div>
        <div class="clr"></div>
        <div class="task-form-left">
            <label class="task-label">Duration <span>:</span></label>
            <input type="text" placeholder="Hr" value="'.str_replace(' hours','',$taskDetail[0]['t_duration']).'" class="tab-input-small validate[required][custom[number]]" maxlength="5" name="t_duration" id="t_duration_edit">
        </div>';

        $html .='<div class="task-form-right">
            <label class="task-label">Deadline <span>:</span></label>
            <input type="text" placeholder="Date" class="tab-input-small "  value="'.$deadline_date.'" name="t_deadline_date" id="t_deadline_date_edit'.$readonly.'" '.$readonly.'>
        </div><div class="clr"></div>';
        $html .= '<div class="task-form-left">
            <label class="task-label">Set Status <span>:</span></label>
              <div class="form-group">
              <select id="t_status" name="t_status" class="selectpicker" data-live-search="true" title="select status">
              <option value="open" '.($taskDetail[0]['t_status'] == 'open' ? 'selected': '').'>Open</option>
              <option value="complete" '.($taskDetail[0]['t_status'] == 'complete' ? 'selected': '').'>Complete</option>';
             if($_SESSION['user_type'] != 'employee'){
              $html .= '<option value="close" '.($taskDetail[0]['t_status'] == 'close' ? 'selected': '').'>Close </option>';
            }
           $html .= '</select>
              </div>
        </div>  
        <div class="task-form-right">
            <label class="task-label">Set Priority<span>:</span></label>
              <div class="form-group priority_div">
              <select id="t_priority" name="t_priority" '.$disable.' class="selectpicker" data-live-search="true" title="select priority">
              <option value="low" id="low_pri" '.($taskDetail[0]['t_priority'] == 'low' ? 'selected': '').'><p class="low_pri">Low </p> </option>
              <option value="high" '.($taskDetail[0]['t_priority'] == 'high' ? 'selected': '').' id="high_pri"><p class="high_pri">High</p></option>
              <option value="urgent" '.($taskDetail[0]['t_priority'] == 'urgent' ? 'selected': '').' id="urgent_pri"><p class="urgent_pri">Urgent</p></option>
              <option value="critical" '.($taskDetail[0]['t_priority'] == 'critical' ? 'selected': '').' id="critical_pri"><p class="critical_pri">Critical </p> </option>
              </select>
              </div>
        </div><div class="clr"></div> ';
        $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);    

         $html .= '<div class="user-choose">
            <div class="user-choose-box">
                <div class="tab-title">Choose Client *</div>
               <div class="form-group">
                    <select id="client_id_edit" name="client_id" '.$disable.' class="selectpicker validate[required]" data-live-search="true" title="Please select a client ...">
                        <option value="">Choose Client</option>';
                         foreach($clientList as $cl){
                          $selected = '';
                          if($taskDetail[0]['t_cl_id'] == $cl['cl_id']){ $selected = ' selected';}
                              $html .= '<option value="'.$cl['cl_id'].'" '.$selected.'>'.$gnrl->trunc_string($cl['cl_company_name'],15).'</option>';
                          } 
                   $html .= ' </select>
                </div>
            </div>';
          
      $rhtml = '<div class="user-choose-box">
                <div class="tab-title">Choose Project *</div>
                <div class="form-group project_div">
                <select id="project_id" name="project_id" '.$disable.' class="selectpicker validate[required]" data-live-search="true" title="Please select a project ...">
                <option value="">Choose Project</option>';
      $retIns = $dclass->select(' * ','tbl_project'," AND pr_cl_id =".$taskDetail[0]['t_cl_id']);
      foreach($retIns as $cl){
        if($taskDetail[0]['t_pr_id'] == $cl['pr_id']){
          $selected = "selected='selected'";
        }else{
          $selected = "";
        }

        $rhtml .='<option value="'.$cl['pr_id'].'" '.$selected.' >'.$gnrl->trunc_string($cl['pr_title'],50).'</option>';
      }
      $rhtml .= '</select>';
       $html .= $rhtml;        
            $html .= '</div></div>
            <div class="user-choose-box">
                <div class="button-box poupup-button-box">
                    <a class="delete-btn" id="cancel_edit_task"href="javascript:void(0)">Cancel</a>';
                    if($_SESSION['user_type'] != 'employee'){
                      $html .= '<a class="save-btn" id="save_edit_task" href="javascript:void(0)">Save</a><a href="javascript:void(0)" taskid="'.$taskDetail[0]['t_id'].'" id="task_delete" style="min-width:0px;" class="delete-btn"><img src="'.SITE_URL.'images/del.png" width="100%" height="25px"></a>';
                    }
                    $html .= '<input type="hidden" value="'.$taskDetail[0]['t_id'].'" id="t_id" name="t_id">
                    <input type="hidden" value="'.$_POST['user_id'].'" id="current_user_id" name="current_user_id">
                    <input type="hidden" value="edit" id="script" name="script">
                    <input type="hidden" name="ctype" id="ctype" value="'.$taskDetail[0]['t_type'].'">
                </div>
            </div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  </div>';
  echo $html;


}else if($_POST['task'] == 'popup_edit_task_queue'){
  if($_SESSION['user_type'] == 'employee'){
    $readonly = 'readonly';
    $disable = 'disabled';
  }else{
    $readonly = '';
    $disable = '';
  }
  if(isset($_POST['ttype']) && $_POST['ttype'] == 'queue'){
    $taskDetail = $dclass->select("* ","tbl_task"," AND t_id= ".$_POST['task_id']);
  }else{
    $taskDetail = $dclass->select("task.*,CONCAT(u.fname,' ', u.lname) AS operator_name ","tbl_task as task inner join tbl_user as u on task.t_operator_id = u.user_id"," AND task.t_id= ".$_POST['task_id']);  
  }

 $html = ' <form name="popup_edit_task" id="popup_edit_task" method="post" action="" enctype="multipart/form-data">
 <div class="modal-dialog task-detail">
    <div class="modal-content task-box">
      <div class="task-title">Task Details</div>
      <div class="task-form">
        <div class="task-form-left">
            <label class="task-label">Task Name *<span>:</span></label>
            <input type="text" class="input-box validate[required]" name="t_title" '.$readonly.' value="'.$taskDetail[0]['t_title'].'">
        </div>

        <div class="task-form-right">
            <label class="task-label">Description<span>:</span></label>
            <textarea placeholder="Description" class="tab-textarea mytextarea" '.$readonly.' id="t_description" name="t_description">'.$taskDetail[0]['t_description'].'</textarea>
        </div>
        <div class="clr"></div>'; 

      
        /*$team = $dclass->select("*","tbl_team"," ANd company_user_id=".$_SESSION['company_id']); 

        $html .='<div class="task-form-left">';
         $html .='<label class="task-label">Select Team<span>:</span></label>
            <div class="form-group">
             <select id="t_team_id" name="t_team_id" '.$disable.' class="selectpicker " data-live-search="true">
                <option value="">Choose Team</option>';

                foreach($team as $tm){ 
                    if($taskDetail[0]['t_team_id'] == $tm['tm_id']){ 
                        $selected = 'selected="selected"';
                    }else{
                        $selected = '';
                    }
                        $html .= "<option value='".$tm['tm_id']."' ".$selected."  >".$tm['tm_title']."</option>";
                     } 

                    $html .= '</select>   </div> '; 
            $html .='
        </div>

        <div class="task-form-right">';
            $html .='<label class="task-label">Operator *<span>:</span></label>
            <div class="form-group operators">
                <select id="t_operatorss" name="t_operators" '.$disable.' class="selectpicker validate[required]" data-live-search="true" title="Choose Operator">
                    <option>Choose Operator</option>
                    <option value='.$taskDetail[0]['t_operator_id'].' selected>'.$taskDetail[0]['operator_name'].'</option>
                </select></div>';
             
            $html .='
        </div>';*/
        $start_time = date("h:i:s A", strtotime($taskDetail[0]['t_start_datetime']));
        $start_date = date("d M y", strtotime($taskDetail[0]['t_start_datetime']));
        //$start_date = phpdateformat($taskDetail[0]['t_start_datetime']);
       
        if($taskDetail[0]['t_expected_deadline_date'] != '0000-00-00' && $taskDetail[0]['t_expected_deadline_date'] != ''){
          $deadline_date = date("d M y", strtotime($taskDetail[0]['t_expected_deadline_date']));
        }else{
          $deadline_date = '';
        }


        $html .='<div class="clr"></div>';
        
       /* $html .='<div class="task-form-left">
            <label class="task-label">Start time <span>:</span></label>
            <input type="text" value="'.$start_time.'" id="t_start_time_edit" name="t_start_time" class="tab-input-small validate[required]" placeholder="Time">
        </div>
        <div class="task-form-right">
            <label class="task-label">Start Date<span>:</span></label>
            <input type="text" placeholder="Start Date" value="'.$start_date.'" class="tab-input-small last-fild validate[required]" name="t_start_date" id="t_start_date_edit">
        </div>
        <div class="clr"></div>
        <div class="task-form-left">
            <label class="task-label">Duration <span>:</span></label>
            <input type="text" placeholder="Hr" value="'.str_replace(' hours','',$taskDetail[0]['t_duration']).'" class="tab-input-small validate[required][custom[number],min[1]]]" maxlength="2" name="t_duration" id="t_duration_edit">
        </div>';*/

        $html .='<div class="task-form-left">
            <label class="task-label">Deadline <span>:</span></label>
            <input type="text" placeholder="Date" class="tab-input-small "  value="'.$deadline_date.'" name="t_deadline_date" id="t_deadline_date_edit'.$readonly.'" '.$readonly.'>
        </div>';


        $html .= '<div class="task-form-right">
            <label class="task-label">Set Status <span>:</span></label>
              <div class="form-group">
              <select id="t_status" name="t_status" class="selectpicker" data-live-search="true" title="select status">
              <option value="open" '.($taskDetail[0]['t_status'] == 'open' ? 'selected': '').'>Open</option>
              <option value="complete" '.($taskDetail[0]['t_status'] == 'complete' ? 'selected': '').'>Complete</option>';
             if($_SESSION['user_type'] != 'employee'){
              $html .= '<option value="close" '.($taskDetail[0]['t_status'] == 'close' ? 'selected': '').'>Close </option>';
            }
           $html .= '</select>
              </div>
        </div>  
            
          <div class="clr"></div>  
        <div class="task-form-left">
            <label class="task-label">Set Priority<span>:</span></label>
              <div class="form-group priority_div">
              <select id="t_priority" name="t_priority" '.$disable.' class="selectpicker" data-live-search="true" title="select priority">
              <option value="low" id="low_pri" '.($taskDetail[0]['t_priority'] == 'low' ? 'selected': '').'><p class="low_pri">Low </p> </option>
              <option value="high" '.($taskDetail[0]['t_priority'] == 'high' ? 'selected': '').' id="high_pri"><p class="high_pri">High</p></option>
              <option value="urgent" '.($taskDetail[0]['t_priority'] == 'urgent' ? 'selected': '').' id="urgent_pri"><p class="urgent_pri">Urgent</p></option>
              <option value="critical" '.($taskDetail[0]['t_priority'] == 'critical' ? 'selected': '').' id="critical_pri"><p class="critical_pri">Critical </p> </option>
              </select>
              </div>
        </div><div class="clr"></div> ';


         $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);    

         $html .= '<div class="user-choose">
            <div class="user-choose-box">
                <div class="tab-title">Choose Client *</div>
               <div class="form-group">
                    <select id="client_id_edit" name="client_id" '.$disable.' class="selectpicker validate[required]" data-live-search="true" title="Please select a client ...">
                        <option value="">Choose Client</option>';
                         foreach($clientList as $cl){
                          $selected = '';
                          if($taskDetail[0]['t_cl_id'] == $cl['cl_id']){ $selected = ' selected';}
                                 $html .= '<option value="'.$cl['cl_id'].'" '.$selected.'>'.$gnrl->trunc_string($cl['cl_company_name'],15).'</option>';

             } 

                   $html .= ' </select>
                </div>
                
            </div>';
          
      $rhtml = '<div class="user-choose-box">
                <div class="tab-title">Choose Project *</div>
                <div class="form-group project_div">
                <select id="project_id" name="project_id" '.$disable.' class="selectpicker validate[required]" data-live-search="true" title="Please select a project ...">
                <option value="">Choose Project</option>';
      $retIns = $dclass->select(' * ','tbl_project'," AND pr_id =".$taskDetail[0]['t_pr_id']);
      foreach($retIns as $cl){
        $rhtml .='<option value="'.$cl['pr_id'].'" selected >'.$gnrl->trunc_string($cl['pr_title'],15).'</option>';
      }
      $rhtml .= '</select>';
       $html .= $rhtml;        

            $html .= '</div></div>

            <div class="user-choose-box">
                
                <div class="button-box poupup-button-box">
                    <a class="delete-btn" id="cancel_edit_task"href="javascript:void(0)">Cancel</a>                                
                    <a class="save-btn" id="save_edit_task" href="javascript:void(0)">Save</a>';
                    if($_SESSION['user_type'] != 'employee'){
                      $html .= '<a href="javascript:void(0)" taskid="'.$taskDetail[0]['t_id'].'" id="task_delete" style="min-width:0px;" class="delete-btn"><img src="'.SITE_URL.'images/del.png" width="100%" height="25px"></a>';
                    }
                    $html .= '<input type="hidden" value="'.$taskDetail[0]['t_id'].'" id="t_id" name="t_id">
                    <input type="hidden" value="edit" id="script" name="script">
                    <input type="hidden" name="ctype" id="ctype" value="'.$taskDetail[0]['t_type'].'">
                </div>
                
            </div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  </div>';
  echo $html;

}
else if($_POST['task'] == 'popup_add_task'){
  $lh = getLunchDuration($_POST['team_id']);
  if($_POST['viewmode'] == 'month'){
      $timedt = getTimeDetail($_POST['team_id']); 
      $start_time = date("h:i:s A", strtotime($timedt[0]));
      $start_date = date("d M y", strtotime($_POST['sdate'])); 
      $trim_time1 = date("H:i:s", strtotime($timedt[0]));
      $trim_time2 = date("H:i:s", strtotime($timedt[1]));
      $taskdur = strtotime($trim_time2) - strtotime($trim_time1);
      $taskduration = $taskdur/3600;
      $includelunch = checkLunch($trim_time1,$trim_time2,$_POST['team_id']);
      if($includelunch > 0){
        $taskduration = $taskduration - $lh; 
      }   
  }else{
      $start_time = date("h:i:s A", strtotime($_POST['sdate']));
      $start_date = date("d M y", strtotime($_POST['sdate']));
      $trim_time1 = date("H:i:s", strtotime($_POST['sdate']));
      $trim_time2 = date("H:i:s", strtotime($_POST['edate']));
      $taskdur = strtotime($trim_time2) - strtotime($trim_time1);
      $taskduration = $taskdur/3600;

      $includelunch = checkLunch($trim_time1,$trim_time2,$_POST['team_id']);
      if($includelunch > 0){
        $taskduration = $taskduration - $lh; 
      }
  }
  if($taskduration < 0){
      $taskduration = 0;
  }
    $chkdate = date("y-m-d", strtotime($_POST['sdate']));
    if(checkholiday($chkdate)){
         echo "not_allow";
         die(); 
    }
 $html = ' <form name="popup_edit_task" id="popup_edit_task" method="post" action="" enctype="multipart/form-data">
 <div class="modal-dialog task-detail">
    <div class="modal-content task-box">
      <div class="task-title">Task Details</div>
      <div class="task-form">
        <div class="task-form-left">
            <label class="task-label">Task Name *<span>:</span></label>
            <input type="text" class="input-box validate[required]" name="t_title" value="">
        </div>
        <div class="task-form-right">
            <label class="task-label">Description<span>:</span></label>
            <textarea placeholder="Description" class="tab-textarea mytextarea" id="t_description" name="t_description"></textarea>
        </div>
        <div class="clr"></div>'; 
        $html .='<div class="clr"></div>
        <div class="task-form-left">
            <label class="task-label">Start time *<span>:</span></label>
            <input type="text" value="'.$start_time.'" id="t_start_time_edit" readonly name="t_start_time" class="tab-input-small validate[required]" placeholder="Time">
        </div>
        <div class="task-form-right">
            <label class="task-label">Start Date *<span>:</span></label>
            <input type="text" placeholder="Start Date" value="'.$start_date.'" class="tab-input-small last-fild validate[required]" name="t_start_date" id="t_start_date_edit">
        </div>
        <div class="clr"></div>
        <div class="task-form-left">
            <label class="task-label">Duration *<span>:</span></label>
            <input type="text" placeholder="Hr" value="'.$taskduration.'" class="tab-input-small validate[required][custom[number]]" maxlength="5" name="t_duration" id="t_duration_edit">
        </div>
        <div class="task-form-right">
            <label class="task-label">Deadline <span>:</span></label>
            <input type="text" placeholder="Date" class="tab-input-small " value="" name="t_deadline_date" id="t_deadline_date_edit">
        </div>';
        $html .= '<div class="task-form-left">
            <label class="task-label">Set Status <span>:</span></label>
              <div class="form-group">
              <select id="t_status" name="t_status" class="selectpicker" data-live-search="true" title="select status">
              <option value="open">Open</option>
              <option value="complete">Complete</option>
              <option value="close">Close </option>
              </select>
              </div>
        </div>  
        <div class="task-form-right">
            <label class="task-label">Set Priority<span>:</span></label>
              <div class="form-group priority_div">
              <select id="t_priority" name="t_priority" class="selectpicker" data-live-search="true" title="select priority">
              <option value="low" id="low_pri"><p class="low_pri">Low </p> </option>
              <option value="high" id="high_pri"><p class="high_pri">High</p></option>
              <option value="urgent" id="urgent_pri"><p class="urgent_pri">Urgent</p></option>
              <option value="critical" id="critical_pri"><p class="critical_pri">Critical </p> </option>
              </select>
              </div>
        </div> ';
         $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);    
         $html .= '<div class="user-choose">
            <div class="user-choose-box">
                <div class="tab-title">Choose Client *</div>
               <div class="form-group">
                    <select id="client_id_edit" name="client_id" class="selectpicker validate[required]" data-live-search="true" title="Please select a client ...">
                        <option value="">Choose Client</option>';
                         foreach($clientList as $cl){
                          $selected = '';
                          //if($taskDetail[0]['t_cl_id'] == $cl['cl_id']){ $selected = ' selected';}
                                 $html .= '<option value="'.$cl['cl_id'].'" '.$selected.'>'.$gnrl->trunc_string($cl['cl_company_name'],15).'</option>';
                          } 
                   $html .= ' </select>
                </div>
            </div>';
      $html .= '<div class="user-choose-box">
                <div class="tab-title">Choose Project *</div>
                <div class="form-group project_div">
                <select id="project_id" name="project_id" class="selectpicker validate[required]" data-live-search="true" title="Please select a project ...">
                  <option value="">Choose Project</option>
                </select>';
            $html .= '</div></div>
            <div class="user-choose-box">';
            $tm_id = $dclass->select("*","tbl_team_detail"," AND user_id = ".$_POST['user_id']);
            $html .= '<div class="button-box">
                                <a class="delete-btn" id="cancel_edit_task"href="javascript:void(0)">Cancel</a>
                                <a class="save-btn" id="save_edit_task" href="javascript:void(0)">Save</a>
                                <input type="hidden" value="" id="t_id" name="t_id">
                                <input type="hidden" value="'.$tm_id[0]['tm_id'].'" id="t_team_id" name="t_team_id">
                                <input type="hidden" value="add" id="script" name="script">
                                <input type="hidden" value="'.$_POST['user_id'].'" id="t_operatorss" name="t_operators">
                                <input type="hidden" value="calendar" id="ctype" name="ctype">
                                <input type="hidden" value="viewpage" id="addfrom" name="addfrom">
                            </div>
            </div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  </div>';
  echo $html;
}
else if($_POST['task'] == 'copy_task'){
$getTeamid = $dclass->select("t_team_id,t_operator_id","tbl_task"," and t_id=".$_POST['copy_task_id']);
$html = ' <form name="copy_task" id="copy_task" method="post" action="" enctype="multipart/form-data">
 <div class="modal-dialog task-detail">
    <div class="modal-content task-box">
      <div class="task-title">Task Details</div>
      <div class="task-form">'; 
        $html .='<div class="task-form-left">
            <label class="task-label">Start time <span>:</span></label>
            <input type="text" value="" id="copy_start_time" name="copy_start_time" class="tab-input-small validate[required]" placeholder="Time">
        </div>
        <div class="task-form-right">
            <label class="task-label">Start Date<span>:</span></label>
            <input type="text" placeholder="Start Date" value="" class="tab-input-small last-fild validate[required]" name="copy_start_date" id="copy_start_date">
        </div>
        <div class="clr"></div>';
            $html .= '<div class="user-choose-box">';
            $html .= '<div class="button-box">
                                <a class="delete-btn" id="cancel_edit_task"href="javascript:void(0)">Cancel</a>
                                <a class="save-btn" id="paste_task" href="javascript:void(0)">Paste</a>
                                <input type="hidden" value="'.$_POST['copy_task_id'].'" id="t_id" name="t_id">
                                <input type="hidden" value="'.$getTeamid[0]['t_operator_id'].'" id="operator_id" name="operator_id">
                                <input type="hidden" value="'.$getTeamid[0]['t_team_id'].'" id="copy_team_id" name="copy_team_id">
                                <input type="hidden" value="paste" id="script" name="script">
                                <input type="hidden" value="calendar" id="ctype" name="ctype">
                            </div>
            </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  </div>';
  echo $html;
} 