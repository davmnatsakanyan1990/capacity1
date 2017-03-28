<?php 
// Configration 
  include("config/configuration.php"); 
  $load  =  new loader(); 
  $objPages = $load->includeclasses('userlist');
  $label = "userlist";
  $dclass   =  new  database();
  $gnrl =  new general;
  global $mail;
  // check for login

// uncomment after client confirmation
if(!$gnrl->checkpaymentstatus()){
  $gnrl->redirectTo("plan");
}

function checkCompanyuserlenth(){
  global $dclass;

  $mydata = $dclass->select("user_id","tbl_user"," AND (user_comp_id= '".$_SESSION['
    company_id']."' OR user_comp_id= 0 ) AND user_id = '".$_SESSION['user_id']."' AND  user_status='active' ");
  return count($mydata);
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

if(!$gnrl->checkUserLogin()){
  $gnrl->redirectTo("home/logfirst");
}

// tesing code end 
if(isset($_REQUEST['script'])){
  if($_REQUEST['script'] == 'add'){
    if(!$gnrl->checkUserAccess("USER_ADD")){
        $gnrl->redirectTo("home/accessdenid");
    }

  }
  if($_REQUEST['script'] == 'edit'){
    if(!$gnrl->checkUserAccess("USER_UPDATE")){
        $gnrl->redirectTo("home/accessdenid");
    }

  }
}

// Insert Record in database starts
if(isset($_POST['Submit_user']) && $_POST['Submit_user'] == 'submit'){
  
  $dataInsert  =  $objPages->insertData();
    if($dataInsert == '1' ){
      $_SESSION['msg'] = 'useradded';
      $objPages->redirect($label.".php");
    }else {
      $_SESSION['msg'] =  $dataInsert;
    }
}
// Insert Record in database ends
// Edit Process Starts

if(isset($_POST['Submit_user']) && $_POST['Submit_user'] == 'edit'){
  
  if(isset($_REQUEST['id']) && $_REQUEST['id']!="") 
  {
    $id = $_REQUEST['id'];      

    
      $id  =  $_REQUEST['id'];
      $dataUpdate =  $objPages->updateData();
      if($dataUpdate == '1'){
        $_SESSION['msg'] = 'userchanged';
        $objPages->redirect('userlist');
      }else{
        $_SESSION['msg']  =  $dataUpdate;
      }
    
  }
}
// Edit Process Ends
//record status deactivate 
if(isset($_REQUEST['script']) && $_REQUEST['script']=='deactivate'){
  if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
    $dataDeactive  =  $objPages->deactiveRecode();
    if($dataDeactive == '1'){
      $_SESSION['msg'] = 'userinactive';
      $objPages->redirect('userlist');
    }else{
      $_SESSION['msg'] = $dataDeactive;     
    }
  }
}
//record status activate
if(isset($_REQUEST['script']) && $_REQUEST['script']=='activate'){
  if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
    $dataActive  =  $objPages->activeRecode();
    if($dataActive == '1'){
      $_SESSION['msg'] = 'useractivated';
      $objPages->redirect('userlist');
    }else{
      $_SESSION['msg'] = $dataActive;     
    }
  }
}
// Delete Record from the database starts
if(isset($_REQUEST['script']) && $_REQUEST['script']=='delete'){
      if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
        $dataDelete  =  $objPages->deleteRecode();
        //$dataDelete = 1;
        if($dataDelete == '1'){
          $_SESSION['msg'] = 'userdel';
          $chkuser =  checkCompanyuserlenth();
          if($chkuser > 0){
            $objPages->redirect('userlist');
          }else{
            $objPages->redirect('logout');
          }
        }else{
          $_SESSION['msg'] = $dataDelete;     
        }
      }

}
// Delete Record from the database ends
// Multiple checkbox functionality starts
if(isset($_REQUEST['chk'])){
  // delete records 
    foreach($_REQUEST['chk'] as $k=>$v){
      $v = mysql_real_escape_string($v);
      if(!in_array($v,$disableIdArray)) {

        $dataDelete  =  $objPages->deleteRecode($v);
        
      }
    }
    
        if($dataDelete == '1'){
          $_SESSION['msg'] = 'usermultidel';
          $objPages->redirect('userlist');
        }else{
          $_SESSION['msg'] = $dataDelete;     
          break;
        }
}

