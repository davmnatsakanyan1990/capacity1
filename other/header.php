<?php 
global $load; 
$objPages = $load->includeclasses('user');	
$objLogin = $load->includeclasses('login');	
$dclass   =  new  database();
global $basefile;
global $gnrl;
global $mess;
global $dataAdmin;
global $dataManager;
global $dataEmployee;
//set gloabl variable for user guide prompt
global $step_1_admin; global $step_1_manager; global $step_1_employee;
global $step_2_admin; global $step_2_manager; global $step_2_employee;
global $step_3_admin; global $step_3_manager; global $step_3_employee;
global $step_4_admin; global $step_4_manager; global $step_4_employee;
global $step_5_admin; global $step_5_manager; global $step_5_employee;
global $step_6_admin; global $step_6_manager; 
global $step_7_admin; global $step_7_manager; 
global $step_8_admin; global $step_8_manager;
global $step_9_admin; global $step_9_manager;

global $step_10_admin; global $step_10_manager; 
global $step_11_admin; global $step_11_manager;
global $step_12_admin; global $step_12_manager;
global $step_13_admin; global $step_13_manager;
global $step_14_admin; global $step_14_manager;
global $step_15_admin; global $step_15_manager;
global $step_16_admin; global $step_16_manager; 
global $step_17_admin;
global $step_18_admin;

$userDetail = $dclass->select("fname,lname,user_avatar,r_id","tbl_user"," AND user_id=".$_SESSION['user_id']); 

