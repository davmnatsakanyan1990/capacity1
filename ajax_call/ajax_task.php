<?php 
require_once("../config/dbconfig.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
global $mail;
session_start();

function str_lreplace($search, $replace, $subject){
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}
function getWorkinghours($team_id=''){
 global $dclass;
  if(isset($team_id) && $team_id != ''){

    $teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id = '".$_SESSION['company_id']."' ");
    if(count($teamWorkDay) > 0){
      $time = explode(':::',$teamWorkDay[0]['working_time']);
      $time1 = strtotime($time[0]);
      $time2 = strtotime($time[1]);
      $diff = $time2 - $time1;
      $workhour = $diff/3600;
      //echo 'work hour->'.$workhour.'<br>';
      //check for company lunch hours
      /*$LunchHours = $dclass->select("*","tbl_lunch_hours"," AND team_id = '".$team_id."' AND show_in_calender = 'yes' AND company_user_id = '".$_SESSION['company_id']."' ");
      if(count($LunchHours) > 0){
        $lhr = explode(':::',$LunchHours[0]['lunch_hours']);
        $lunch1 = strtotime($lhr[0]);
        $lunch2 = strtotime($lhr[1]);
        $diff1 = $lunch2 - $lunch1;
        $lunch_hr = $diff1/3600;
        $workhour_of_day = $workhour - $lunch_hr;
      }else{
        $workhour_of_day = $workhour;
      }*/
      $workhour_of_day = $workhour;

    }else{
      $teamWorkDay_ge = $dclass->select("*","tbl_working_day_time"," AND  type= 'general' ");
      $time = explode(':::',$teamWorkDay_ge[0]['working_time']);
      $time1 = strtotime($time[0]);
      $time2 = strtotime($time[1]);
      $diff = $time2 - $time1;
      $workhour = $diff/3600;

      $workhour_of_day = $workhour;
      //$workhour_of_day = '8';
    }
  
  }else{
    //$workhour_of_day = '8';
    $teamWorkDay_ge = $dclass->select("*","tbl_working_day_time"," AND  type= 'general' ");
    $time = explode(':::',$teamWorkDay_ge[0]['working_time']);
    $time1 = strtotime($time[0]);
    $time2 = strtotime($time[1]);
    $diff = $time2 - $time1;
    $workhour = $diff/3600;

    $workhour_of_day = $workhour;
  }
  return $workhour_of_day;
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
function getfreehr($bshr,$s_time,$e_time){

//echo $sdate.' '.$curr_date;

  /*$temp_s = '10:00:00';
  $temp_e = '19:00:00';*/
  if($s_time != ''){
    $temp_s = $s_time;  
  }else{
    $temp_s = '10:00:00';
  }
  if($e_time != ''){
    $temp_e = $e_time;  
  }else{
    $temp_s = '19:00:00';
  }

  $freehr = array();

  foreach($bshr as $busy){
      $busy_temp = explode(':::',$busy);
     
      if( strtotime($temp_s) < strtotime($busy_temp[0])){
        
        $freehr[] = $temp_s.':::'.$busy_temp[0];
     
      }
      $temp_s = $busy_temp[1];
     
  }
  
  if(strtotime($temp_s) <  strtotime($temp_e)){
    $freehr[] = $temp_s.':::'.$temp_e;
  }
 
return $freehr;

}
function checkfornextAvailabeltime($userid,$startdate,$enddate,$work_hour,$t_start_datetime,$task_end,$caldr,$postdr,$s_time,$e_time,$team_id,$t_id){
 
global $dclass;
$tskArr = array();


if($t_id != ''){
  $chktime = $dclass->select("t_start_datetime,t_end_datetime,t_duration","tbl_task"," 
    AND t_operator_id = ".$userid." AND t_id != ".$t_id." AND t_type = 'calendar'
      AND ((t_start_datetime BETWEEN '".$startdate."' AND '".$enddate."') OR (t_end_datetime BETWEEN '".$startdate."' AND '".$enddate."') )
   ORDER BY t_start_datetime asc");
}else{
    $chktime = $dclass->select("t_start_datetime,t_end_datetime,t_duration","tbl_task"," 
    AND t_operator_id = ".$userid." AND t_type = 'calendar'
      AND ((t_start_datetime BETWEEN '".$startdate."' AND '".$enddate."') OR (t_end_datetime BETWEEN '".$startdate."' AND '".$enddate."') )
   ORDER BY t_start_datetime asc");
}


 $ex_start_time = date("H:i:s", strtotime($startdate));
 $ex_end_time = date("H:i:s", strtotime($enddate));
 $lh = getLunchDuration($team_id);


if(count($chktime) > 0){


      $dr = 0;
      foreach($chktime as $ch){
        $chr = str_replace(' hours','',$ch['t_duration']);
        $dr += $chr;
      }

      if($dr < $work_hour){
        
        $busyHr = array();
        foreach($chktime as $chk){
            $start_time = date("H:i:s", strtotime($chk['t_start_datetime']));
            $end_time = date("H:i:s", strtotime($chk['t_end_datetime']));
            $busyHr[] = $start_time.':::'.$end_time;
        }
        
        $freehr = getfreehr($busyHr,$ex_start_time,$ex_end_time); 
        
        foreach($freehr as $fr){
                
                $fr_temp = explode(':::',$fr);

                $start_temp = date("Y-m-d", strtotime($startdate)); 
                $start = $start_temp.' '.$fr_temp[0];

                $end_temp = date("Y-m-d", strtotime($end_time)); 
                $enddate = $start_temp.' '.$fr_temp[1];

                $timeslot1 = strtotime($fr_temp[0]);
                $timeslot2 = strtotime($fr_temp[1]);
                   
                $tdr_temp = $timeslot2 - $timeslot1;
                $tdr = $tdr_temp/3600;

                $tdr =  number_format($tdr,2);
                
                $includelunch = checkLunch($fr_temp[0],$fr_temp[1],$team_id);

                if($includelunch > 0){
                    $tdr = $tdr - $lh;
                }

                   //echo $caldr.' + '.$tdr.' <= '.$postdr; 
                    if($caldr + $tdr <= $postdr){
                       $caldr += $tdr;
                       $flg = 'true';
                    }else{
                       $tdr = $postdr - $caldr;
                      
                      // $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($start)) . " +".round($tdr)." hours "));
                      $reminders = explode('.',$tdr);
            
                      $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($start)) . " +".$reminders[0]." hours ")); 
                        if(isset($reminders[1]) && $reminders[1] != 0){
                          $exminut = ($reminders[1] / 10) * 60;
                          $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$exminut." minutes "));          
                        }

                        $end_time = date("H:i:s", strtotime($enddate)); 

                        $includelunch = checkLunch($fr_temp[0],$end_time,$team_id);
                        if($includelunch > 0){
                          //$tdr = $tdr - $lh; 
                          $reminders = explode('.',$lh);

                           $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$reminders[0]." hours ")); 
                            if(isset($reminders[1]) && $reminders[1] != 0){
                              $exminut = ($reminders[1] / 10) * 60;
                              $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$exminut." minutes "));          
                          }
                        }
                        $caldr += $tdr;
                        $flg = 'false';

                    }


                  if($tdr > 0 ){
                    $tskArr[] = array(
                        "td"=>$dr,
                        "startdate"=>$start,
                        "enddate"=>$enddate,
                        "task_duration"=>$tdr,
                        "total_duration"=>$caldr,
                        "flage"=>$flg,
                      );
                  }
         }
        return $tskArr;
      
      }else{
          //$caldr += $tdr;
        $flg = 'true';
        $tskArr[] = array(
                    "td"=>$dr,
                    "startdate"=>'',
                    "enddate"=>'',
                    "task_duration"=>'',
                    "total_duration"=>$caldr,
                    "flage"=>$flg,
                );
        
           return $tskArr;
      
      }


}else{

              $td = 0;
              $timeslot1 = strtotime($ex_start_time);
              $timeslot2 = strtotime($ex_end_time);
              $tdr_temp = $timeslot2 - $timeslot1;
              $tdr = $tdr_temp/3600;
              $tdr =  number_format($tdr,2);
              $includelunch = checkLunch($ex_start_time,$ex_end_time,$team_id);
              if($includelunch > 0){
                $tdr = $tdr - $lh; 
              }

              if($caldr + $tdr <= $postdr){
                 $caldr += $tdr;
                 $flg = 'true';
              }else{
                 $tdr = $postdr - $caldr;
                 $reminders = explode('.',$tdr);
      
            
            $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($startdate)) . " +".$reminders[0]." hours ")); 
            if(isset($reminders[1]) && $reminders[1] != 0){
              $exminut = ($reminders[1] / 10) * 60;
              $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$exminut." minutes "));          
            }
            //$enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($startdate)) . " +".round($tdr)." hours "));
            $end_time = date("H:i:s", strtotime($enddate)); 

            $includelunch = checkLunch($ex_start_time,$end_time,$team_id);
            if($includelunch > 0){
                //$tdr = $tdr - $lh;
                $reminders = explode('.',$lh);

                  $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$reminders[0]." hours ")); 
                  if(isset($reminders[1]) && $reminders[1] != 0){
                      $exminut = ($reminders[1] / 10) * 60;
                      $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$exminut." minutes "));          
                  }
                  //$enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($startdate)) . " +".round($tdr)." hours "));
            }
                  $caldr += $tdr;
                  $flg = 'false';
              } 
   
              if($tdr > 0){  
                $tskArr[] = array(
                    "td"=>$td,
                    "startdate"=>$startdate,
                    "enddate"=>$enddate,
                    "task_duration"=>$tdr,
                    "total_duration"=>$caldr,
                    "flage"=>$flg,
                );
              }
              
      return $tskArr;

  }
  
}
function checkfornextAvailabeltime_05032016($userid,$startdate,$enddate,$work_hour,$t_start_datetime,$task_end,$caldr,$postdr,$s_time,$e_time,$team_id,$t_id){
 
global $dclass;
  $tskArr = array();
 //get task list

if($t_id != ''){
  $chktime = $dclass->select("t_start_datetime,t_end_datetime,t_duration","tbl_task"," 
    AND t_operator_id = ".$userid." AND t_id != ".$t_id." AND t_type = 'calendar'
      AND ((t_start_datetime BETWEEN '".$startdate."' AND '".$enddate."') OR (t_end_datetime BETWEEN '".$startdate."' AND '".$enddate."') )
   ORDER BY t_start_datetime asc");
}else{
    $chktime = $dclass->select("t_start_datetime,t_end_datetime,t_duration","tbl_task"," 
    AND t_operator_id = ".$userid." AND t_type = 'calendar'
      AND ((t_start_datetime BETWEEN '".$startdate."' AND '".$enddate."') OR (t_end_datetime BETWEEN '".$startdate."' AND '".$enddate."') )
   ORDER BY t_start_datetime asc");
}

$ex_start_time = date("H:i:s", strtotime($startdate));
$ex_end_time = date("H:i:s", strtotime($enddate));
$lh = getLunchDuration($team_id);



if(count($chktime) > 0){


      $dr = 0;
      foreach($chktime as $ch){
        $chr = str_replace(' hours','',$ch['t_duration']);
        $dr += $chr;
      }

    
      if($dr < $work_hour){
        
        $busyHr = array();
        foreach($chktime as $chk){
            $start_time = date("H:i:s", strtotime($chk['t_start_datetime']));
            $end_time = date("H:i:s", strtotime($chk['t_end_datetime']));
            $busyHr[] = $start_time.':::'.$end_time;
        }
        
        $freehr = getfreehr($busyHr,$ex_start_time,$ex_end_time); 
        
        foreach($freehr as $fr){
                
                $fr_temp = explode(':::',$fr);

                $start_temp = date("Y-m-d", strtotime($startdate)); 
                $start = $start_temp.' '.$fr_temp[0];

                $end_temp = date("Y-m-d", strtotime($end_time)); 
                $end = $start_temp.' '.$fr_temp[1];


                $timeslot1 = strtotime($fr_temp[0]);
                $timeslot2 = strtotime($fr_temp[1]);
                   
                $tdr_temp = $timeslot2 - $timeslot1;
                $tdr = $tdr_temp/3600;

                $tdr =  number_format($tdr,2);
                
                $includelunch = checkLunch($fr_temp[0],$fr_temp[1],$team_id);

                if($includelunch > 0){
                  $tdr = $tdr - $lh; 
                }

                   //echo $caldr.' + '.$tdr.' <= '.$postdr; 
                    if($caldr + $tdr <= $postdr){
                       $caldr += $tdr;
                       $flg = 'true';
                    }else{
                       $tdr = $postdr - $caldr;
                       // $end = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($start)) . " +".round($tdr)." hours "));
                        
                        $reminders = explode('.',$tdr);
            
                  
                  $end = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($start)) . " +".$reminders[0]." hours ")); 
                  if(isset($reminders[1]) && $reminders[1] != 0){
                    $exminut = ($reminders[1] / 10) * 60;
                    $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end)) . " +".$exminut." minutes "));          
                }



                        $end_time = date("H:i:s", strtotime($end)); 

                        $includelunch = checkLunch($fr_temp[0],$end_time,$team_id);
                        if($includelunch > 0){
                          //$tdr = $tdr - $lh; 
                          $reminders = explode('.',$lh);

                           $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$reminders[0]." hours ")); 
                    if(isset($reminders[1]) && $reminders[1] != 0){
                      $exminut = ($reminders[1] / 10) * 60;
                      $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$exminut." minutes "));          
                  }
                        }
                        $caldr += $tdr;
                        $flg = 'false';

                    }
                  if($tdr > 0 ){
                    $tskArr[] = array(
                        "td"=>$dr,
                        "startdate"=>$start,
                        "enddate"=>$end,
                        "task_duration"=>$tdr,
                        "total_duration"=>$caldr,
                        "flage"=>$flg,
                      );
                  }
                                
            
         }

        return $tskArr;
      
      }else{
        


          //$caldr += $tdr;
        $flg = 'true';
        $tskArr[] = array(
                    "td"=>$dr,
                    "startdate"=>'',
                    "enddate"=>'',
                    "task_duration"=>'',
                    "total_duration"=>$caldr,
                    "flage"=>$flg,
                );
           return $tskArr;
      
      }

  
  
  
  
  }else{

            $td = 0;
                    $timeslot1 = strtotime($ex_start_time);
                    $timeslot2 = strtotime($ex_end_time);
                   
                    $tdr_temp = $timeslot2 - $timeslot1;
                    $tdr = $tdr_temp/3600;

                    $tdr =  number_format($tdr,2);

                    $includelunch = checkLunch($ex_start_time,$ex_end_time,$team_id);

                    if($includelunch > 0){
                      $tdr = $tdr - $lh; 
                    }

                    //echo '====='.$caldr.' + '.$tdr.'<='.$postdr.'==';
                    
                    if($caldr + $tdr <= $postdr){
                       $caldr += $tdr;
                       $flg = 'true';
                    }else{
                       $tdr = $postdr - $caldr;
                      // echo  $tdr.' = '.$postdr.' - '.$caldr;
                       $reminders = explode('.',$tdr);
            
                  
                  $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($startdate)) . " +".$reminders[0]." hours ")); 
                  if(isset($reminders[1]) && $reminders[1] != 0){
                    $exminut = ($reminders[1] / 10) * 60;
                    $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$exminut." minutes "));          
                }

                        //$enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($startdate)) . " +".round($tdr)." hours "));
                        $end_time = date("H:i:s", strtotime($enddate)); 

                        
                        $includelunch = checkLunch($ex_start_time,$end_time,$team_id);
                        if($includelunch > 0){
                          //$tdr = $tdr - $lh;
                          $reminders = explode('.',$lh);

                           $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$reminders[0]." hours ")); 
                    if(isset($reminders[1]) && $reminders[1] != 0){
                      $exminut = ($reminders[1] / 10) * 60;
                      $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($enddate)) . " +".$exminut." minutes "));          
                  }

                          //$enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($startdate)) . " +".round($tdr)." hours "));
                        
                            
                        }
                         $caldr += $tdr;
                        $flg = 'false';

                    } 
   
              if($tdr > 0){  
                $tskArr[] = array(
                    "td"=>$td,
                    "startdate"=>$startdate,
                    "enddate"=>$enddate,
                    "task_duration"=>$tdr,
                    "total_duration"=>$caldr,
                    "flage"=>$flg,
                );
              }

      return $tskArr;

  
  
  }
  

    

}
function extenddate($t_start_datetime,$t_team_id,$t_duration){

      $trim_time = date("H:i:s", strtotime($t_start_datetime));
      $t_start_date = date("Y-m-d", strtotime($t_start_datetime));
      
      $duration = $t_duration;
      // get total working hr of days
      $team_work_hour = getWorkinghours($t_team_id);
      
      //get start and end timing of day
      $timedt = getTimeDetail($t_team_id);
      
      $t_duration = str_replace(' hours','',$t_duration);

      
      if($team_work_hour > $t_duration){
        

        
          $time_st1 = strtotime($trim_time);
          $time_st2 = strtotime($timedt[1]);
          
          $taskdur = $time_st2 - $time_st1;
          $taskduration = $taskdur/3600;
           
          if($t_duration < $taskduration){
           
            $duration_temp = round($t_duration)." hours";
            $reminders = explode('.',$t_duration);
            
            //$end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($t_start_datetime)) . " +".$duration_temp." "));    
            $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($t_start_datetime)) . " +".$reminders[0]." hours ")); 
            if(isset($reminders[1]) && $reminders[1] != 0){
              $exminut = ($reminders[1] / 10) * 60;
              $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date)) . " +".$exminut." minutes "));          
          }

          }else{
            $reminder = ($t_duration - $taskduration);
            //echo $reminder;
            $reminders = explode('.',$reminder);
            
            $duration_temp = round($reminder)." hours";
            
            $end_date_temp = date("Y-m-d", strtotime(date("Y-m-d", strtotime($t_start_datetime)) . " +1 days"));
            $end_date_temp1 = $end_date_temp.' '.$trim_time;
            $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp1)) . " +".$reminders[0]." hours ")); 
            if(isset($reminders[1]) && $reminders[1] != 0){
              $exminut = ($reminders[1] / 10) * 60;
              $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date)) . " +".$exminut." minutes "));          
            }
          } 
        
      
      
      
      }else{
    
        $myextend =  round($t_duration / $team_work_hour);   
        
        $reminder = ($t_duration % $team_work_hour);

           $time_st1 = strtotime($trim_time);
           $time_st2 = strtotime($timedt[1]);
          

           $taskdur = $time_st2 - $time_st1;
           $taskduration = $taskdur/3600;


          if($reminder > $taskduration){
           
            $remindertemp = ($reminder - $taskduration);
            $reminders = explode('.',$remindertemp);
           
            $duration_temp = round($remindertemp)." hours";
            $myextend = $myextend + 1;
          }else{
            
            $duration_temp = round($reminder)." hours";            
            $reminders = explode('.',$reminder);
            
          }

                
        //$duration_temp = $reminder." hours";
        
        $end_date_temp = date("Y-m-d", strtotime(date("Y-m-d", strtotime($t_start_datetime)) . " +".$myextend." days "));      
        $end_date_temp1 = $end_date_temp.' '.$trim_time;

        //$end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp1)) . " +".$duration_temp." "));
        $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp1)) . " +".$reminders[0]." hours ")); 
        if(isset($reminders[1]) && $reminders[1] != 0){
          $exminut = ($reminders[1] / 10) * 60;
          $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date)) . " +".$exminut." minutes "));          
       }
        
      
      }

     return  $end_date;

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
function checkworkingday($team_id,$chkdate){
 global $dclass;
 
     $dayArr = array(
        "Sun"=>0,
        "Mon"=>1,
        "Tue"=>2,
        "Wed"=>3,
        "Thu"=>4,
        "Fri"=>5,
        "Sat"=>6
     );
    
    $workingdays = $dclass->select("working_days","tbl_working_day_time"," AND company_user_id=".$_SESSION['company_id']." ");
  
     
    if(count($workingdays) > 0){
     $work_day = explode(':::',$workingdays[0]['working_days']);
     
     $weekday = date("D", strtotime($chkdate));

     
     if(in_array($dayArr[$weekday],$work_day)){
        // its working day
        return 0;
     }else{
      // its off day increament loop
      return 1;
     }
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
function checkUserteam($userid){
  global $dclass;
  $data = $dclass->select("tm_id","tbl_team_detail"," AND user_id=".$userid);
  
  if(count($data) > 0){
    return 0;
  }else{
    return 1;
  }
}
function h2m($hours){
            $t = explode(".", $hours); 
            $h = $t[0];
            $m = 0; 
            if (isset($t[1])) { 
                $m = $t[1]; 
            } else { 
                $m = "00"; 
            } 
            $mm = ($h * 60); 
            $mm += ($m / 10) * 60;
            return $mm; 
}  
function checktaskoverlapping($userid,$startdate,$enddate,$task_id){
  global $dclass;

  if($task_id != ''){
    $checkoverlap1 = $dclass->select("t_id","tbl_task","  AND t_operator_id = ".$userid." AND t_type = 'calendar'
       AND t_id != '".$task_id."' 
       AND ((t_start_datetime > '".$startdate."'  AND  t_start_datetime < '".$enddate."') 
       OR (t_end_datetime > '".$startdate."' AND t_end_datetime  < '".$enddate."') ) ");

    $checkoverlap2 = $dclass->select("t_id","tbl_task","  AND t_operator_id = ".$userid." AND t_type = 'calendar'
       AND t_id != '".$task_id."' 
       AND (( '".$startdate."' > t_start_datetime AND  '".$startdate."' < t_end_datetime ) 
       OR ( '".$enddate."' >  t_start_datetime AND '".$enddate."' < t_end_datetime ) )");

  }else{
    $checkoverlap1 = $dclass->select("t_id","tbl_task"," AND t_operator_id = ".$userid." AND t_type = 'calendar'
       AND ((t_start_datetime > '".$startdate."'  AND t_start_datetime < '".$enddate."')  
       OR (t_end_datetime > '".$startdate."' AND t_end_datetime < '".$enddate."'))");

    $checkoverlap2 = $dclass->select("t_id","tbl_task","  AND t_operator_id = ".$userid." AND t_type = 'calendar'
       AND (( '".$startdate."' > t_start_datetime AND '".$startdate."' < t_end_datetime) 
       OR ('".$enddate."' > t_start_datetime AND '".$enddate."' < t_end_datetime ))");
  }


   if(count($checkoverlap1) > 0) {
    return 0;
    
   }
   else if(count($checkoverlap2) > 0){
    return 0;
   }
   else{
    $checkoverlap3 = $dclass->select("t_id","tbl_task","  AND t_operator_id = ".$userid." AND t_type = 'calendar'
       AND '".$startdate."' = t_start_datetime AND '".$enddate."' = t_end_datetime  ");
    if(count($checkoverlap3) > 0){
      return 0;
    }else{
      return 1; 
    }
  } 
}
function task_updated($t_id){
   global $dclass;    
      $get_users = $dclass->select("*","tbl_user"," AND user_id = '".$_SESSION['company_id']."' OR user_comp_id = '".$_SESSION['company_id']."' ");
      foreach($get_users as $ud){
        if($ud['user_id'] != $_SESSION['user_id']){
          $ins = array(
            "t_id"=>$t_id,
            "user_id"=>$ud['user_id'],
          );
          $id = $dclass->insert("tbl_task_update_notification",$ins);
        }
      }

}  
if(isset($_POST['tsk']) && $_POST['tsk'] != '' ){ 
	if($_POST['tsk'] == 'client_save'){


		if(isset($_POST['script']) && $_POST['script'] == 'edit'){
			 $ins = array(
        "cl_name"=>$_POST['cl_name'],
        "cl_company_name"=>$_POST['cl_company_name'],
        "cl_email"=>$_POST['cl_email'],
        "cl_phone"=>$_POST['cl_phone'],
      );

        $id = $dclass->update("tbl_client",$ins," cl_id=".$_POST['client_id']);
        $id = $_POST['client_id'];
			
      }else{
        $ins = array(
        "cl_name"=>$_POST['cl_name'],
        "cl_comp_user_id"=>$_SESSION['company_id'],
        "cl_company_name"=>$_POST['cl_company_name'],
        "cl_email"=>$_POST['cl_email'],
        "cl_phone"=>$_POST['cl_phone'],
        "cl_status"=>'active',
      );

      $id = $dclass->insert("tbl_client",$ins);  
      }
	    $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);
		$retIns = $dclass->select("*","tbl_project"," AND pr_cl_id = '".$id."' AND  pr_company_id=".$_SESSION['company_id']);
         
          $myhtml = '<div class="top-tab-td td-first">
                          <div class="full-box">
                           <div class="tab-title">Choose Client</div>
                                <div class="form-group">
                                <select id="client_id" name="client_id" class="selectpicker clientlist" data-live-search="true" title="Please select a client ...">
                                    <option value="">Choose Client</option>';
                                    foreach($clientList as $cl){ 
                                      
                                      if($cl['cl_id'] == $id){
                                        $sele = "selected='selected'";
                                      }else{
                                        $sele = '';
                                      }
                                        $myhtml .= '<option value="'.$cl['cl_id'].'" '.$sele.' >'.$gnrl->trunc_string($cl['cl_company_name'],15).'</option>';
                                    } 

                                $myhtml .= '</select>
                                </div>
                                <div class="clr5"></div>
                                <div class="add-btn">
                                  <a class="select-tab" rel="2">Add new</a></div>
                                <div class="clr20"></div>
                            </div>
                            <div class="full-box" style="border-top:1px solid #323232">
                              <div class="tab-title">Choose Project</div>
                                <div class="form-group project_div">';
                                    $myhtml .= '<select id="project_id" name="project_id" class="selectpicker projList" data-live-search="true" title="Please select a project ..."><option value="">Choose Project</option>';
          
                                  foreach($retIns as $cl){
                                    $myhtml .='<option value="'.$cl['pr_id'].'" >'.$gnrl->trunc_string($cl['pr_title'],15).'</option>';
                                  }
                                  $myhtml .= '</select>';
                                $myhtml .= '</div>
                                <div class="clr5"></div>
                                <div class="add-btn">
                                
                                <a class="select-tab" rel="1">Add new</a></div>

                                <div class="clr20"></div>
                            </div>
                        </div>';
           
      echo $myhtml;
  
  }else if($_POST['tsk'] == 'get_client_detail'){



      $clDetail = $dclass->select("*","tbl_client"," AND cl_id=".$_POST['client_id']);
            $rhtml = '<div class="full-box">
                              <div class="tab-title">Client Details</div>
                                <div class="tab-left-box">
                                  <input type="text" id="cl_company_name" name="cl_company_name" value="'.$clDetail[0]['cl_company_name'].'" class="tab-input validate[required]" placeholder="Company Name">
                                </div>
                                <div class="tab-left-box">
                                  <input type="text" value="'.$clDetail[0]['cl_name'].'" id="cl_name" name="cl_name" class="tab-input" placeholder="Contact Person ">
                                </div>
                                <div class="clr"></div>
                                <div class="tab-left-box">
                                  <input type="text" value="'.$clDetail[0]['cl_email'].'" id="cl_email" name="cl_email" class="tab-input validate[custom[email]]" placeholder="Email">
                                </div>
                                <div class="tab-left-box">
                                  <input type="text" value="'.$clDetail[0]['cl_phone'].'" id="cl_phone" name="cl_phone" class="tab-input validate[custom[phone]]" placeholder="Phone">
                                </div>
                                <div class="button-box">
                                    <input type="hidden" name="script" id="script" value="edit" >
                                    <button class="cancel-btn" id="client_cancel" type="button">Cancel</button>
                                    <button class="save-btn" id="client_save" type="button">Save</button>
                                </div>
                            </div>';
      echo $rhtml;
  
  
  
  }else if($_POST['tsk'] == 'add_new_client_form'){


      $rhtml = '<div class="full-box">
                              <div class="tab-title">Client Details</div>
                                <div class="tab-left-box">
                                  <input type="text" id="cl_company_name" name="cl_company_name" value="" class="tab-input validate[required]" placeholder="Company Name">
                                </div>
                                <div class="tab-left-box">
                                  <input type="text" value="" id="cl_name" name="cl_name" class="tab-input" placeholder="Contact Person ">
                                </div>
                                <div class="clr"></div>
                                <div class="tab-left-box">
                                  <input type="text" value="" id="cl_email" name="cl_email" class="tab-input validate[custom[email]]" placeholder="Email">
                                </div>
                                <div class="tab-left-box">
                                  <input type="text" value="" id="cl_phone" name="cl_phone" class="tab-input validate[custom[phone]]" placeholder="Phone">
                                </div>
                                <div class="button-box">
                                    <button class="cancel-btn" id="client_cancel" type="button">Cancel</button>
                                    <button class="save-btn" id="client_save" type="button">Save</button>
                                </div>
                            </div>'; 
    echo $rhtml;
  
  
  
  }else if($_POST['tsk'] == 'add_new_project_form'){


      $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);
      $rhtml = '<div class="full-box">
                              <div class="tab-title">Project Details</div>
                                <div class="tab-left-box">
                                  <input type="text" value="" name="pr_title" id="pr_title" class="tab-input validate[required]" placeholder="Name">
                                  <textarea name="pr_description" id="pr_description" class="tab-textarea" placeholder="Description"></textarea>
                                   <div class="form-group">
                                      <select id="cl_id" name="cl_id" class="selectpicker validate[required]" data-live-search="true" title="Choose Client">
                                    <option value="">Choose Client</option>';
                                    foreach($clientList as $cl){ 
                                      $rhtml .= '<option value="'.$cl['cl_id'].'" >'.$gnrl->trunc_string($cl['cl_company_name'],15).'</option>';
                                     } 
                                    $rhtml .= '</select>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="button-box">
                                       <button class="cancel-btn" id="project_cancel" type="button">Cancel</button>
                                      <button class="save-btn" id="project_save" type="button">Save</button>
                                  </div>

                                </div>
                                <div class="tab-right-box">
                                  <input type="text" name="pr_start_date" id="pr_start_date" value="" class="tab-input" placeholder="Start Date">
                                  <input type="text" name="pr_end_date" id="pr_end_date" value="" class="tab-input" placeholder="End Date">
                                  <div class="input-group demo2">
                                      
                                      <span>Project Colour</span>
                                      <select name="pr_color" id="pr_color">
                                        <option value="#7bd148">Green</option>
                                        <option value="#5484ed">Bold blue</option>
                                        <option value="#a4bdfc" selected="selected">Blue</option>
                                        <option value="#46d6db">Turquoise</option>
                                        <option value="#7ae7bf">Light green</option>
                                        <option value="#51b749">Bold green</option>
                                        <option value="#fbd75b">Yellow</option>
                                        <option value="#ffb878">Orange</option>
                                        <option value="#ff887c">Red</option>
                                        <option value="#dc2127">Bold red</option>
                                        <option value="#dbadff">Purple</option>
                                        <option value="#e1e1e1">Gray</option>
                                      </select>
                                  </div>
                                  <div class="full-box">
                                        <div class="tab-title">Reminder
                                            <div style="display: inline-block;margin: 0 0 0 10px;vertical-align: middle;">
                                                <div class="checkbox">
                                                  <input id="check1" class="mycheckbox_pro" type="checkbox" name="pro_reminder" >
                                                  <label for="check1" ></label>
                                                </div>
                                            </div> 
                                        </div>



                                        <div class="full-box pro_reminder_box hide">
                                          <div class="form-group reminder-box">
                                            <div class="checkbox">
                                              <input id="pro_check_email"  type="checkbox" name="pro_check_email" >
                                              <label for="pro_check_email"></label>
                                            </div> 
                                            <div class="clr"></div>
                                            Email 
                                          </div>
                                          <div class="form-group reminder-box">
                                            <div class="checkbox">
                                              <input id="pro_check_popup" type="checkbox" name="pro_check_popup" >
                                              <label for="pro_check_popup"></label>
                                            </div>
                                            <div class="clr"></div>
                                            Popup
                                          </div>
                                          <input type="text" value="" id="reminder_date" name="reminder_date" class="selectpicker tab-input-small last-fild " placeholder="Date">
                                        </div>
                                </div>
                                <div class="clr"></div>
                                </div>
                                <div class="clr"></div>
                              </div>
                          </div>'; 
    echo $rhtml;
  
  
  }else if($_POST['tsk'] == 'add_new_team_form'){


    //$userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
    if($_SESSION['user_type'] == 'company_admin'){
       $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']." OR user_id = '".$_SESSION['user_id']."' "); 
     }else{
       $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
     }
    $rhtml = '<div class="full-box">
                              <div class="tab-title">Team Details</div>
                                <div class="tab-left-box">
                                  <input type="text" value="" name="tm_title" id="tm_title" class="tab-input validate[required]" placeholder="Team Name">
                                </div>
                                <div class="clr"></div>
                                 <div class="tab-left-box">
                                <select id="userids" name="user_id[]" class="" multiple>';
                                foreach($userlist as $ui){
                                  if(checkUserteam($ui['user_id']) == 0){
                                    continue;
                                  } 
                                $rhtml .= '<option value="'.$ui['user_id'].'"> '.$ui['fname'].' '.$ui['lname'].'</option>';
                                 }
                                $rhtml .= '</select>
                                </div>
                                <div class="button-box">
                                    <button class="cancel-btn" id="team_cancel" type="button">Cancel</button>
                                    <button class="save-btn" id="team_save" type="button">Save</button>
                                </div>
                                <div class="clr"></div>
                            </div>'; 
    echo $rhtml;
  
  
  }else if($_POST['tsk'] == 'get_project_detail'){


    $prDetail = $dclass->select("*","tbl_project"," AND pr_id=".$_POST['project_id']);
    $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);

    $chkreminder = $dclass->select("*","tbl_project_reminder"," AND pr_id= ".$_POST['project_id']." AND rem_status = 'on'");
    $emailreminder = $dclass->select("*","tbl_project_reminder"," AND rem_type = 'email' AND pr_id= ".$_POST['project_id']."");
    $popupreminder = $dclass->select("*","tbl_project_reminder"," AND rem_type = 'popup' AND pr_id= ".$_POST['project_id']."");
    
     if($prDetail[0]['pr_start_datetime'] == '1970-01-01 00:00:00' || $prDetail[0]['pr_start_datetime'] == '1969-12-31 00:00:00') { 
      $start_Date == '';
    }else{
      $start_Date = date("d M y", strtotime($prDetail[0]['pr_start_datetime']));         
    }
   if($prDetail[0]['pr_end_datetime'] == '1970-01-01 00:00:00' || $prDetail[0]['pr_end_datetime'] == '1969-12-31 00:00:00') {
      $end_Date == '';
    }else{
      $end_Date = date("d M y", strtotime($prDetail[0]['pr_end_datetime']));
    }
    //$prDetail[0]['pr_color']
             $rhtml = '<div class="full-box">
                              <div class="tab-title">Choose Client</div>
                                <div class="tab-left-box">
                                  <input type="text" name="pr_title" id="pr_title" value="'.$prDetail[0]['pr_title'].'" class="tab-input validate[required]" placeholder="Name">
                                  <textarea name="pr_description" id="pr_description" class="tab-textarea" placeholder="Description">'.$prDetail[0]['pr_description'].'</textarea>
                                  <div class="clr"></div>
                                  <div class="form-group">
                                    <select id="cl_id" name="cl_id" class="selectpicker validate[required]" data-live-search="true" title="Choose Client">
                                    <option value="">Choose Client</option>';
                                     foreach($clientList as $cl){ 
                                      if($cl['cl_id'] == $prDetail[0]['pr_cl_id']){
                                        $selected = "selected='selected'";
                                      }else{
                                        $selected = "";
                                      }
                                      $rhtml .='<option value="'.$cl['cl_id'].'" '.$selected.' >'.$gnrl->trunc_string($cl['cl_company_name'],15).'</option>';
                                     } 
                                    $rhtml .='</select>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="button-box">
                                      <input type="hidden" name="script" id="script" value="edit" >
                                      <input type="hidden" name="project_id" id="project_id" value="'.$_POST['project_id'].'" >
                                       <button class="cancel-btn" id="project_cancel" type="button">Cancel</button>
                                      <button class="save-btn" id="project_save" type="button">Save</button>
                                  </div>
                                </div>
                                <div class="tab-right-box">
                                  <input type="text" name="pr_start_date" id="pr_start_date" value="'.$start_Date.'" class="tab-input" placeholder="Start Date">
                                  <input type="text" name="pr_end_date" id="pr_end_date" value="'.$end_Date.'" class="tab-input" placeholder="End Date">
                                  
                                  <div class="input-group demo2">
                                      <span>Project Colour</span>
                                      <select name="pr_color" id="pr_color">
                                        <option value="#7bd148"'; if($prDetail[0]['pr_color'] == '#7bd148') { $rhtml .='selected'; } $rhtml .=' >Green</option>
                                        <option value="#5484ed"'; if($prDetail[0]['pr_color'] == '#5484ed') { $rhtml .='selected'; } $rhtml .='>Bold blue</option>
                                        <option value="#a4bdfc"'; if($prDetail[0]['pr_color'] == '#a4bdfc') { $rhtml .='selected'; } $rhtml .='>Blue</option>
                                        <option value="#46d6db"'; if($prDetail[0]['pr_color'] == '#46d6db') { $rhtml .='selected'; } $rhtml .='>Turquoise</option>
                                        <option value="#7ae7bf"'; if($prDetail[0]['pr_color'] == '#7ae7bf') { $rhtml .='selected'; } $rhtml .='>Light green</option>
                                        <option value="#51b749"'; if($prDetail[0]['pr_color'] == '#51b749') { $rhtml .='selected'; } $rhtml .='>Bold green</option>
                                        <option value="#fbd75b"'; if($prDetail[0]['pr_color'] == '#fbd75b') { $rhtml .='selected'; } $rhtml .='>Yellow</option>
                                        <option value="#ffb878"'; if($prDetail[0]['pr_color'] == '#ffb878') { $rhtml .='selected'; } $rhtml .='>Orange</option>
                                        <option value="#ff887c"'; if($prDetail[0]['pr_color'] == '#ff887c') { $rhtml .='selected'; } $rhtml .='>Red</option>
                                        <option value="#dc2127"'; if($prDetail[0]['pr_color'] == '#dc2127') { $rhtml .='selected'; } $rhtml .='>Bold red</option>
                                        <option value="#dbadff"'; if($prDetail[0]['pr_color'] == '#dbadff') { $rhtml .='selected'; } $rhtml .='>Purple</option>
                                        <option value="#e1e1e1"'; if($prDetail[0]['pr_color'] == '#e1e1e1') { $rhtml .='selected'; } $rhtml .='>Gray</option>
                                      </select>

                                  </div>
                                  <div class="full-box">';
                                   if(count($chkreminder) > 0){
                                       $chk = 'checked="checked"';
                                        $hdclass = ''; 
                                     }else{
                                      $chk = '';
                                      $hdclass = 'hide';
                                     } 

                                     if($emailreminder[0]['rem_status'] == 'on' ){
                                        $emlChk = 'checked="checked"';
                                     }else{
                                        $emlChk = '';
                                     }

                                     if($popupreminder[0]['rem_status'] == 'on' ){
                                        $popChk = 'checked="checked"';
                                     }else{
                                        $popChk = '';
                                     }


                                      $rhtml .=' <div class="tab-title">Reminder
                                            <div style="display: inline-block;margin: 0 0 0 10px;vertical-align: middle;">
                                                <div class="checkbox">
                                                  <input id="check1" '.$chk.'  class="mycheckbox_pro" type="checkbox" name="pro_reminder" >
                                                  <label for="check1" ></label>
                                                </div>
                                            </div> 
                                        </div>

                                        <div class="full-box pro_reminder_box '.$hdclass.' ">
                                          <div class="form-group reminder-box">
                                            <div class="checkbox">
                                              <input id="pro_check_email" '.$emlChk.'  type="checkbox" name="pro_check_email" >
                                              <label for="pro_check_email"></label>
                                            </div> 
                                            <div class="clr"></div>
                                            Email 
                                          </div>
                                          <div class="form-group reminder-box">
                                            <div class="checkbox">
                                              <input id="pro_check_popup" '.$popChk.' type="checkbox" name="pro_check_popup" >
                                              <label for="pro_check_popup"></label>
                                            </div>
                                            <div class="clr"></div>
                                            Popup
                                          </div>
                                          <input type="text" value="'.$chkreminder[0]['reminder_date'].'" id="reminder_date" name="reminder_date" class="selectpicker tab-input-small last-fild " placeholder="Date">
                                        </div>
                                </div>
                                <div class="clr"></div>
                                </div>



                                <div class="clr"></div>
                                
                                
                            </div>'; 

      echo $rhtml;

  
  
  
  }else if($_POST['tsk'] == 'get_team_detail'){


    $teamDetail = $dclass->select("*","tbl_team"," AND tm_id=".$_POST['team_id']);
    // $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
     
      if($_SESSION['user_type'] == 'company_admin'){
         $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']." OR user_id = '".$_SESSION['user_id']."' "); 
      }else{
         $userlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['company_id']);
      }


     $teamUser = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id=".$_POST['team_id']);
    
     $uarray = explode(',',$teamUser[0]['uid']);
     
     $rhtml = '<div class="full-box">
                              <div class="tab-title">Team Details</div>
                                <div class="tab-left-box">
                                  <input type="text" value="'.$teamDetail[0]['tm_title'].'" name="tm_title" id="tm_title" class="tab-input validate[required]" placeholder="Team Name">
                                </div>
                                <div class="clr"></div>
                                 <div class="tab-left-box">
                                <select id="userids" name="user_id[]" class="" multiple>';
                                foreach($userlist as $ui){ 
                                    if(in_array($ui['user_id'],$uarray)){
                                      $selected = "selected='selected'";
                                    }else{
                                      $selected = "";
                                      if(checkUserteam($ui['user_id']) == 0){
                                        continue;
                                      }
                                    }

                                    $rhtml .= '<option value="'.$ui['user_id'].'" '.$selected.'> '.$ui['fname'].' '.$ui['lname'].'</option>';
                                 }
                                $rhtml .='</select>
                                </div>
                                <div class="button-box">
                                    <input type="hidden" name="script" id="script" value="edit" >
                                    <button class="cancel-btn" id="team_cancel" type="button">Cancel</button>
                                    <button class="save-btn" id="team_save" type="button">Save</button>

                                </div>
                                <div class="clr"></div>
                            </div>';

      echo $rhtml;
  
  }else if($_POST['tsk'] == 'project_save'){


    $start_Date = date("Y-m-d", strtotime($_POST['pr_start_date']));
    $end_Date = date("Y-m-d", strtotime($_POST['pr_end_date']));
    $myl = '';   
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
       if( $gnrl->checkmaxallowproject() > $gnrl->gettotalcompanyproject() || $gnrl->checkmaxallowproject() == ''){ 
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

                
               if($_POST['pro_check_popup'] == 'on'){     
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
              }
            
        }
        $myl = 'you have reached maximum no of project';
      }

      $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);
          $retIns = $dclass->select("*","tbl_project"," AND pr_cl_id = '".$_POST['cl_id']."' AND  pr_company_id=".$_SESSION['company_id']);
         
          $myhtml .= '<div class="top-tab-td td-first">
                          <div class="full-box">
                           <div class="tab-title">Choose Client</div>
                                <div class="form-group">
                                <select id="client_id" name="client_id" class="selectpicker clientlist" data-live-search="true" title="Please select a client ...">
                                    <option value="">Choose Client</option>';
                                    foreach($clientList as $cl){ 
                                      
                                      if($cl['cl_id'] == $_POST['cl_id']){
                                        $sele = "selected='selected'";
                                      }else{
                                        $sele = '';
                                      }
                                        $myhtml .= '<option value="'.$cl['cl_id'].'" '.$sele.' >'.$gnrl->trunc_string($cl['cl_company_name'],15).'</option>';
                                    } 

                                $myhtml .= '</select>
                                </div>
                                <div class="clr5"></div>
                                <div class="add-btn">
                                  <a class="select-tab" rel="2">Add new</a></div>
                                <div class="clr20"></div>
                            </div>
                            <div class="full-box" style="border-top:1px solid #323232">
                              <div class="tab-title">Choose Project</div>
                                <div class="form-group project_div">';
                                    $myhtml .= '<select id="project_id" name="project_id" class="selectpicker projList" data-live-search="true" title="Please select a project ..."><option value="">Choose Project</option>';
          
                                  foreach($retIns as $cl){
                                    if($cl['pr_id'] == $id){
                                      $sel = "selected='selected'";
                                    }else{
                                      $sel = '';
                                    }
                                    $myhtml .='<option value="'.$cl['pr_id'].'" '.$sel.'  >'.$gnrl->trunc_string($cl['pr_title'],15).'</option>';
                                  }
                                  $myhtml .= '</select>';
                                $myhtml .= '</div>
                                <div class="clr5"></div>
                                <div class="add-btn">
                                
                                <a class="select-tab" rel="1">Add new</a></div>';
                               if($myl != ''){
                                $myhtml .= '<span style="color:#fff">'.$myl.'</spa>';
                              }
                               $myhtml .= '<div class="clr20"></div>
                            </div>
                        </div>';
           
      echo $myhtml;
  
  
  
  
  }else if($_POST['tsk'] == 'team_save'){


    if(isset($_POST['script']) && $_POST['script'] == 'edit'){

          $ins = array(
                "tm_title" => $_POST['tm_title'],
                "company_user_id" => $_SESSION['company_id'],
              );
          $dclass->update('tbl_team',$ins," tm_id=".$_POST['team_id']);

          $dclass->delete('tbl_team_detail'," tm_id = '".$_POST['team_id']."'");
          foreach($_POST['user_id'] as $uid){
            $insDetail = array(
                "tm_id" => $_POST['team_id'],
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
      echo 1;
  
  
  
  
  }else if($_POST['tsk'] == 'get_project_list'){


			$rhtml = '<select id="project_id" name="project_id" class="selectpicker projList validate[required]" data-live-search="true" title="Please select a project ..."><option value="">Choose Project</option>';
			$retIns = $dclass->select(' * ','tbl_project'," AND pr_cl_id =".$_POST['client_id']);
			foreach($retIns as $cl){
				$rhtml .='<option value="'.$cl['pr_id'].'" >'.$gnrl->trunc_string($cl['pr_title'],50).'</option>';
			}
			$rhtml .= '</select>';
			echo $rhtml;
  
  
  
  
  }else if($_POST['tsk'] == 'get_task_list'){


			$rhtml = '<div class="top-tab-td td-second tasklistbox"><div class="full-box"><div class="tab-title">Task Details</div>
                                  <div class="tab-left-box">';
                         $rhtml .= ' </div><div class="clr5"></div>
                                <div class="add-btn"><a href="javascript:void(0);" class="add_new_task" ctype="calendar">Add Calendar Task</a></div>
                                <div class="add-btn"><a href="javascript:void(0);" class="add_new_task" ctype="queue">Add Queue Task</a></div>
                                <div class="clr20"></div></div>';
		echo $rhtml;
  
  
  
  }else if($_POST['tsk'] == 'add_new_project'){


	$rowInserted = $dclass->select(' * ','tbl_project'," AND pr_title = '".$_POST['project_name']."' AND pr_cl_id =".$_POST['client_id']);
		if(count($rowInserted) > 0){
			echo 1;
		}else{
			$ins = array(
				"pr_title"=>$_POST['project_name'],
				"pr_cl_id"=>$_POST['client_id'],
				"pr_status"=>'active',
			);	
			$id = $dclass->insert("tbl_project",$ins);
			
			$rhtml = '';
			$retIns = $dclass->select(' * ','tbl_project'," AND pr_cl_id =".$_POST['client_id']);
			
			foreach($retIns as $pr){
				if($id == $pr['pr_id']){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$rhtml .='<option value="'.$pr['pr_id'].'" '.$selected.' >'.$gnrl->trunc_string($pr['pr_title'],15).'</option>';
				
			}
			echo $rhtml;
		}
  
  
  
  
  }else if($_POST['tsk'] == 'add_new_task'){


        if($_POST['ctype'] == 'queue'){
          $deadlncl = 'validate[required]';
          $durationcl = 'validate[required,custom[number]]';
          $stdatecl = '';
          $opratorcl = '';
        }else{
          $deadlncl = '';
          $durationcl = 'validate[custom[number]]';
          $stdatecl = 'validate[required]';
          $opratorcl = 'validate[required]';
        }

         $taskList = $dclass->select("t_id,t_title","tbl_task"," AND t_company_user_id = ".$_SESSION['user_id']);
          $operator = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id = ".$_SESSION['company_id']." OR user_id =".$_SESSION['company_id']);
          $team = $dclass->select("*","tbl_team"," ANd company_user_id=".$_SESSION['company_id']);    
           $html = '<div class="top-tab-td td-second tasklistbox">
                            <div class="full-box">
                                <div class="tab-title">Task Details</div>
                                  <div class="tab-left-box">
                                    <input type="text" value="" name="t_title" id="t_title" class="tab-input validate[required]" placeholder="Name" autofocus>
                                    <textarea name="t_description" id="t_description" class="tab-textarea" placeholder="Description"></textarea>
                                  
                                  <div class="form-group">
                                    <select id="t_team_id" name="t_team_id" class="selectpicker validate[required]" data-live-search="true">
                                    <option value="">Choose Team</option>';
                                    foreach($team as $tm){ 
                                      
                                       $html .='<option value="'.$tm['tm_id'].'"  >'.$gnrl->trunc_string($tm['tm_title'],15).'</option>';
                                     } 
                                    $html .='</select>
                                  </div>
                                  <div class="form-group operators">
                                      
                                      <select id="t_operators" name="t_operators" class="selectpicker '.$opratorcl.'" data-live-search="true" title="Select opreator"><option>Choose Operator</option>';
                                       foreach($operator as $ui){
                                          $html .='<option value="'.$ui['user_id'].'">'.$ui['fname'].' '.$ui['lname'].'</option>'; 
                                       }
                                          
                                      $html .='</select>

                                  </div>
                                  
                                  </div>
                                  <div class="tab-right-box">
                                    <div class="tab-fild-box">
                                        <label class="tab-fild-label">Start</label>
                                          <input type="text" value="" id="t_start_time" name="t_start_time" class="tab-input-small validate[required]" placeholder="Time">
                                          <input type="text" value="" id="t_start_date" name="t_start_date" class="tab-input-small last-fild '.$stdatecl.'" placeholder="Date">
                                      </div>
                                      <div class="tab-fild-box">
                                        <label class="tab-fild-label">Duration</label>
                                          <input type="text" id="t_duration" name="t_duration" maxlength="5" value="" class="tab-input-small '.$durationcl.' validate[required][custom[number]]" placeholder="Hr">
                                      </div>
                                      <div class="tab-fild-box">
                                        <label class="tab-fild-label">Deadline</label>
                                          <input type="text" value="" id="t_deadline_date" name="t_deadline_date" class="tab-input-small '.$deadlncl.'" placeholder="Date">
                                      </div>
                                      <div class="clr5"></div>';
        /*rm added code for displaying reminder on calendar task only*/
                                     if($_POST['ctype'] != 'queue'){
                                      $html .='<div class="full-box">
                                        <div class="tab-title">Reminder
                                            <div style="display: inline-block;margin: 0 0 0 10px;vertical-align: middle;">
                                              <!-- <input type="checkbox" id="check_reminder123" name="check_reminder">-->
                      											  <div class="checkbox">
                      												<input id="check1" class="mycheckbox" type="checkbox" name="check_reminder" >
                      												<label for="check1" ></label>
                      											</div>
                                                                  </div> 
                                                              </div>';
                                          }
        /* rm added code for displaying reminder on calendar task only*/
                                $html .='<div class="full-box reminder_box hide">
                                    <div class="form-group reminder-box">
                    									<div class="checkbox">
                    										<input id="check_email"  type="checkbox" name="check_email" >
                    										<label for="check_email"></label>
                    									</div> 
									                     <div class="clr"></div>
                                        <!-- <input type="checkbox" id="check_email" name="check_email"> -->Email 
                                    </div>
                                  <div class="form-group reminder-box">
                  										<div class="checkbox">
                  										<input id="check_popup" type="checkbox" name="check_popup" >
                  										<label for="check_popup"></label>
    									               </div>
									                   <div class="clr"></div>
                                        <!-- <input type="checkbox" id="check_popup" name="check_popup">-->Popup
                                    </div>
                                     <div class="form-group reminder-box">
                                     <input type="text" value="" id="reminder_date" name="reminder_date" class="selectpicker1 tab-input-small " placeholder="Date">
                                    </div>
                                </div>';

                                     $html .='</div>
                                  </div>
                              </div>
                        </div>
                        <div class="top-tab-td td-third tasklistbox">
                            <div class="full-box">
                                <div class="tab-title">Set Status</div>
                                  <div class="form-group">
                                  <select id="t_status" name="t_status" class="selectpicker" data-live-search="true" title="select status">
                                  <option value="open">Open</option>
                                  <option value="complete">Complete</option>
                                  <option value="close">Close </option>
                                  </select>
                                  </div>
                </div>  
                              <div class="clr5"></div>
                              <div class="full-box">
                                <div class="tab-title">Set Priority</div>
                                  <div class="form-group priority_div">
                                  <select id="t_priority" name="t_priority" class="selectpicker" data-live-search="true" title="select priority">
                                  <option value="low" id="low_pri"><p class="low_pri">Low </p> </option>
                                  <option value="high" id="high_pri"><p class="high_pri">High</p></option>
                                  <option value="urgent" id="urgent_pri"><p class="urgent_pri">Urgent</p></option>
                                  <option value="critical" id="critical_pri"><p class="critical_pri">Critical </p> </option>
                                  </select>
                                  </div>
                </div>   
                              <div class="button-box">
                                <input type="hidden" name="ctype" id="ctype" value="'.$_POST['ctype'].'">
                                <button class="cancel-btn" id="task_cancel" type="button">Cancel</button>
                                <button class="save-btn" id="task_save" type="button">Save</button>
                              </div>
                           
                          <!-- Task client end-->
                      </div>
                                      	';
						echo $html;				

    
 
 
 }else if($_POST['tsk'] == 'get_operator'){


        if($_POST['ctype'] == 'queue'){
          $opercl = '';
        }else{
          $opercl = 'validate[required]';
        }

         $getOperator = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id = ".$_POST['team_id']);
         $operator = $dclass->select("user_id,fname,lname","tbl_user"," AND user_id IN (".$getOperator[0]['uid'].") ");
         
          $html = '<select id="t_operatorss" name="t_operators" class="selectpicker '.$opercl.'" data-live-search="true" title="Choose Operator"><option value="">Choose Operator</option>';
                     foreach($operator as $ui){
                        $html .='<option value="'.$ui['user_id'].'">'.$ui['fname'].' '.$ui['lname'].'</option>'; 
                     }
                        
                    $html .='</select>';
            echo $html;       
  
  
  }else if($_POST['tsk'] == 'get_task_detail'){


		$taskDetail = $dclass->select("*","tbl_task"," AND t_id= ".$_POST['t_id']."");
		$taskOperator = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_task_operator"," AND task_id= ".$_POST['t_id']."");
		//echo '<pre>'; print_r($taskDetail); echo '</pre>'; die();
    $userids = explode(',',$taskOperator[0]['uid']);
		
		$chkreminder = $dclass->select("*","tbl_task_reminder"," AND task_id= ".$_POST['t_id']."");

    $emailreminder = $dclass->select("*","tbl_task_reminder"," AND rem_type = 'email' AND task_id= ".$_POST['t_id']."");
		$popupreminder = $dclass->select("*","tbl_task_reminder"," AND rem_type = 'popup' AND task_id= ".$_POST['t_id']."");
		
		$start_time = date("h:i:s A", strtotime($taskDetail[0]['t_start_datetime']));
		$start_date = date("d M y", strtotime($taskDetail[0]['t_start_datetime']));
		$deadline_date =date("d M y", strtotime( $taskDetail[0]['t_expected_deadline_date']));
		$duration = explode(' ',$taskDetail[0]['t_duration']);
	  $operator = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id = ".$_SESSION['company_id']." OR user_id =".$_SESSION['company_id']);
     $team = $dclass->select("*","tbl_team"," ANd company_user_id=".$_SESSION['company_id']);    
           $html = '<div class="top-tab-td td-second tasklistbox">
                            <div class="full-box">
                                <div class="tab-title">Task Details</div>
                                  <div class="tab-left-box">
                                    <input type="text" value="'.$taskDetail[0]['t_title'].'" name="t_title" id="t_title" class="tab-input validate[required]" placeholder="Name" autofocus>
                                      <textarea name="t_description" id="t_description" class="tab-textarea" placeholder="Description">'.$taskDetail[0]['t_description'].'</textarea>
                                     
                                  <div class="form-group">
                                    <select id="t_team_id" name="t_team_id" class="selectpicker validate[required]" data-live-search="true">
                                    <option value="">Choose Team</option>';
                                    foreach($team as $tm){ 
                                      if($taskDetail[0]['t_team_id'] == $tm['tm_id']){ 
                                              $selected = 'selected="selected"';
                                             }else{
                                              $selected = '';
                                             }
                                       $html .='<option value="'.$tm['tm_id'].'" '.$selected.' >'.$gnrl->trunc_string($tm['tm_title'],15).'</option>';
                                     } 
                                    $html .='</select>
                                  </div>
                                   <div class="form-group operators">
                                      <select id="t_operators" name="t_operators" class="selectpicker" data-live-search="true" title="Select opreator"><option>Choose Operator</option>';
                                       foreach($operator as $ui){
                                             //if(in_array($ui['user_id'],$userids)){
                                             if($taskDetail[0]['t_operator_id'] == $ui['user_id']){ 
                                              $selected = 'selected="selected"';
                                             }else{
                                              $selected = '';
                                             }
                                              $html .='<option value="'.$ui['user_id'].'" '.$selected.'>'.$ui['fname'].' '.$ui['lname'].'</option>'; 
                                              }
                                          
                                      $html .='</select>
                                  </div>
                                  

                                  </div>
                                  <div class="tab-right-box">';
                                  // disable task timig on edit
                                  if($taskDetail[0]['t_type'] == 'queue'){
                                    $html .='<div class="tab-fild-box">
                                        <label class="tab-fild-label">Start</label>
                                          <input type="text" value="'.$start_time.'" id="t_start_time" name="t_start_time" class="tab-input-small validate[required]" placeholder="Time">
                                          <input type="text" value="'.$start_date.'" id="t_start_date" name="t_start_date" class="tab-input-small last-fild validate[required]" placeholder="Date">
                                      </div>
                                      <div class="tab-fild-box">
                                        <label class="tab-fild-label">Duration</label>
                                          <input type="text" id="t_duration" name="t_duration" value="'.$duration[0].'" maxlength="2" class="tab-input-small validate[required][custom[number],min[1]]" placeholder="Hr">
                                      </div>';
                                    }

                                      $html .='<div class="tab-fild-box">
                                        <label class="tab-fild-label">Deadline</label>
                                          <input type="text" value="'.$deadline_date.'" id="t_deadline_date" name="t_deadline_date" class="tab-input-small " placeholder="Date">
                                      </div>
                                      <div class="clr5"></div>';
                                     if(count($chkreminder) > 0){
                                      $chk = 'checked="checked"';
                                      $hdclass = '';
                                     }else{
                                      $chk = '';
                                      $hdclass = 'hide';
                                     } 

                                     if($emailreminder[0]['rem_status'] == 'on' ){
                                        $emlChk = 'checked="checked"';
                                     }else{
                                        $emlChk = '';
                                     }

                                     if($popupreminder[0]['rem_status'] == 'on' ){
                                        $popChk = 'checked="checked"';
                                     }else{
                                        $popChk = '';
                                     }
                                     
                                      $html .='<div class="full-box">
                                        <div class="tab-title">Reminder
                                            <div style="display: inline-block;margin: 0 0 0 10px;vertical-align: middle;">
                                              <!-- <input type="checkbox" id="check_reminder" name="check_reminder">-->
                                  
                        <div class="checkbox">
                        <input id="check1" class="mycheckbox" type="checkbox" '.$chk.' name="check_reminder" >
                        <label for="check1" ></label>
                      </div>
                                            </div> 
                                        </div>

                                          <div class="full-box reminder_box '.$hdclass.'">
                                             <div class="form-group reminder-box">
                                      <div class="checkbox">
                                        <input id="check_email"  type="checkbox" name="check_email" '.$emlChk.' >
                                        <label for="check_email"></label>
                                      </div> 
                                       <div class="clr"></div>
                                        <!-- <input type="checkbox" id="check_email" name="check_email"> -->Email 
                                    </div>
                                  <div class="form-group reminder-box">
                                      <div class="checkbox">
                                      <input id="check_popup" type="checkbox" name="check_popup" '.$popChk.' >
                                      <label for="check_popup"></label>
                                     </div>
                                     <div class="clr"></div>
                                        <!-- <input type="checkbox" id="check_popup" name="check_popup">-->Popup
                                    </div>
                                    <div class="form-group reminder-box">
                                        <input type="text" value="'.date('d M y h:i:s',strtotime($emailreminder[0]['reminder_date'])).'" id="reminder_date" name="reminder_date" class="selectpicker tab-input-small last-fild " placeholder="Date">
                                    </div>        
                                          </div>';

                                      $html .='</div>
                                  </div>
                              </div>
                        </div>
                        
                        <div class="top-tab-td td-third tasklistbox">
                            <div class="full-box">
                                <div class="tab-title">Set Status</div>
                                  <div class="form-group">
                                  <select id="t_status" name="t_status" class="selectpicker" data-live-search="true" title="select status">
                                  <option value="open" '; if($taskDetail[0]['t_status'] == 'open' ){  $html .=' selected="selected" '; } $html .='>Open</option>
                                  <option value="complete" '; if($taskDetail[0]['t_status'] == 'complete' ){  $html .=' selected="selected" '; } $html .='>Complete</option>
                                  <option value="close" '; if($taskDetail[0]['t_status'] == 'close' ){  $html .=' selected="selected" '; } $html .='>Close </option>
                                  </select>
                                  </div>
                </div>  
                              <div class="clr5"></div>
                              <div class="full-box">
                                <div class="tab-title">Set Priority</div>
                                  <div class="form-group priority_div">
                                  <select id="t_priority" name="t_priority" class="selectpicker" data-live-search="true" title="select priority">
                                  <option value="low" '; if($taskDetail[0]['t_priority'] == 'low' ){  $html .=' selected="selected" '; } $html .='>Low</option>
                                  <option value="high" '; if($taskDetail[0]['t_priority'] == 'high' ){  $html .=' selected="selected" '; } $html .='>High</option>
                                  <option value="urgent" '; if($taskDetail[0]['t_priority'] == 'urgent' ){  $html .=' selected="selected" '; } $html .='>Urgent</option>
                                  <option value="critical" '; if($taskDetail[0]['t_priority'] == 'critical' ){  $html .=' selected="selected" '; } $html .='>Critical </option>
                                  </select>
                                  </div>
                </div>   
                              <div class="button-box">
                                <input type="hidden" value="'.$taskDetail[0]['t_id'].'" id="t_id" name="t_id">
                                <input type="hidden" name="ctype" id="ctype" value="'.$taskDetail[0]['t_type'].'">
                    <input type="hidden" value="edit" id="script" name="script">
                                <button class="cancel-btn" id="task_cancel" type="button">Cancel</button>
                                <button class="save-btn" id="task_save" type="button">Save</button>
                                <button class="delete-btn" id="task_delete" taskid="'.$taskDetail[0]['t_id'].'" type="button">X</button>
                              </div>
                           
                          <!-- Task client end-->
                      </div>';
            echo $html; 
  
  
  
  }else if($_POST['tsk'] == 'task_save'){

      
	  $trim_time = date("H:i:s", strtotime($_POST['t_start_time']));
    $t_start_date = date("Y-m-d", strtotime($_POST['t_start_date']));

      $t_start_datetime = date("Y-m-d H:i:s", strtotime($_POST['t_start_date'].' '.$trim_time));
      if($_POST['t_duration'] != ''){
        	$duration = h2m($_POST['t_duration'])." minutes";
          $queue_duration = $_POST['t_duration']." hours";
      }else{
					$duration = "0 minutes";
          $queue_duration = "0 hours";
			}
      
      $team_work_hour = getWorkinghours($_POST['t_team_id']);
      $timedt = getTimeDetail($_POST['t_team_id']);
      if($_POST['t_deadline_date'] != ''){
        $t_deadline_date = date("Y-m-d", strtotime($_POST['t_deadline_date']));
        $check_t_deadline_date = $t_deadline_date.' '.$timedt[1] ;
      }else{
        $t_deadline_date = '';
        $check_t_deadline_date = '';
      }

     $lh = getLunchDuration($_POST['t_team_id']);
     if($team_work_hour > $_POST['t_duration']){
          
          $time_st1 = strtotime($trim_time);
          $time_st2 = strtotime($timedt[1]);
          $taskdur = $time_st2 - $time_st1;
          $taskduration = $taskdur/3600;
          if($_POST['t_duration'] < $taskduration){
            $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($t_start_datetime)) . " +".$duration." "));    
          }else{
            $reminder = ($_POST['t_duration'] - $taskduration);
            $duration_temp = round($reminder)." hours";
            
            $end_date_temp = date("Y-m-d", strtotime(date("Y-m-d", strtotime($t_start_datetime)) . " +1 days"));
            $end_date_temp1 = $end_date_temp.' '.$timedt[0];
            
            $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp1)) . " +".$duration_temp." "));          

          } 

      }else{

          //check start time extend
          $tm1 = strtotime($timedt[1]);
          $tm2 = strtotime($trim_time);
          $temp_tmdr = $tm1 - $tm2;
          $tmdr = $temp_tmdr/3600;
        
          $myextend =  round($_POST['t_duration'] / $team_work_hour);   
          
          $reminder = ($_POST['t_duration'] % $team_work_hour);
          
          $time_st1 = strtotime($trim_time);
          $time_st2 = strtotime($timedt[1]);
          $taskdur = $time_st2 - $time_st1;
          
          $taskduration = $taskdur/3600;
          
          if($reminder > $taskduration){
           
            $remindertemp = ($reminder - $taskduration);
            $duration_temp = round($remindertemp) + $tmdr;
            $myextend = $myextend + 1;
          }else{
            $duration_temp = round($reminder) + $tmdr;            
          }
          
        
        //$duration_temp = $duration_temp." hours";
        $reminders_temp = explode('.',$duration_temp);
        $end_date_temp = date("Y-m-d", strtotime(date("Y-m-d", strtotime($t_start_datetime)) . " +".$myextend." days "));      
        $end_date_temp1 = $end_date_temp.' '.$timedt[0];
        
        //$end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp1)) . " +".$duration_temp." "));
       
        $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp1)) . " +".$reminders_temp[0]." hours ")); 
        if(isset($reminders_temp[1]) && $reminders_temp[1] != 0){
          $exminut = ($reminders_temp[1] / 10) * 60;
          $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date)) . " +".$exminut." minutes "));          
        }
        
      }

     $datediff = strtotime($end_date) - strtotime($t_start_datetime);
     $daydiff = round($datediff / (3600*24));
     $du_temp = ($daydiff * $lh) + ($lh*5);
     
     $du_temp = $du_temp.' hours';
    //echo $end_date.'== first before==';

     $end_date = extenddate($end_date,$_POST['t_team_id'],$du_temp);
     $pre_end_date = $end_date;
    //echo $end_date.'== first after==';
    //echo $t_start_datetime.'=='.$end_date;
   if($_POST['ctype'] == 'calendar'){

      if(isset($_POST['script']) && $_POST['script'] == 'edit1'){
       
          // code for only edit detail of task (option for individual task) start
          $tid = $_POST['t_id'];
          $checkrole = $dclass->select("r_id","tbl_user"," and user_id = ". $_SESSION['user_id']);
         
         if($checkrole[0]['r_id'] == '4'){
             $ins = array(
                 "t_status"=>$_POST['t_status']
             );    
         }else{
            // code for only edit detail of task (option for individual task) start
            $ins = array(
            "t_pr_id"=>$_POST['project_id'],  
            "t_cl_id"=>$_POST['client_id'],  
            "t_title"=>$_POST['t_title'],
            "t_description"=>$_POST['t_description'],
            "t_priority"=>$_POST['t_priority'],
            /*"t_team_id"=>$_POST['t_team_id'],
            "t_operator_id"=> $_POST['t_operators'],*/
            "t_expected_deadline_date"=>$t_deadline_date,
            "t_type"=>$_POST['ctype'],
            "t_status"=>$_POST['t_status'],
          );
         } 
        
         $dclass->update("tbl_task",$ins, 't_id='.$tid);
         task_updated($tid);
        if(isset($_POST['check_reminder']) && $_POST['check_reminder'] == 'on'){

            if($_POST['check_email'] == 'on'){
              $emstatus = 'on';
            }else{
              $emstatus = 'off';
            }
              $Remins = array(
                "rem_status"=>$emstatus,
                "rem_time"=>$_POST['popup_time'],
                "rem_time_period"=>$_POST['popup_reminder_type'],
                                                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
              $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="email"');
            
            if($_POST['check_popup'] == 'on'){
              $popstatus = 'on';
            }else{
              $popstatus = 'off';
            } 
            
            $Remins = array(
              "rem_status"=>$popstatus,
              "rem_time"=>$_POST['popup_time'],
              "rem_time_period"=>$_POST['popup_reminder_type'],
              "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
            );  
            $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="popup"');
        }else{
            $Remins = array(
                "rem_status"=>'off',
              );  
              $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="email"');

              $Remins = array(
                "rem_status"=>'off',
              );  
              $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="popup"');
        }
        // Notification mail for edit task
         // code for mail send
             $to = $dclass->select("u.user_id,u.fname,u.lname,u.email,t.t_title,t.t_description,t.t_end_datetime,t.t_start_datetime,cl.cl_name,pr.pr_title","tbl_user as u left join tbl_task as t on u.user_id = t.t_operator_id 
                  left join  tbl_client cl ON t.t_cl_id = cl.cl_id
                  left join  tbl_project pr ON t.t_pr_id = pr.pr_id
                   ", " and t.t_id = '".$tid."'");

            $where = " AND user_id='".$to[0]['user_id']."' and notification_name = 'task_edit' and status = '1'";
            $notifyDetails = $dclass->select(" * ","tbl_notification_details", $where );
                
                if(count($notifyDetails))
                {
                    $noti_date =  date("Y-m-d",strtotime($to[0]['t_start_datetime']));
                  $noti_time =  date("H:i:s",strtotime($to[0]['t_start_datetime']));
                    $noti_add = array(
                        'task_id'=>$tid,
                        'task_event'=>'Task Edited',
                        'task_title'=>$to[0]['t_title'],
                        'task_date'=>$noti_date,
                        'task_starttime'=>$noti_time,
                        'generate_datetime'=>date('Y-m-d H:i:s'),
                        'read_status'=>'0',
                        'user_id'=>$to[0]['user_id'],
                    );
                    $dclass->insert("tbl_user_notification",$noti_add);

                    $email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Notification for task"');
                    
                    $subject = $email_template[0]['email_subject'];
                    $subject  = str_replace('{TASKNAME}',$to[0]['t_title'],$email_template[0]['email_subject']);

                    $html = str_replace('{NAME}',$to[0]['fname'].' '.$to[0]['lname'],$email_template[0]['email_body']);
                    
                    $logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
                    $html = str_replace('{LOGO}',$logo,$html);

                    $currentdate = date("Y-m-d H:i:s"); 
                    $seconds = strtotime($to[0]['t_start_datetime']) - strtotime($currentdate);
                    $days    = floor($seconds / 86400);
                    $hours   = floor(($seconds - ($days * 86400)) / 3600);
                    $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
          
                    $html = str_replace('{HR}',$hours,$html);
                    $html = str_replace('{MN}',$minutes,$html);

                    $html = str_replace('{TASKNAME}',$to[0]['t_title'],$html);
                    $html = str_replace('{CLIENTNAME}',$to[0]['cl_name'],$html);
                    $html = str_replace('{PROJECTNAME}',$to[0]['pr_title'],$html);
                    $html = str_replace('{DESCRIPTION}',$to[0]['t_description'],$html);
                    $html = str_replace('{ENDDATE}',$to[0]['t_end_datetime'],$html);
                    
                    $mail->addAddress($to[0]['email'],$to[0]['fname'].' '.$to[0]['lname']);
                    $mail->Subject = $subject;
                    $mail->msgHTML($html);
                    $mail->send();
                }

        // code for only edit detail of task (option for individual task) end
      }else{
         
         $checkuserchange = $dclass->select("t_operator_id","tbl_task", " AND t_id='".$_POST['t_id']."'");
         if(isset($_POST['script']) && $_POST['script'] == 'edit' && $checkuserchange[0]['t_operator_id'] != $_POST['t_operators']){
            $chkdata = checktaskoverlapping($_POST['t_operators'],$t_start_datetime,$end_date,$_POST['t_id']);
             if(!$chkdata){
                echo "task_overlap";
                die(); 
             }
          }
             
          $curr_dt = date("Y-m-d", strtotime($t_start_datetime));
          
          $ed_dt = date("Y-m-d", strtotime($end_date));

          $starttime = date("H:i:s", strtotime($t_start_datetime));
          $endtime = date("H:i:s", strtotime($end_date));

          $caldr = 0;
          //echo $t_start_datetime.'==='.$end_date;
          // if task for more then one day 
           while (strtotime($curr_dt) <= strtotime($end_date)){
            $endtime_new = date("H:i:s", strtotime($end_date));
            if(checkholiday($curr_dt)){
              $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
              $end_date = date ("Y-m-d", strtotime("+1 day", strtotime($end_date)));
              $end_date = $end_date.' '.$endtime_new;
              $du_temp = $lh.' hours';
              $end_date = extenddate($end_date,$_POST['t_team_id'],$du_temp);
               //echo $end_date.'==holiday==<br>';
              continue;
            }else if(checkworkingday($_POST['t_team_id'],$curr_dt)){
              $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
              $end_date = date ("Y-m-d", strtotime("+1 day", strtotime($end_date)));
              $end_date = $end_date.' '.$endtime_new;
              $du_temp = $lh.' hours';
              $end_date = extenddate($end_date,$_POST['t_team_id'],$du_temp);
              //echo $end_date.'==offday==<br>';
              continue;
            }

          $query_stdt = $curr_dt;
          $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
          $query_endt = $curr_dt;
     
          if($query_stdt == date("Y-m-d",strtotime($t_start_datetime))){


                    $time_st1 = strtotime($starttime);
                    $time_st2 = strtotime($timedt[1]);
                   
                    $taskdur = $time_st2 - $time_st1;
                    $taskduration = $taskdur/3600;

                    $taskduration =  number_format($taskduration,2);
                    $includelunch = checkLunch($starttime,$timedt[1],$_POST['t_team_id']);

                    if($includelunch > 0){
                      $taskduration = $taskduration - $lh; 
                      $pstdr = $_POST['t_duration'] + 1;
                    }else{
                      $pstdr = $_POST['t_duration'];
                    }

                    $task_start = $query_stdt.' '.$starttime;
                    $task_endt = $query_stdt.' '.$timedt[1];

                    $query_stdt = $query_stdt.' '.$starttime;
                    $query_endt = $query_endt.' '.$timedt[1];
                  
                    $tstp = 'start';
          
          
          }else if($query_stdt == date("Y-m-d", strtotime($end_date)) ){


                   $time_st1 = strtotime($endtime_new);
                   $time_st2 = strtotime($timedt[0]);

                   $taskdur = $time_st1 - $time_st2;
                   $taskduration = $taskdur/3600;

                   $taskduration =  number_format($taskduration,2);  
                   $includelunch = checkLunch($timedt[0],$endtime_new,$_POST['t_team_id']);
                    if($includelunch > 0){
                      $taskduration = $taskduration - $lh; 
                      $pstdr = $_POST['t_duration'] + $lh;
                    }else{
                      $pstdr = $_POST['t_duration'];
                    }
                   $task_start = $query_stdt.' '.$timedt[0];
                   $task_endt = $query_stdt.' '.$endtime_new; 

                   $query_stdt = $query_stdt.' '.$timedt[0];
                   $query_endt = $query_endt.' '.$endtime_new;
                   
                   $tstp = 'end';
          
          
          }else{

                  $time_st1 = strtotime($timedt[0]);
                  $time_st2 = strtotime($timedt[1]);
                  
                  $taskdur = $time_st2 - $time_st1;
                  $taskduration = $taskdur/3600;

                  $taskduration =  number_format($taskduration,2);
                  
                  $taskduration = $taskduration - $lh; 
                  $pstdr = $_POST['t_duration'] + $lh;
                  
                  $task_start = $query_stdt.' '.$timedt[0];
                  $task_endt = $query_stdt.' '.$timedt[1]; 

                  $query_stdt = $query_stdt.' '.$timedt[0];
                  $query_endt = $query_endt.' '.$timedt[1];
                  $tstp = 'middle';
          }
                $chkFreeDate = checkfornextAvailabeltime($_POST['t_operators'],$task_start,$task_endt,$team_work_hour,$t_start_datetime,$end_date,$caldr,$_POST['t_duration'],$timedt[0],$timedt[1],$_POST['t_team_id'],$_POST['t_id']); 
            
                
                 foreach($chkFreeDate as $chr){
                  if($check_t_deadline_date != ''  && strtotime($check_t_deadline_date) < strtotime($pre_end_date)){
                        $wlflage = 'false';
                        echo "Deadline_date";
                        $ins = array(
                            "t_pr_id"=>$_POST['project_id'],
                            "t_cl_id"=>$_POST['client_id'],
                            "t_company_user_id"=>$_SESSION['company_id'],
                            "t_title"=>$_POST['t_title'],
                            "t_description"=>$_POST['t_description'],
                            "t_priority"=>$_POST['t_priority'],
                            "t_team_id"=>$_POST['t_team_id'],
                            "t_operator_id"=> $_POST['t_operators'],
                            "t_start_datetime"=> $t_start_datetime,
                            "t_end_datetime"=> $end_date,
                            "t_duration"=>$_POST['t_duration'],
                            "t_expected_deadline_date"=>$check_t_deadline_date,
                            "t_type"=>'queue',
                            "t_status"=>$_POST['t_status'],
                          );
                        $tid = $dclass->insert("tbl_task",$ins);
                        task_updated($tid);
                        if($_POST['script'] == 'edit'){
                          $dclass->delete('tbl_task'," t_id=".$_POST['t_id']);
                        }
                        $wlflage = 'false';
                    }else{
                      if($chr['startdate'] != ''){ 
                        if($_POST['t_id'] != ''){
                            $ins = array(
                              "t_pr_id"=>$_POST['project_id'],
                              "t_cl_id"=>$_POST['client_id'],
                              "t_company_user_id"=>$_SESSION['company_id'],
                              "t_title"=>$_POST['t_title'],
                              "t_description"=>$_POST['t_description'],
                              "t_priority"=>$_POST['t_priority'],
                              "t_team_id"=>$_POST['t_team_id'],
                              "t_operator_id"=> $_POST['t_operators'],
                              "t_start_datetime"=> $chr['startdate'],
                              "t_end_datetime"=> $chr['enddate'],
                              "t_duration"=>$chr['task_duration'],
                              "t_expected_deadline_date"=>$check_t_deadline_date,
                              "t_type"=>$_POST['ctype'],
                              "t_status"=>$_POST['t_status'],
                            );  
                            $tid = $_POST['t_id'];
                            $dclass->update("tbl_task",$ins, 't_id='.$tid);
                            task_updated($tid);
                            $_POST['t_id'] = '';
                        }else{
                            $ins = array(
                              "t_pr_id"=>$_POST['project_id'],
                              "t_cl_id"=>$_POST['client_id'],
                              "t_company_user_id"=>$_SESSION['company_id'],
                              "t_title"=>$_POST['t_title'],
                              "t_description"=>$_POST['t_description'],
                              "t_priority"=>$_POST['t_priority'],
                              "t_team_id"=>$_POST['t_team_id'],
                              "t_operator_id"=> $_POST['t_operators'],
                              "t_start_datetime"=> $chr['startdate'],
                              "t_end_datetime"=> $chr['enddate'],
                              "t_duration"=>$chr['task_duration'],
                              "t_expected_deadline_date"=>$check_t_deadline_date,
                              "t_type"=>$_POST['ctype'],
                              "t_status"=>$_POST['t_status'],
                            );  
                            $tid = $dclass->insert("tbl_task",$ins);
                            task_updated($tid);
                      }
                 
                  $caldr = $chr['total_duration'];
             // code for mail send
             $to = $dclass->select("u.user_id,u.fname,u.lname,u.email,t.t_title,t.t_description,t.t_start_datetime,t.t_end_datetime,cl.cl_name,pr.pr_title","tbl_user as u left join tbl_task as t on u.user_id = t.t_operator_id 
                  left join  tbl_client cl ON t.t_cl_id = cl.cl_id
                  left join  tbl_project pr ON t.t_pr_id = pr.pr_id
                   ", " and t.t_id = '".$tid."'");

            $whereInapp = " AND user_id='".$to[0]['user_id']."' and notification_name = 'inapp_notification' and status = '1'";
            $notifyDetailsInapp = $dclass->select(" * ","tbl_notification_details", $whereInapp );
            if(count($notifyDetailsInapp) > 0)
            {   

                  $whereInappInner = " AND user_id='".$to[0]['user_id']."' and notification_name = 'task_add' and status = '1'";
                  $notifyDetailsInappInner = $dclass->select(" * ","tbl_notification_details", $whereInappInner );
                  if(count($notifyDetailsInappInner) > 0)
                  {
                    $noti_date =  date("Y-m-d",strtotime($to[0]['t_start_datetime']));
                    $noti_time =  date("H:i:s",strtotime($to[0]['t_start_datetime']));
                      $noti_add = array(
                          'task_id'=>$tid,
                          'task_event'=>'Task Added',
                          'task_title'=>$to[0]['t_title'],
                          'task_date'=>$noti_date,
                          'task_starttime'=>$noti_time,
                          'generate_datetime'=>date('Y-m-d H:i:s'),
                          'read_status'=>'0',
                          'user_id'=>$to[0]['user_id'],
                      );
                      $dclass->insert("tbl_user_notification",$noti_add);
                  }
            }

            $where = " AND user_id='".$to[0]['user_id']."' and notification_name = 'email_notification' and status = '1'";
            $notifyDetails = $dclass->select(" * ","tbl_notification_details", $where );
                
            if(count($notifyDetails) > 0)
            {
                  
                    $whereInner = " AND user_id='".$to[0]['user_id']."' and notification_name = 'task_add' and status = '1'";
                    $notifyDetailsInner = $dclass->select(" * ","tbl_notification_details", $whereInner );
                    if(count($notifyDetailsInner) > 0)
                    {
                            $email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Notification for task"');
                            
                            $subject = $email_template[0]['email_subject'];
                            $subject  = str_replace('{TASKNAME}',$to[0]['t_title'],$email_template[0]['email_subject']);

                            $html = str_replace('{NAME}',$to[0]['fname'].' '.$to[0]['lname'],$email_template[0]['email_body']);
                            $logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
                            $html = str_replace('{LOGO}',$logo,$html);

                            $html = str_replace('{TASKNAME}',$to[0]['t_title'],$html);
                            $html = str_replace('{CLIENTNAME}',$to[0]['cl_name'],$html);
                            $html = str_replace('{PROJECTNAME}',$to[0]['pr_title'],$html);
                            $html = str_replace('{DESCRIPTION}',$to[0]['t_description'],$html);
                            $html = str_replace('{ENDDATE}',$to[0]['t_end_datetime'],$html);

                            
                            $mail->addAddress($to[0]['email'],$to[0]['fname'].' '.$to[0]['lname']);
                            $mail->Subject = $subject;
                            $mail->msgHTML($html);
                            try{
                              $mail->send();
                            }catch(Exception $e){
                              print_r($e);
                            }
                            
                      }
            }
                  //code for task reminder
                  if(isset($_POST['check_reminder']) && $_POST['check_reminder'] == 'on'){
            
            if($_POST['check_email'] == 'on'){
              $emstatus = 'on';
            }else{
              $emstatus = 'off';
            }
              $Remins = array(
                "task_id"=>$tid,
                "rem_type"=>'email',
                "rem_status"=>$emstatus,
                "rem_time"=>$_POST['popup_time'],
                "rem_time_period"=>$_POST['popup_reminder_type'],
                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
               $dclass->insert("tbl_task_reminder",$Remins);

            

            if(isset($_POST['check_popup']) && $_POST['check_popup'] == 'on'){
              $popstatus = 'on';
                    $noti_date =  date("Y-m-d",strtotime($to[0]['t_start_datetime']));
                    $noti_time =  date("H:i:s",strtotime($to[0]['t_start_datetime']));

                    $task_date_time =  date("Y-m-d H:i:s",strtotime($to[0]['t_start_datetime']));
                    $reminder_datetime = date('Y-m-d H:i:s', strtotime($_POST['reminder_date']));
                    $hourdiff = round((strtotime($task_date_time) - strtotime($reminder_datetime))/3600, 0);
                    $noti_add = array(
                        'task_id'=>$tid,
                        'task_event'=>'Task Reminder',
                        'task_title'=>$to[0]['t_title'],
                        'task_date'=>$noti_date,
                        'task_starttime'=>$noti_time,
                        'generate_datetime'=>date('Y-m-d H:i:s'),
                        'read_status'=>'0',
                        'description' => $hourdiff,
                        'user_id'=>$to[0]['user_id'],
                    );
                    $dclass->insert("tbl_user_notification",$noti_add);

            }else{
              $popstatus = 'off';
            }
              $Remins = array(
                "task_id"=>$tid,
                "rem_type"=>'popup',
                "rem_status"=>$popstatus,
                "rem_time"=>$_POST['popup_time'],
                "rem_time_period"=>$_POST['popup_reminder_type'],
                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
               $dclass->insert("tbl_task_reminder",$Remins);
            
        } 
                  // task reminder code end

                  }  
                    $wlflage = $chr['flage'];
                   }
                  
                  
                    }
                  
                  if($wlflage == 'false'){
                    break;
                  }
              // code for extend end date according to available time period
                 $duration_tempp = round($chkFreeDate[0]['td']).' hours';
                 $end_date = extenddate($end_date,$_POST['t_team_id'],$duration_tempp);
                
           
            }
         
      }

   
   }else{
    //code start for queue task
    if(isset($_POST['script']) && $_POST['script'] == 'edit'){


          $tid = $_POST['t_id'];

          $ins = array(
          
          "t_pr_id"=>$_POST['project_id'],  
          "t_cl_id"=>$_POST['client_id'],  
          "t_title"=>$_POST['t_title'],
          "t_description"=>$_POST['t_description'],
          "t_priority"=>$_POST['t_priority'],
         /* "t_team_id"=>$_POST['t_team_id'],
          "t_operator_id"=> $_POST['t_operators'],*/
          "t_expected_deadline_date"=>$t_deadline_date,
          "t_type"=>$_POST['ctype'],
          "t_status"=>$_POST['t_status'],
        );  
        $dclass->update("tbl_task",$ins, 't_id='.$tid);
        task_updated($tid);
        // code start for reminder
        if(isset($_POST['check_reminder']) && $_POST['check_reminder'] == 'on'){


            if($_POST['check_email'] == 'on'){
              $emstatus = 'on';
            }else{
              $emstatus = 'off';
            }
              $Remins = array(
                "rem_status"=>$emstatus,
                "rem_time"=>$_POST['popup_time'],
                "rem_time_period"=>$_POST['popup_reminder_type'],
                                                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
              $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="email"');
            
            if($_POST['check_popup'] == 'on'){
              $popstatus = 'on';
            }else{
              $popstatus = 'off';
            } 
            
            $Remins = array(
              "rem_status"=>$popstatus,
              "rem_time"=>$_POST['popup_time'],
              "rem_time_period"=>$_POST['popup_reminder_type'],
              "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
            );  
            $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="popup"');
            
            
        
        
        }else{

            $Remins = array(
                "rem_status"=>'off',
              );  
              $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="email"');

              $Remins = array(
                "rem_status"=>'off',
              );  
              $dclass->update("tbl_task_reminder",$Remins, ' task_id ='.$tid.' AND rem_type="popup"');
        
        }
      
      
      
      }else{
      
        $ins = array(
          "t_pr_id"=>$_POST['project_id'],
          "t_cl_id"=>$_POST['client_id'],
          "t_company_user_id"=>$_SESSION['company_id'],
          "t_title"=>$_POST['t_title'],
          "t_description"=>$_POST['t_description'],
          "t_priority"=>$_POST['t_priority'],
          "t_team_id"=>$_POST['t_team_id'],
          "t_operator_id"=> $_POST['t_operators'],
          "t_start_datetime"=> $t_start_datetime,
          "t_end_datetime"=> $end_date,
          "t_duration"=>$queue_duration,
          "t_expected_deadline_date"=>$t_deadline_date,
          "t_type"=>$_POST['ctype'],
          "t_status"=>$_POST['t_status'],
        );  
        $tid = $dclass->insert("tbl_task",$ins);
        task_updated($tid);
          if($_POST['check_reminder'] == 'on'){
            
            if($_POST['check_email'] == 'on'){
              $emstatus = 'on';
            }else{
              $emstatus = 'off';
            }
              $Remins = array(
                "task_id"=>$tid,
                "rem_type"=>'email',
                "rem_status"=>$emstatus,
                "rem_time"=>$_POST['popup_time'],
                "rem_time_period"=>$_POST['popup_reminder_type'],
                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
               $dclass->insert("tbl_task_reminder",$Remins);

            

            if($_POST['check_popup'] == 'on'){
              $popstatus = 'on';
            }else{
              $popstatus = 'off';
            }
              $Remins = array(
                "task_id"=>$tid,
                "rem_type"=>'popup',
                "rem_status"=>$popstatus,
                "rem_time"=>$_POST['popup_time'],
                "rem_time_period"=>$_POST['popup_reminder_type'],
                "reminder_date" => date('Y-m-d H:i:s', strtotime($_POST['reminder_date'])),
              );  
               $dclass->insert("tbl_task_reminder",$Remins);
        

            
        }
      
      }

   }       
  
  
  
  
  
  
  }else if($_POST['tsk'] == 'task_delete'){


    if(isset($_POST['taskid']) && $_POST['taskid'] != ''){
          $dclass->delete('tbl_task'," t_id=".$_POST['taskid']);
          $dclass->delete('tbl_task_reminder'," task_id=".$_POST['taskid']);
          $dclass->delete('tbl_task_timing'," t_id=".$_POST['taskid']);
    }
  
  
  
  }else if($_POST['tsk'] == 'add_new_user'){


    $html = '<div class="modal-dialog task-detail">
    <div class="modal-content user-box">
     <form name="new_user" id="new_user" method="post" autocomplete="off" action="" enctype="multipart/form-data">
      <div class="task-title">User Details</div>
      <div class="task-form">
        <div class="task-form-left">
          <label class="task-label">Email <span>:</span></label>
            <input type="text" name="email" id="email" class="input-box validate[required,custom[email]]" value="" autofocus>
        </div>
        <div class="task-form-right">
          <label class="task-label">Password <span>:</span></label>
            <input type="password" name="password" id="password" class="input-box validate[required]" value="">
        </div>
        <div class="clr"></div>

        <div class="task-form-left">
          <label class="task-label">First name <span>:</span></label>
            <input type="text" name="f_name" maxlength="9" id="f_name" class="input-box validate[required]" value="">
        </div>
        <div class="task-form-right">
          <label class="task-label">Last name <span>:</span></label>
            <input type="text" name="l_name" maxlength="9" id="l_name" class="input-box validate[required]" value="">
        </div>
        <div class="clr"></div>
        <div class="task-form-left">
         <label class="task-label">Job Title <span>:</span></label>
                <input type="text" name="job_title" id="job_title" class="input-box validate[required]" value="">
        </div>
        <div class="task-form-right">
          <label class="task-label">Profile Pic<span>:</span></label>
            <input type="file" name="user_avatar" id="user_avatar_new" class="filestyle" data-icon="false">

        </div>
		<div class="clr"></div>
		<div class="task-form-left">
          <label class="task-label">Order <span>:</span></label>
          <input type="text" name="display_order" maxlength="9" id="display_order" class="input-box" value="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,\'\')">
        </div>
         <div class="task-form-right">
          <label class="task-label">Show in cal <span>:</span></label>
            <div class="form-group">
			<select id="display_status" name="display_status" class="selectpicker" data-live-search="true">
                <option value="1" >show</option>
				<option value="0" >hide</option>
            </select>
			</div>
        </div>
        <div class="clr"></div>
        <div class="user-choose">
          <div class="user-choose-box">';
          $team = $dclass->select("*","tbl_team"," ANd company_user_id=".$_SESSION['company_id']); 
             $html .= '<div class="tab-title">Choose Team</div>
                <div class="form-group">
                    <select id="user_team_id" name="user_team_id" class="selectpicker validate[required]" data-live-search="true">
                    <option value="">Choose Team</option>';
                    foreach($team as $tm){ 
                       $html .= '<option value="'.$tm['tm_id'].'">'.$gnrl->trunc_string($tm['tm_title'],15).'</option>';
                     }
                    $html .= '</select>
                </div>
              
            </div>
            <div class="user-choose-box">';
             
                if($_SESSION['user_type'] == 'manager'){ 
                    $Roll = $dclass->select("*","tbl_role"," ANd r_id = '4'  ");
                  }else{
                    $Roll = $dclass->select("*","tbl_role"," ANd r_id != '1' ");
                  }
              $html .= '<div class="tab-title">Choose User Role </div>
                <div class="form-group">
                    <select id="r_id" name="r_id" class="selectpicker validate[required]" data-live-search="true" >
                    <option value="">Choose Role</option>';
                    foreach($Roll as $ri){
                    $html .= '<option value="'.$ri['r_id'].'">'.$ri['r_title'].'</option>';
                    } 
                    $html .= '</select>
                </div>
              
            </div>
            <div class="user-choose-box">
              
                <div class="button-box">
                              <a class="cancel-btn" id="close_popup" >Cancel</a>
                               <button class="save-btn" name="Submit_user" value="submit" type="submit">Save</button> 
                            </div>
              
            </div>
        </div>


      </div>
      </form>
    <div class="clr"></div>
    </div>
  </div>';

  echo  $html;
  
  
  
  }else if($_POST['tsk'] == 'check_team_time'){


    if($_POST['team_id'] != '' && $_POST['tm_value'] != ''){
      $checkTime = $dclass->select(' * ','tbl_working_day_time',"  AND company_user_id = '".$_SESSION['company_id']."' ");
      $tmvl = explode(':::',$checkTime[0]['working_time']);
      
         $chktime = date('H:i', strtotime($_POST['tm_value']));
         $strat_time = date('H:i', strtotime($tmvl[0]));
         $end_time = date('H:i', strtotime($tmvl[1]));
  
        if($chktime >= $strat_time && $end_time >= $chktime ) {
          echo 1;
        }else{
          echo 0;
        } 
    }else{
      echo 2;
    }
  
  
  
  }else if($_POST['tsk'] == 'get_project_box'){



    $prList = $dclass->select("*","tbl_project"," AND pr_company_id = ".$_SESSION['company_id']);
    $html = '<select id="project_id_add" name="project_id" class="selectpicker" data-live-search="true" >
               <option value="" selected> Edit Projects </option>';
               foreach($prList as $pr){ 
                      $html .= '<option value="'.$pr['pr_id'].'" >'.$gnrl->trunc_string($pr['pr_title'],50).'</option>';
                } 
              $html .= '</select>';
    echo $html;
  
  
  
  }else if($_POST['tsk'] == 'get_client_box'){



    $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);

        $html = '<select id="client_id_add" name="client_id" class="selectpicker" data-live-search="true" title="Choose Client">
                  <option value="">Edit Client</option>';
                  foreach($clientList as $cl){ 
                  $html .='<option value="'.$cl['cl_id'].'" >'.$gnrl->trunc_string($cl['cl_company_name'],50).'</option>';
                   } 
                  $html .='</select>';
                                                        
    echo $html;
  
  
  }else if($_POST['tsk'] == 'get_team_box'){


    $teamlist = $dclass->select("*","tbl_team"," AND company_user_id =".$_SESSION['company_id']);
      $html ='<select id="team_id_add" name="team_id" class="selectpicker" data-live-search="true" >
                                <option value="">Edit Team</option>';
                                foreach($teamlist as $tm){
                                $html .='<option value="'.$tm['tm_id'].'">'.$gnrl->trunc_string($tm['tm_title'],50).'</option>';
                                 } 
                                $html .= '</select>';
    echo $html;
  
  }else if($_POST['tsk'] == 'check_update'){


    $checkupdate = $dclass->select("*","tbl_task_update_notification"," AND user_id = '".$_SESSION['user_id']."'");
    echo count($checkupdate);
    $dclass->delete('tbl_task_update_notification'," user_id = '".$_SESSION['user_id']."'");
    // die();
  
  
  
  }
  
}

?>

