<?php

// Configration 
  include("config/configuration.php");
  include("config/dbconfig.php");
  $load  =  new loader(); 
  $objPages = $load->includeclasses('userlist');
  $label = "userlist";
  $dclass   =  new  database();
  $gnrl =  new general;
  $load->includeother('header');
  global $mail;
  
  

// Send project Reminder
  $projectList = $dclass->select("pr.*,rem.rem_type,rem_status,rem.reminder_date,u.fname,u.lname,u.email",'tbl_project as pr INNER JOIN tbl_project_reminder as rem on pr.pr_id = rem.pr_id INNER JOIN tbl_user as u on pr.pr_company_id = u.user_id'," and rem.reminder_date != '0000-00-00 00:00:00'"); 
  //echo '<pre>';print_r($projectList);exit;
  
  $date = date('Y-m-d H:i:s');
  $future_date = date('Y-m-d H:i:s', strtotime("+5 min"));
  
  foreach($projectList as $prokey => $provalue)
  {
     
    //echo $date .'=='. $provalue['reminder_date'] .'and'. $provalue['rem_type'] .'== email';
    if($provalue['reminder_date'] != '0000-00-00 00:00:00' and $provalue['reminder_date'] >= $date and $provalue['reminder_date'] < $future_date  and $provalue['rem_type'] == 'email' and $provalue['rem_status'] == 'on')
    {
        // mail code
        // this is code mail sending and its working fine
        $html = 'Hello '.$provalue['fname'].'<br>';
        $html .= 'Project Title is : '.$provalue["pr_title"].'<br>';
        $html .= 'Project Description is : '.$provalue["pr_description"].'<br><br>';
        
        /*$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
        $message = str_replace('{LOGO}',$logo,$message);*/

        echo $html .= 'Thank You <br> Capacity team.';
      
        
        $mail->addAddress($provalue['email'], $provalue['fname'].' '.$provalue['lname']);
        $mail->Subject = 'Capacity Project Reminder';
        $mail->msgHTML($html);
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!"."<br>";
        }
       
    }
    else
    {
        //echo 'No reminder on this time';
    }
    
  }
 
  
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
        
        /*$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
        $message = str_replace('{LOGO}',$logo,$message);*/

        $mail->addAddress($taskvalue['email'], $taskvalue['fname'].' '.$taskvalue['lname']);
        $mail->Subject = 'Capacity Task Reminder';
        $mail->msgHTML($html);
        
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!"."<br>";
        }
    }
    
  }
  
  //echo "<script>setTimeout('self.close();',60000);</script>";
  