if($_SESSION['r_id'] != $userDetail[0]['r_id']){
 
    $role = $dclass->select(' * ','tbl_role',' AND r_id='.$userDetail[0]['r_id']);  
    $_SESSION['user_type'] = $role[0]['r_type'];
    $_SESSION['r_id'] = $userDetail[0]['r_id'];
}
//$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$actual_link = ACTUAL_LINK;
//step 1 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_1_admin = 'data-step="1" data-intro="'.$dataAdmin['1'].'" data-position="bottom"';
  $step_1_manager = '';
  $step_1_employee = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_1_admin = '';
  $step_1_manager = 'data-step="1" data-intro="'.$dataManager['1'].'" data-position="bottom"';
  $step_1_employee = '';
}else{
  $step_1_admin = '';
  $step_1_manager = '';
  $step_1_employee = 'data-step="1" data-intro="'.$dataEmployee['1'].'" data-position="bottom"';
}
//step 2 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_2_admin = 'data-step="2" data-intro="'.$dataAdmin['2'].'" data-position="bottom"';
  $step_2_manager = '';
  $step_2_employee = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_2_admin = '';
  $step_2_manager = 'data-step="2" data-intro="'.$dataManager['2'].'" data-position="bottom"';
  $step_2_employee = '';
}else{
  $step_2_admin = '';
  $step_2_manager = '';
  $step_2_employee = 'data-step="2" data-intro="'.$dataEmployee['2'].'" data-position="bottom"';
}
//step 3 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_3_admin = 'data-step="3" data-intro="'.$dataAdmin['3'].'" data-position="left"';
  $step_3_manager = '';
  $step_3_employee = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_3_admin = '';
  $step_3_manager = 'data-step="3" data-intro="'.$dataManager['3'].'" data-position="left"';
  $step_3_employee = '';
}else{
  $step_3_admin = '';
  $step_3_manager = '';
  $step_3_employee = 'data-step="3" data-intro="'.$dataEmployee['3'].'" data-position="right"';
}
//step 4 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_4_admin = 'data-step="4" data-intro="'.$dataAdmin['4'].'" data-position="bottom"';
  $step_4_manager = '';
  $step_4_employee = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_4_admin = '';
  $step_4_manager = 'data-step="4" data-intro="'.$dataManager['4'].'" data-position="bottom"';
  $step_4_employee = '';
}else{
  $step_4_admin = '';
  $step_4_manager = '';
  $step_4_employee = 'data-step="4" data-intro="'.$dataEmployee['4'].'" data-position="bottom" data-tooltipClass="forfourthStep"';
}
//step 5 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_5_admin = 'data-step="5" data-intro="'.$dataAdmin['5'].'" data-position="bottom" ';
  $step_5_manager = '';
  $step_5_employee = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_5_admin = '';
  $step_5_manager = 'data-step="5" data-intro="'.$dataManager['5'].'" data-position="bottom"';
  $step_5_employee = '';
}else{
  $step_5_admin = '';
  $step_5_manager = '';
  $step_5_employee = 'data-step="5" data-intro="'.$dataEmployee['5'].'" data-position="bottom"';
}
//step 6 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_6_admin = 'data-step="6" data-intro="'.$dataAdmin['6'].'" data-position="bottom"';
  $step_6_manager = '';
  
}else if($_SESSION['user_type'] == 'manager'){
  $step_6_admin = '';
  $step_6_manager = 'data-step="6" data-intro="'.$dataManager['6'].'" data-position="bottom"';
}
//step 7 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_7_admin = 'data-step="7" data-intro="'.$dataAdmin['7'].'" data-position="bottom"';
  $step_7_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_7_admin = '';
  $step_7_manager = 'data-step="7" data-intro="'.$dataManager['7'].'" data-position="bottom"';
}
//step 8 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_8_admin = 'data-step="8" data-intro="'.$dataAdmin['8'].'" data-position="bottom"';
  $step_8_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_8_admin = '';
  $step_8_manager = 'data-step="8" data-intro="'.$dataManager['8'].'" data-position="bottom"';
}
//step 9 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_9_admin = 'data-step="9" data-intro="'.$dataAdmin['9'].'" data-position="bottom"';
  $step_9_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_9_admin = '';
  $step_9_manager = 'data-step="9" data-intro="'.$dataManager['9'].'" data-position="bottom"';
}
//step 10 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_10_admin = 'data-step="10" data-intro="'.$dataAdmin['10'].'" data-position="bottom"';
  $step_10_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_10_admin = '';
  $step_10_manager = 'data-step="10" data-intro="'.$dataManager['10'].'" data-position="bottom"';
}
//step 11 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_11_admin = 'data-step="11" data-intro="'.$dataAdmin['11'].'" data-position="bottom"';
  $step_11_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_11_admin = '';
  $step_11_manager = 'data-step="11" data-intro="'.$dataManager['11'].'" data-position="bottom"';
}
//step 12 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_12_admin = 'data-step="12" data-intro="'.$dataAdmin['12'].'" data-position="bottom"';
  $step_12_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_12_admin = '';
  $step_12_manager = 'data-step="12" data-intro="'.$dataManager['12'].'" data-position="bottom"';
}
//step 13 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_13_admin = 'data-step="13" data-intro="'.$dataAdmin['13'].'" data-position="bottom"';
  $step_13_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_13_admin = '';
  $step_13_manager = 'data-step="13" data-intro="'.$dataManager['13'].'" data-position="bottom"';
}
//step 14 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_14_admin = 'data-step="14" data-intro="'.$dataAdmin['14'].'" data-position="bottom"';
  $step_14_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_14_admin = '';
  $step_14_manager = 'data-step="14" data-intro="'.$dataManager['14'].'" data-position="bottom"';
}
//step 15 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_15_admin = 'data-step="15" data-intro="'.$dataAdmin['15'].'" data-position="bottom"';
  $step_15_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_15_admin = '';
  $step_15_manager = 'data-step="15" data-intro="'.$dataManager['15'].'" data-position="bottom"';
}
//step 16 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_16_admin = 'data-step="16" data-intro="'.$dataAdmin['16'].'" data-position="bottom"';
  $step_16_manager = '';
}else if($_SESSION['user_type'] == 'manager'){
  $step_16_admin = '';
  $step_16_manager = 'data-step="16" data-intro="'.$dataManager['16'].'" data-position="bottom"';
}
//step 17 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_17_admin = 'data-step="17" data-intro="'.$dataAdmin['17'].'" data-position="bottom"';
}else{
  $step_17_admin = '';
}
//step 18 msg
if($_SESSION['user_type'] == 'company_admin'){
  $step_18_admin = 'data-step="18" data-intro="'.$dataAdmin['18'].'" data-position="bottom"';
}else{
  $step_18_admin = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Capacity</title>
<link href="<?php echo SITE_URL; ?>css_ui/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo SITE_URL; ?>css_ui/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo SITE_URL; ?>css_ui/datepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>css_ui/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>css_ui/bootstrap-datetimepicker.css">

<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
<link rel="shortcut icon" href="<?php echo SITE_URL; ?>images/favicon(1).ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE_URL; ?>images/favicon(1).ico" type="image/x-icon">
<link href='<?php echo SITE_URL; ?>fullcalendar_2.2.1/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo SITE_URL; ?>fullcalendar_2.2.1/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href="<?php echo SITE_URL; ?>css_ui/jquery.dataTables.css" rel="stylesheet" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="<?php echo SITE_URL; ?>assets/Responsive-Tabs-master/css/responsive-tabs.css" />
<link href="<?php echo SITE_URL; ?>css_ui/select2.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/jquery-ui.css">
<link href="<?php echo SITE_URL; ?>css_ui/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_URL; ?>css_ui/jquery.gritter.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/font-awesome.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/jquery.simplecolorpicker.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/jquery.simplecolorpicker-regularfont.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/jquery.simplecolorpicker-glyphicons.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/jquery.simplecolorpicker-fontawesome.css">
<link href="<?php echo SITE_URL; ?>css_ui/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo SITE_URL; ?>css_ui/responsive.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>css_ui/introjs.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,700italic,400italic&subset=latin,latin-ext,cyrillic' rel='stylesheet' type='text/css'>


<script src="<?php echo SITE_URL; ?>js_ui/html5.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.gritter.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/bootstrap-timepicker.js"></script>
<script src="<?php echo SITE_URL; ?>js_ui/bootstrap.min.js" type="text/javascript" ></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/bootstrap-datetimepicker.js"></script>
<script src="<?php echo SITE_URL; ?>js_ui/jquery.contextMenu.js" type="text/javascript"></script>
<script src="<?php echo SITE_URL; ?>js_ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.blockUI.js"></script>
<script type="text/javascript">
    var cSpeed=4;
    var cWidth=66;
    var cHeight=66;
    var cTotalFrames=20;
    var cFrameWidth=66;
    var cImageSrc='<?php echo SITE_URL; ?>images/loader_green_sprite.png';
    var cImageTimeout=false;
    var cIndex=0;
    var cXpos=0;
    var cPreloaderTimeout=false;
    var SECONDS_BETWEEN_FRAMES=0;
    function startAnimation(){
        
        document.getElementById('loaderImage').style.backgroundImage='url('+cImageSrc+')';
        document.getElementById('loaderImage').style.width=cWidth+'px';
        document.getElementById('loaderImage').style.height=cHeight+'px';
        
        //FPS = Math.round(100/(maxSpeed+2-speed));
        FPS = Math.round(100/cSpeed);
        SECONDS_BETWEEN_FRAMES = 1 / FPS;
        cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES/1000);
    }
    
    function continueAnimation(){
        cXpos += cFrameWidth;
        //increase the index so we know which frame of our animation we are currently on
        cIndex += 1;
        //if our cIndex is higher than our total number of frames, we're at the end and should restart
        if (cIndex >= cTotalFrames) {
            cXpos =0;
            cIndex=0;
        }
        
        if(document.getElementById('loaderImage'))
            document.getElementById('loaderImage').style.backgroundPosition=(-cXpos)+'px 0';
        
        cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES*1000);
    }
    
    function stopAnimation(){//stops animation
        clearTimeout(cPreloaderTimeout);
        cPreloaderTimeout=false;
    }
    
    function imageLoader(s, fun)//Pre-loads the sprites image
    {
        clearTimeout(cImageTimeout);
        cImageTimeout=0;
        genImage = new Image();
        genImage.onload=function (){cImageTimeout=setTimeout(fun, 0)};
        genImage.onerror=new Function('alert(\'Could not load the image\')');
        genImage.src=s;
    }
    
    //The following code starts the animation
   
