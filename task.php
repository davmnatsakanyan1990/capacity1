<?php
// Configuration  
include('config/configuration.php');
//include('config/guidemsg.php');
$load  =  new loader(); 
$objages = $load->includeclasses('pages');  
$label = "register";
$dclass   =  new  database();
$gnrl =  new general;
$load->includeother('header');
if(!$gnrl->checkUserLogin()){
    $gnrl->redirectTo("home/logfirst");
}
// uncomment after client confirmation
if(!$gnrl->checkpaymentstatus()){
  $gnrl->redirectTo("plan");
}
function string_sanitize($s) {
    $result = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($s, ENT_QUOTES));
    return $result;
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
function getUserteam($userid){
    global $dclass;
    $data = $dclass->select("tm_id","tbl_team_detail"," AND user_id=".$userid);

    if(count($data) > 0){
        return $data[0]['tm_id'];
    }
}


if(isset($_REQUEST['t_id']) && $_REQUEST['t_id'] != '' ){
  $_SESSION['view_task'] = $_REQUEST['t_id'];
  
  $getDt = $dclass->select('t_start_datetime',"tbl_task"," AND t_id=".$_REQUEST['t_id']);
            
  $dtt = date("Y-m-d",strtotime($getDt[0]['t_start_datetime']));
  $_SESSION['def_date'][$_SESSION['user_id']] = $dtt;

  header("Location:".SITE_URL."view");
  die();
}
 $dclass->delete('tbl_task_update_notification'," user_id = '".$_SESSION['user_id']."'");
?>
<!-----------------------------////content////---------------------------------->
<!--main content start-->
<style>
    .desing-studio li ul{height:300px; overflow-x:hidden; } 
</style>
<link type="text/css" rel="stylesheet" href="<?php echo SITE_URL ?>css_ui/collapsible-menu.css" />
<script type="text/javascript" src="<?php echo SITE_URL ?>js_ui/collapsible-menu.js"></script>
<section id="calender-main">

<div class="calender-top-section" <?php echo $step_4_employee; ?><?php echo $step_9_admin; ?><?php echo $step_9_manager; ?>>
            <ul class="collapsible-menu desing-studio" data-collapsible-menu="slide" >  
            <li>
                <span class="desing-studio-tag-span">Filter View</span>
            </li>
        </ul>
        <?php 
            if(isset($_REQUEST['pr_id']) && $_REQUEST['pr_id'] == 'all'){
                $_REQUEST['pr_id'] = '';
            }
            if(isset($_REQUEST['cl_id']) && $_REQUEST['cl_id'] == 'all'){
                $_REQUEST['cl_id'] = '';
            }
            if(isset($_REQUEST['tm_id']) && $_REQUEST['tm_id'] == 'all'){
                $_REQUEST['tm_id'] = '';
            }

            $tmlink = SITE_URL.'view/';
            if(isset($_REQUEST['pr_id']) && $_REQUEST['pr_id'] != ''){
                $tmlink .= $_REQUEST['pr_id'].'/'; 
            }else{
                $tmlink .= 'all/'; 
            }
            if(isset($_REQUEST['cl_id']) && $_REQUEST['cl_id'] != ''){
                $tmlink .= $_REQUEST['cl_id'].'/'; 
            }else{
                $tmlink .= 'all/'; 
            }

            
            $prlink = SITE_URL.'view/';
            $prlink1 = '';
            
            if(isset($_REQUEST['cl_id']) && $_REQUEST['cl_id'] != ''){
                $prlink1 .= $_REQUEST['cl_id'].'/'; 
            }else{
                $prlink1 .= 'all/'; 
            }
            if(isset($_REQUEST['tm_id']) && $_REQUEST['tm_id'] != ''){
                $prlink1 .= $_REQUEST['tm_id']; 
            }else{
                $prlink1 .= 'all'; 
            }

            $cllink = SITE_URL.'view/';
            $cllink1 = '';
            $cllink2 = '';
            if(isset($_REQUEST['pr_id']) && $_REQUEST['pr_id'] != ''){
                $cllink1 .= $_REQUEST['pr_id'].'/'; 
            }else{
                $cllink1 .= 'all/'; 
            }

            if(isset($_REQUEST['tm_id']) && $_REQUEST['tm_id'] != ''){
                $cllink2 .= $_REQUEST['tm_id']; 
            }else{
                $cllink2 .= 'all'; 
            }

         ?>
        <?php
            if($_SESSION['user_type'] != 'employee'){
         $team = $dclass->select("*","tbl_team"," AND company_user_id =".$_SESSION['company_id'] );?>
                <ul class="collapsible-menu desing-studio" data-collapsible-menu="slide" >  
                <li>
                    <?php if(isset($_REQUEST['tm_id']) && $_REQUEST['tm_id'] != ''){ 
                        $teamDe = $dclass->select("*","tbl_team"," AND tm_id =".$_REQUEST['tm_id'] )    
                        ?>
                        <a class="collapsed desing-studio-tag"><?php echo trunc_string($teamDe[0]['tm_title'],13); ?></a>
                    <?php }else{ ?>
                        <a class="collapsed desing-studio-tag">Select Team</a>
                    <?php } ?>
                    <ul>
                    <?php foreach($team as $tm){ ?>
                        <li><a href="<?php echo $tmlink; ?><?php echo $tm['tm_id']; ?>"><?php echo trunc_string($tm['tm_title'],15); ?></a></li>
                    <?php } ?>
                        <li><a href="<?php echo trim(trim($tmlink,'/'),'/'); ?>/all">All</a></li>   
                    </ul>
                </li>
           </ul>
        <?php 
    }
        $project = $dclass->select("*","tbl_project"," AND pr_company_id =".$_SESSION['company_id'] );?>
        <ul class="collapsible-menu desing-studio" data-collapsible-menu="slide" >  
                <li>
                    <?php if(isset($_REQUEST['pr_id']) && $_REQUEST['pr_id'] != ''){ 
                        $projectDe = $dclass->select("*","tbl_project"," AND pr_id =".$_REQUEST['pr_id'] )  
                        ?>
                        <a class="collapsed desing-studio-tag"><?php echo trunc_string($projectDe[0]['pr_title'],13); ?></a>
                    <?php }else{ ?>
                        <a class="collapsed desing-studio-tag">Select Project</a>
                    <?php } ?>
                    <ul>
                    <?php foreach($project as $pr){ ?>
                        <li><a title="<?php echo trunc_string($pr['pr_title'],50); ?>" href="<?php echo $prlink; ?><?php echo $pr['pr_id'].'/'.$prlink1; ?>"><?php echo trunc_string($pr['pr_title'],15); ?></a></li>
                    <?php } ?>
                        <li><a href="<?php echo $prlink.'all/'.$prlink1; ?>">All</a></li>   
                    </ul>
                </li>
           </ul>
        <?php $client = $dclass->select("*","tbl_client"," AND cl_comp_user_id =".$_SESSION['company_id'] );?>
        <ul class="collapsible-menu desing-studio" data-collapsible-menu="slide">  
                <li>
                    <?php if(isset($_REQUEST['cl_id']) && $_REQUEST['cl_id'] != ''){ 
                        $clientDe = $dclass->select("*","tbl_client"," AND cl_id =".$_REQUEST['cl_id'] )    
                        ?>
                        <a class="collapsed desing-studio-tag"><?php  echo trunc_string($clientDe[0]['cl_company_name'],13); ?></a>
                    <?php }else{ ?>
                        <a class="collapsed desing-studio-tag">Select Client</a>
                    <?php } ?>
                    <ul>
                    <?php foreach($client as $cl){ ?>
                        <li><a href="<?php echo $cllink; ?><?php echo $cllink1; ?><?php echo $cl['cl_id']; ?>/<?php echo $cllink2; ?>"><?php echo trunc_string($cl['cl_company_name'],15); ?></a></li>
                    <?php } ?>
                        <li><a href="<?php echo $cllink; ?><?php echo $cllink1; ?>all/<?php echo $cllink2; ?>">All</a></li>   
                    </ul>
                </li>
           </ul>
        <?php /*if($_SESSION['user_type'] == 'manager'){ ?>  
         <a href="<?php echo SITE_URL; ?>task.php?u_id=me">Me</a>
        <?php }*/ ?>
        <div class="alert alert-success hide taskmsgDiv">Task successfully save.</div>       
    </div>
   