$load->includeother('header');
?>
<!--div class="welcome-note" <?php echo $step_1_admin; ?><?php echo $step_1_manager; ?><?php echo $step_1_employee; ?> ></div-->
<section id="setting-main">
      <div class="setting-box">
         <div class="clr"></div>   
        <h3 class="table_heading">Users</h3>
        <div class="full-box table-box">
          <div>
        <div class="table-toolbar">
                <div class="btn-group" id="addbtntop" <?php echo $step_2_admin; ?><?php echo $step_2_manager; ?> >

                  <?php 
                  if($gnrl->checkUserAccess("USER_ADD")){ 
                     if($gnrl->checkmaxallowuser() > $gnrl->gettotalcompanyuser() || $gnrl->checkmaxallowuser() == ''){ ?>
                     <a href="javascript:void(0)" class="save-btn newclssbtn" id="add_new_user_btn"  >+ Add New</a>
                  <?php }else{ ?>
                    <p>You have now reached the maximum number of <?php echo $gnrl->gettotalcompanyuser(); ?> users. To add any new users you must delete one or more old user first.</p>
                    <?php if($_SESSION['user_type'] == 'company_admin'){ ?>
                      <!--a href="<?php echo SITE_URL ?>subscriptions" class="save-btn" > Upgrade Subscription</a-->
                    <?php } ?> 
                  <?php } 
                  } ?>
                  <?php 
                  if($_SESSION['user_type'] == 'employee'){  ?>
                    <a href="#" data-toggle="modal" class="save-btn" data-target="#user_guide_prompt">User Guide popups on/off</a>
                  <?php } ?>
                </div>
              </div>
        <div class="clr"></div>
          <!--form class="" name="frm" id="frm" action="" method="post" enctype="multipart/form-data"-->
          <table class="table table-striped table-hover" id="sample_1_demo" <?php echo $step_2_employee; ?>>
                <thead>
                  <tr>
                   
					<th class="hidden-480">Name</th>
                    <th class="hidden-480">Email</th>
                    <th class="hidden-480">Team</th>
                    <th class="hidden-480" <?php echo $step_3_admin; ?><?php echo $step_3_manager; ?> >User Type</th>
					<th class="hidden-480">Order</th>
					<th class="hidden-480">Show in calendar</th>
                   <th class="hidden-480">Last Login</th>
                    <th class="hidden-480" width="80px" >Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php  

                  if($_SESSION['user_type'] == 'company_admin'){
                    $datalist = $dclass->select("*",'tbl_user'," AND user_comp_id = '".$_SESSION['company_id']."' OR user_id = '".$_SESSION['company_id']."' ORDER BY user_id desc"); 
                  }else if($_SESSION['user_type'] == 'manager'){
                    //$datalist = $dclass->select("*",'tbl_user'," AND (user_comp_id = '".$_SESSION['company_id']."' AND r_id IN (4)) OR user_id = '".$_SESSION['user_id']."'  ORDER BY user_id desc"); 
                    $datalist = $dclass->select("*",'tbl_user'," AND user_comp_id = '".$_SESSION['company_id']."' OR user_id = '".$_SESSION['company_id']."' ORDER BY user_id desc"); 
                  }else{
                    //$datalist = $dclass->select("*",'tbl_user'," AND user_id = '".$_SESSION['user_id']."' ORDER BY user_id desc");   
                    $datalist = $dclass->select("*",'tbl_user'," AND user_comp_id = '".$_SESSION['company_id']."' OR user_id = '".$_SESSION['company_id']."' ORDER BY user_id desc"); 
                  }

                  foreach($datalist as $rows){ 
                  $getUserRole = $dclass->select("*","tbl_role"," AND r_id=".$rows['r_id']);
                  $userteam = $dclass->select("t1.tm_title","tbl_team t1 LEFT JOIN tbl_team_detail t2 ON t1.tm_id = t2.tm_id "," AND t2.user_id='".$rows['user_id']."' ");
                ?>
                  <tr class="odd gradeX">
                    <td class="hidden-480"> <?php echo $rows['fname'].' '.$rows['lname']; ?></td>
                    <td class="hidden-480"><?php echo $rows['email']; ?></td>
                    <td class="hidden-480"><?php 
                        if(count($userteam) > 0){
                          
                          echo $userteam[0]['tm_title'];
                        }else{
                          echo '<span class="redcls">Unassigned</span>';
                        }
                     ?></td>
                    <td class="hidden-480"><?php echo $getUserRole[0]['r_title']; ?></td>
					<td class="hidden-480"><?php echo $rows['display_order']; ?></td>
					<td class="hidden-480"><?php 
					if($rows['display_status'] == 1){
						echo "show";
					}else{
						echo "hide";
					}
					?></td>
					
					
                    <td class="hidden-480"><?php 
                    if($rows['last_login'] != ''){
                      echo phpdateformat($rows['last_login']); 
                    }else{
                      echo 'Never';
                    } ?></td>
                    <td class="left hidden-480" style="padding-right: 0px;">
          <?php if($_SESSION['user_type'] == 'company_admin'){
           ?>
              <span><a href="#" data-toggle="modal" class="user_edit" data-target="#user_modal_<?php echo $rows['user_id'];?>"><img src="<?php echo SITE_URL; ?>images/edit-icon.png" alt="Edit"></a></span> 
              <?php  if( $rows['user_status'] == 'inactive'){ ?>
                      <span><a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['user_id'];?>" onclick="return confirm('Are you sure to enable this user?');" title="Activate" ><img src="<?php echo SITE_URL; ?>images/des-icon.png" alt="Inactive"></a></span>
              <?php   }else{ ?> 
                      <span><a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['user_id'];?>" onclick="return confirm('Are you sure to disable this user?');" title="Deactivate" ><img src="<?php echo SITE_URL; ?>images/enb-icon.png" alt="Active"></a></span>     
              <?php } ?>
              <?php if($rows['user_id'] != $_SESSION['company_id']){ ?>
              <span><a href="<?php echo  $label.'.php?script=delete&id='.$rows['user_id'];?>" onclick="return confirm('Are you sure to delete this user?');"  id="del1"><img src="<?php echo SITE_URL; ?>images/delete-icon.png" alt="Delete"> </a></span>
              <?php } ?>
          <?php 
        }else if($_SESSION['user_type'] == 'manager'){ ?>
          <?php  if($rows['r_id'] != 2){  ?>
            <?php  if($gnrl->checkUserAccess("USER_UPDATE")){ ?>
                <span><a href="#" data-toggle="modal" class="user_edit" data-target="#user_modal_<?php echo $rows['user_id'];?>"><img src="<?php echo SITE_URL; ?>images/edit-icon.png" alt="Edit"></a></span> 
                <?php  if( $rows['user_status'] == 'inactive'){ ?>
                        <span><a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['user_id'];?>" onclick="return confirm('Are you sure to enable this user?');" title="Activate" ><img src="<?php echo SITE_URL; ?>images/des-icon.png" alt="Inactive"></a></span>
                <?php   }else{ ?> 
                        <span><a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['user_id'];?>" onclick="return confirm('Are you sure to disable this user?');" title="Deactivate" ><img src="<?php echo SITE_URL; ?>images/enb-icon.png" alt="Active"></a></span>     
                <?php } ?>
        <?php }else{ 
                  if($rows['user_id'] == $_SESSION['user_id']){ ?>
                    <span><a href="#" data-toggle="modal" class="" data-target="#user_modal_<?php echo $rows['user_id'];?>"><img src="<?php echo SITE_URL; ?>images/edit-icon.png" alt="Edit"></a></span> 
                  <?php } ?>
              <?php } 
              if($rows['user_id'] != $_SESSION['company_id']){
                if($gnrl->checkUserAccess("USER_DELETE")){ ?>
                  <span><a href="<?php echo  $label.'.php?script=delete&id='.$rows['user_id'];?>" onclick="return confirm('Are you sure to delete this user?');"  id="del1"><img src="<?php echo SITE_URL; ?>images/delete-icon.png" alt="Delete"> </a></span>
             <?php } 
             }
           } 

          ?>

          <?php }else{ ?>
            <?php if($rows['user_id'] == $_SESSION['user_id']){ ?>
                    <span><a href="#" data-toggle="modal" class="" data-target="#user_modal_<?php echo $rows['user_id'];?>"><img src="<?php echo SITE_URL; ?>images/edit-icon.png" alt="Edit"></a></span> 
                  <?php } ?>
          <?php } ?>
          


        
          

                    

<div class="modal bs-example-modal-lg" id="user_modal_<?php echo $rows['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog task-detail">
    
    <div class="modal-content user-box">
     <form name="user_<?php echo $rows['user_id']; ?>" class="user_edit_forms" id="user_<?php echo $rows['user_id']; ?>" method="post" action="" enctype="multipart/form-data">
      <div class="task-title">User Detail</div>
      <div class="task-form">
        <div class="task-form-left">
          <label class="task-label">Email <span>:</span></label>
            <input type="text" name="email" id="email" class="input-box validate[required,custom[email]]" value="<?php echo $rows['email']; ?>">
        </div>
        <div class="task-form-right">
          <label class="task-label">Password <span>:</span></label>
            <input type="password" name="password" id="password" class="input-box validate[required]" value="<?php echo base64_decode($rows['password']); ?>">
        </div>
        <div class="clr"></div>

        <div class="task-form-left">
          <label class="task-label">First name <span>:</span></label>
          <input type="text" name="f_name" maxlength="9" id="f_name" class="input-box validate[required]" value="<?php echo $rows['fname']; ?>">
        </div>
        <div class="task-form-right">
          <label class="task-label">Last name <span>:</span></label>
            <input type="text" name="l_name" maxlength="9" id="l_name" class="input-box validate[required]" value="<?php echo $rows['lname']; ?>">
        </div>
        <div class="clr"></div>
        <div class="task-form-left">
          <label class="task-label">Job Title <span>:</span></label>
            
                <input type="text" name="job_title" id="job_title" class="input-box validate[required]" value="<?php echo $rows['job_title']; ?>">
            
        </div>
        <div class="task-form-right">
          <label class="task-label">Profile Pic<span>:</span></label>
            <input type="file" name="user_avatar" id="user_avatar" class="filestyle" data-icon="false">
            <?php if($rows['user_avatar'] != ''){ 
                      $user_image = SITE_URL.'upload/user/'.$rows['user_avatar'];

                      ?>
                                <div class="User-Accoutn-pic">
                                <img src="<?php echo SITE_URL.'timthumb.php?src='.$user_image.'&h=46&w=46&zc=1&q=100' ?>"  /> 
                                </div>
                            <?php } ?>
            <label class="task-label" id="new_img"></label><span id="showimgn" class="task-label" style="margin-left: 130px;margin-top: -30px;"></span>
        </div>
		<div class="clr"></div>
        <div class="task-form-left">
          <label class="task-label">Order <span>:</span></label>
          <input type="text" name="display_order" maxlength="9" id="display_order" class="input-box" value="<?php echo $rows['display_order']; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
        </div>
        <div class="task-form-right">
          <label class="task-label">Show in cal <span>:</span></label>
		<div class="form-group">	
		   <select id="user_display_status" name="display_status" <?php //if($rows['user_comp_id'] == 0){ echo "disabled='disabled'"; } ?> class="selectpicker" data-live-search="true">
                <option value="1" <?php if($rows['display_status'] == 1){ echo "selected='selected'";} ?> >show</option>
				<option value="0" <?php if($rows['display_status'] == 0){ echo "selected='selected'";} ?> >hide</option>
            </select>
		</div>	
        </div>
        <div class="clr"></div>
		<div class="user-choose">
        <?php if($_SESSION['user_type'] == 'employee'){ ?>
            <div class="user-choose-box">
            <?php 
                $getUserTeam = $dclass->select("t1.tm_title"," tbl_team t1 LEFT JOIN tbl_team_detail t2 ON t1.tm_id = t2.tm_id"," AND t2.user_id=".$rows['user_id']);
             ?>
                <div class="tab-title">Your Team</div>
                  <div class="form-group">
                     <?php echo $getUserTeam[0]['tm_title']; ?>
                  </div>
                </div>
            <div class="user-choose-box">
              <?php $Roll = $dclass->select("*","tbl_role"," ANd r_id = '".$rows['r_id']."' "); ?>
                <div class="tab-title">User Role </div>
                  <div class="form-group">
                      <?php echo $Roll[0]['r_title']; ?>
                      <input type="hidden" name="r_id" value="<?php echo $rows['r_id']; ?>">
                  </div>               
              </div>

          <?php }else{ ?>
            <div class="user-choose-box">
          <?php $team = $dclass->select("*","tbl_team"," ANd company_user_id=".$_SESSION['company_id']);
              $getUserTeam = $dclass->select("*","tbl_team_detail"," AND user_id=".$rows['user_id']);
           ?>
              <div class="tab-title">Choose Team</div>
                <div class="form-group">
                   
                    <select id="user_team_id" name="user_team_id" class="selectpicker validate[required]" data-live-search="true">
                    <option value="">Select Team</option>
                    <?php foreach($team as $tm){
                          if($tm['tm_id'] == $getUserTeam[0]['tm_id']){
                            $selected = 'selected="selected"'; 
                          }else{
                            $selected = ''; 
                          }
                     ?>
                       <option value="<?php echo $tm['tm_id']; ?>" <?php echo $selected; ?> ><?php echo $gnrl->trunc_string($tm['tm_title'],15); ?></option>
                    <?php } ?>
                    </select>
                </div>
              </div>

            <div class="user-choose-box">
            <?php if($_SESSION['user_type'] == 'manager'){ 
                // displaying only employee
                    if($rows['user_id'] == $_SESSION['user_id']){
                      $Roll = $dclass->select("*","tbl_role"," ANd r_id IN ('3','4') ");
                    }else{
                      $Roll = $dclass->select("*","tbl_role"," ANd r_id = '4' ");
                    }
                    
                  }else{
                    $Roll = $dclass->select("*","tbl_role"," ANd r_id != '1' ");
                  } ?>
              <div class="tab-title">Choose User Role </div>
                <div class="form-group">
                    <select id="r_id" name="r_id" class="selectpicker validate[required]" data-live-search="true" >
                    <option value="">Select Role</option>
                    <?php foreach($Roll as $ri){ 
                        if($ri['r_id'] == $rows['r_id']){
                          $selected = "selected='selected'";
                        }else{
                          $selected = "";
                        }
                      ?>
                    <option value="<?php echo $ri['r_id']; ?>" <?php echo $selected; ?>><?php echo $ri['r_title']; ?></option>
                    <?php } ?>
                    </select>
                </div>
              
            </div>
          <?php } ?>  

            <div class="user-choose-box">
              
                <div class="button-box">
                              <input type="hidden" name="id" id="id" value="<?php echo $rows['user_id']; ?>">
                              <a class="cancel-btn close_popup_all" href="javascript:void(0);">Cancel</a>
                               <button class="save-btn" name="Submit_user" value="edit" type="submit">Save</button> 
                            </div>
              
            </div>
        </div>
      </div>
     </form>
    <div class="clr"></div>
    </div>
  </div>
</div>
                    </td>
                  </tr>
                  <?php }  ?>
                </tbody>
              </table>
        <div class="clr"></div>
        <!--/form-->
        </div>
        </div>
      </div>
        
    </section>