</script>
<script type="text/javascript">
 $(document).ready(function(){
  $('.loader').block({ 
                                message: '<div id="loaderImage" style="margin:0% 50%"></div>', 
                        });
                         new imageLoader(cImageSrc, 'startAnimation()');
  });                       
</script>
<style type="text/css">
  .loader {position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;/*background: url('<?php echo SITE_URL; ?>images/Loading-Green-Gray01.gif') 50% 50% no-repeat rgb(249,249,249)*/;}
</style>
</head>
<body>
<div class="loader"></div>
<?php 
                    //code for get notification message
                    $notifications = $dclass->select("*","tbl_notification_details"," AND user_id = ".$_SESSION['user_id']);
                        $user_event = '';
                        $str = '';
                        foreach($notifications as $key=>$value)
                        {
                            foreach($value as $k=>$v)
                            {
                                if($k == 'notification_name' and $v == 'task_add')
                                {
                                    $task_add = ($v == 'task_add')? 'checked = "checked"' : '';
                                    $user_event = 'Task Added';
                                    $str .= '"'.$user_event.'",';
                                    
                                }
                                else if($k == 'notification_name' and $v == 'task_move')
                                {
                                    $task_move = ($v == 'task_move')? 'checked = "checked"' : '';
                                    $user_event = 'Task Moved';
                                    $str .= '"'.$user_event.'",';
                                }
                                else if($k == 'notification_name' and $v == 'task_edit')
                                {    
                                    $task_edit = ($v == 'task_edit')? 'checked = "checked"' : '';
                                    $user_event = 'Task Edited';
                                    $str .= '"'.$user_event.'",';
                                }
                            }   
                          }
                      $str .= '"Task Reminder",';
                       if($_SESSION['user_type'] != 'employee'){
                          $str .= '"Project Reminder",';
                       }
                      $str = trim($str,',');

                        if($_SESSION['user_type'] == 'employee'){
                                $user_notification = $dclass->select('t2.*,t1.t_id,t3.pr_id','tbl_task t1 RIGHT JOIN tbl_user_notification t2 ON t1.t_id = t2.task_id 
                                  LEFT JOIN tbl_project t3 ON t2.task_id = t3.pr_id',
                             " AND (t2.user_id = '".$_SESSION['user_id']."') AND t2.task_event  IN (".$str.") AND (t1.t_id != 'NULL' OR t3.pr_id != 'NULL')  ORDER BY t2.id desc"); 
                        }else{
                                $user_notification = $dclass->select('t2.*,t1.t_id,t3.pr_id','tbl_task t1 RIGHT JOIN tbl_user_notification t2 ON t1.t_id = t2.task_id 
                                  LEFT JOIN tbl_project t3 ON t2.task_id = t3.pr_id',
                             " AND (t2.user_id = '".$_SESSION['user_id']."' OR  t2.user_id = '".$_SESSION['company_id']."') AND t2.task_event  IN (".$str.") AND (t1.t_id != 'NULL' OR t3.pr_id != 'NULL')  ORDER BY t2.id desc"); 
                        }

                      $cntclass = 'hide';
                      if(isset($notifications) > 0){
                        foreach($notifications as $nt){
                          if($nt['notification_name'] == 'inapp_notification'){
                            $cntclass = '';
                            break;
                          }else{
                            $cntclass = 'hide';
                          }
                        }
                      }else{
                        $cntclass = 'hide';
                      } 
                            
                    ?>