<div class="calender-section" >
    <div class="calender-border">
        <div class="clr10"></div>
            <?php if($_SESSION['user_type'] != 'employee'){ ?>
                <div class="full-box queue-task-cls" id="queue-task-box" >
                    <div class="calender-left tab-close" id="queue_tsk" <?php echo $step_8_admin; ?><?php echo $step_8_manager; ?>>
                        <div class="calender-click"><a href="#"></a></div>
                        <span><b style="width: 125px !important;">Queued Tasks</b></span>
                    </div>
                    <div class="calender-right right-calender" style="display:none">
                        <div class="calender-right-box" style="padding:5px;" >
                            <div id='external-events' class="divs">
                              
                            
 <?php 
$condition1 = '';
if(isset($_REQUEST['pr_id']) && $_REQUEST['pr_id'] != ''){
   $condition1 .= " AND t1.t_pr_id = ".$_REQUEST['pr_id']." ";
}
if(isset($_REQUEST['cl_id']) && $_REQUEST['cl_id'] != ''){
   $condition1 .= " AND t1.t_cl_id = ".$_REQUEST['cl_id']." ";
}
if(isset($_REQUEST['tm_id']) && $_REQUEST['tm_id'] != ''){
   $condition1 .= " AND t1.t_team_id =".$_REQUEST['tm_id']." ";
}

$queue_task = $dclass->select(
                "t1.t_id,t1.t_title,t1.t_cl_id,t1.t_start_datetime,t1.t_description,t1.t_duration,t1.t_end_datetime,t1.t_expected_deadline_date,t1.t_team_id,t2.tm_title,t3.pr_title",
                        "tbl_task t1 LEFT JOIN tbl_team t2 ON t1.t_team_id = t2.tm_id LEFT JOIN tbl_project t3 ON t1.t_pr_id = t3.pr_id",
                " AND t1.t_company_user_id=".$_SESSION['company_id']." AND t1.t_type = 'queue' ".$condition1." ");
                ?>
                <?php   
                if(count($queue_task) > 0){
                    $i = 0;
                    $j = 0;
                    ?>
                    <div class="cls<?php echo $i ?> sliderDiv" >
                        <span style="width:20%;float:left;">
                                            <?php
                    foreach($queue_task as $qu){ 
                            $client_name = $dclass->select("cl_company_name","tbl_client"," AND cl_id=".$qu['t_cl_id']);
                            
                            if($i % 5 == 0 && $i != 0 ){
                               echo '</span><span style="width:20%;float:left;">';
                            }
                            if($j % 25 == 0 && $j != 0){
                                echo '</span></div><div class="cls<?php echo $i ?> sliderDiv" ><span style="width:20%;float:left;">';
                            }
                        ?>  
                            <table id="<?php echo $i; ?>"><tr>
                                <td>
                                    <!--div class='fc-event' t_id='<?php echo $qu['t_id']; ?>' t_duration='<?php echo str_replace(' hours','',$qu['t_duration']); ?>' ><?php echo '<b>Title</b>:'.$qu['t_title'].'   <b>Deadline</b>:'.$qu['t_expected_deadline_date'].'   <b>Team</b>:'.$qu['tm_title']; ?></div-->
                                 <div class='fc-event rem<?php echo $qu['t_id']; ?>' t_id='<?php echo $qu['t_id']; ?>' t_duration='<?php echo str_replace(' hours','',$qu['t_duration']); ?>' title="<?php echo $qu['t_title'].'  '.str_replace('hours','hr',$qu['t_duration']).' '.$qu['pr_title'].' '.$client_name[0]['cl_company_name']; ?>" >

                                 <?php echo '<b>'.trunc_string($qu['t_title'],15).'</b>  '.str_replace('hours','hr',$qu['t_duration']).' '.trunc_string($qu['pr_title'],15).' '.$client_name[0]['cl_company_name']; ?></div>
                                </td>
                                </tr>
                        </table>
                        <?php  
                            $i++;
                            $j++;
                        }
                    ?>
                            
                        </span>
                    </div>
                    <?php
                }?>
                </div>
                    <div class="queue-navi">
                        <a id="prev"><img src="<?php echo SITE_URL;?>/images/prev-arrow.png" alt="Prev"></a>
                        <a id="next"><img src="<?php echo SITE_URL;?>/images/next-arrow.png" alt="Next"></a>                    
                    </div>    
                        </div>
                </div>
                    
                </div>
                <?php }else{ ?>
				<div class="full-box queue-task-cls" id="queue-task-box" >&nbsp;
				</div>
				<?php } ?>
                <div class="clr10"></div>
                
                <?php 

                   if($_SESSION['user_type'] == 'company_admin'){

                       if(isset($_REQUEST['tm_id']) && !empty($_REQUEST['tm_id'])){
                            $get_userids = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id = ".$_REQUEST['tm_id']." ");
                            $userlist = $dclass->select("user_id,fname,lname,job_title,user_avatar,r_id","tbl_user"," AND user_status = 'active' AND (user_comp_id = '".$_SESSION['company_id']."' OR  user_comp_id = '0')  
                            AND user_id IN (".$get_userids[0]['uid'].") AND display_status = '1' ORDER BY display_order asc"); 
                       }else{

                            $userlist = $dclass->select("user_id,fname,lname,job_title,user_avatar,r_id","tbl_user"," AND user_status = 'active' AND (user_comp_id = '".$_SESSION['company_id']."' OR user_id = '".$_SESSION['user_id']."') AND display_status = '1' ORDER BY display_order asc "); 
							
                       }
                    
                    
                    }else if($_SESSION['user_type'] == 'manager'){
                        if(isset($_REQUEST['tm_id']) && !empty($_REQUEST['tm_id'])){
                         
                            $get_userids = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id = ".$_REQUEST['tm_id']." ");


        $userlist = $dclass->select("user_id,fname,lname,job_title,user_avatar,r_id","tbl_user"," AND display_status = '1'  AND user_status = 'active' AND (user_comp_id = '".$_SESSION['company_id']."' OR user_id = '".$_SESSION['user_id']."') 
        AND r_id IN (3,4) AND user_id IN (".$get_userids[0]['uid'].")  ORDER BY display_order asc"); 
   }else{
        $userlist = $dclass->select("user_id,fname,lname,job_title,user_avatar,r_id","tbl_user"," AND display_status = '1' AND user_status = 'active' AND (user_comp_id = '".$_SESSION['company_id']."' OR user_id = '".$_SESSION['user_id']."') AND (r_id IN (3,4) AND user_comp_id = '".$_SESSION['company_id']."' )  ORDER BY display_order asc"); 



                       }   
                    }else{
                        $userTeamid = getUserteam($_SESSION['user_id']);
                        $get_userids = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id = ".$userTeamid." ");

                        /*AND (user_id = '".$_SESSION['user_id']."' OR user_comp_id = '".$_SESSION['company_id']."') */
                        $userlist = $dclass->select("user_id,fname,job_title,lname,user_avatar,r_id","tbl_user"," AND user_status = 'active' AND user_id IN (".$get_userids[0]['uid'].") AND display_status = '1' ORDER BY display_order asc"); 
                    
                    }
                   ?>
     
                 <?php 
                 $j = 0;
                 foreach($userlist as $ul){ 
                        if(checkUserteam($ul['user_id']) == 1){
                            continue;
                        }
                    ?>
                <!--input type="text" name="MyminiCalendar<?php echo $ul['user_id']; ?>" id="MyminiCalendar<?php echo $ul['user_id']; ?>"-->
                <div class="full-box" id="main-calender<?php echo $ul['user_id']; ?>" >
                    <?php if($j == 0){
                        $firstclass = 'leftfirst';
                    }else{
                        $firstclass = '';
                    } ?>
                    <?php if($_SESSION['user_id'] == $ul['user_id']){ ?>
                    <div class="calender-left tab-close <?php echo $firstclass ?>" id="user<?php echo $ul['user_id']; ?>" <?php echo $step_3_employee; ?><?php echo $step_10_admin; ?><?php echo $step_10_manager; ?>>
                    <?php }else{ ?>
                    <div class="calender-left tab-close <?php echo $firstclass ?>" id="user<?php echo $ul['user_id']; ?>" <?php echo $step_3_employee; ?>>
                    <?php } ?>    
                        <div class="customdivgray"></div>
                        <div class="customdiv" id="mycustom<?php echo $ul['user_id']; ?>"></div>
                        <div class="calender-click">
                        <a href="#"></a></div>
                        <span class="calender-user" >
                        <?php 
                        if($ul['user_avatar'] != '' ){
			                $user_image = SITE_URL.'upload/user/'.$ul['user_avatar'];
                        }else{ 
                            $user_image = SITE_URL.'images/default_user.png';   
                        } ?> 
                        
                        <img src="<?php echo SITE_URL.'timthumb.php?src='.$user_image.'&h=46&w=46&zc=1&q=100' ?>"  />    
                        </span>
                        <span>
                        <b>
                        <?php 
                        echo trunc_string(trim(ucfirst($ul['fname'].' '.$ul['lname'])),15);
                        ?> 

                        <i title="<?php echo $ul['job_title']; ?>"><?php echo trunc_string(trim($ul['job_title']),23); ?></i>

                         </i> 
                        </b>
                        </span>
                    </div>
                    <div class="calender-right right-calender">
                        <?php /* if($j == 0){ $newcls = "";}else{$newcls = "allCalenderCls";} */
                        $newcls = "allCalenderCls";
                        ?>
                        <div class="calender-right-box <?php echo $newcls; ?>" id='calendar<?php echo $ul['user_id']; ?>'></div>
                    </div>
                    <div class="calender-right " id='calendar_free<?php echo $ul['user_id']; ?>'>
                        <div class="calender-right-box">
                            <div class="hr-box">
                                <span class="free_hours">Hours Free</span>
                                <span class="free_value" id="free_hours_vl<?php echo $ul['user_id']; ?>">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clr10"></div>
                <?php 
                $j++;
            } ?>
            </div>
        </div>
</div>
</section>

<!-- /MAIN CONTENT -->
<!--main content end-->


<div class="modal bs-example-modal-lg" id="new_user_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog task-detail">
    <div class="modal-content task-box">
    <div class="clr"></div>
    </div>
  </div>
</div>


<?php $load->includeother('footer');?>
<?php $dtformat = $dclass->select("*","tbl_dateformate"); ?>
<style type="text/css">
.sliderDiv table div {display: block !important;}
.innerdiv{width: 14.27% !important}
/*.top-tab-main{padding-top:60px !important; }*/
</style>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.scrollTo.js"></script>
<script>

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
    var copy_task_id = '';
    $(document).ready(function(){

    hash = window.location.hash;
    /*if(hash == '#tab-1' || hash == '#tab-2' || hash == '#tab-3' || hash == '#tab-4'){
      $('.task-form-div').show();
      $("html, body").animate({ scrollTop: 0 });

      $('.nav-main li a').css({ "color": "#fff"});
      $('.add_task').css({ "color": "#9cd778"});
      $('.nav-main li a span.notify-icon').css({ "background": "url(images/notify-icon.png)","width":"18px"});
      $('.nav-main li a span.report-icon').css({ "background": "url(images/report-icon.png)","width":"15px"});
      $('.nav-main li a span.add-icon').css({ "background": "url(images/add-icon-hover.png)","width":"11px"});
    }*/
       
        var startDate = new Date('01/01/2012');
        var FromEndDate = new Date();
        var ToEndDate = new Date();
        $(".calender-right-box").click(function(){
            return false;
        });
        $('#external-events .fc-event').each(function(){

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });
        $('#popup_edit_task').validationEngine({validateNonVisibleFields: true,scroll:false});

        <?php 
    $i=0;
    $userday = array();
    $workingDay = $dclass->select("*","tbl_working_day_time"," AND company_user_id =".$_SESSION['company_id']);
    
    if(count($workingDay) > 0){
        $time = explode(':::',$workingDay[0]['working_time']);
        
        $min_time = date("H:i:s", strtotime($time[0]));
        $max_time = date("H:i:s", strtotime($time[1]));
        $work_day_temp = explode(':::',$workingDay[0]['working_days']);
        $work_day = getworkdays($work_day_temp);
        $workingdays = implode(',',$work_day);
        $userday[$ul['user_id']] = $work_day;
        
    }else{
        $workingDay = $dclass->select("*","tbl_working_day_time"," AND type = 'general'");
        $time = explode(':::',$workingDay[0]['working_time']);
        
        $min_time = date("H:i:s", strtotime($time[0]));
        $max_time = date("H:i:s", strtotime($time[1]));

        $work_day_temp = explode(':::',$workingDay[0]['working_days']);
        $work_day = getworkdays($work_day_temp);
        $workingdays = implode(',',$work_day);
        $userday[$ul['user_id']] = $work_day;
    }

    $condition = '';
    if(isset($_REQUEST['pr_id']) && !empty($_REQUEST['pr_id'])){
        /*$prids = implode(',',$_POST['pr_id']);
        $prids = trim($prids,',');*/
        $condition .= " AND t_pr_id =".$_REQUEST['pr_id']." ";
    }
    if(isset($_REQUEST['cl_id']) && !empty($_REQUEST['cl_id']) ){
        /*$clids = implode(',',$_POST['cl_id']);
        $clids = trim($clids,',');*/

        $condition .= " AND t_cl_id =".$_REQUEST['cl_id']." ";
    }
    $getHoliday = $dclass->select("*","tbl_holidays"," AND holi_user_id = '".$_SESSION['company_id']."' ");
    foreach($userlist as $ul){ 
    if(checkUserteam($ul['user_id']) == 1){
            continue;
    }   
    $userTeamid = $dclass->select("*","tbl_team_detail"," AND user_id =".$ul['user_id']);
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
    
        $getTask = $dclass->select("*","tbl_task","  AND t_operator_id = '".$ul['user_id']."' AND t_type='calendar' ".$condition." GROUP BY t_id ");
        
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
                        
                        
                        //$dur = str_replace('hours','',$gt['t_duration'] );
                        $dur = str_replace('hours','',number_format($gt['t_duration'],1));
                        $dur = $dur.' hr';
                    
                        //$title = trunc_string($gt['t_title'],15)." ".$dur;
                        $title = trunc_string($gt['t_title'],10);
                        $desc1=str_replace(array("\r", "\n"), ' ', string_sanitize($gt['t_description']));
                        $prtitle=str_replace(array("\r", "\n"), ' ', string_sanitize($pro_color[0]['pr_title']));
                        
                        $desc = trunc_string($desc1,15);
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
                        
                    $userTask .= "{
                                title: '".$title."',
                                title1: '".$title."',
                                event_id: '".$gt['t_id']."',
                                start: '".$start_datetime."',
                                end: '".$end_datetime."',
                                color: '".$clr."',
                                className: ['".$clrpr."'],
                                dur : '".$dur."',
                                project : '".$prtitle."',
                                client : '".$cl_name[0]['cl_company_name']."',
                                desc : '".$desc."',
                                
                            },";
            }
        }
        foreach($getHoliday as $hl){
            $hl_start_date = date("Y-m-d", strtotime($hl['holi_start_date']));
            $hl_end_date = date("Y-m-d", strtotime($hl['holi_end_date']));
            $hltitle = string_sanitize($hl['holi_title']);
            
                $userTask .= "{
                    title: '".$hltitle."',
                    start: '".$hl_start_date."',
                    end: '".$hl_end_date."',
                    rendering: 'background',
                    className: ['holidaycls']
                },";

                $hl_start_date = $hl_start_date.' 00:00:00';
                $hl_end_date = $hl_end_date.' 23:59:59';

                $userTask .= "{
                    title: '".$hltitle."',
                    start: '".$hl_start_date."',
                    end: '".$hl_end_date."',
                    rendering: 'background',
                    className: ['holidaycls']
                },";
        }
        /*
                    color: '#ff9f89',
        */
        if($showlunch == 'true'){         
             $userTask .= "{
                    start: '".$lunch_start."',
                    end: '".$lunch_end."',
                    rendering: 'background',
                    color: '#ff9f89',
                    className: ['lunchhours']
                },";
            }
        //constraint: 'businessHours'
        $userTask = trim($userTask,',');
       
       if(!isset($_SESSION['def_date'][$ul['user_id']])){
            $_SESSION['def_date'][$ul['user_id']] = date("Y-m-d");
       }
       if(isset($_REQUEST['t_id']) && $_REQUEST['t_id'] != '' && $ul['user_id'] == $_SESSION['user_id']){
           $getDt = $dclass->select('t_start_datetime',"tbl_task"," AND t_id=".$_REQUEST['t_id']);
            
           $dtt = date("Y-m-d",strtotime($getDt[0]['t_start_datetime']));
           $_SESSION['def_date'][$ul['user_id']] = $dtt;
       }

    ?> 

 
 
        $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar({
           <?php //if($i == 0){ ?>
            
            header: {
                /*left: 'title',
                center: 'prev,next today',
                right: 'month,agendaWeek,agendaDay'*/
                left: 'today prev,next',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
         <?php //} ?>   
            height: 'auto',
            aspectRatio: 2,
            defaultDate: '<?php echo $_SESSION['def_date'][$ul['user_id']]; ?>',
            //defaultDate: '<?php echo date("Y-m-d"); ?>',
            defaultView : 'agendaWeek',
            eventLimit: true,
            eventOverlap:true,
            minTime: '<?php echo $min_time; ?>',
            maxTime: '<?php echo $max_time; ?>',
            //businessHours: true, // display business hours
            //hiddenDays:[<?php echo $workingdays?>],
    
    <?php if($_SESSION['user_type'] == 'employee'){ ?>
            selectable: false,
            editable: false,
    <?php }else{ ?>
            selectable: true,
            editable: true,
    <?php } ?> 
            timeFormat:'h(:mm)a',
            allDaySlot:false,
            droppable: true,
            select: function(startDate, endDate, allDay, jsEvent, view){
               view =  $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar('getView');
               var viewmode = view.name;

                var c = moment(startDate);
                var checkstartdate = c.format("d");

                var d = moment(endDate);
                var checkenddate = d.format("d");
                var offdays = '<?php echo $workingdays; ?>';

                arr = offdays.split(',');
                if(viewmode == 'month'){
                    if(arr.indexOf(checkstartdate) != -1){
	                    alert("Please choose a time that is within working hours.");
	                    $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar('unselect');
	                    return false;
	                }
	            }else{
	            	if(arr.indexOf(checkstartdate) != -1 || arr.indexOf(checkenddate) != -1){
	                    alert("Please choose a time that is within working hours.");
	                    $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar('unselect');
	                    return false;
	                }
	            }
                
                var m = moment(startDate);
                var sdate = m.format("YYYY-MM-DD  HH:mm:ss");
                var n = moment(endDate);
                var edate = n.format("YYYY-MM-DD  HH:mm:ss");               

                    // Add Task on empty area of day
                    var user_id = "<?php echo $ul['user_id']; ?>";
                    var task = "popup_add_task";
                    var team_id = "<?php echo $team_id; ?>";
                    $.ajax({
                    type:"post",
                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_calander_task.php",
                    data:{user_id:user_id,task:task,sdate:sdate,edate:edate,viewmode:viewmode,team_id:team_id},
                        success:function(data){  

                            if($.trim(data) == 'not_allow' ){
                                alert("Please choose a time that is within working hours.");
                                $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar('unselect');
                                return false;
                            }else{             
                                $("#new_user_modal").html(data);
                                $('.selectpicker').selectpicker();
                                $("#new_user_modal").modal('show');
                                $('#t_start_time_edit').timepicker({
                                minuteStep: 30,
                                showInputs: false,
                                disableFocus: true,
                                defaultTime: false
                                
                          }).dblclick(function(e){
                             $('.bootstrap-timepicker-widget').remove();
                          }).on('show.timepicker',function(e){
                              $('#save_edit_task').prop('disabled', true);
                          }).on('hide.timepicker', function(e) {
                              var team_id = $('#t_team_id').val();
                               var tsk = 'check_team_time';
                               var tm_value = $(this).val();
                               $.ajax({
                                  type:"post",
                                  url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                                  data:{team_id:team_id,tsk:tsk,tm_value:tm_value},
                                  success:function(data){             
                                    if(data != 1){
                                        if(data == 0){
                                          $('#t_start_time_edit').validationEngine('showPrompt', 'Please choose a time that is within working hours', 'pass');
                                        }else{
                                          $('#t_team_id').validationEngine('showPrompt', 'Please Select Team Before Set Time', 'pass');
                                        }
                                      $('#t_start_time_edit').timepicker('setTime', ''); 

                                    }
                                    $('#save_edit_task').prop('disabled', false);
                                  }
                              });  
                        });

                  $('#t_start_date_edit').datepicker({
                      
                      weekStart: 1,
                      /*startDate: startDate,
                      endDate: ToEndDate,*/
                      format:'dd M yy',
                      autoclose: true
                  }).on('changeDate', function(selected){
                          startDate = new Date(selected.date.valueOf());
                          startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_deadline_date_edit').datepicker('setStartDate', startDate);
                   });

                 $('#t_deadline_date_edit').datepicker({
                          
                          weekStart: 1,
                          //startDate: startDate,
                          //endDate: ToEndDate,
                          format:'dd M yy',
                          autoclose: true
                      }).on('changeDate', function(selected){
                          FromEndDate = new Date(selected.date.valueOf());
                          FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_start_date_edit').datepicker('setEndDate', FromEndDate);
                  }); 
 
                  $('#popup_edit_task').validationEngine({validateNonVisibleFields: true,scroll:false});   
                            }


                            
                        }
                    });
            
            },
            drop: function(date, jsEvent,ui, view){
                var c = moment(date);
                var cdate = c.format("d");
                var offdays = '<?php echo $workingdays; ?>';
                arr = offdays.split(',');
                if(arr.indexOf(cdate) != -1){
                    alert("Please select another day.");
                    //window.location.reload();
                    //return false;
                }
               
                $(this).remove();
                var user_id = "<?php echo $ul['user_id']; ?>";
                var team_id = "<?php echo $team_id; ?>";    
                var tsk = 'edit_task_new';
                var edit_by = 'drop_event';
                var task_id = $(this).attr('t_id');
                var t_duration = $(this).attr('t_duration');
                var m = moment(date);
                var sdate = m.format("YYYY-MM-DD  HH:mm:ss");
               
                /*var n = moment(date);
                var edate = n.format("YYYY-MM-DD  HH:mm:ss");*/
                
                $.ajax({
                type:"post",
                url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                data:{user_id:user_id,tsk:tsk,sdate:sdate,task_id:task_id,team_id:team_id,t_duration:t_duration,edit_by:edit_by},
                beforeSend: function() {
                        $('#calendar'+user_id).block({ 
                                //message: '<img src="<?php echo SITE_URL; ?>/images/loader/loading.gif" width="150px"  />', 
                                message: '<div id="loaderImage" style="margin:0% 50%"></div>', 
                        });
                         new imageLoader(cImageSrc, 'startAnimation()');
                    },    
                    success:function(data){
                      
                    if(data == 'not_allow'){
                        alert("You can not drop an event accross multiple days.");
                        //setTimeout(function(){ window.location.reload(); },1000);
                        window.location.reload(); 
                    }else if(data == 'Allready_assign'){
                        alert("Task already assigned to someone. Please reload your page to get the updated task in Queue.");
                        //setTimeout(function(){ window.location.reload(); },1000); 
                        window.location.reload();  
                    }else if(data == 'task_overlap'){
                        alert("Task is overlapping, please choose another time.");
                        //setTimeout(function(){ window.location.reload(); },1000); 
                        window.location.reload();  
                    }else if(data == 'start_in_lunch'){
                        alert("You can not start task from lunch time while dragging.");
                        //setTimeout(function(){ window.location.reload(); },1000); 
                        window.location.reload();
                    }else{             
                        var wurl =  window.location.href.split('#')[0];
                        wurl += "#main-calender"+user_id;
                        //location.reload();

                        var tsk1 = 'get_user_event';
                        $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,user_id:user_id},
                            dataType: 'json',
                            success:function(data1){             
                                $('#calendar'+user_id).fullCalendar( 'removeEvents' );
                                $('#calendar'+user_id).fullCalendar( 'addEventSource', data1 );  
                                var p = moment(view.start);
                                var gsdate = p.format("YYYY-MM-DD  HH:mm:ss");           
                                var cid = '<?php echo $ul['user_id']; ?>';
                                var team_id = "<?php echo $team_id; ?>";    
                                var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
                                var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
                                var gtsk = 'get_free_hours';
                                var q = moment(view.end);
                                var gedate = q.format("YYYY-MM-DD  HH:mm:ss");
                                var gviewmode = view.name;
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                                    data:{user_id:user_id,tsk:gtsk,viewmode:gviewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:gsdate,edate:gedate},
                                        success:function(data){             
                                            var arr = data.split(':::');
                                            $("#mycustom<?php echo $ul['user_id']; ?>").css("height",arr[1]);
                                            $("#mycustom<?php echo $ul['user_id']; ?>").css("background",arr[2]);
                                            $("#free_hours_vl<?php echo $ul['user_id']; ?>").html(arr[0]);
                                            $(".free_hours").width('74px');
                                            var dw = $(".fc-mon").width() - 2;
                                            $(".fc-content .fc-time span").css("display","none");
                                        }
                                   });
                            }
                        });

                    }
                    $('#calendar'+user_id).unblock();
                     stopAnimation()
                }
               });
                $(".fc-content .fc-time span").css("display","none");
            },
            eventDrop: function(event, delta, revertFunc, jsEvent, ui, view){ 
                var c = moment(event.start);
                var checkstartdate = c.format("d");

                var d = moment(event.end);
                var checkenddate = d.format("d");
                var offdays = '<?php echo $workingdays; ?>';
                arr = offdays.split(',');
                if(arr.indexOf(checkstartdate) != -1 || arr.indexOf(checkenddate) != -1){
                    alert("Please select another day.");
                    revertFunc();
                    return false;
                }

                
               var user_id = "<?php echo $ul['user_id']; ?>";

                var team_id = "<?php echo $team_id; ?>";    
                var tsk = 'edit_task_new';
                var edit_type = 'task_move';
                var event_type = 'event_drop';

                var task_id = event.event_id;
            
                var m = moment(event.start);
                var sdate = m.format("YYYY-MM-DD  HH:mm:ss");

                var n = moment(event.end);
                var edate = n.format("YYYY-MM-DD  HH:mm:ss");


             $.ajax({
                type:"post",
                url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                data:{user_id:user_id,tsk:tsk,sdate:sdate,edate:edate,task_id:task_id,team_id:team_id,edit_type:edit_type,event_type:event_type},
                    beforeSend: function() {
                        $('#calendar'+user_id).block({ 
                                //message: '<img src="<?php echo SITE_URL; ?>/images/loader/loading.gif" width="150px"  />', 
                                message: '<div id="loaderImage" style="margin:0% 50%"></div>', 
                        });
                         new imageLoader(cImageSrc, 'startAnimation()');
                    },
                    success:function(data){
                             
                    if(data == 'not_allow'){
                        alert("You can not drop an event accross multiple days.");
                        revertFunc();
                    }else if(data == 'task_overlap'){
                        alert("Task is overlapping, please choose another time.");
                        revertFunc();
                    }else if(data == 'holiday_overlap'){
                        alert("Please select another day.");
                        revertFunc();
                    }else if(data == 'start_in_lunch'){
                        alert("You can not start task from lunch time while dragging.");
                        revertFunc();
                    }else{
                        
                       var wurl =  window.location.href.split('#')[0];
                       wurl += "#main-calender"+user_id;
                       //location.reload();
                         
                       var tsk1 = 'get_user_event';
                        $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,user_id:user_id},
                            dataType: 'json',
                            success:function(data1){             
                                $('#calendar'+user_id).fullCalendar( 'removeEvents' );
                                $('#calendar'+user_id).fullCalendar( 'addEventSource', data1 );  
                                var p = moment(view.start);
                                var gsdate = p.format("YYYY-MM-DD  HH:mm:ss");           
                                var cid = '<?php echo $ul['user_id']; ?>';
                                var team_id = "<?php echo $team_id; ?>";    
                                var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
                                var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
                                var gtsk = 'get_free_hours';
                                var q = moment(view.end);
                                var gedate = q.format("YYYY-MM-DD  HH:mm:ss");
                                var gviewmode = view.name;
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                                    data:{user_id:user_id,tsk:gtsk,viewmode:gviewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:gsdate,edate:gedate},
                                        success:function(data){             
                                            var arr = data.split(':::');
                                            $("#mycustom<?php echo $ul['user_id']; ?>").css("height",arr[1]);
                                            $("#mycustom<?php echo $ul['user_id']; ?>").css("background",arr[2]);
                                            $("#free_hours_vl<?php echo $ul['user_id']; ?>").html(arr[0]);
                                            $(".free_hours").width('74px');
                                            var dw = $(".fc-mon").width() - 2;
                                            $(".fc-content .fc-time span").css("display","none");
                                        }
                                   });    

                            }
                        });
                        
                    }
                    $('#calendar'+user_id).unblock();
                    stopAnimation(); 
                }
               });

             $(".fc-content .fc-time span").css("display","none");
            },
            eventDragStop: function(event,jsEvent){
                //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                var user_id = "<?php echo $ul['user_id']; ?>";
                var trashEl = jQuery('#calendar'+user_id);
                var ofs = trashEl.offset();

                var x1 = ofs.left;
                var x2 = ofs.left + trashEl.outerWidth(true);
                var y1 = ofs.top;
                var y2 = ofs.top + trashEl.outerHeight(true);
                
                if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 &&
                    jsEvent.pageY>= y1 && jsEvent.pageY <= y2) {
                }else{
                    /* alert("Please edit the task through \"Edit Task\" popup to change the assignee of the task.");*/
                     alert("Please use the \"Edit Task\" popup to assign this task to someone else.");
                }
            },
            eventResize: function(event, delta, revertFunc, jsEvent, ui, view){ 
                var c = moment(event.start);
                var checkstartdate = c.format("d");

                var d = moment(event.end);
                var checkenddate = d.format("d");
                var offdays = '<?php echo $workingdays; ?>';
                arr = offdays.split(',');
                if(arr.indexOf(checkstartdate) != -1 || arr.indexOf(checkenddate) != -1){
                    alert("Please select another day.");
                    revertFunc();
                    return false;
                }
                var cm = moment(event.start);
                var cksdate = cm.format("YYYY-MM-DD");
                
                var cn = moment(event.end);
                var ckedate = cn.format("YYYY-MM-DD");
                if(cksdate != ckedate){
                    alert("You can not drop an event accross multiple days.");
                    revertFunc();
                    return false;
                }

                var user_id = "<?php echo $ul['user_id']; ?>";
                var team_id = "<?php echo $team_id; ?>";  
                var tsk = 'edit_task_new';
                var task_id = event.event_id;
                var edit_type = 'task_move';
                var m = moment(event.start);
                var sdate = m.format("YYYY-MM-DD  HH:mm:ss");

                var n = moment(event.end);
                var edate = n.format("YYYY-MM-DD  HH:mm:ss");
                

             $.ajax({
                type:"post",
                url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                data:{user_id:user_id,tsk:tsk,sdate:sdate,edate:edate,task_id:task_id,team_id:team_id,edit_type:edit_type},
                   
                    beforeSend: function() {
                        $('#calendar'+user_id).block({ 
                                //message: '<img src="<?php echo SITE_URL; ?>/images/loader/loading.gif" width="150px"  />', 
                                message: '<div id="loaderImage" style="margin:0% 50%"></div>', 
                        });
                         new imageLoader(cImageSrc, 'startAnimation()');
                    },
                    success:function(data){
                                
                    if(data == 'not_allow'){
                        alert("You can not drop an event accross multiple days.");
                        revertFunc();
                    }else if(data == 'task_overlap'){
                        alert("Task is overlapping, please choose another time.");
                        revertFunc();
                    }else if(data == 'holiday_overlap'){
                        alert("Please select another day.");
                        revertFunc();
                    }else{
                     
                       var wurl =  window.location.href.split('#')[0];
                       wurl += "#main-calender"+user_id;
                       //location.reload();
                        /*var tsk1 = 'get_task_title';
                        $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,task_id:task_id},
                            success:function(data1){             
                                var str = '<div class="fc-content"></div><div class="fc-bg"></div><div class="fc-resizer"></div><strong>Replace title</strong>7 hr<br>Evil Intas<br>Intas Pharma<br>7 hr task<br>'; 
                                $("#"+task_id+" .eve-title").html(data1);
                                $('#calendar'+user_id).fullCalendar('refresh');
                            }
                        });*/
                        var tsk1 = 'get_user_event';
                        $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,user_id:user_id},
                            dataType: 'json',
                            success:function(data1){             
                                $('#calendar'+user_id).fullCalendar( 'removeEvents' );
                                $('#calendar'+user_id).fullCalendar( 'addEventSource', data1 );
                                
                                var p = moment(view.start);
                                var gsdate = p.format("YYYY-MM-DD  HH:mm:ss");           
                                var cid = '<?php echo $ul['user_id']; ?>';
                                var team_id = "<?php echo $team_id; ?>";    
                                var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
                                var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
                                var gtsk = 'get_free_hours';
                                var q = moment(view.end);
                                var gedate = q.format("YYYY-MM-DD  HH:mm:ss");
                                var gviewmode = view.name;
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                                    data:{user_id:user_id,tsk:gtsk,viewmode:gviewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:gsdate,edate:gedate},
                                        success:function(data){             
                                            var arr = data.split(':::');
                                            $("#mycustom<?php echo $ul['user_id']; ?>").css("height",arr[1]);
                                            $("#mycustom<?php echo $ul['user_id']; ?>").css("background",arr[2]);
                                            $("#free_hours_vl<?php echo $ul['user_id']; ?>").html(arr[0]);
                                            $(".free_hours").width('74px');
                                            var dw = $(".fc-mon").width() - 2;
                                            $(".fc-content .fc-time span").css("display","none");
                                        }
                                   });
                                

                            }
                        });

                             
                                

                        
                        
                    }
                     $('#calendar'+user_id).unblock();
                    stopAnimation();
                }
               });
             $(".fc-content .fc-time span").css("display","none");
            },
            eventClick: function(calEvent, jsEvent, view){

                var user_id = "<?php echo $ul['user_id']; ?>";
                var team_id = "<?php echo $team_id; ?>";    
                var task_id = calEvent.event_id;
            
                var m = moment(calEvent.start);
                var sdate = m.format("YYYY-MM-DD  HH:mm:ss");

                var n = moment(calEvent.end);
                var edate = n.format("YYYY-MM-DD  HH:mm:ss");
                var task = "popup_edit_task";
                var edit_type = 'task_edit';
                $.ajax({
                type:"post",
                url:"<?php echo SITE_URL; ?>ajax_call/ajax_calander_task.php",
                data:{user_id:user_id,sdate:sdate,edate:edate,task_id:task_id,team_id:team_id,task:task,edit_type:edit_type},
                    success:function(data){   
                        $("#new_user_modal").html(data);
                        $('.selectpicker').selectpicker();
                        $("#new_user_modal").modal('show');


                 $('#t_start_time_edit').timepicker({
                            minuteStep: 30,
                            showInputs: false,
                            disableFocus: true,
                            defaultTime: false
                          }).on('show.timepicker',function(e){
                              $('#save_edit_task').prop('disabled', true);
                          }).on('hide.timepicker', function(e){
                               var team_id = $('#t_team_id').val();
                               var tsk = 'check_team_time';
                               var tm_value = $(this).val();
                               $.ajax({
                                  type:"post",
                                  url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                                  data:{team_id:team_id,tsk:tsk,tm_value:tm_value},
                                  success:function(data){             
                                    if(data != 1){
                                        if(data == 0){
                                          $('#t_start_time_edit').validationEngine('showPrompt', 'Please choose a time that is within working hours', 'pass');
                                        }else{
                                          $('#t_team_id').validationEngine('showPrompt', 'Please Select Team Before Set Time', 'pass');
                                        }
                                      $('#t_start_time_edit').timepicker('setTime', '');              
                                    }
                                    $('#save_edit_task').prop('disabled', false);
                                  }
                              });  
                        });
                 
                 $('#t_start_date_edit').datepicker({
                      
                      weekStart: 1,
                      startDate: '01/01/2012',
                      format:'dd M yy',
                      autoclose: true
                  }).on('changeDate', function(selected){
                          startDate = new Date(selected.date.valueOf());
                          startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_deadline_date_edit').datepicker('setStartDate', startDate);
                   });

                 $('#t_deadline_date_edit').datepicker({
                          
                          weekStart: 1,
                          startDate: startDate,
                          //endDate: ToEndDate,
                          format:'dd M yy',
                          autoclose: true
                      }).on('changeDate', function(selected){
                          FromEndDate = new Date(selected.date.valueOf());
                          FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_start_date_edit').datepicker('setEndDate', FromEndDate);
                  }); 


                           

                 $('#popup_edit_task').validationEngine({validateNonVisibleFields: true,scroll:false});

                    }
                });
                $(".fc-content .fc-time span").css("display","none");
            },
            viewRender: function(view, element){
            var m = moment(view.start);
            var sdate = m.format("YYYY-MM-DD  HH:mm:ss");           
            var cid = '<?php echo $ul['user_id']; ?>';
            var user_id = "<?php echo $ul['user_id']; ?>";
            var team_id = "<?php echo $team_id; ?>";    
            var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
            var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
            var tsk = 'get_free_hours';
            //var sdate = new Date(view.start);
            //var edate = new Date(view.end);
            var n = moment(view.end);
            var edate = n.format("YYYY-MM-DD  HH:mm:ss");
            var viewmode = view.name;
            $.ajax({
                type:"post",
                url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                data:{user_id:user_id,tsk:tsk,viewmode:viewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:sdate,edate:edate},
                    success:function(data){             
                        var arr = data.split(':::');
                        $("#mycustom<?php echo $ul['user_id']; ?>").css("height",arr[1]);
                        $("#mycustom<?php echo $ul['user_id']; ?>").css("background",arr[2]);
                        $("#free_hours_vl<?php echo $ul['user_id']; ?>").html(arr[0]);
                        $(".free_hours").width('74px');
                        var dw = $(".fc-mon").width() - 2;
                        $(".fc-content .fc-time span").css("display","none");
                    }
               });

                if(view.name == 'month'){
                    //fc-agendaWeek-view
                    var ght = $("#calendar"+<?php echo $ul['user_id']; ?>+" .fc-month-view").height()+98;
                    $("#user"+<?php echo $ul['user_id']; ?>).css("height",ght);
                    $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","295px");
                
                }else if(view.name == 'agendaDay'){
                    //fc-agendaDay-view
                    var ght = $("#calendar"+<?php echo $ul['user_id']; ?>+" .fc-agendaDay-view").height()+98;
                        $("#user"+<?php echo $ul['user_id']; ?>).css("height",ght);
                        $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","150px");
                
                }else{
                    //fc-agendaWeek-view
                    var ght = $("#calendar"+<?php echo $ul['user_id']; ?>+" .fc-agendaWeek-view").height()+98;
                    if($("#user"+<?php echo $ul['user_id']; ?>).hasClass("tab-open") == true){
                        $("#user"+<?php echo $ul['user_id']; ?>).css("height",ght);
                         $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","150px");
                    }else{
                        $("#user"+<?php echo $ul['user_id']; ?>).css("height","52px");
                         $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","10px");
                    }                   
                
                }
            },
            eventRender: function (event, element,view){
              
              if(view.name != 'month'){
                if(event.title1 != undefined){
                    element.find(".fc-title").remove();
                    element.find(".fc-time").remove();
                    var new_description =  '<span class="eve-title"><strong>'+event.title1+'</strong> ' + event.dur + '</span><br/><span class="eve-desc">'
                        + event.project + '<br/>'
                        + event.client + '<br/>'
                        + event.desc + '</span><br/>';
                       
                    element.append(new_description); 
                } 
              }else if(view.name == 'month'){
                  if(event.title1 != undefined){
                    element.find(".fc-title").remove();
                    element.find(".fc-time").remove();
                    var new_description =  '<span class="eve-title"><strong>'+event.title1+'</strong> ' + event.dur + '</span><br/><span class="eve-desc"></span><br/>';
                       
                    element.append(new_description); 
                }  
              }

                var originalClass = element[0].className;

                
                if(originalClass == 'fc-bgevent holidaycls'){
                    element[0].id = event.title;
                }else{
                    element[0].className = originalClass + ' copytask';
                    element[0].id = event.event_id;    
                }

            },
            events: [<?php echo $userTask; ?>],
        });

        $('<span class="fc-button-datepicker" id="MyminiCalendar<?php echo $ul['user_id']; ?>">'+'<img src="<?php echo SITE_URL ?>images/date-icon.png"></span>')