<!-- code for add new user popup1 -->
<div class="modal bs-example-modal-lg" id="new_user_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog task-detail">
    <div class="modal-content user-box">
        <form name="new_user" id="new_user" method="post" action="" enctype="multipart/form-data">
      <div class="task-title">User Detail</div>
      <div class="task-form">
        <div class="task-form-left">
          <label class="task-label">Username <span>:</span></label>
            <input type="text" name="email" id="email" class="input-box validate[required,custom[email]]" value="">
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
            <div class="form-group">
                <input type="text" name="job_title" id="job_title" class="input-box validate[required]" value="<?php echo $rows['job_title']; ?>">
            </div>
        </div>
        <div class="task-form-right">
          <label class="task-label">Profile Pic<span>:</span></label>
            <input type="file" name="user_avatar" id="user_avatar_edit" class="filestyle" data-icon="false">
        </div>
		<div class="task-form-left">
          <label class="task-label">Display Order <span>:</span></label>
          <input type="text" name="display_order" maxlength="9" id="display_order" class="input-box" value="">
        </div>
         <div class="task-form-right">
          <label class="task-label">Display status <span>:</span></label>
            <select id="user_display_status" name="display_status" class="selectpicker" data-live-search="true">
                <option value="1" >show</option>
				<option value="0" >hide</option>
            </select>
        </div>
        <div class="clr"></div>
        <div class="user-choose">
          <div class="user-choose-box">
          <?php $team = $dclass->select("*","tbl_team"," ANd company_user_id=".$_SESSION['company_id']); ?>
              <div class="tab-title">Choose Team</div>
                <div class="form-group">
                    <select id="user_team_id" name="user_team_id" class="selectpicker" data-live-search="true">
                    <option value="">Select Team</option>
                    <?php foreach($team as $tm){ ?>
                       <option value="<?php echo $tm['tm_id']; ?>"><?php echo $gnrl->trunc_string($tm['tm_title'],15); ?></option>
                    <?php } ?>
                    </select>
                </div>
              
            </div>
            <div class="user-choose-box">
            <?php $Roll = $dclass->select("*","tbl_role"," ANd r_id != '1' "); ?>
              <div class="tab-title">Choose User Role </div>
                <div class="form-group" style="position:relative">
                    <select id="r_id" name="r_id" class="selectpicker validate[required]" data-live-search="true" >
                    <option value="">Select Role</option>
                    <?php foreach($Roll as $ri){ ?>
                    <option value="<?php echo $ri['r_id']; ?>"><?php echo $ri['r_title']; ?></option>
                    <?php } ?>
                    </select>
                </div>
              
            </div>
            <div class="user-choose-box">
              
                <div class="button-box">
                              <a class="cancel-btn" id="close_popup" href="javascript:void(0);">Cancel</a>
                               <button class="save-btn" name="Submit_user" value="submit" type="submit">Save</button> 
                            </div>
              
            </div>
        </div>


      </div>
      </form>
    <div class="clr"></div>
    </div>
  </div>