<div id="wap">
  <header id="header-main">
      <div class="header-box">
            <div class="traffic-logo"><a href="#"><img src="<?php echo SITE_URL; ?>images/logo.svg"></a></div>
            <?php if($basefile != 'plan.php') {?>
            <ul class="nav-main" >
              <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){ ?>
                <li>
                  <a href="<?php echo SITE_URL; ?>view" <?php if($basefile == 'task.php'){ echo $step_5_employee; }else if($basefile == 'setting.php'){  echo $step_16_manager.' '.$step_18_admin; } ?> <?php if($basefile == 'task.php'){ echo 'class="active"'; } ?>  ><span class="view-icon"></span>View</a>
                </li>
                <?php if($gnrl->checkUserAccess("TASK_ADD")){ ?>
                <?php if($basefile == 'task.php'){ ?>
                <li>
                  <a href="<?php echo $actual_link.'#tab-1'; ?>"  class="add_task" ><span class="add-icon"></span>Add</a>
                  <?php  ?>
                </li>
                <?php }else{ ?>
                <li >
                  <a href="<?php echo $actual_link.'#tab-1'; ?>" class="add_task" ><span class="add-icon"></span>Add</a>
                  <?php  ?>
                </li>
                <?php } ?>
                
                <?php } ?>
                <li>
                  <a href="javascript:void(0)" <?php if($basefile == 'setting.php'){  echo $step_14_manager; echo $step_16_admin; } ?> class="add_notify"><span class="notify-icon"></span>Notify
                  	<?php if(count($user_notification) > 0){
                      $n = 0;
                      foreach($user_notification as $notif){ 
                          if($notif['task_date'] == '1969-12-31' || $notif['task_date'] == '1970-01-01'){
                            continue;
                          }
                          $n++;
                      }
                    if($n > 0){
                     ?>
                  	<div class="note-box <?php echo $cntclass; ?>" cnt="<?php echo $n; ?>"><?php echo $n; ?></div>
                  	<?php }
                     } ?>
                  </a>
                </li>
                <?php if($_SESSION['user_type'] != 'employee'){ ?>
                <?php /* ?><li>
                  <a href="javascript:void(0)" <?php if($basefile == 'setting.php'){  echo $step_15_manager; echo $step_17_admin;  } ?> class="add_report"><span class="report-icon"></span>Reports</a>
                </li> <?php */ ?>
                <li><a href="<?php echo SITE_URL; ?>setting" <?php if($basefile == 'setting.php'){ echo 'class="active"'; } ?>><span class="setting-icon"></span>Settings</a></li>
                <?php } ?>
                <li><a href="<?php echo SITE_URL; ?>userlist" <?php if($basefile == 'userlist.php'){ echo 'class="active"'; echo $step_1_manager; echo $step_1_admin; echo $step_1_employee;  } ?>><span class="user-icon"></span>Users</a></li>
              <?php } ?>
            </ul>
            <?php } ?>
            <div class="user-prof-nav desk-user">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){ ?>
                    <label>Hi <?php echo  ucfirst($userDetail[0]['fname']) ?>!</label> 
                    <?php if($userDetail[0]['user_avatar'] != ''){ ?>
                      <?php //echo SITE_URL."upload/user/".$userDetail[0]["user_avatar"] ?>
                        <img src="<?php echo SITE_URL.'timthumb.php?src='.SITE_URL.'/upload/user/'.$userDetail[0]["user_avatar"].'&h=46&w=46&zc=1&q=100' ?>"  />
                        <!--img src="<?php echo SITE_URL."upload/user/".$userDetail[0]["user_avatar"]; ?>"  /-->
                    <?php }else{ ?>
                        <img alt="" src="<?php echo SITE_URL; ?>img/User_icon.png" />
                    <?php } ?>
                    <label><a class="helplink" href="<?php echo SITE_URL; ?>contact-us"><span class="help-icon"></span>Help</a></label>
                    <span><a href="<?php echo SITE_URL; ?>logout">Logout</a></span>
                <?php }else{ ?>
                    <span><a href="<?php echo SITE_URL; ?>login">Login</a></span>
                <?php } ?>
            </div>
            <div class="user-prof-nav tab-user">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){ ?>
                    <?php if($userDetail[0]['user_avatar'] != ''){ ?>
                        <img src="<?php echo SITE_URL.'timthumb.php?src='.SITE_URL.'/upload/user/'.$userDetail[0]["user_avatar"].'&h=46&w=46&zc=1&q=100' ?>"  />
                    <?php }else{ ?>
                        <img alt="" src="<?php echo SITE_URL; ?>img/User_icon.png" />
                    <?php } ?>
                    <div class="tab-lables">
                    <label>Hi <?php echo  ucfirst($userDetail[0]['fname']) ?>!</label>
                    <span><a href="<?php echo SITE_URL; ?>logout">Logout</a></span>
                    </div>
                <?php }else{ ?>
                    <span><a href="<?php echo SITE_URL; ?>login">Login</a></span>
                <?php } ?>
            </div>
        </div>
        
        <div class="top-tab-main">
        <div class="top-tab">
        <?php if($gnrl->checkUserAccess("TASK_ADD")){ ?>
              <div id="horizontalTab" class="task-form-div" style="display:none">
                  <ul class="top-tab-titiel">
                    <?php if($basefile == 'task.php'){ ?>
                    <li><a id="custom_click" href="#tab-1" <?php echo $step_7_admin; ?><?php echo $step_7_manager; ?> >Task</a></li>
                    <li><a href="#tab-2" <?php echo $step_5_admin; ?><?php echo $step_5_manager; ?>>Project</a></li>
                    <li><a href="#tab-3" <?php echo $step_4_admin; ?><?php echo $step_4_manager; ?> >Client</a></li>
                    <li><a href="#tab-4" <?php echo $step_6_admin; ?><?php echo $step_6_manager; ?>>Team</a></li>
                    <?php }else{ ?>
                     <li><a id="custom_click" href="#tab-1" >Task</a></li>
                    <li><a href="#tab-2"  >Project</a></li>
                    <li><a href="#tab-3" >Client</a></li>
                    <li><a href="#tab-4">Team</a></li> 
                    <?php  } ?>
                  </ul>
                <!-- task tab -->
                <div id="tab-1" class="top-tab-sub">
                  <form name="new_task" id="new_task" method="post" action="" enctype="multipart/form-data">
                    <div class="top-tab-table taskbox taskboxfirst">
                        <div class="top-tab-td td-first">
                          <div class="full-box">
                    <?php $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);
                    ?> 
                              <div class="tab-title">Add new task</div>
                                <div class="form-group">
                                <select id="client_id" name="client_id" class="selectpicker" data-live-search="true" title="Choose Client">
                                    <option value="">Choose Client</option>
                                    <?php foreach($clientList as $cl){ ?>
                                              <option value="<?php echo $cl['cl_id']; ?>" ><?php echo $gnrl->trunc_string($cl['cl_company_name'],15); ?></option>
                                   <?php } ?> 

                                </select>
                                </div>
                                <div class="clr5"></div>
                                <div class="add-btn">
                                  <a class="select-tab" rel='2'>Add new client</a></div>
                                <div class="clr20"></div>
                            </div>
                            <div class="full-box" style="border-top:1px solid #323232">
                              <div class="tab-title"><!--Choose Project--></div>
                                <div class="form-group project_div">
                                    <select id="project_id" name="project_id" class="selectpicker projList" data-live-search="true" title="Choose project">
                                        <option>Choose Project</option>
                                    </select>
                                </div>
                                <div class="clr5"></div>
                                <div class="add-btn"><a class="select-tab" rel='1'>Add new project</a></div>

                                <div class="clr20"></div>
                            </div>
                        </div>
                  </div>
                  </form>
                </div>
                <!-- project tab -->
                <div id="tab-2" class="top-tab-sub">
                  <form name="new_project" id="new_project" method="post" action="" enctype="multipart/form-data">
                    <div class="top-tab-table">
                      <!-- choose client-->
                        <?php $prList = $dclass->select("*","tbl_project"," AND pr_company_id = ".$_SESSION['company_id']);
                    ?>
                        <div class="top-tab-td td-first">
                          <div class="full-box">
                               <?php  

                                if( $gnrl->checkmaxallowproject() > $gnrl->gettotalcompanyproject() || $gnrl->checkmaxallowproject() == ''){ ?>
                                  <div class="add-btn"><a href="javascript:void(0)" class="add_new_project_btn">Add new project</a></div>
                                <?php }else{ ?>
                                  <div class="add-btn"><a href="javascript:void(0)"  class="gotoproject">Add new project</a></div>
                                <?php } ?>
                               <div class="clr5"></div> 
                              <div class="tab-title"></div>
                                <div class="form-group projectmaster">
                                <select id="project_id_add" name="project_id" class="selectpicker" data-live-search="true" >
                                 <option value=""> Edit Project </option>
                                 <?php foreach($prList as $pr){ ?>
                                    <?php echo $pr['pr_title']; ?>
                                        <option value="<?php echo $pr['pr_id']; ?>" ><?php echo $pr['pr_title']; ?></option>
                                 <?php } ?>
                                </select>
                                </div>
                               
                                <div class="clr20"></div>
                            </div>
                        </div>
                        <!-- choose client end-->
                        <!-- Project -->
                    <?php $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);?>
                        <div class="top-tab-td td-fore project-detail">
                          
                        </div>   
                    </div>
                  </form>  
                </div>
                <!-- client tab -->
                <div id="tab-3" class="top-tab-sub">
                  <form name="new_client" id="new_client" method="post" action="" enctype="multipart/form-data">
                    <div class="top-tab-table">
                       <div class="top-tab-td td-first">
                          <div class="full-box">
                              <div class="add-btn"><a href="javascript:void(0)" class="add_new_client_btn">Add new client</a></div>
                              <div class="clr5"></div>
                              <div class="tab-title"></div>
                              <div class="form-group clientmater" >
                                  <?php $clientList = $dclass->select("*","tbl_client"," AND cl_comp_user_id = ".$_SESSION['company_id']);
                              ?>
                                <select id="client_id_add" name="client_id" class="selectpicker" data-live-search="true" title="Choose Client">
                                <option value="">Edit Client</option>
                                <?php foreach($clientList as $cl){ ?>
                                  <option value="<?php echo $cl['cl_id'] ?>" ><?php echo $cl['cl_company_name'] ?></option>
                                <?php } ?>
                                </select>
                              </div>
                                
                                <div class="clr20"></div>

                            </div>
                        </div>
                        <div class="top-tab-td td-fore client-detail">
                        </div>   
                    </div>
                  </form>  
                </div>
                <!-- team tab -->
                <div id="tab-4" class="top-tab-sub">
                  <form name="new_team" id="new_team" method="post" action="" enctype="multipart/form-data">
                  <div class="top-tab-table">
                      <!-- choose client-->
                        <?php $teamlist = $dclass->select("*","tbl_team"," AND company_user_id =".$_SESSION['company_id']); ?>
                        <div class="top-tab-td td-first">
                          <div class="full-box">
                              <div class="add-btn"><a href="javascript:void(0)" class="add_new_team_btn">Add new team</a></div>
                              <div class="clr5"></div>
                              <div class="tab-title"></div>
                                <div class="form-group teammaster">
                                <select id="team_id_add" name="team_id" class="selectpicker" data-live-search="true" >
                                <option value="">Edit Team</option>
                                <?php foreach($teamlist as $tm){ ?>
                                <option value="<?php echo $tm['tm_id']; ?>"><?php echo $tm['tm_title']; ?></option>
                                <?php } ?>
                                </select>
                                </div>
                                <div class="clr20"></div>
                            </div>
                        </div>
                        <!-- choose client end-->
                        <!-- Project -->
                        <div class="top-tab-td td-fore team_detail">
                        
                        </div>   
                        <!-- Project end-->
                    </div>
                   </form> 
            </div>
            </div>
        <?php } ?>
        
            <div class="notify-div" id="notify-div" style="display:none">
                <form name="notify-form" id="notify-form" method="post" action="" enctype="multipart/form-data">
                 <ul class="top-tab-titiel ">
                     <li>
                    <?php

                      $notificationsMain = $dclass->select("*","tbl_notification_details"," AND user_id = ".$_SESSION['user_id']." AND status='1'");
                      
                      $emailnoti = '';
                      $inappnoti = '';
                     $hdclass = 'hide';
                      foreach($notificationsMain as $nt){
                        if($nt['notification_name'] == 'email_notification'){
                          $emailnoti = "checked='checked'";
                        }else if($nt['notification_name'] == 'inapp_notification'){
                          $inappnoti = "checked='checked'";
                          $hdclass = '';
                        }
                      }
                    ?>
                        <div style="float:left;">
                            <div class="lbl-tab">
                                <span class="tab-title">Email Notifications 
                                   <div class="checkbox relposition">
                                        <input type="checkbox"  value="email_notification" <?php echo $emailnoti; ?> name="notification[]" id="email_notification" class="topnotify">
                                        <label for="email_notification" class="check-lbl"></label>
                                    </div> 

                                </span>
                            </div>
                            <div class="lbl-tab">
                                <span class="tab-title">In App Notifications 
                                    <div class="checkbox relposition">
                                        <input type="checkbox"  value="inapp_notification" <?php echo $inappnoti; ?> name="notification[]" id="inapp_notification" class="topnotify">
                                        <label for="inapp_notification" class="check-lbl"></label>
                                    </div>
                                </span>
                            </div>
                            <div style="float:right;"><button class="save-btn" id="save-notifications" type="submit">Save all changes</button></div>
                        </div>
                     </li>
                     <li>
                      <div class="top-tab-sub">
                    <div class="top-tab-table notificationDiv">
                        <div class="top-tab-td td-fore">
                          <span class="title-bl-tab">Notify me when:</span>
                          <div class="full-box">
                             
                                <div class="tab-title_notify">
                                    <div class="checkbox">
                                        <input type="checkbox" <?php echo $task_add;?> value="task_add" name="notify[]" id="task_add" class="chknotify dispmsg">
                                        <label for="task_add" class="check-lbl">Task Added.</label>
                                    </div>
                                </div>   
                                <div class="tab-title_notify">
                                    <div class="checkbox">
                                        <input type="checkbox" <?php echo $task_move;?> value="task_move" name="notify[]" id="task_move" class="chknotify dispmsg">
                                        <label for="task_move" class="check-lbl">Task Moved.</label>
                                    </div>
                                </div>
                                <div class="tab-title_notify">
                                    <div class="checkbox">
                                        <input type="checkbox" <?php echo $task_edit;?> value="task_edit" name="notify[]" id="task_edit" class="chknotify dispmsg">
                                        <label for="task_edit" class="check-lbl">Task Edited.</label>
                                    </div>
                                </div>
                                <div class="tab-title_notify">
                                    <div class="checkbox">
                                        <input type="checkbox" value="get_all" name="notify[]" id="get_all" class="dispmsg">
                                        <label for="get_all" class="check-lbl">Get All Notifications.</label>
                                    </div>
                                </div>
                                <div class="clr"></div>
                            
                                <div class="clr"></div>
                            </div>
                          </div>   
                    </div>
                </div>
                     </li> 
                </ul>
                </form>
                

                <div class="top-tab-sub">
                    <div class="top-tab-table notificationDiv notify-box <?php echo $hdclass; ?>">
                        <div class="top-tab-td td-fore">
                          <div class="full-box scroll-tab">
                            
                            <table cellpadding="5" cellspacing="5" border-s class="notify-inbox">
                            <?php 

                              if(count($user_notification) > 0){
                              foreach($user_notification as $notif){ 
                                  if($notif['task_event'] == 'Task Reminder'){
                                      $task_event = 'Task due in '.$notif['description'].' hrs';
                                  }else if($notif['task_event'] == 'Project Reminder'){
                                      $task_event = 'Project due in '.$notif['description'].' hrs';
                                      //$proj_task = $dclass->select("t_id","tbl_task"," AND t_pr_id = ".$notif['task_id']." ORDER BY t_id desc");
                                  }else{
                                      $task_event = $notif['task_event'];
                                  }
                                  //echo $notif['task_date'].' '.$notif['task_starttime'];
                                  if($notif['task_date'] == '1969-12-31' || $notif['task_date'] == '1970-01-01'){
                                    continue;
                                  }
                                ?>
                              <tr id="tbtr<?php echo $notif['id'] ?>">
                                <td width="80%"><?php echo $notif['task_title'].' &nbsp; '.$notif['task_date'].' &nbsp; '.$notif['task_starttime'].' - <i>'.$task_event.'</i>';  ?></td>
                                <td width="10%">
                                <?php if($notif['task_event'] != 'Project Reminder'){ ?>
                                   <a href="<?php echo SITE_URL; ?>view/<?php echo $notif['task_id'];  ?>">View task</a>
                                <?php }else{ 
                                    /*if(!empty($proj_task)){ ?>
                                      <a href="<?php echo SITE_URL; ?>view/<?php echo $proj_task[0]['t_id'];  ?>">View task</a>
                                    <?php }*/ ?>
                                <?php } ?>
                                </td>
                                <td width="10%"><a class="dismiss_msg" msgid="<?php echo $notif['id']; ?>">Dismiss</a></td>
                              </tr>
                              <?php }
                              }else{ ?> 
                              <tr>
                                  <td colspan="3">No notifications at this time.</td>
                              </tr>
                              <?php  } ?>
                            </table>   
                          <div class="clr"></div>
                          </div>
                          </div>   
                    </div>
                </div>
            </div>
            <div class="report-div" id="report-div" style="display:none">
                <div class="report-box report-box-top">
                	<div class="report-titel">Reports Selection</div>
                </div>
                <form name="report-form" id="report-form" method="post" action="" enctype="multipart/form-data">
                <div class="report-box">
                	<div class="tab-title">Choose Date Range For Reports</div>
                    <div class="report-date">
                    	<input type="text" name="report_start_date" id="report_start_date" value="" class="tab-input" placeholder="Date">
                        <label>to</label>
                        <input type="text" name="report_end_date" id="report_end_date" class="tab-input" placeholder="Date">
                    </div>
                </div>    
                 <div class="top-tab-sub report-box">
                    <div class="top-tab-table">
                    <?php $ProjectList = $dclass->select("t1.*,t2.cl_id","tbl_project as t1 LEFT JOIN tbl_client as t2 ON t1.pr_cl_id = t2.cl_id"," AND t2.cl_comp_user_id = ".$_SESSION['company_id']); ?>
                        <div class="top-tab-td td-first">
                            <div class="full-box">
                            	<div class="tab-title">Choose Project</div>
                                <div class="form-group">
                                <select name="project_id" id="project_id" class="selectpicker" data-live-search="true" title="Choose Project">
                                    <option value="all"> All Project</option>
                                <?php foreach($ProjectList as $pr){ ?>
                                    <option value="<?php echo $pr['pr_id']; ?>" ><?php echo $pr['pr_title']; ?></option>
                                <?php } ?>
                                </select>
                                </div>
                            </div>
                        </div>
                        <?php $teamList = $dclass->select("*","tbl_team"," AND company_user_id = ".$_SESSION['company_id']); ?>
                        <div class="top-tab-td td-first">
                        	<div class="full-box">
                            	<div class="tab-title">Choose Team</div>
                                <div class="form-group">
                                <select name="team_id" id="team_id" class="selectpicker validate[required]" data-live-search="true" title="Choose Team">
                                   <option value="all"> All Team</option>
                                    <?php foreach($teamList as $tm){ ?>
                                    <option value="<?php echo $tm['tm_id']; ?>" ><?php echo $tm['tm_title']; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div>
                        </div>
                        <?php $userList = $dclass->select("*","tbl_user"," AND user_comp_id = ".$_SESSION['company_id']); ?>
                        <div class="top-tab-td td-first">
                        	<div class="full-box">
                            	<div class="tab-title">Choose Employee</div>
                                <div class="form-group report_employee">
                                <select name="user_id" id="user_id" class="selectpicker validate[required]" data-live-search="true" title="Choose Employee">
                                    <option value="all"> All Employee</option>
                                    <?php foreach($userList as $ui){ ?>
                                    
                                    <option value="<?php echo $ui['user_id']; ?>" ><?php echo $ui['fname'].' '.$ui['lname']; ?></option>
                                    <?php } ?>
                              </select>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                              
                <div class="report-box" >
                    <input type="button" class="download-btn" id="download_report" value="Download Reports">
                </div>
                     <div id="download_link"></div>
                </form>
             </div>
        
            </div>
        </div>
        </div>
</header>
<div class="alert alert-success msgDiv hide"><button data-dismiss="alert" class="close"></button></div>
<?php 
if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){ ?>
      <div class="alert alert-success"><button data-dismiss="alert" class="close"></button>
      <?php echo $mess[$_SESSION['msg']]; $_SESSION['msg'] = ''; 
      ?>
            </div>
      <?php } ?>
</div>
<div class="clr"></div>
