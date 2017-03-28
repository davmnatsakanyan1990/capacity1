<?php

// Configration 
require_once("../config/dbconfig.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
global $mail;
session_start();
  
  
// Send project Reminder
  $date = date('Y-m-d H:i:s');
  //$date = '2015-02-25 15:37:45';
  //$future_date = date('Y-m-d H:i:s', strtotime("+5 min"));
  //$future_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +5 min "));
 
 /*$projectList = $dclass->select("t1.reminder_date,t2.pr_company_id,t2.pr_title,t2.pr_start_datetime,t2.pr_end_datetime,t3.fname,t3.lname","tbl_project_reminder t1 
 LEFT JOIN tbl_project t2 ON t1.pr_id = t2.pr_id
 LEFT JOIN tbl_user t3 on t2.pr_company_id = t3.user_id"," AND t3.user_id = '".$_SESSION['user_id']."' AND t1.reminder_date BETWEEN '".$date."' AND '".$future_date."' limit 0,1 ");  */
  
 $projectList = $dclass->select("t1.reminder_date,t2.pr_company_id,t2.pr_title,t2.pr_start_datetime,t2.pr_end_datetime,t3.fname,t3.lname","tbl_project_reminder t1 
 LEFT JOIN tbl_project t2 ON t1.pr_id = t2.pr_id
 LEFT JOIN tbl_user t3 on t2.pr_company_id = t3.user_id"," AND t3.user_id = '".$_SESSION['user_id']."' AND t1.reminder_date = '".$date."'  ");

    if(count($projectList) > 0){
      $title = '';
      $description = '';
      $body = '';
      foreach($projectList as $pr)
      {
         $title .= 'Dear '.$pr['fname'].' '.$pr['lname'].' <br> This is the project reminder <br>';
         $description .= 'Your project '.$pr['pr_title'].' started on '.$pr['pr_start_datetime'].'<br>' ;
         $body .= $title.':::'.$description;
         
      }
      echo $body;
    }else{
      echo '0'; 
    }
 
  /*
  // Send Task Reminder
  $taskList = $dclass->select("task.*,rem.rem_type,rem_status,rem.reminder_date,u.fname,u.lname,u.email",'tbl_task as task INNER JOIN tbl_task_reminder as rem on task.t_id = rem.task_id INNER JOIN tbl_user as u ON task.t_operator_id = u.user_id'," and rem.reminder_date != '0000-00-00 00:00:00'"); 
  //echo '<pre>';print_r($taskList);exit;
  $date = date('Y-m-d H:i:s');
  $future_date = date('Y-m-d H:i:s', strtotime("+5 min"));
  foreach($taskList as $taskkey => $taskvalue)
  {
    if($taskvalue['reminder_date'] != '0000-00-00 00:00:00' and $taskvalue['reminder_date'] >= $date and $taskvalue['reminder_date'] < $future_date and $taskvalue['rem_type'] == 'email' and $taskvalue['rem_status'] == 'on')
    {
        $html = 'Hello '.$taskvalue['fname'].'<br>';
        $html .= 'Task Title is : '.$taskvalue["t_title"].'<br>';
        $html .= 'Task Description is : '.$taskvalue["t_description"].'<br><br>';
        
        $html .= 'Thank You <br>';
      
        $mail->addAddress($taskvalue['email'], $taskvalue['fname'].' '.$taskvalue['lname']);
        $mail->Subject = 'Capacity Task Reminder';
        $mail->msgHTML($html);
        
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!"."<br>";
        }
    }
    
  }*/
  
  ?>

  