</div>


<div class="modal bs-example-modal-lg" id="user_guide_prompt" tabindex="-1" role="dialog" aria-labelledby="abc" aria-hidden="true">
  <div class="modal-dialog task-detail">
    <div class="modal-content user-box">
        <form name="user_guide_prompt_frm" id="user_guide_prompt_frm" method="post" action="" enctype="multipart/form-data">
      <div class="task-title">User Guide popups on or off</div>
      <div class="task-form">
        <div class="task-form-left">
         <?php $guide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id =".$_SESSION['user_id']);
                    if(count($guide) > 0 && $guide[0]['status'] == 'on'){
                      $checked = 'checked="checked"';
                    }else{
                      $checked = '';
                    }
                 ?>
                  <div class="checkbox mychk">
                      <input type="checkbox" <?php echo $checked; ?> value="0" name="user_guide" id="user_guide">
                      <label for="user_guide">&nbsp; Activate User Guide Popups</label>
                  </div>
        </div>
        
            <div class="button-box">
              <a class="cancel-btn close_popup_all" href="javascript:void(0);">Cancel</a>
              <button class="save-btn" name="user_guide_btn" id="user_guide_btn" type="button">Save</button> 
            </div>
        
        
        <div class="clr"></div>

      </div>
      
      </form>
    <div class="clr"></div>
    </div>
  </div>
