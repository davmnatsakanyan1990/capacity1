<?php 
require_once("../config/dbconfig.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
global $mail;

session_start();
function string_sanitize($s) {
    $result = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($s, ENT_QUOTES));
    return $result;
}
function days_in_month($month, $year){
// calculate number of days in a month
	return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
} 
function isSunday($day, $month, $year,$daynumber){
    if (date('w', $date = mktime(0,0,0,$month,$day,$year)) == $daynumber) {
        return $date;
    }
    return false;
}
function getWednesdays($month, $year,$daynumber){
    
    for ($day=1; $day<=7; $day++) {
        if ($date = isSunday($day, $month, $year,$daynumber)) {
            break;
        }
    }

    $wednesdays = array();

    while (date('m',$date) == $month) {
        $wednesdays[] = $date;
        $day += 7;
        $date = isSunday($day, $month, $year,$daynumber);
    }
    return $wednesdays;
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
function taskdayhours($time3,$time4,$team_id,$startflg,$endflg){
	global $dclass;
	$teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id = '".$_SESSION['company_id']."' ");
	
	$time = explode(':::',$teamWorkDay[0]['working_time']);
			
	$time1 = strtotime($time[0]);
	$time2 = strtotime($time[1]);

	$df1 = $time3 - $time1;
	$df2 = $time2 - $time4;

	$hr1 = $df1/3600;
	$hr2 = $df2/3600;
	$fnhr = 0;
	if($startflg == 'true'){
		$fnhr += $hr1;		
	}
	if($endflg == 'true'){
		$fnhr += $hr2;		
	}
	return $fnhr;
} 
function getFreehourMonth($user_id,$team_id=''){
	global $dclass;
	
	//code for get total days of month
	$month = date("m");
	$year = date("Y");
	$total_day = days_in_month($month,$year);
	//echo 'Total Days of Month->'.$total_day.'<br>';
	//code for get total holidays of month
	$total_holidays = $dclass->select("*","tbl_holidays"," 
		AND holi_user_id=".$_SESSION['company_id']." 
		AND ($month=MONTH(holi_start_date) OR $month=MONTH(holi_end_date)) ");
	
	$holiday = 0;
	foreach($total_holidays as $hl){
		$startdt = $hl['holi_start_date'];
		$currdt = $hl['holi_start_date'];
		$enddt = $hl['holi_end_date'];
		
		do{
		   $ckdt = strtotime("+1 day", strtotime($currdt));
		   $currdt = date("Y-m-d", $ckdt);
			if(date("m", $ckdt) == $month){
				$holiday++;	
			}	
		}while($currdt < $enddt);
	}
	
	 $holiday;
	 //echo 'Total Holidays of Month->'.$holiday.'<br>';
	//code for get total working day of month
	 $total_working_day = $total_day - $holiday;
	 //echo 'Total Working days of Month->'.$total_working_day.'<br>';
	//code for get total working hours of month
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
			/*$LunchHours = $dclass->select("*","tbl_lunch_hours"," AND team_id = '".$team_id."' AND company_user_id = '".$_SESSION['company_id']."' ");
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
			$workhour_of_day = '8';
		}
	}else{
		$workhour_of_day = '8';
	}
	//echo 'Total Working hours of day->'.$workhour_of_day.'<br>';
	// code for calculate total working hours of month
	$total_working_hours_of_month = $total_working_day * $workhour_of_day;
	//echo 'Total Working hours of month->'.$total_working_hours_of_month.'<br>';

	//code for getting user busy hours
	$userTask = $dclass->select("*","tbl_task"," AND t_operator_id= '".$user_id."' 
		AND ($month=MONTH(t_start_datetime) OR $month=MONTH(t_end_datetime))");
	
	$duratin = 0;
	foreach($userTask as $tsk){
		 $dr = str_replace(' hours','',$tsk['t_duration']);	
		 $duratin += $dr;
	}
	$busyhour = $duratin;
	//echo 'Total busy hours of month->'.$busyhour.'<br>';

	$free_hours_of_month = $total_working_hours_of_month - $busyhour;
	//echo 'Total Free hours of month->'.$free_hours_of_month.'<br>';

	echo number_format($free_hours_of_month,2);

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
function get_freehour_week($user_id,$team_id='',$startDate,$endDate,$mode,$project_id,$client_id){
	global $dclass;
		
	//$endDate = 'Sun Jan 18 2015 05:30:00 GMT+0530 (IST)';
	//$startDate = 'Sun Jan 11 2015 05:30:00 GMT+0530 (IST)';

	$sdate = date("Y-m-d", strtotime($startDate));
	$edate = date("Y-m-d", strtotime($endDate));
	
	//$edate = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($edate_temp)) . " -1 days "));
	//echo '<br>';
	$month_val = date("m", strtotime($startDate));
	$year_val = date("Y", strtotime($startDate));

	$start_date = strtotime($sdate);	
	$end_date = strtotime(date("Y-m-d", strtotime($edate)));

	$datediff = $end_date - $start_date ;
    $total_day = floor($datediff/(60*60*24));
   	
   	//echo 'Total days of week->'.$total_day.'<br>';
   	// code for get weok hour of day
   	if(isset($team_id) && $team_id != ''){
		$teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id = '".$_SESSION['company_id']."' ");
		if(count($teamWorkDay) > 0){
			$time = explode(':::',$teamWorkDay[0]['working_time']);
			$time1 = strtotime($time[0]);
			$time2 = strtotime($time[1]);
			$diff = $time2 - $time1;
			$lh = getLunchDuration($team_id);
			$workhour = $diff/3600;
			
			$workhour_of_day = $workhour - $lh;
		
		}else{
			$teamWorkDay_ge = $dclass->select("*","tbl_working_day_time"," AND  type= 'general' ");
			$time = explode(':::',$teamWorkDay_ge[0]['working_time']);
			$time1 = strtotime($time[0]);
			$time2 = strtotime($time[1]);
			$diff = $time2 - $time1;
			$workhour = $diff/3600;
			$lh = getLunchDuration($team_id);
			$workhour_of_day = $workhour - $lh;

		}
	}else{
			$teamWorkDay_ge = $dclass->select("*","tbl_working_day_time"," AND  type= 'general' ");
			$time = explode(':::',$teamWorkDay_ge[0]['working_time']);
			$time1 = strtotime($time[0]);
			$time2 = strtotime($time[1]);
			$diff = $time2 - $time1;
			$workhour = $diff/3600;
			$workhour_of_day = $workhour;

			/*$LunchHours = $dclass->select("*","tbl_lunch_hours"," AND team_id = '".$team_id."' AND company_user_id = '".$_SESSION['company_id']."' AND show_in_calender = 'yes'");
			if(count($LunchHours) > 0){
				$lhr = explode(':::',$LunchHours[0]['lunch_hours']);
				$lunch1 = strtotime($lhr[0]);
				$lunch2 = strtotime($lhr[1]);
				$diff1 = $lunch2 - $lunch1;
				$lunch_hr = $diff1/3600;
				//echo 'linch hour->'.$lunch_hr.'<br>';
				$workhour_of_day = $workhour - $lunch_hr;
			}else{
				$workhour_of_day = $workhour;
			}*/

			$workhour_of_day = $workhour;

			
	}
	
	//echo 'work hour of day'.$workhour_of_day.'<br>';
	//code for get total holidays of month

	$teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id = '".$_SESSION['company_id']."' ");
   	$weekday = array(0,1,2,3,4,5,6);
   	
   	$onday_array = explode(":::",$teamWorkDay[0]['working_days']);
   	$offday = array_diff($weekday, $onday_array);
   if($mode == 'agendaWeek'){
	   	
	 	$offday_counter = count($weekday) - count($onday_array);
   }else{
   		$offday_counter = 0;
   }
   	
   	/*foreach($offday as $off){
   		$data = getWednesdays($month_val,$year_val,$off);
   		$offday_counter += count($data);
   	}*/

$holiday_arr = array();
	$total_holidays = $dclass->select("*","tbl_holidays"," 
		AND holi_user_id=".$_SESSION['company_id']." 
		AND (holi_start_date BETWEEN '".$sdate."' AND '".$edate."' OR holi_end_date BETWEEN '".$sdate."' AND '".$edate."') ");
 	//echo '<pre>'; print_r($total_holidays); echo '</pre>';
 	$holiday = 0;

	foreach($total_holidays as $hl){
		$startdt = $hl['holi_start_date'];
		
		$enddt = $hl['holi_end_date'];
		$currdt = $hl['holi_start_date'];
		if($hl['holi_start_date'] > $sdate){
			
			while (strtotime($currdt) < strtotime($enddt)){
				
				if(strtotime($currdt) <= strtotime($edate) ){
					$holiday++;	
					$holiday_arr[] = $currdt;
				}
				 $currdt = date ("Y-m-d", strtotime("+1 day", strtotime($currdt)));
			}
		}else{
			
			while (strtotime($currdt) < strtotime($enddt)){
				if(strtotime($currdt) <= strtotime($sdate) ){
					$holiday++;	
					$holiday_arr[] = $currdt;
				}
				 $currdt = date ("Y-m-d", strtotime("+1 day", strtotime($currdt)));
			}
		}
	}
	//$holiday = 0;
	
	//echo 'Total holiday of week ->'.$holiday.'<br>';
	//echo 'Total day ->'.$total_day.'<br>';
	//echo 'Total offday ->'.$offday_counter.'<br>';
	
	$total_working_day = $total_day - $holiday;
	$total_working_day = $total_working_day - $offday_counter;

	//echo 'Total Working days of Week->'.$total_working_day.'<br>';
	$total_working_hours_of_month = $total_working_day * $workhour_of_day;
	//echo 'Total Working hours of Week->'.$total_working_hours_of_month.'<br>';
	//code for get total working hours of month
	
	if($total_working_day > 0){
		//code for getting user busy hours
		$cond = '';
		if($project_id != ''){
			$cond .= ' AND t_pr_id = "'.$project_id.'" ';
		}
		if($client_id != ''){
			$cond .= ' AND t_cl_id = "'.$client_id.'" ';
		}

		
			$newArr = array();
			$durations = 0;
					$curr_dt = $sdate;
					//echo $sdate.' '.$edate;
					while (strtotime($curr_dt) < strtotime($edate)) {
            	      
            	       $taskDuration = $dclass->select("t_duration","tbl_task","  AND t_type = 'calendar' AND t_operator_id= '".$user_id."' AND t_start_datetime like '%".date("Y-m-d", strtotime($curr_dt))."%' ");
						
            	    	//print_r($taskDuration);
            	    	$temp_dr = 0;   
						foreach($taskDuration as $dr){
							$t_duration = str_replace(' hours','',$dr['t_duration']);
							$temp_dr += $t_duration;

						}
						$d = date("d", strtotime($curr_dt));
						$newArr[$d] = $workhour_of_day - $temp_dr;
				       
				        $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
				        $durations += $temp_dr;
            		}

		

			$busyhour = $durations;	
	}else{
		$busyhour = 0;
	}
	ksort($newArr);
	//echo '<pre>'; print_r($newArr); echo '</pre>';
	
	$freehours = 0;
	
	foreach($newArr as $key=>$val ){
		$freehours += $val;
	}
	$status_free_hours_of_month = $total_working_hours_of_month - $busyhour;

	//echo 'Total Working hours of Week->'.$total_working_hours_of_month.'<br>';
	//echo 'Total busy hours of week->'.$busyhour.'<br>';
	//echo 'Total free hours of week->'.$status_free_hours_of_month.'<br>';
	
	//$free_hours_of_month = $freehours;
	
	$freePercentage = ($busyhour * 100) / $total_working_hours_of_month;
		
	$bgcolor = 'green';	
	if($freePercentage <= 75){
		$bgcolor = 'green';	
	}else if($freePercentage > 75 && $freePercentage <= 90){
		$bgcolor = 'orange';
	}else if($freePercentage > 90 && $freePercentage <= 100){
		$bgcolor = 'red';
	}
	
	//echo 'Total free Percentage->'.$freePercentage." === ".$bgcolor.'<br>';
	//print_r($newArr);
	$tempdt = $sdate;

	if($mode == 'agendaWeek'){
		$myhtml = '';
		while (strtotime($tempdt) < strtotime($edate)) {
		    if(checkholiday($tempdt)){
		    	$myhtml .='<div class="innerdiv" >'.number_format(0,2).'</div>';	
		    }else if(checkworkingday($team_id,$tempdt)){
		    	$myhtml .='<div class="innerdiv" >'.number_format(0,2).'</div>';
		    }else{
		       $d = date("d", strtotime($tempdt));
				if(isset($newArr[$d])){
					
					$myhtml .='<div class="innerdiv" >'.number_format($newArr[$d],2).'</div>';
				}else{
					
					$myhtml .='<div class="innerdiv" >'.number_format(8,2).'</div>';
				}
			}
		    $tempdt = date ("Y-m-d", strtotime("+1 day", strtotime($tempdt)));
	    }
	    return  $myhtml.':::'.$freePercentage.'%:::'.$bgcolor;	
	}else{
		if(checkholiday($tempdt)){
		    return number_format(0,2).' Hours free in this Day:::'.$freePercentage.'%:::'.$bgcolor;
		}else if(checkworkingday($team_id,$tempdt)){
		    return number_format(0,2).' Hours free in this Day:::'.$freePercentage.'%:::'.$bgcolor;
		}else{
			return number_format($status_free_hours_of_month,2).' Hours free in this Day:::'.$freePercentage.'%:::'.$bgcolor;
		}	
		  
	}


	
	//echo 'Total Free hours of month->'.$free_hours_of_month.'<br>';
	//return  number_format($free_hours_of_month,2);
}
function get_freehour_week_1($user_id,$team_id='',$startDate,$endDate,$mode,$project_id,$client_id){
	global $dclass;
		
	//$endDate = 'Sun Jan 18 2015 05:30:00 GMT+0530 (IST)';
	//$startDate = 'Sun Jan 11 2015 05:30:00 GMT+0530 (IST)';
	$getmonthdat = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($startDate)) . " +10 days "));
	$month_val = date("m", strtotime($getmonthdat));
	$year_val = date("Y", strtotime($getmonthdat));
	$total_day = days_in_month($month_val,$year_val);
	
	$sdate = date($year_val.'-'.$month_val.'-01');
    $edate = date($year_val.'-'.$month_val.'-'.$total_day);

    //$edate = date($year_val.'-'.$month_val.'-'.$total_day);
    $edate = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($edate)) . " +1 days "));

	/*$sdate = date("Y-m-d", strtotime($startDate));
	$edate = date("Y-m-d", strtotime($endDate));*/
	
	$start_date = strtotime($sdate);	
	$end_date = strtotime(date("Y-m-d", strtotime($edate)));

	//$datediff = $end_date - $start_date ;
    //$total_day = floor($datediff/(60*60*24));
   
   	//echo 'Total days of month->'.$total_day.'<br>';
   	// code for get weok hour of day
   	

   	if(isset($team_id) && $team_id != ''){
		$teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id = '".$_SESSION['company_id']."' ");
		
		if(count($teamWorkDay) > 0){
			$time = explode(':::',$teamWorkDay[0]['working_time']);
			$time1 = strtotime($time[0]);
			$time2 = strtotime($time[1]);
			$diff = $time2 - $time1;
			$lh = getLunchDuration($team_id);
			$workhour = $diff/3600;
			
			$workhour_of_day = $workhour - $lh;
		
		}else{
			$teamWorkDay_ge = $dclass->select("*","tbl_working_day_time"," AND  type= 'general' ");
			$time = explode(':::',$teamWorkDay_ge[0]['working_time']);
			$time1 = strtotime($time[0]);
			$time2 = strtotime($time[1]);
			$diff = $time2 - $time1;
			$workhour = $diff/3600;
			$lh = getLunchDuration($team_id);
			$workhour_of_day = $workhour - $lh;

		}
	
	
	}else{
			$teamWorkDay_ge = $dclass->select("*","tbl_working_day_time"," AND  type= 'general' ");
			$time = explode(':::',$teamWorkDay_ge[0]['working_time']);
			$time1 = strtotime($time[0]);
			$time2 = strtotime($time[1]);
			$diff = $time2 - $time1;
			$workhour = $diff/3600;
			$workhour_of_day = $workhour;

			/*$LunchHours = $dclass->select("*","tbl_lunch_hours"," AND team_id = '".$team_id."' AND company_user_id = '".$_SESSION['company_id']."' AND show_in_calender = 'yes'");
			if(count($LunchHours) > 0){
				$lhr = explode(':::',$LunchHours[0]['lunch_hours']);
				$lunch1 = strtotime($lhr[0]);
				$lunch2 = strtotime($lhr[1]);
				$diff1 = $lunch2 - $lunch1;
				$lunch_hr = $diff1/3600;
				//echo 'linch hour->'.$lunch_hr.'<br>';
				$workhour_of_day = $workhour - $lunch_hr;
			}else{
				$workhour_of_day = $workhour;
			}*/
		
	}
	//echo 'work hour of day'.$workhour_of_day.'<br>';
	//code for get total holidays of month

	$teamWorkDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id = '".$_SESSION['company_id']."' ");
   	$weekday = array(0,1,2,3,4,5,6);
   	
   	$onday_array = explode(":::",$teamWorkDay[0]['working_days']);
   	$offday = array_diff($weekday, $onday_array);
 
   	$offday_counter = 0;
   	foreach($offday as $off){
   		$data = getWednesdays($month_val,$year_val,$off);
   		$offday_counter += count($data);
   	}


	$total_holidays = $dclass->select("*","tbl_holidays"," 
		AND holi_user_id=".$_SESSION['company_id']." 
		AND (holi_start_date BETWEEN '".$sdate."' AND '".$edate."' OR holi_end_date BETWEEN '".$sdate."' AND '".$edate."') ");
 	//echo '<pre>'; print_r($total_holidays); echo '</pre>';
 	$holiday = 0;

	foreach($total_holidays as $hl){
		$startdt = $hl['holi_start_date'];
		
		$enddt = $hl['holi_end_date'];
		$currdt = $hl['holi_start_date'];
		if($hl['holi_start_date'] > $sdate){
			
			while (strtotime($currdt) < strtotime($enddt)){
				
				if(strtotime($currdt) <= strtotime($edate) ){
					$holiday++;	
				}
				 $currdt = date ("Y-m-d", strtotime("+1 day", strtotime($currdt)));
			}
		}else{
			
			while (strtotime($currdt) < strtotime($enddt)){
				if(strtotime($currdt) <= strtotime($sdate) ){
					$holiday++;	
				}
				 $currdt = date ("Y-m-d", strtotime("+1 day", strtotime($currdt)));
			}
		}
	}
	//$holiday = 0;
	//echo 'Total holiday of week ->'.$holiday.'<br>';
	//echo 'Total offday ->'.$offday_counter.'<br>';
	//echo "total day -> ".$total_day;

	$total_working_day = $total_day - $holiday;
	$total_working_day = $total_working_day - $offday_counter;
	
	//echo 'Total Working days of Week->'.$total_working_day.'<br>';
	$total_working_hours_of_month = $total_working_day * $workhour_of_day;
	//echo 'Total Working hours of Week->'.$total_working_hours_of_month.'<br>';
	//code for get total working hours of month
	
	if($total_working_day > 0){
		//code for getting user busy hours
		$cond = '';
		if($project_id != ''){
			$cond .= ' AND t_pr_id = "'.$project_id.'" ';
		}
		if($client_id != ''){
			$cond .= ' AND t_cl_id = "'.$client_id.'" ';
		}

		
			$newArr = array();
			$durations = 0;
					$curr_dt = $sdate;
					//echo $sdate.' '.$edate;
					while (strtotime($curr_dt) < strtotime($edate)) {
            	      
            	       $taskDuration = $dclass->select("t_duration","tbl_task","  AND t_type = 'calendar' AND t_operator_id= '".$user_id."' AND t_start_datetime like '%".date("Y-m-d", strtotime($curr_dt))."%' ");
						
            	    	//print_r($taskDuration);
            	    	$temp_dr = 0;   
						foreach($taskDuration as $dr){
							$t_duration = str_replace(' hours','',$dr['t_duration']);
							$temp_dr += $t_duration;

						}
						
						$d = date("d", strtotime($curr_dt));
						//echo $d.'==>'.$temp_dr.'<br>';
						$newArr[$d] = $workhour_of_day - $temp_dr;
				        $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
				        $durations += $temp_dr;	
            		}

		

			$busyhour = $durations;	
	}else{
		$busyhour = 0;
	}
	
	ksort($newArr);
	
	//echo '<pre>'; print_r($newArr); echo '</pre>';
	//echo 'Total busy hours of week->'.$busyhour.'<br>';
	$freehours = 0;
	
	foreach($newArr as $key=>$val ){
		$freehours += $val;
	}

	$free_hours_of_month = $total_working_hours_of_month - $busyhour;

	$status_free_hours_of_month = $total_working_hours_of_month - $busyhour;
	 
	//$free_hours_of_month = $freehours;
	$freePercentage = ($busyhour * 100) / $total_working_hours_of_month;

	//echo 'Total free Percentage->'.$freePercentage.'<br>';
	if($freePercentage < 75){
		$bgcolor = 'green';	
	}else if($freePercentage > 75 && $freePercentage < 90){
		$bgcolor = 'orange';
	}else if($freePercentage > 90 && $freePercentage < 100){
		$bgcolor = 'red';
	}
	//$free_hours_of_month = $freehours;
	$tempdt = $sdate;
	if($mode == 'agendaWeek'){
		$myhtml = '';
		while (strtotime($tempdt) < strtotime($edate)) {
		       
		       $d = date("d", strtotime($tempdt));
				if(isset($newArr[$d])){
					
					$myhtml .='<div class="innerdiv" >'.number_format($newArr[$d],2).'</div>';
				}else{
					
					$myhtml .='<div class="innerdiv" >'.number_format(8,2).'</div>';
				}
		    $tempdt = date ("Y-m-d", strtotime("+1 day", strtotime($tempdt)));
	    }
	    return  $myhtml.':::'.$freePercentage.'%:::'.$bgcolor;		
	}else{
		return  number_format($free_hours_of_month,2).' Hours free in this month:::'.$freePercentage.'%:::'.$bgcolor;
	}

	
	//echo 'Total Free hours of month->'.$free_hours_of_month.'<br>';
	//return  number_format($free_hours_of_month,2);
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
function checkfornextAvailabeltime_16032017($userid,$startdate,$enddate,$work_hour,$t_start_datetime,$task_end,$caldr,$postdr,$s_time,$e_time,$team_id){
 
global $dclass;
  $tskArr = array();
 //get task list

  $chktime = $dclass->select("t_start_datetime,t_end_datetime,t_duration","tbl_task"," 
    AND t_operator_id = ".$userid." AND t_type = 'calendar'
      AND ((t_start_datetime BETWEEN '".$startdate."' AND '".$enddate."') OR (t_end_datetime BETWEEN '".$startdate."' AND '".$enddate."') )
   ORDER BY t_start_datetime asc");
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
          
          if($t_duration <= $taskduration){
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
            if($reminders[1] != 0){
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
function getLunchDuration1($team_id,$starttime,$endtime){
 global $dclass;
 $lunchhr = $dclass->select("lunch_hours","tbl_lunch_hours"," AND team_id = '".$team_id."' AND show_in_calender = 'yes' AND company_user_id=".$_SESSION['company_id']."");
 if(count($lunchhr) > 0){
  $lunch = explode(':::',$lunchhr[0]['lunch_hours']);
     $tm1 = strtotime(date("H:i:s", strtotime($lunch[0])));
     $tm2 = strtotime(date("H:i:s", strtotime($lunch[1])));
      
      if(strtotime($starttime) >=  $tm1 && strtotime($starttime) <  $tm2) {
      	$temp_tmdr = $tm2 - strtotime($starttime);
      	$tmdr = $temp_tmdr/3600;
      }else if(strtotime($endtime) >  $tm1 && strtotime($endtime) <  $tm2){
      	$temp_tmdr = $tm2 - strtotime($endtime);
      	$tmdr = $temp_tmdr/3600;
      }else if(strtotime($starttime) <=  $tm1 && strtotime($endtime) >=  $tm2){
      	$temp_tmdr = $tm2 - $tm1;
      	$tmdr = $temp_tmdr/3600;
      }else if(strtotime($starttime) <  $tm1 && strtotime($endtime) >=  $tm2){
      	$temp_tmdr = $tm2 - $tm1;
      	$tmdr = $temp_tmdr/3600;
      }else{
      	//$temp_tmdr = $tm2 - $tm1;
      	$tmdr = 0;
      }   
      
      return $tmdr;	


 }else{
  return 0;
 }

}
function checkstartinlunch($team_id,$starttime){
 global $dclass;
 $lunchhr = $dclass->select("lunch_hours","tbl_lunch_hours"," AND team_id = '".$team_id."' AND show_in_calender = 'yes' AND company_user_id=".$_SESSION['company_id']."");
 if(count($lunchhr) > 0){
  $lunch = explode(':::',$lunchhr[0]['lunch_hours']);
     $tm1 = strtotime(date("H:i:s", strtotime($lunch[0])));
     $tm2 = strtotime(date("H:i:s", strtotime($lunch[1])));
      
      if(strtotime($starttime) >=  $tm1 && strtotime($starttime) <  $tm2) {
      	$tmdr = 1;
      }else{
      	$tmdr = 0;
      }   
      
      return $tmdr;	


 }else{
  return 0;
 }

}
function checkholidayoverlapping($startdate,$enddate){
	//echo 'check holiday=='.$startdate.'=='.$enddate.'==';
 global $dclass;
 $holidays = $dclass->select("holi_id","tbl_holidays"," 
    AND holi_user_id=".$_SESSION['company_id']." 
    AND ( holi_start_date BETWEEN '".$startdate."' AND '".$enddate."' OR holi_end_date BETWEEN '".$startdate."' AND '".$enddate."' )  ");

  if(count($holidays) > 0){
    return 1;
  }else{
    return 0;
  }
}
function trunc_string($str,$len){
    if( strlen($str) >= $len ){
        return substr($str,0,$len).'...';
    }else{
        return substr($str,0,$len);
    }
}
function getworkdays($workarray){
    $myarray = array("0","1","2","3","4","5","6");
    $data = array_diff($myarray,$workarray);
    return $data;
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
function task_updated($t_id) {
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
if(isset($_POST['tsk']) && $_POST['tsk'] != '' ){ 
	if($_POST['tsk'] == 'get_free_hours'){


		if($_REQUEST['viewmode'] == 'month'){
			//$data = getFreehourMonth($_POST['user_id'],$_POST['team_id']);	
			$sdate = date("Y-m-d", strtotime($_POST['sdate']));
			$edate = date("Y-m-d", strtotime($_POST['edate']));
			//echo $sdate.''.$edate; die();
			$data = get_freehour_week_1($_POST['user_id'],$_POST['team_id'],$sdate,$edate,'month',$_POST['project_id'],$_POST['client_id']);
			echo $data;
		}
		else if($_REQUEST['viewmode'] == 'agendaWeek'){

			
			$sdate = date("Y-m-d", strtotime($_POST['sdate']));
			$edate = date("Y-m-d", strtotime($_POST['edate']));
			$data = get_freehour_week($_POST['user_id'],$_POST['team_id'],$sdate,$edate,'agendaWeek',$_POST['project_id'],$_POST['client_id']);
			echo $data;
		
		}else if($_REQUEST['viewmode'] == 'agendaDay'){
			$sdate = date("Y-m-d", strtotime($_POST['sdate']));
			$edate = date("Y-m-d", strtotime($_POST['edate']));
		

			$data = get_freehour_week($_POST['user_id'],$_POST['team_id'],$sdate,$edate,'agendaDay',$_POST['project_id'],$_POST['client_id']);
			echo $data;
		}
	
	
	$_SESSION['def_date'][$_POST['user_id']] = $sdate;
	

	
	
	
	
	
	
	
	
	
	}else if($_POST['tsk'] == 'edit_task'){



    $t_start_date = date("Y-m-d", strtotime($_POST['sdate']));
    $t_start_datetime = $_POST['sdate'];
	$curr_dt = date("Y-m-d", strtotime($t_start_datetime));
	$starttime = date("H:i:s", strtotime($t_start_datetime));
	$timedt = getTimeDetail($_POST['team_id']);
	$lh = getLunchDuration($_POST['team_id']);
	if(isset($_POST['edit_by']) && $_POST['edit_by'] == 'drop_event'){


		$team_work_hour = getWorkinghours($_POST['team_id']);
      	
      
      if($team_work_hour > $_POST['t_duration']){
        
          $time_st1 = strtotime($trim_time);
          $time_st2 = strtotime($timedt[1]);
          
          $taskdur = $time_st2 - $time_st1;
          $taskduration = $taskdur/3600;
           
          if($_POST['t_duration'] < $taskduration){
            $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($t_start_datetime)) . " +".$duration." "));    
          }else{
            $reminder = ($_POST['t_duration'] - $taskduration);
            $duration_temp = $reminder." hours";

            $end_date_temp = date("Y-m-d", strtotime(date("Y-m-d", strtotime($t_start_datetime)) . " +1 days"));
            $end_date_temp1 = $end_date_temp.' '.$timedt[0];
            $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp1)) . " +".$duration_temp." "));          
          } 
        
      }else{
        $myextend =  round($_POST['t_duration'] / $team_work_hour);   
        $reminder = ($_POST['t_duration'] % $team_work_hour);
        $duration_temp = $reminder." hours";
        $end_date_temp = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($t_start_datetime)) . " +".$myextend." days "));      
        $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($end_date_temp)) . " +".$duration_temp." "));      
      }
      $fndr = $_POST['t_duration'];
	
	}else{

		$end_date = $_POST['edate'];	 
		
		$tm1 = strtotime(date("H:i:s", strtotime($t_start_datetime)));
		$tm2 = strtotime(date("H:i:s", strtotime($end_date)));
		
		$taskd = $tm2 - $tm1;
        
        $fndr = $taskd/3600;
	
	} 



	$endtime = date("H:i:s", strtotime($end_date));	
    $myduration = 0;
    $ed_dt = date("Y-m-d", strtotime($end_date));
    $tid = $_POST['task_id'];
     $checktime = $dclass->select(" * ","tbl_task_timing"," AND t_id=".$tid);    
          if(count($checktime) > 0){
            $dclass->delete("tbl_task_timing"," t_id=".$tid);    
     }
    if($curr_dt != $ed_dt){
    	while (strtotime($curr_dt) < strtotime($end_date)){
            
          $query_stdt = $curr_dt;
          $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
          $query_endt = $curr_dt;

              
          if($query_stdt == date("Y-m-d",strtotime($t_start_datetime)) ){

                    $time_st1 = strtotime($starttime);
                    $time_st2 = strtotime($timedt[1]);
                    
                    $taskdur = $time_st2 - $time_st1;
                    $taskduration = $taskdur/3600;
                    $taskduration = $taskduration;

                    $includelunch = checkLunch($starttime,$timedt[1],$_POST['team_id']);

                    if($includelunch > 0){
                      $taskduration = $taskduration - $lh; 
                    }

                    $myduration += $taskduration; 
                     $tstp = 'start';
                     $query_stdt = $query_stdt.' '.$starttime;
                    $query_endt = $query_endt.' '.$timedt[1];

          }else if($query_stdt == date("Y-m-d", strtotime($end_date)) ){

                   $time_st1 = strtotime($endtime);
                   $time_st2 = strtotime($timedt[0]);

                   $taskdur = $time_st1 - $time_st2;
                   $taskduration = $taskdur/3600;                     
                   
                   $includelunch = checkLunch($timedt[0],$endtime,$_POST['team_id']);
                    if($includelunch > 0){
                      $taskduration = $taskduration - $lh; 
                    }

                   $myduration += $taskduration;
                   $tstp = 'end';
                   $query_stdt = $query_stdt.' '.$timedt[0];
                   $query_endt = $query_endt.' '.$endtime;
                
          }else{
                  $time_st1 = strtotime($timedt[0]);
                  $time_st2 = strtotime($timedt[1]);
                  $df1 = $time_st2 - $time_st1;

                  $taskduration = $df1/3600;
                  $taskduration = $taskduration - $lh; 
                  $myduration += $taskduration;
                  $tstp = 'middle';
                  $query_stdt = $query_stdt.' '.$timedt[0];
                  $query_endt = $query_endt.' '.$timedt[1];
                
          }
                
            $ina = array(
                  "t_id"=>$tid,
                  "t_start_datetime"=>$query_stdt,
                  "t_end_datetime"=>$query_endt,
                  "duration" => $taskduration,
                  "task_tp" => $tstp,
            );  
                     
            $dclass->insert("tbl_task_timing",$ina);    
  
        }
    }else{
    		
                $ina = array(
                  "t_id"=>$tid,
                  "t_start_datetime"=>$t_start_datetime,
                  "t_end_datetime"=>$end_date,
                  "duration" => $fndr,
                  "task_tp" => 'start',
                  
                );  
             

              $dclass->insert("tbl_task_timing",$ina);
              $myduration =  $fndr;
    }



        $myduration = round($myduration);
	    $dr = $myduration.' hours';
     		if(isset($_POST['edit_by']) && $_POST['edit_by'] == 'drop_event'){
     			$ins = array(
			  	"t_start_datetime"=> $t_start_datetime,
				"t_end_datetime"=> $end_date,
				"t_duration"=>$dr,
				"t_operator_id"=>$_POST['user_id'],
				"t_type"=>'calendar',
		  		);
     		}else{
     			$ins = array(
			    "t_start_datetime"=> $t_start_datetime,
				"t_end_datetime"=> $end_date,
				"t_duration"=>$dr,
				"t_operator_id"=>$_POST['user_id'],
				
          	);
     		}
     		
     		//print_r($ins);	
     		$dclass->update("tbl_task",$ins, 't_id='.$tid);
			task_updated($tid);
	
	
	
	
	}else if($_POST['tsk'] == 'edit_task_new'){




    $t_start_date = date("Y-m-d", strtotime($_POST['sdate']));
    $t_start_datetime = $_POST['sdate'];

	$curr_dt = date("Y-m-d", strtotime($t_start_datetime));
	$starttime = date("H:i:s", strtotime($t_start_datetime));
	$trim_time = date("H:i:s", strtotime($t_start_datetime));
	$timedt = getTimeDetail($_POST['team_id']);
	if($starttime == '00:00:00'){
		$starttime = $timedt[0];
		$t_start_datetime = $t_start_date.' '.$starttime;
	}
	
	//check overlaping event
	if(isset($_POST['edit_type']) && $_POST['edit_type'] == 'task_move' ){
		$end_date_test = $_POST['edate'];
		$endtime_test = date("H:i:s", strtotime($end_date_test));
		if(strtotime($endtime_test) > strtotime($timedt[1]) || strtotime($starttime) <  strtotime($timedt[0])){
			echo "not_allow";
	        die(); 
		}
	}
	//echo 'myend date::'.$end_date;

	$team_work_hour = getWorkinghours($_POST['team_id']);
 	$lh = getLunchDuration($_POST['team_id']);
 	
	if(isset($_POST['edit_by']) && $_POST['edit_by'] == 'drop_event'){

		$CheckQueueexist = $dclass->select('t_start_datetime,t_end_datetime',"tbl_task","AND t_type='queue' AND t_id=".$_POST['task_id']);
		if(count($CheckQueueexist) == 0){
			echo "Allready_assign";
	       	die();
		}

		/*$cklunc = checkstartinlunch($_POST['team_id'],$starttime);
		if($cklunc){
		 	echo "start_in_lunch";
	       	die(); 
		}*/
      $end_date = extenddate($t_start_datetime,$_POST['team_id'],$_POST['t_duration'].' hours');
     
      $includelunch = checkLunch(date("H:i:s", strtotime($t_start_datetime)),date("H:i:s", strtotime($end_date)),$_POST['team_id']);
	  $fndr = $_POST['t_duration'];
	  if($includelunch > 0){
	      //$fndr = $fndr + $lh;
	      $end_date = extenddate($t_start_datetime,$_POST['team_id'],$fndr.' hours'); 
	  }
	  $_POST['t_duration'] = $fndr; 
    
	
	}else if(isset($_POST['event_type']) && $_POST['event_type'] == 'event_drop'){


		$end_date = $_POST['edate'];	 
		$tm1 = strtotime(date("H:i:s", strtotime($t_start_datetime)));
		$tm2 = strtotime(date("H:i:s", strtotime($end_date)));
		$taskd = $tm2 - $tm1;
        $fndr = $taskd/3600;
		
		/*$cklunc = checkstartinlunch($_POST['team_id'],$starttime);
 		if($cklunc){
		 	echo "start_in_lunch";
	        die(); 
		}*/

		$CheckPrevdate = $dclass->select('t_start_datetime,t_end_datetime',"tbl_task"," AND t_id=".$_POST['task_id']);
        
        $chkprevlunct = checkLunch(date("H:i:s", strtotime($CheckPrevdate[0]['t_start_datetime'])),date("H:i:s", strtotime($CheckPrevdate[0]['t_end_datetime'])),$_POST['team_id']);
        $lh1 = getLunchDuration1($_POST['team_id'],$starttime,date("H:i:s", strtotime($end_date)));

        if($chkprevlunct < 1){
	        
	        $includelunch = checkLunch(date("H:i:s", strtotime($t_start_datetime)),date("H:i:s", strtotime($end_date)),$_POST['team_id']);
	        if($includelunch > 0){
	          $fndr = $fndr + $lh;
	          $end_date = extenddate($t_start_datetime,$_POST['team_id'],$fndr.' hours'); 
	        }
    	}else{
    		
    		$includelunch = checkLunch(date("H:i:s", strtotime($t_start_datetime)),date("H:i:s", strtotime($end_date)),$_POST['team_id']);
	        if($includelunch < 1){
	          $fndr = $fndr - $lh;
	          $end_date = extenddate($t_start_datetime,$_POST['team_id'],$fndr.' hours'); 
	        }
    	}
    	
    	$includelunch = checkLunch(date("H:i:s", strtotime($t_start_datetime)),date("H:i:s", strtotime($end_date)),$_POST['team_id']);
    	if($includelunch > 0){
    	    $fndr = $fndr - $lh; 
        }
  	
  	}else{


		$end_date = $_POST['edate'];	 
		$tm1 = strtotime(date("H:i:s", strtotime($t_start_datetime)));
		$tm2 = strtotime(date("H:i:s", strtotime($end_date)));
		$taskd = $tm2 - $tm1;
        $fndr = $taskd/3600;
        $includelunch = checkLunch(date("H:i:s", strtotime($t_start_datetime)),date("H:i:s", strtotime($end_date)),$_POST['team_id']);
        if($includelunch > 0){
          $fndr = $fndr - $lh; 
        }
        //echo $fndr;
	}
	
	//echo $t_start_datetime.' == '.$end_date.'<br>';
	
    $datediff = strtotime($end_date) - strtotime($t_start_datetime);
    $daydiff = round($datediff / (3600*24));
    $du_temp = $daydiff * $lh;
     
    $du_temp = $du_temp.' hours';
	$endtime = date("H:i:s", strtotime($end_date));

	//check overlaping event
	if(strtotime($endtime) > strtotime($timedt[1]) || strtotime($starttime) <  strtotime($timedt[0])){
			echo "not_allow";
	        die(); 
	}

	if(isset($_POST['edit_type']) && $_POST['edit_type'] == 'task_move' ){
		$chkdata = checktaskoverlapping($_POST['user_id'],$t_start_datetime,$end_date,$_POST['task_id']);
		 if(!$chkdata){
		 	echo "task_overlap";
	       die(); 
		 }
	}

	if(isset($_POST['edit_by']) && $_POST['edit_by'] != 'drop_event'){
		//check for not in multiple days
		$s_dt = date("Y-m-d", strtotime($t_start_datetime));
		$e_dt = date("Y-m-d", strtotime($end_date));
	    if(strtotime($s_dt) !=  strtotime($e_dt) ){
	    	echo "not_allow";
	        die(); 	
	    }
		// check for holiday overlapping
		$chkdata = checkholidayoverlapping($s_dt,$e_dt);
		if($chkdata){
		 	echo "holiday_overlap";
	       die(); 
		}	
	}
	
    $myduration = 0;
    $ed_dt = date("Y-m-d", strtotime($end_date));
    $tid = $_POST['task_id'];

		    $taskdetail = $dclass->select(" * ","tbl_task"," AND t_id=".$tid);    
		    $checktime = $dclass->select(" * ","tbl_task_timing"," AND t_id=".$tid);    
		        if(count($checktime) > 0){
		            $dclass->delete("tbl_task_timing"," t_id=".$tid);    
		    	}
		    $caldr = 0;	

		    $exdr = 0;

     		if(isset($_POST['edit_by']) && $_POST['edit_by'] == 'drop_event'){

		    	//if($curr_dt != $ed_dt){

		    	$end_date = extenddate($end_date,$_POST['team_id'],$du_temp);
		    	  
		    	  while(strtotime($curr_dt) < strtotime($end_date)){
		          $endtime_new = date("H:i:s", strtotime($end_date));
		    	  if(checkholiday($curr_dt)){

		    	  	  $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
		              $end_date = date ("Y-m-d", strtotime("+1 day", strtotime($end_date)));
		              
		              $end_date = $end_date.' '.$endtime_new;
		              $du_temp = $lh.' hours';
		              $end_date = extenddate($end_date,$_POST['team_id'],$du_temp);
		              //echo $end_date.'==holiday==<br>';
		              continue;
		            }else if(checkworkingday($_POST['team_id'],$curr_dt)){
		
			              $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
			              $end_date = date ("Y-m-d", strtotime("+1 day", strtotime($end_date)));
			              
			              $end_date = $end_date.' '.$endtime_new;
			              $du_temp = $lh.' hours';
			              $end_date = extenddate($end_date,$_POST['team_id'],$du_temp);
			              //echo $end_date.'==offday==<br>';
			              continue;
		            }

		          $query_stdt = $curr_dt;
		          $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
		          $query_endt = $curr_dt;
		              
		          if($query_stdt == date("Y-m-d",strtotime($t_start_datetime)) ){


		                    $time_st1 = strtotime($starttime);
		                    $time_st2 = strtotime($timedt[1]);
		                    $taskdur = $time_st2 - $time_st1;
		                    $taskduration = $taskdur/3600;
		                    $taskduration = $taskduration;
		                    $includelunch = checkLunch($starttime,$timedt[1],$_POST['team_id']);
		                    if($includelunch > 0){
		                      $taskduration = $taskduration - $lh; 
		                      $pstdr = $_POST['t_duration'] + $lh;
		                    }else{
		                      $pstdr = $_POST['t_duration'];
		                    }

		                    $myduration += $taskduration; 
		                     $tstp = 'start';
		                    
		                    $task_start = $query_stdt.' '.$starttime;
                    		$task_endt = $query_stdt.' '.$timedt[1];

		                    $query_stdt = $query_stdt.' '.$starttime;
		                    $query_endt = $query_endt.' '.$timedt[1];
		          
		          
		          
		          }else if($query_stdt == date("Y-m-d", strtotime($end_date)) ){



		                   $time_st1 = strtotime($endtime_new);
		                   $time_st2 = strtotime($timedt[0]);

		                   $taskdur = $time_st1 - $time_st2;
		                   $taskduration = $taskdur/3600;                     
		                   
		                   $includelunch = checkLunch($timedt[0],$endtime_new,$_POST['team_id']);
		                    if($includelunch > 0){
		                      $taskduration = $taskduration - $lh; 
		                      $pstdr = $_POST['t_duration'] + $lh;
		                    }else{
		                      $pstdr = $_POST['t_duration'];
		                    }

		                   $myduration += $taskduration;
		                   $tstp = 'end';
		                   
		                    $task_start = $query_stdt.' '.$timedt[0];
                   			$task_endt = $query_stdt.' '.$endtime_new; 

		                   $query_stdt = $query_stdt.' '.$timedt[0];
		                   $query_endt = $query_endt.' '.$endtime_new;
		          
		          
		          
		          }else{

		                  $time_st1 = strtotime($timedt[0]);
		                  $time_st2 = strtotime($timedt[1]);
		                  $df1 = $time_st2 - $time_st1;

		                  $taskduration = $df1/3600;
		                  $taskduration = $taskduration - $lh;
		                  $pstdr = $_POST['t_duration'] + $lh; 
		                  $myduration += $taskduration;
		                  $tstp = 'middle';
		                  
		                  $task_start = $query_stdt.' '.$timedt[0];
                   		  $task_endt = $query_stdt.' '.$timedt[1]; 

		                  $query_stdt = $query_stdt.' '.$timedt[0];
		                  $query_endt = $query_endt.' '.$timedt[1];
		          
		          }
		        //echo $task_start.'=='.$task_endt;										
		        $chkFreeDate = checkfornextAvailabeltime($_POST['user_id'],$task_start,$task_endt,$team_work_hour,$t_start_datetime,$end_date,$caldr,$_POST['t_duration'],$timedt[0],$timedt[1],$_POST['team_id']); 

				foreach($chkFreeDate as $chr){
				    if($chr['startdate'] != ''){
				    	$ins = array(
		                "t_pr_id"=>$taskdetail[0]['t_pr_id'],
		                "t_cl_id"=>$taskdetail[0]['t_cl_id'],
		                "t_company_user_id"=>$_SESSION['company_id'],
		                "t_title"=>$taskdetail[0]['t_title'],
		                "t_description"=>$taskdetail[0]['t_description'],
		                "t_priority"=>$taskdetail[0]['t_priority'],
		                "t_team_id"=>$_POST['team_id'],
		                "t_operator_id"=> $_POST['user_id'],
		                "t_start_datetime"=> $chr['startdate'],
	                    "t_end_datetime"=> $chr['enddate'],
	                    "t_duration"=>$chr['task_duration'],
		                "t_expected_deadline_date"=>$taskdetail[0]['t_expected_deadline_date'],
		                "t_type"=>'calendar',
		                "t_status"=>$taskdetail[0]['t_status'],
		              );  
		            
		           $tid_new = $dclass->insert("tbl_task",$ins);
		           task_updated($tid_new);
			       /* $ina = array(
		                  "t_id"=>$tid_new,
		                  "t_start_datetime"=>$chr['startdate'],
	                      "t_end_datetime"=>$chr['enddate'],
	                      "duration" => $chr['task_duration'],
		                  "task_tp" => $tstp,
			        );  
			        $dclass->insert("tbl_task_timing",$ina);    */
			        $caldr = $chr['total_duration'];
			            
			    // code for mail send
			    $to = $dclass->select("u.user_id,u.fname,u.lname,u.email,t.t_title,t.t_description,t.t_end_datetime,t.t_start_datetime,cl.cl_name,pr.pr_title","tbl_user as u left join tbl_task as t on u.user_id = t.t_operator_id 
                	left join  tbl_client cl ON t.t_cl_id = cl.cl_id
                	left join  tbl_project pr ON t.t_pr_id = pr.pr_id
                	 ", " and t.t_id = '".$tid_new."'");

		    $whereInapp = " AND user_id='".$to[0]['user_id']."' and notification_name = 'inapp_notification' and status = '1'";
            $notifyDetailsInapp = $dclass->select(" * ","tbl_notification_details", $whereInapp );
            if(count($notifyDetailsInapp))
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
                
            if(count($notifyDetails))
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
                }


		        	}
		            $wlflage = $chr['flage'];
                }
		        	
		        	if($wlflage == 'false'){
	                	break;
	                }
	                
		        	$duration_temp = round($chkFreeDate[0]['td']).' hours';	
		        	
         			//echo $end_date.'====';
         			$end_date = extenddate($end_date,$_POST['team_id'],$duration_temp);
         			//echo $end_date.'====';
         	    }// end of while
			    
			    $dclass->delete("tbl_task"," t_id=".$tid);	
				
				
				//}

				//put code here

     		
     		
     		
     		}else{
     		
     			$post_duration = $fndr;
		    	if($curr_dt != $ed_dt){
			    
			    	while(strtotime($curr_dt) < strtotime($end_date)){
			            
			          $query_stdt = $curr_dt;
			          $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
			          $query_endt = $curr_dt;
			              
			          if($query_stdt == date("Y-m-d",strtotime($t_start_datetime)) ){



			                    $time_st1 = strtotime($starttime);
			                    $time_st2 = strtotime($timedt[1]);
			                    $taskdur = $time_st2 - $time_st1;
			                    $taskduration = $taskdur/3600;
			                    $taskduration = $taskduration;
			                    $includelunch = checkLunch($starttime,$timedt[1],$_POST['team_id']);
			                    if($includelunch > 0){
			                      $taskduration = $taskduration - $lh; 
			                    }
			                    $myduration += $taskduration; 
			                     $tstp = 'start';
			                    $task_start = $query_stdt.' '.$starttime;
                    			$task_endt = $query_stdt.' '.$timedt[1];
			                     $query_stdt = $query_stdt.' '.$starttime;
			                    $query_endt = $query_endt.' '.$timedt[1];
			          
			          
			          
			          }else if($query_stdt == date("Y-m-d", strtotime($end_date)) ){



			                   $time_st1 = strtotime($endtime);
			                   $time_st2 = strtotime($timedt[0]);

			                   $taskdur = $time_st1 - $time_st2;
			                   $taskduration = $taskdur/3600;                     
			                   
			                   $includelunch = checkLunch($timedt[0],$endtime,$_POST['team_id']);
			                    if($includelunch > 0){
			                      $taskduration = $taskduration - $lh; 
			                    }

			                   $myduration += $taskduration;
			                   $tstp = 'end';

			                   $task_start = $query_stdt.' '.$timedt[0];
                   				$task_endt = $query_stdt.' '.$endtime; 

			                   $query_stdt = $query_stdt.' '.$timedt[0];
			                   $query_endt = $query_endt.' '.$endtime;
			          
			          
			          
			          }else{
			                  $time_st1 = strtotime($timedt[0]);
			                  $time_st2 = strtotime($timedt[1]);
			                  $df1 = $time_st2 - $time_st1;

			                  $taskduration = $df1/3600;
			                  $taskduration = $taskduration - $lh; 
			                  $myduration += $taskduration;
			                  $tstp = 'middle';
			                  
			                  $task_start = $query_stdt.' '.$timedt[0];
                   				$task_endt = $query_stdt.' '.$timedt[1]; 

			                  $query_stdt = $query_stdt.' '.$timedt[0];
			                  $query_endt = $query_endt.' '.$timedt[1];
			                
			          }
			          
			       
			        $chkFreeDate = checkfornextAvailabeltime($_POST['user_id'],$task_start,$task_endt,$team_work_hour,$t_start_datetime,$end_date,$caldr,$post_duration,$timedt[0],$timedt[1],$_POST['team_id']); 
				    foreach($chkFreeDate as $chr){
					    if($chr['startdate'] != ''){
					    	$ins = array(
			                "t_pr_id"=>$taskdetail[0]['t_pr_id'],
			                "t_cl_id"=>$taskdetail[0]['t_cl_id'],
			                "t_company_user_id"=>$_SESSION['company_id'],
			                "t_title"=>$taskdetail[0]['t_title'],
			                "t_description"=>$taskdetail[0]['t_description'],
			                "t_priority"=>$taskdetail[0]['t_priority'],
			                "t_team_id"=>$taskdetail[0]['t_team_id'],
			                "t_operator_id"=> $_POST['user_id'],
			                "t_start_datetime"=> $chr['startdate'],
		                    "t_end_datetime"=> $chr['enddate'],
		                    "t_duration"=>$chr['task_duration'],
			                "t_expected_deadline_date"=>$taskdetail[0]['t_expected_deadline_date'],
			                "t_type"=>'calendar',
			                "t_status"=>$taskdetail[0]['t_status'],
			              );  
			            $tid_new = $dclass->insert("tbl_task",$ins);
			            task_updated($tid_new);
				        $ina = array(
			                  "t_id"=>$tid_new,
			                  "t_start_datetime"=>$chr['startdate'],
		                      "t_end_datetime"=>$chr['enddate'],
		                      "duration" => $chr['task_duration'],
			                  "task_tp" => $tstp,
				          );  
			            $dclass->insert("tbl_task_timing",$ina);    
			            $caldr = $chr['total_duration'];
			        	}
			        	if($chr['flage'] == 'false') {
	                    $wlflage = 'false';
	                    break;
	                  }
			        }
			        
	  				$duration_temp = round($chkFreeDate[0]['td']).' hours';
	         		$end_date = extenddate($end_date,$_POST['t_team_id'],$duration_temp);
			  
			        }
			        $dclass->delete("tbl_task"," t_id=".$tid);
		    	}else{
		    	  
		            
		            $myduration =  $fndr;
		            $myduration = $myduration;
			    	
			    	//echo $t_start_datetime.'=='.$end_date;
			    	
			    	$dr = $myduration.' hours';	
	     			$ins = array(
					    "t_start_datetime"=> $t_start_datetime,
						"t_end_datetime"=> $end_date,
						"t_duration"=>$dr,
						"t_operator_id"=>$_POST['user_id'],
					);
	     			
	     			$dclass->update("tbl_task",$ins, 't_id='.$tid);
	     			task_updated($tid);
	     			
		    	}

     			// Notification mail for edit and move task
                
               $to = $dclass->select("u.user_id,u.fname,u.lname,u.email,t.t_title,t.t_description,t.t_end_datetime,t.t_start_datetime,cl.cl_name,pr.pr_title","tbl_user as u left join tbl_task as t on u.user_id = t.t_operator_id 
                	left join  tbl_client cl ON t.t_cl_id = cl.cl_id
                	left join  tbl_project pr ON t.t_pr_id = pr.pr_id
                	 ", " and t.t_id = '".$tid."'");

             if($_POST['edit_type'] == 'task_move'){
		                
		                $taskev = 'Task Moved';
		     }if($_POST['edit_type'] == 'task_edit'){
		            	
		            	$taskev = 'Task Edited';
		     }   
		       
            $whereInapp = " AND user_id='".$to[0]['user_id']."' and notification_name = 'inapp_notification' and status = '1'";
            $notifyDetailsInapp = $dclass->select(" * ","tbl_notification_details", $whereInapp );
            if(count($notifyDetailsInapp))
            {
                   
            	  $whereInappInner = " AND user_id='".$to[0]['user_id']."' and notification_name = '".$_POST['edit_type']."' and status = '1'";
                  $notifyDetailsInappInner = $dclass->select(" * ","tbl_notification_details", $whereInappInner );
                  if(count($notifyDetailsInappInner) > 0)
                  {

                    $noti_date = 	date("Y-m-d",strtotime($to[0]['t_start_datetime']));
                	$noti_time = 	date("H:i:s",strtotime($to[0]['t_start_datetime']));
                    $noti_add = array(
                        'task_id'=>$tid,
                        'task_event'=>$taskev,
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


		            if($_POST['edit_type'] == 'task_move'){
		                $taskev = 'Task Moved';
		            }if($_POST['edit_type'] == 'task_edit'){
		            	$taskev = 'Task Edited';
		            }

		        $where = " AND user_id='".$to[0]['user_id']."' and notification_name = 'email_notification' and status = '1'";    
                $notifyDetails = $dclass->select(" * ","tbl_notification_details", $where );
                
                if(count($notifyDetails) > 0)
                {

	                  $whereInner = " AND user_id='".$to[0]['user_id']."' and notification_name = '".$_POST['edit_type']."' and status = '1'";
	                  $notifyDetailsInner = $dclass->select(" * ","tbl_notification_details", $whereInner );
	                  if(count($notifyDetailsInner) > 0)
	                  {

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
			
			    sendmails::commonmailfun($to[0]['email'],$to[0]['fname'].' '.$to[0]['lname'],$subject, $html);	

	                    /*$mail->addAddress($to[0]['email'],$to[0]['fname'].' '.$to[0]['lname']);
	                    $mail->Subject = $subject;
	                    $mail->msgHTML($html);
	                    $mail->send();*/
                           
	                }

                }
				


                

               
    
     		}
	




}else if($_POST['tsk'] == 'paste_task'){

	$tid = $_POST['t_id'];
	$taskdetail = $dclass->select(" * ","tbl_task"," AND t_id=".$tid);    	
    // /print_r($taskdetail);
    $trim_time = date("H:i:s", strtotime($_POST['copy_start_time']));
    $t_start_date = date("Y-m-d", strtotime($_POST['copy_start_date']));
    $t_start_datetime = date("Y-m-d H:i:s", strtotime($_POST['copy_start_date'].' '.$trim_time));

   
	$curr_dt = date("Y-m-d", strtotime($t_start_datetime));
	$starttime = date("H:i:s", strtotime($t_start_datetime));
	$timedt = getTimeDetail($_POST['copy_team_id']);
	$lh = getLunchDuration($_POST['copy_team_id']);
	$team_work_hour = getWorkinghours($_POST['copy_team_id']);
 
	$copyduration = str_replace(' hours','',$taskdetail[0]['t_duration']);
	//$copyduration = h2m($_POST['t_duration'])." minutes";
	/*$cklunc = checkstartinlunch($_POST['copy_team_id'],$starttime);
		if($cklunc){
	 	echo "start_in_lunch";
       die(); 
	}*/

	$duration_tem = round($copyduration).' hours';

    $end_date = extenddate($t_start_datetime,$_POST['copy_team_id'],$duration_tem);
    
    $fndr = $copyduration;
	$endtime = date("H:i:s", strtotime($end_date));

	$includelunch = checkLunch(date("H:i:s", strtotime($t_start_datetime)),date("H:i:s", strtotime($end_date)),$_POST['copy_team_id']);
    if($includelunch > 0){
      $fndr = $fndr + $lh;
      $end_date = extenddate($t_start_datetime,$_POST['copy_team_id'],$fndr.' hours'); 
    }
	
	//check overlaping event
	
	$chkdata = checktaskoverlapping($taskdetail[0]['t_operator_id'],$t_start_datetime,$end_date,'');
	if(!$chkdata){
		echo "task_overlap";
	    die(); 
	}
	
	// check for holiday overlapping
	$s_dt = date("Y-m-d", strtotime($t_start_datetime));
	$e_dt = date("Y-m-d", strtotime($end_date));
	$chkdata = checkholidayoverlapping($s_dt,$e_dt);
	if($chkdata){
	   echo "holiday_overlap";
       die(); 
	}	
	if(checkworkingday($_POST['team_id'],$s_dt)){
		echo "not_allow";
        die(); 
	}
	
	
	

    $myduration = 0;
    $ed_dt = date("Y-m-d", strtotime($end_date));
    	    $caldr = 0;	
     			$post_duration = $fndr;
     			$caldr = 0;	
		    	//if($curr_dt != $ed_dt){

			    	while (strtotime($curr_dt) <= strtotime($end_date)){
			          $endtime_new = date("H:i:s", strtotime($end_date));
			         
			          $query_stdt = $curr_dt;
          $curr_dt = date ("Y-m-d", strtotime("+1 day", strtotime($curr_dt)));
          $query_endt = $curr_dt;

          //echo '=='.$query_stdt.'==';
              
          if($query_stdt == date("Y-m-d",strtotime($t_start_datetime))){


                    $time_st1 = strtotime($starttime);
                    $time_st2 = strtotime($timedt[1]);
                    $taskdur = $time_st2 - $time_st1;
                    $taskduration = $taskdur/3600;
			        $taskduration =  number_format($taskduration,2);

                    $includelunch = checkLunch($starttime,$timedt[1],$_POST['copy_team_id']);

                    if($includelunch > 0){
                      $taskduration = $taskduration - $lh; 
                      
                      $pstdr = $taskdetail[0]['t_duration'] + 1;
                    }else{
                      $pstdr = $taskdetail[0]['t_duration'];
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

                   $includelunch = checkLunch($timedt[0],$endtime_new,$_POST['copy_team_id']);
                    if($includelunch > 0){
                      $taskduration = $taskduration - $lh; 
                      $pstdr = $taskdetail[0]['t_duration'] + $lh;
                    }else{
                      $pstdr = $taskdetail[0]['t_duration'];
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
                  $pstdr = $taskdetail[0]['t_duration'] + $lh;
                  
                  $task_start = $query_stdt.' '.$timedt[0];
                  $task_endt = $query_stdt.' '.$timedt[1]; 

                  $query_stdt = $query_stdt.' '.$timedt[0];
                  $query_endt = $query_endt.' '.$timedt[1];
                  $tstp = 'middle';
          }
			          
			          $chkFreeDate = checkfornextAvailabeltime($taskdetail[0]['t_operator_id'],$task_start,$task_endt,$team_work_hour,$t_start_datetime,$end_date,$caldr,$taskdetail[0]['t_duration'],$timedt[0],$timedt[1],$_POST['copy_team_id']); 
			         
			            foreach($chkFreeDate as $chr){
		                  	if($chr['startdate'] != ''){
							    $ins = array(
					                "t_pr_id"=>$taskdetail[0]['t_pr_id'],
					                "t_cl_id"=>$taskdetail[0]['t_cl_id'],
					                "t_company_user_id"=>$taskdetail[0]['t_company_user_id'],
					                "t_title"=>$taskdetail[0]['t_title'],
					                "t_description"=>$taskdetail[0]['t_description'],
					                "t_priority"=>$taskdetail[0]['t_priority'],
					                "t_team_id"=>$taskdetail[0]['t_team_id'],
					                "t_operator_id"=> $taskdetail[0]['t_operator_id'],
					               	"t_start_datetime"=> $chr['startdate'],
				                    "t_end_datetime"=> $chr['enddate'],
				                    "t_duration"=>$chr['task_duration'],
					                "t_expected_deadline_date"=>$taskdetail[0]['t_expected_deadline_date'],
					                "t_type"=>'calendar',
					                "t_status"=>$taskdetail[0]['t_status'],
					              );  
					            $tid_new = $dclass->insert("tbl_task",$ins);
					            task_updated($tid_new);
					            
					            $caldr = $chr['total_duration'];
					        }

					        $wlflage = $chr['flage'];
					        if($wlflage == 'false'){
		                   		break;
			                }
			                $duration_tempp = round($chkFreeDate[0]['td']).' hours';
	                 		$end_date = extenddate($end_date,$taskdetail[0]['t_team_id'],$duration_tempp);

	              		}
			        }
    
	}else if($_POST['tsk'] == 'get_task_title'){

		$gettitle = $dclass->select("t_title,t_duration","tbl_task"," AND t_id = '".$_POST['task_id']."'");
		$dur = str_replace('hours','',$gettitle[0]['t_duration'] );
		$dur = $dur.' hr';
		$title = "<strong>".substr($gettitle[0]['t_title'],0,15)."</strong> ".$dur;
		echo $title;
	}else if($_POST['tsk'] == 'get_user_event'){

	$getHoliday = $dclass->select("*","tbl_holidays"," AND holi_user_id = '".$_SESSION['company_id']."' ");
	$userTeamid = $dclass->select("*","tbl_team_detail"," AND user_id =".$_POST['user_id']);
    $team_id = $userTeamid[0]['tm_id'];

    // code for get team vise lunch hours
    $showlunch = 'true';
    $lunch_start = '';
    $lunch_end = '';

  
        $lunchhour = $dclass->select("*","tbl_lunch_hours"," AND team_id = ".$team_id." AND company_user_id =".$_SESSION['company_id']);
        
        if(count($lunchhour) > 0){
            if($lunchhour[0]['show_in_calender'] == 'yes'){
                $showlunch = 'true';   
                $lunchtime = explode(':::',$lunchhour[0]['lunch_hours']);
                $lunch_start = date("H:i:s", strtotime($lunchtime[0]));
                $lunch_end = date("H:i:s", strtotime($lunchtime[1]));
            }else{
                $showlunch = 'false';
            }
            
        }else{
            $showlunch = 'true';
            $lunchhour = $dclass->select("*","tbl_lunch_hours"," AND type = 'general'");
            $lunchtime = explode(':::',$lunchhour[0]['lunch_hours']);
            
            $lunch_start = date("H:i:s", strtotime($lunchtime[0]));
            $lunch_end = date("H:i:s", strtotime($lunchtime[1]));
        }
    
        $getTask = $dclass->select("*","tbl_task","  AND t_operator_id = '".$_POST['user_id']."' AND t_type='calendar' ".$condition." GROUP BY t_id ");
        
        $userTask = '';

        foreach($getTask as $gt){

            $chkdur = str_replace('hours','',$gt['t_duration'] );
            if($chkdur > 0){
                        $pro_color = $dclass->select("pr_color,pr_title","tbl_project"," AND pr_id=".$gt['t_pr_id']);
                        $cl_name = $dclass->select("cl_company_name","tbl_client"," AND cl_id=".$gt['t_cl_id']);
                        $start_date = date("Y-m-d", strtotime($gt['t_start_datetime']));    
                        $start_time = date("H:i:s", strtotime($gt['t_start_datetime']));    
                        $start_datetime = $start_date.'T'.$start_time;
                        
                        $end_date = date("Y-m-d", strtotime($gt['t_end_datetime']));    
                        $end_time = date("H:i:s", strtotime($gt['t_end_datetime']));    
                        
                        $end_datetime = $end_date.'T'.$end_time;
                        
                        
                        $dur = str_replace('hours','',number_format($gt['t_duration'],1));
                        $dur = $dur.' hr';
                    
                        $title = trunc_string($gt['t_title'],10);
                        $desc1=str_replace(array("\r", "\n"), ' ', string_sanitize($gt['t_description']));
                        if($desc1 != ''){
                        	$desc = trunc_string($desc1,15);	
                        }else{
                        	$desc = '';
                        }
                        
                    $clrpr ='';

                        if($gt['t_priority'] == 'high'){
                            $clrpr = 'yellow-task';
                        }else if($gt['t_priority'] == 'urgent'){
                            $clrpr = 'orange-task';
                        }else if($gt['t_priority'] == 'low'){
                            $clrpr = 'green-task';
                        }else if($gt['t_priority'] == 'critical'){
                            $clrpr = 'red-task';
                        }
                        
                    $clr = $pro_color[0]['pr_color'];
                        
                    $userTask[] =array(
                    				"title"=>$title,
                    				"title1"=>$title,
                    				"event_id"=>$gt['t_id'],
                    				"start"=>$start_datetime,
                    				"end"=>$end_datetime,
                    				"color"=>$clr,
                    				"className"=>$clrpr,
                    				"dur"=>$dur,
                    				"project"=>$pro_color[0]['pr_title'],
                    				"client"=>$cl_name[0]['cl_company_name'],
                    				"desc"=>$desc,
                    				);
                    	
            }
        }
        foreach($getHoliday as $hl){
            $hl_start_date = date("Y-m-d", strtotime($hl['holi_start_date']));
            $hl_end_date = date("Y-m-d", strtotime($hl['holi_end_date']));
            $hltitle = string_sanitize($hl['holi_title']);
            
            	$userTask[] = array(
            		"title"=>$hltitle,
            		"start"=>$hl_start_date,
            		"end"=>$hl_end_date,
            		"rendering"=>'background',
            		"className"=>'holidaycls',

            		);
              
                $hl_start_date = $hl_start_date.' 00:00:00';
                $hl_end_date = $hl_end_date.' 23:59:59';
                $userTask[] = array(
            		"title"=>$hltitle,
            		"start"=>$hl_start_date,
            		"end"=>$hl_end_date,
            		"rendering"=>'background',
            		"className"=>'holidaycls',

            		);
             
        }
        /*
                    color: '#ff9f89',
        */
        if($showlunch == 'true'){         
                $userTask[] = array(
            		"start"=>$lunch_start,
            		"end"=>$lunch_end,
            		"rendering"=>'background',
            		"color"=> '#ff9f89',
            		);
            }


        echo json_encode($userTask);


	}
}
?>
