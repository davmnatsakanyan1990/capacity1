<?php
require_once("../config/dbconfig.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
session_start();


if($_POST['tsk'] == 'notification_save')
{
    // delete setting od this user before save
    $dclass->delete('tbl_notification_details'," user_id = '".$_SESSION['user_id']."'"); 
    // save user notification settings
    
    foreach($_POST['notification'] as $key=>$value)
    {
        $dataArray = array(
        "user_id" => $_SESSION['user_id'],
        "notification_name" => $value,
        "status" => '1',
        );
        $id = $dclass->insert("tbl_notification_details",$dataArray);
    }
    foreach($_POST['notify'] as $key=>$value)
    {
      if($value == 'get_all'){
        continue;
      }
        $dataArray = array(
        "user_id" => $_SESSION['user_id'],
        "notification_name" => $value,
        "status" => '1',
        );
        $id = $dclass->insert("tbl_notification_details",$dataArray);
    }
   
}else if($_POST['tsk'] == 'delete_msg'){
    $dclass->delete('tbl_user_notification'," id = '".$_POST['msg_id']."'"); 

     //$user_notification = $dclass->select('COUNT(*) as cnt','tbl_user_notification'," AND user_id = '".$_SESSION['user_id']."' ORDER BY id desc");
     

}
else if($_POST['tsk'] == 'displaymsg'){
   
     $str = '';
     foreach($_POST['notify'] as $key=>$value)
    { 
        $user_event = '';

        if($value == 'task_add'){
          $user_event = 'Task Added';
        }else if($value == 'task_move'){
          $user_event = 'Task Moved';
        }else if($value == 'task_edit'){
          $user_event = 'Task Edited';
        }


       $str .= '"'.$user_event.'",';
    }
    $str .= '"Task Reminder",';
                        
                      
    $str = trim($str,',');
   
     $user_notification = $dclass->select('*','tbl_user_notification'," AND user_id = '".$_SESSION['user_id']."' AND task_event  IN (".$str.")  ORDER BY id desc");
     $html = '';
    if(count($user_notification) > 0){
     foreach($user_notification as $notif){ 
          if($notif['task_event'] == 'Task Reminder'){
                                      $task_event = 'Task due in '.$notif['description'].' hrs';
                                  }else{
                                      $task_event = $notif['task_event'];
                                  }
          $html .= "<tr>
            <td width='80%'>".$notif['task_title']." &nbsp; ".$notif['task_date']." &nbsp; ".$notif['task_starttime']." - <i>".$task_event."</i> </td>
            <td width='10%'><a href='".SITE_URL."view/".$notif['task_id']."' >View task</a></td>
            <td width='10%'><a class='dismiss_msg' msgid='".$notif['id']."'>Dismiss</a></td>
          </tr>";
     }
   }else{
      $html .= '<tr>
                  <td colspan="3">No notifications at this time.</td>
                </tr>';
   }
echo $html;
}
?>