</div>


<?php $load->includeother('footer');?>

<!--script type="text/javascript" src="admin/assets/plugins/data-tables/jquery.dataTables.js"></script-->
<!--script src="admin/assets/scripts/table-managed.js"></script-->

<!--link rel="stylesheet" type="text/css" href="css_ui/introjs.css"-->
<!--link href='css_ui/skeleton.css' media='all' rel='stylesheet' type='text/css' /-->


<script type="text/javascript" src="js_ui/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js_ui/bootstrap-filestyle.js"> </script>
<style type="text/css">
  .mychk label{margin-top: 0px !important}
</style>
<script>
var prefix = "selectBox_";
  $(document).ready(function() {
 
    /*$('.selectpicker').each(function() {
        $(this).next('div.bootstrap-select').attr("id", prefix + this.id).removeClass("validate[required]");
    });*/
    
    //$('.user_edit_forms').validationEngine({validateNonVisibleFields: true});
     $(".user_edit_forms").validationEngine('attach', {
                      binded: false,
                      validateNonVisibleFields: true,
                      autoHidePrompt:true
      });
    //$("#new_user").validationEngine({validateNonVisibleFields: true});
   
  $("#user_avatar_edit").filestyle();

    $('#sample_1_demo').DataTable({
          "bLengthChange": false,
          "bFilter": true,
          "aoColumns": [
              { "sType": "string", "bSearchable": true, "bSortable": true, "bVisible": true },
              { "sType": "string", "bSearchable": true, "bSortable": true, "bVisible": true },
              { "sType": "string", "bSearchable": true, "bSortable": true, "bVisible": true },
              { "sType": "string", "bSearchable": true, "bSortable": true, "bVisible": true },
              { "sType": "string", "bSearchable": true, "bSortable": true, "bVisible": true },
			  { "sType": "string", "bSearchable": true, "bSortable": true, "bVisible": true },
			  { "sType": "string", "bSearchable": true, "bSortable": true, "bVisible": true },
              { "sType": "numeric", "bSearchable": false, "bSortable": false, "bVisible": true },
              
           ]
    });


      $("body").on('click','.user_edit',function(){
      $('.task-form-right').find('#showimgn').text('');
      $('.task-form-right').find('#new_img').text('');
        $('.selectpicker').selectpicker();
        $(".modal").on('click','.close_popup_all',function(){
          $(".modal").modal("hide");
        });
      });
      $("#new_user_modal").on('click','#close_popup',function(){
        $('#new_user').trigger("reset");
        $('#gender').selectpicker();
        $("#new_user_modal").modal("hide");
      });
      $(".modal").on('click','.close_popup_all',function(){
        $(".modal").modal("hide");
      });
      
$('body').on('click','#add_new_user_btn', function(){
      var tsk = 'add_new_user';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{tsk:tsk},
                success:function(data){             
               $("#new_user_modal").removeClass("hide");
                $("#new_user_modal").html(data);
                $("#new_user_modal").modal("show");
                setTimeout( function() { $( '#new_user #email' ).focus() }, 500 );
                $('.selectpicker').selectpicker();
                //$(":file").filestyle();
                $("#user_avatar_new").filestyle();
                //$("#new_user").validationEngine({validateNonVisibleFields: true,autoHidePrompt:true  });
                 $("#new_user").validationEngine('attach', {
                      binded: false,
                      validateNonVisibleFields: true,
                      autoHidePrompt:true
                 });

            }
           });
    });