.prependTo('#calendar<?php echo $ul['user_id']; ?> .fc-left');


        $('#MyminiCalendar<?php echo $ul['user_id']; ?>').datepicker({"format":'dd M yy',}).on('changeDate', function (ev) {
               // $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar('gotoDate',ev.date);
                $(".allCalenderCls").fullCalendar('gotoDate',ev.date);
                var view = $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar('getView');
                if (view.name == 'agendaWeek'){
                    //$('#calendar<?php echo $ul['user_id']; ?>').fullCalendar( 'changeView', 'agendaWeek' );
                    $(".allCalenderCls").fullCalendar('changeView', 'agendaWeek');
                }else if(view.name == 'agendaDay'){
                   // $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar( 'changeView', 'agendaDay' );
                    $(".allCalenderCls").fullCalendar('changeView', 'agendaDay');
                }else{
                   // $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar( 'changeView', 'month' );
                    $(".allCalenderCls").fullCalendar('changeView', 'month');
                }
                $(this).datepicker('hide');
        });

        new jQueryCollapse($("#main-calender<?php echo $ul['user_id']; ?>"),{
            open: function() {
                 this.slideDown(150);

                $("#user"+<?php echo $ul['user_id']; ?>).addClass("tab-open");
                $("#user"+<?php echo $ul['user_id']; ?>).removeClass("tab-close");

                var view = $('#calendar<?php echo $ul['user_id']; ?>').fullCalendar('getView');
                if(view.name == 'month'){
                    var ght = $("#calendar"+<?php echo $ul['user_id']; ?>+" .fc-month-view").height()+98;

                    $("#user"+<?php echo $ul['user_id']; ?>).css("height",ght+'px');
                    $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","295px");
                }else if(view.name == 'agendaDay'){
                    var ght = $("#calendar"+<?php echo $ul['user_id']; ?>+" .fc-agendaDay-view").height()+98;
                        $("#user"+<?php echo $ul['user_id']; ?>).css("height",ght+'px');
                        $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","150px");
                }else{
                    var ght = $("#calendar"+<?php echo $ul['user_id']; ?>+" .fc-agendaWeek-view").height()+98;
                    
                    if($("#user"+<?php echo $ul['user_id']; ?>).hasClass("tab-open") == true){
                        $("#user"+<?php echo $ul['user_id']; ?>).css("height",ght+'px');
                         $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","150px");
                    }else{
                        $("#user"+<?php echo $ul['user_id']; ?>).css("height","52px");
                         $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","10px");
                    }                   
                }   


                 
                 

          },
          close: function() {
                this.slideUp(150);
                $("#user"+<?php echo $ul['user_id']; ?>).css("height","52px");
                $("#user"+<?php echo $ul['user_id']; ?>).css("padding-top","0px"); 
                $("#user"+<?php echo $ul['user_id']; ?>).removeClass("tab-open");
                $("#user"+<?php echo $ul['user_id']; ?>).addClass("tab-close");

              //$("#calendar_free"+<?php echo $ul['user_id']; ?>).removeClass("hide");
                
          }
        });
        $("#main-calender<?php echo $ul['user_id']; ?>").trigger("open");
       
        <?php if($i == 0){ ?>
            $(".fc-toolbar").addClass("commonHeader");
        <?php } ?>    

    <?php $i++; ?>
    <?php } ?>

    new jQueryCollapse($("#queue-task-box"),{
            open: function() {
                 this.slideDown(150);
                 $("#queue_tsk").addClass("tab-open");
                 $("#queue_tsk").removeClass("tab-close");
          },
          close: function() {
                this.slideUp(150);
                $("#queue_tsk").removeClass("tab-open");
                $("#queue_tsk").addClass("tab-close");
          }
        });
            
    $(".divs div").each(function(e){
             if(e != 0) 
                $(this).hide();
             });
    $("#next").click(function(){
        if ($(".divs div:visible").next().length != 0){

            $(".divs div:visible").next().show('slide',{direction: 'left'}, 1000);
            $(".divs div:visible").prev().hide();

            if ($('#prev').css('display') == 'none') {
                $("#prev").show();
            }

            if($(".divs div:visible").next().length == 0){
                $("#next").hide();   
            }

        }else {
           /* $(".divs div:visible").hide('slide', {direction: 'left'}, 1000);
            $(".divs div:first").show('slide', {direction: 'left'}, 1000);*/
        }
        return false;
    });
    $("#prev").click(function(){
                    if ($(".divs div:visible").prev().length != 0){
                        $(".divs div:visible").prev().show('slide', {direction: 'left'},1000);
                        $(".divs div:visible").next().hide();
                        if ($('#next').css('display') == 'none') {
                            $("#next").show();
                        }

                        if($(".divs div:visible").prev().length == 0){
                            $("#prev").hide();   
                        }

                    } else {
                        /*$(".divs div:visible").hide('slide', {direction: 'left'}, 1000);
                        $(".divs div:last").show('slide', {direction: 'left'}, 1000);*/
                    }
                    return false;
             });
    // Cancel task and popup on cancel from edit task in popup
    $('#new_user_modal').on('click','#cancel_edit_task', function(){
        $("#new_user_modal").modal('hide');
    });
    // Save task on save from edit task in popup
    //$('#popup_edit_task').validationEngine({validateNonVisibleFields: true,scroll:false});
    $('#new_user_modal').on('click','#save_edit_task',function(){
      
      var validateAction = $('#popup_edit_task').validationEngine('validate'); 
       
      if(validateAction  ==  true  ||   validateAction  ==  'true' ||  validateAction ==  'TRUE'){
        var drvl = $("#t_duration_edit").val();
        if(drvl > 99){
            if(!confirm("Your task is more then 99 hrs. Are you sure you want to proceed?")){
                return false;       
            }
        }
        var tsk = 'task_save';
        $(this).attr('disabled','disabled');
        //$(this).hide();
        var operator_id = $("#t_operatorss").val();
        var current_user_id = $("#current_user_id").val();
        
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
              data:$("#popup_edit_task").serialize() + "&tsk="+tsk,
              beforeSend: function() {
                $('.modal-content').block({ 
                    message: '<div id="loaderImage" style="margin:-17% 35%"></div>', 
                });
                new imageLoader(cImageSrc, 'startAnimation()');
              },
              success:function(data){             
                  if(data.trim() == 'Deadline_date'){
                    alert("here is no available time slots for this task, it will be added to the queue.")
                  }else if(data.trim() == 'task_overlap'){
                      alert("Task is overlapping, please choose another time.");
                      stopAnimation();
                      $('.modal-content').unblock();
                      return false;

                  }

                    
                  $(".taskmsgDiv").removeClass("hide");
                  $(".taskmsgDiv").text("Task successfully save.");  
                  $(".taskmsgDiv").fadeTo(2000, 500).slideUp(500, function(){
                    //$(".taskmsgDiv").alert('close');
                    $(".taskmsgDiv").text("");
                    $(".taskmsgDiv").addClass("hide");
                  });

                  stopAnimation();
                  $('.modal-content').unblock();

                  $("#new_user_modal").modal('hide');
                  
                  var wurl =  window.location.href.split('#')[0];
                  wurl += "#main-calender"+operator_id;
                  //location.reload();  
                 
                  var tsk1 = 'get_user_event';
                  $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,user_id:operator_id},
                            dataType: 'json',
                            success:function(data1){             
                                $('#calendar'+operator_id).fullCalendar( 'removeEvents' );
                                $('#calendar'+operator_id).fullCalendar( 'addEventSource', data1 );     
                                // code for refresh free hours
                                var view = $('#calendar'+operator_id).fullCalendar('getView');
                                var p = moment(view.start);
                                var gsdate = p.format("YYYY-MM-DD  HH:mm:ss");           
                                var team_id = "<?php echo $team_id; ?>";    
                                var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
                                var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
                                var gtsk = 'get_free_hours';
                                var q = moment(view.end);
                                var gedate = q.format("YYYY-MM-DD  HH:mm:ss");
                                var gviewmode = view.name;
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                                    data:{user_id:operator_id,tsk:gtsk,viewmode:gviewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:gsdate,edate:gedate},
                                        success:function(data){             
                                            var arr = data.split(':::');
                                            $("#mycustom"+operator_id).css("height",arr[1]);
                                            $("#mycustom"+operator_id).css("background",arr[2]);
                                            $("#free_hours_vl"+operator_id).html(arr[0]);
                                            $(".free_hours").width('74px');
                                            var dw = $(".fc-mon").width() - 2;
                                            $(".fc-content .fc-time span").css("display","none");
                                        }
                                   });
                            }
                        });
                  if(current_user_id != operator_id){
                    var tsk1 = 'get_user_event';
                    $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,user_id:current_user_id},
                            dataType: 'json',
                            success:function(data1){             
                                $('#calendar'+current_user_id).fullCalendar( 'removeEvents' );
                                $('#calendar'+current_user_id).fullCalendar( 'addEventSource', data1 );     
                                // code for refresh free hours
                                var view = $('#calendar'+current_user_id).fullCalendar('getView');
                                var p = moment(view.start);
                                var gsdate = p.format("YYYY-MM-DD  HH:mm:ss");           
                                var team_id = "<?php echo $team_id; ?>";    
                                var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
                                var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
                                var gtsk = 'get_free_hours';
                                var q = moment(view.end);
                                var gedate = q.format("YYYY-MM-DD  HH:mm:ss");
                                var gviewmode = view.name;
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                                    data:{user_id:current_user_id,tsk:gtsk,viewmode:gviewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:gsdate,edate:gedate},
                                        success:function(data){             
                                            var arr = data.split(':::');
                                            $("#mycustom"+current_user_id).css("height",arr[1]);
                                            $("#mycustom"+current_user_id).css("background",arr[2]);
                                            $("#free_hours_vl"+current_user_id).html(arr[0]);
                                            $(".free_hours").width('74px');
                                            var dw = $(".fc-mon").width() - 2;
                                            $(".fc-content .fc-time span").css("display","none");
                                        }
                                   });
                            }
                        });
                  }  
              }
            });
        
      }

        
    });
    $('#new_user_modal').on('change','#t_team_id', function(){
      var tsk = 'get_operator';
      var team_id = $(this).val();
      var ctype = $('#ctype').val();

       $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{team_id:team_id,tsk:tsk,ctype:ctype},
              success:function(data){             
                $(".operators").html(data);
                $('.selectpicker').selectpicker();
              }
           });
    });
    // Get all project of client on clinet dropdown change in edit task popup
    $('#new_user_modal').on('change','#client_id_edit', function(){

      var client_id = $(this).val();
      var tsk = 'get_project_list';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{client_id:client_id,tsk:tsk},
                success:function(data){     
                $(".project_div").html(data);
                $('.projList').selectpicker();
          }
      });
      
    });

    $(".fc-content .fc-time span").css("display","none");
    $(".fc-axis").removeClass('pastetask');     
    $(".lunchhours").removeClass('copytask');
    $(".holidaycls").removeClass('copytask');     
    
    //code for commonr header
    $(".fc-month-button").click(function(){
      $(".allCalenderCls").fullCalendar( 'changeView', 'month');  
    });
    $(".fc-agendaWeek-button").click(function(){
      $(".allCalenderCls").fullCalendar( 'changeView', 'agendaWeek');  
    });
    $(".fc-agendaDay-button").click(function(){
      $(".allCalenderCls").fullCalendar( 'changeView', 'agendaDay');  
    });
    $(".fc-next-button").click(function(){
      $(".allCalenderCls").fullCalendar('next');  
    });
    $(".fc-prev-button").click(function(){
      $(".allCalenderCls").fullCalendar('prev');  
    });
    $(".fc-today-button").click(function(){
      $(".allCalenderCls").fullCalendar('today');  
    });
    
    //code for commonr header end

    $('.holidaycls').each(function() {
        var strvl = $(this).attr('id');
        $(this).html(strvl);
    });

    <?php if(isset($_SESSION['view_task']) && $_SESSION['view_task'] != ''){
            
     ?>
        var ud = '<?php  echo $_SESSION["user_id"] ?>';
        var wurl = '<?php echo SITE_URL; ?>view';
        //alert(wurl);
        var myhash = 'main-calender'+ud;
       // location.href=wurl+myhash;

        setTimeout(function() {  
            window.location.hash=myhash;
        },1000);



                 var user_id = '<?php  echo $_SESSION["user_id"] ?>';
                    
                var task_id = "<?php echo $_SESSION['view_task'] ?>";
                var task = "popup_edit_task";
                var edit_type = 'task_edit';
                $.ajax({
                type:"post",
                url:"<?php echo SITE_URL; ?>ajax_call/ajax_calander_task.php",
                data:{user_id:user_id,task_id:task_id,task:task,edit_type:edit_type},
                    success:function(data){   
                        $("#new_user_modal").html(data);
                        $('.selectpicker').selectpicker();
                        $("#new_user_modal").modal('show');


                        $('#t_start_time_edit').timepicker({
                            minuteStep: 30,
                            showInputs: false,
                            disableFocus: true,
                            defaultTime: false
                          }).on('show.timepicker',function(e){
                              $('#save_edit_task').prop('disabled', true);
                          }).on('hide.timepicker', function(e) {
                              var team_id = $('#t_team_id').val();
                               var tsk = 'check_team_time';
                               var tm_value = $(this).val();
                               $.ajax({
                                  type:"post",
                                  url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                                  data:{team_id:team_id,tsk:tsk,tm_value:tm_value},
                                  success:function(data){             
                                    if(data != 1){
                                        if(data == 0){
                                          $('#t_start_time_edit').validationEngine('showPrompt', 'Please choose a time that is within working hours', 'pass');
                                        }else{
                                          $('#t_team_id').validationEngine('showPrompt', 'Please Select Team Before Set Time', 'pass');
                                        }
                                      $('#t_start_time_edit').timepicker('setTime', '');   
                                                 
                                    }
                                    $('#save_edit_task').prop('disabled', false); 
                                  }
                              });  
                        });
                        $('#t_start_date_edit').datepicker({
                              
                              weekStart: 1,
                              startDate: '01/01/2012',
                              format:'dd M yy',
                              autoclose: true
                          }).on('changeDate', function(selected){
                                  startDate = new Date(selected.date.valueOf());
                                  startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                                  $('#t_deadline_date_edit').datepicker('setStartDate', startDate);
                           }); 
                        $('#t_deadline_date_edit').datepicker({
                                  
                                  weekStart: 1,
                                  startDate: startDate,
                                  //endDate: ToEndDate,
                                 format:'dd M yy',
                                  autoclose: true
                              }).on('changeDate', function(selected){
                                  FromEndDate = new Date(selected.date.valueOf());
                                  FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                                  $('#t_start_date_edit').datepicker('setEndDate', FromEndDate);
                                });
                        $('#popup_edit_task').validationEngine({validateNonVisibleFields: true,scroll:false});

                    }
                });


    <?php 
    $_SESSION['view_task']='';
     } ?>
   /* hash1 = window.location.hash;
    if(hash1 != ''){
        $.scrollTo($(hash1), {                  
                offset:{top:-40},
                duration: 1400,
                easing: 'easeOutSine',          
        });
    }*/
    <?php if($_SESSION['user_type'] != 'employee'){ ?>
    // copy and paste task
    $.contextMenu({
        
        selector: '.copytask', 
        //trigger: 'none',
        callback: function(key, options) {
            
            copy_task_id = $(this).attr('id');
            var task = "copy_task";
                    
            $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_calander_task.php",
            data:{task:task,copy_task_id:copy_task_id},
                success:function(data){   
                    $("#new_user_modal").html(data);
                    $('.selectpicker').selectpicker();
                    $("#new_user_modal").modal('show');


            $('#copy_start_time').timepicker({
                    minuteStep: 30,
                    showInputs: false,
                    disableFocus: true,
                    defaultTime: false
                    
              }).dblclick(function (e) {
                 $('.bootstrap-timepicker-widget').remove();
              }).on('hide.timepicker', function(e) {
                  var team_id = $('#copy_team_id').val();
                   var tsk = 'check_team_time';
                   var tm_value = $(this).val();
                   $.ajax({
                      type:"post",
                      url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                      data:{team_id:team_id,tsk:tsk,tm_value:tm_value},
                      success:function(data){             
                        if(data != 1){
                            if(data == 0){
                              $('#copy_start_time').validationEngine('showPrompt', 'Please choose a time that is within working hours', 'pass');
                            }
                          $('#copy_start_time').timepicker('setTime', '');              
                        }
                      }
                  });  
            });;

            $('#copy_start_date').datepicker({
                  
                  weekStart: 1,
                  startDate: '01/01/2012',
                  format:'dd M yy',
                  autoclose: true,
              }); 
              
            $('#copy_task').validationEngine({validateNonVisibleFields: true,scroll:false});
                }
            });
            
        },
        items: {
            "copy": {name: "Copy", icon: "copy"},
        }
    });
    <?php } ?>            
                
    // save task copy and paste
    $('#copy_task').validationEngine({validateNonVisibleFields: true,scroll:false});
    $('#new_user_modal').on('click','#paste_task', function(){
      
      var validateAction = $('#copy_task').validationEngine('validate'); 
      if(validateAction  ==  true  ||   validateAction  ==  'true' ||  validateAction ==  'TRUE'){
         var tsk = 'paste_task';
         //$(this).hide();
         var operator_id = $("#operator_id").val();
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
              data:$("#copy_task").serialize() + "&tsk="+tsk,
              beforeSend: function() {
                $('.modal-content').block({ 
                    message: '<div id="loaderImage" style="margin:-17% 35%"></div>', 
                });
                new imageLoader(cImageSrc, 'startAnimation()');
              },
              success:function(data){  
                  stopAnimation();
                  $('.modal-content').unblock();
                if(data == 'not_allow'){
                        alert("You can not drop an event accross multiple days.");
                    }else if(data == 'task_overlap'){
                        alert("Task is overlapping, please choose another time..");
                    }else if(data == 'holiday_overlap'){
                        alert("Please select another day.");
                    }else if(data == 'start_in_lunch'){
                        alert("You can not start task from lunch time while coping task.");
                    }else{
                        $("#new_user_modal").modal('hide');
                        //setTimeout(function(){ window.location.reload(); },1000);            
                       // window.location.reload(); 
                       var tsk1 = 'get_user_event';
                        $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,user_id:operator_id},
                            dataType: 'json',
                            success:function(data1){             
                                $('#calendar'+operator_id).fullCalendar( 'removeEvents' );
                                $('#calendar'+operator_id).fullCalendar( 'addEventSource', data1 );     
                               var view = $('#calendar'+operator_id).fullCalendar('getView');
                               var p = moment(view.start);
                                var gsdate = p.format("YYYY-MM-DD  HH:mm:ss");           
                                var team_id = "<?php echo $team_id; ?>";    
                                var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
                                var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
                                var gtsk = 'get_free_hours';
                                var q = moment(view.end);
                                var gedate = q.format("YYYY-MM-DD  HH:mm:ss");
                                var gviewmode = view.name;
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                                    data:{user_id:operator_id,tsk:gtsk,viewmode:gviewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:gsdate,edate:gedate},
                                        success:function(data){             
                                            var arr = data.split(':::');
                                            $("#mycustom"+operator_id).css("height",arr[1]);
                                            $("#mycustom"+operator_id).css("background",arr[2]);
                                            $("#free_hours_vl"+operator_id).html(arr[0]);
                                            $(".free_hours").width('74px');
                                            var dw = $(".fc-mon").width() - 2;
                                            $(".fc-content .fc-time span").css("display","none");
                                        }
                                   });
                            }
                        });
                    }
              }
             });
      }


        
    });
    
        /*$("#calender-main").click(function(){
            if($(".desing-studio-tag").hasClass("expanded")){
                $(".desing-studio-tag").removeClass("expanded");
                $(".desing-studio-tag").addClass("collapsed");
                $(".desing-studio-tag").addClass("collapsed");
            }
        });*/
            
    $(document).click(function (event) {
        var clickover = $(event.target);
        var _opened = $(".desing-studio-tag").hasClass("expanded");
        if ( _opened === true && !clickover.hasClass("expanded") && !clickover.parent().parent().hasClass("chklink") ) {
            //$("button.navbar-toggle").click();
                $(".desing-studio-tag").removeClass("expanded");
                $(".desing-studio-tag").addClass("collapsed");
                $(".collapsible-menu li ul").slideUp('slow');
            
        }
    });
    $('#external-events').on('click',' .fc-event',function(){
           
            var task_id = $(this).attr('t_id');
            var task = "popup_edit_task_queue";
            var ttype = 'queue';
            //var edit_type = 'task_edit';
            $.ajax({
                type:"post",
                url:"<?php echo SITE_URL; ?>ajax_call/ajax_calander_task.php",
                data:{task_id:task_id,task:task,ttype:ttype},
                    success:function(data){   
                            $("#new_user_modal").html(data);
                            $('.selectpicker').selectpicker();
                            $("#new_user_modal").modal('show');
                            
                            $('#t_deadline_date_edit').datepicker({
                                  
                                  weekStart: 1,
                                  //startDate: new Date(),
                                  format:'dd M yy',
                                  autoclose: true,
                              }).on('changeDate', function(selected){
                                  FromEndDate = new Date(selected.date.valueOf());
                                  FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                                  $('#t_start_date_edit').datepicker('setEndDate', FromEndDate);
                                });
                                $('#popup_edit_task').validationEngine({validateNonVisibleFields: true,scroll:false});   
                            
                     }
                 });
             });
    
      setInterval(function(){
        var tsk = 'check_update';
        $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{tsk:tsk},
              success:function(data){             
                   if(parseInt(data) > 0){
                        $(".taskmsgDiv").removeClass("hide");
                        $(".taskmsgDiv").text("Someone from your team has changed task/s in the calendar. Please reload/refresh the page to see the latest changes.");  
                        /*$(".taskmsgDiv").fadeTo(3000, 3000).slideUp(500, function(){
                            $(".taskmsgDiv").text("");
                            $(".taskmsgDiv").addClass("hide"); 
                        });*/
                   }
              }
        });
      },10000);
    
    });