$('#user_modal_20, #new_user_modal').on('hidden.bs.modal', function (){
      $('.form-control').val('');
   });
$('input[type="file"]').on('change', function (event, files, label) {
        var file_name = this.value.replace(/\\/g, '/').replace(/.*\//, '');
	var ext = file_name.split('.').pop().toLowerCase();
        
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert('You can only upload "JPG,JPEG,PNG,GIF" image');
            $('.form-control').val('');
            $('input[type="file"]').val('');
	    $('.task-form-right').find('#showimgn').text('');
            $('.task-form-right').find('#new_img').text('');
        }
        else
        {
         $('.form-control').val(file_name);
         $('.task-form-right').find('#showimgn').text(file_name);
         $('.task-form-right').find('#new_img').html('New Image<span>:</span>');
        }
      });
$('#user_guide_prompt').on('click','#user_guide_btn', function(){
      
      var tsk = 'user_guide_propmt_setting';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
            data:$("#user_guide_prompt_frm").serialize()+'&tsk='+tsk,
            success:function(data){             
              window.location.reload();
            }
           });
      
    });

  


  });
</script> 
<style type="text/css">
  .redcls{color: red;}
  /*.table tr td a{padding: 0 5px;}*/
</style>
 <?php $guide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id =".$_SESSION['user_id']);
if(count($guide) == 0  || $guide[0]['status'] == 'on'){ ?>
<script type="text/javascript">
    introJs().setOption('doneLabel', 'NEXT').start().oncomplete(function() {
      window.location.href = 'view?multipage=true#tab-1';
    });
 </script>
 <?php } ?>