</script>
<?php 
    $dayArr = array("sun","mon","tue","wed","thu","fri","sat");
    //foreach($userday as $key=>$work_day){
        foreach($work_day as $off){
            echo '<style>
            td.fc-'.$dayArr[$off].' {
                    background:url("'.SITE_URL.'/images/back_pattern.png") repeat scroll 0 0 #FFFFFF !important;

            }
                  </style>';
        }
    //}  
    
?>
<!--td.fc-sun {
      background:url("images/back_pattern.png");
    }-->
<style>
  .calenderDiv{
    width:80%;
    margin: 0 auto;
  }
  .imgDiv{
    border-radius:50%;
    width:50%;
  }
    .fc-sun {
     /*background-image:url(img/sunday_bg.jpg);*/
    }
  .freehours{border: 1px solid #ccc;min-height: 30px;line-height: 30px;padding: 0 !important;margin: 0 !important}
  .col-lg-1-5{width: 14.2%;float: left;}
  .fc table{font-size: 10px !important;}
  .fc-slats .fc-minor td{border-top-style:none !important;}
  
  .datepicker{z-index: 9999;}
  .bootstrap-timepicker-widget{z-index: 9999;}
  
  .yellow-task{
        background-image: url("<?php echo SITE_URL; ?>images/yellow.png");
        background-position: right top;
        background-repeat: no-repeat;
   }
   .orange-task{
        background-image: url("<?php echo SITE_URL; ?>images/orange.png");
        background-position: right top;
        background-repeat: no-repeat;
   }
   .green-task{
        background-image: url("<?php echo SITE_URL; ?>images/green.png");
        background-position: right top;
        background-repeat: no-repeat;
   }
   .red-task{
        background-image: url("<?php echo SITE_URL; ?>images/red.png");
        background-position: right top;
        background-repeat: no-repeat;
   }
   .holidaycls{background:url("<?php echo SITE_URL; ?>images/back_pattern.png") ;color: #444444;text-indent:3px;}
   
   
section#calender-main {overflow: hidden;}
.innerdiv{
    border-left: 1px solid #CCCCCC;
    float: left;
    text-align: left;
    text-indent: 7px;
}
    .fc-event{opacity: 0.80;}
    .fc-bgevent{opacity: 1.0; z-index: 9999 !important;}
   
   /* .fc-toolbar{display: none;}
    .commonHeader{display: block !important;}
    .leftfirst{margin-top: 43px;}
	.calender-left.leftfirst.tab-close {margin-top: 0px !important;}*/
</style>
 <?php $guide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id =".$_SESSION['user_id']);
if(count($guide) == 0  || $guide[0]['status'] == 'on'){ ?>
     <?php if($_SESSION['user_type'] == 'employee'){ ?>
     <script type="text/javascript">
     if (RegExp('multipage', 'gi').test(window.location.search)) {
            introJs().start();
          }
     </script>
     <?php }else{ ?>
     <script type="text/javascript">
     setTimeout(function(){  
     if (RegExp('multipage', 'gi').test(window.location.search)) {
            introJs().setOption('doneLabel', 'NEXT').start().oncomplete(function() {
              window.location.href = 'setting?multipage=true';
            });
           
     }
    },2000);
     

    
     </script>
     <?php } ?>

     <?php if($_SESSION['user_type'] == 'employee'){ ?>
        <script>
                setTimeout(function(){
                $(".introjs-tooltip").on("click",".introjs-prevbutton",function(){
                        var chkds = $(".introjs-helperNumberLayer").text();
                        if(chkds == '3'){
                            window.location.href = 'userlist';
                        } 
                 });
             },1000);
        </script>   
        <?php }else if($_SESSION['user_type'] == 'manager'){ ?>
        <script>
                setTimeout(function(){
                $(".introjs-tooltip").on("click",".introjs-prevbutton",function(){
                        var chkds = $(".introjs-helperNumberLayer").text();
                        if(chkds == '4'){
                            window.location.href = 'userlist';
                        } 
                 });
             },1000);
        </script>   
        <?php }else{ ?>
            <script>
                setTimeout(function(){
                $(".introjs-tooltip").on("click",".introjs-prevbutton",function(){
                        var chkds = $(".introjs-helperNumberLayer").text();
                        if(chkds == '4'){
                            window.location.href = 'userlist';
                        } 
                 });
             },1000);
        </script>
        <?php } ?>


<?php } ?>
