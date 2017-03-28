<?php 
// Configration 
	include("../config/configuration.php"); $load  =  new loader();	
	$objPages = $load->includeclasses('user');
	
	$label = "user";
	$dclass   =  new  database();
	$gnrl =  new general;



	// check for login
if(!$gnrl->checkLogin())
{
	$gnrl->redirectTo("index.php?msg=logfirst");
	
}
$gnrl->checkLoginExpire();

// Insert Record in database starts
if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Submit'){
	$dataInsert  =  $objPages->userTabRegister();
		if($dataInsert != '' ){
			$objPages->redirect($label.".php?msg=moreadd&script=edit&id=".$dataInsert.'&type='.$_REQUEST['type']);
		}else {
			$_GET['msg']  =  $dataInsert;
		}
}
// Insert Record in database ends

// Edit Process Starts

if(isset($_REQUEST['script']) && $_REQUEST['script']=='edit') {

	if(isset($_REQUEST['id']) && $_REQUEST['id']!="") 
	{
		$id = $_REQUEST['id'];			
// Update Records in database starts
		if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Save Changes') 
		{
      
			$id  =  $_REQUEST['id'];
			$dataUpdate =  $objPages->updateprofData();
			if($dataUpdate == '1'){
				$objPages->redirect('home.php?msg=profileupdate');
			}else{
				$_GET['msg']  =  $dataUpdate;
			}
		}
		// Update Records in database ends
		else {
			$row  =  $objPages->getData();
			if(!empty($row))	extract($row);
		}	
	}

}

// Edit Process Ends


//record status deactivate 
if(isset($_REQUEST['script']) && $_REQUEST['script']=='deactivate') {
	if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
		$dataDeactive  =  $objPages->deactiveRecode();
		if($dataDeactive == '1'){
			$objPages->redirect($label.'.php?msg=deactive&type='.$_REQUEST['type']);
		}else{
			$_GET['msg'] = $dataDeactive;			
		}
	}
}
//record status activate
if(isset($_REQUEST['script']) && $_REQUEST['script']=='activate') {
	if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
		$dataActive  =  $objPages->activeRecode();
		if($dataActive == '1'){
			$objPages->redirect($label.'.php?msg=active&type='.$_REQUEST['type']);
		}else{
			$_GET['msg'] = $dataActive;			
		}
	}
}



// Delete Record from the database starts

if(isset($_REQUEST['script']) && $_REQUEST['script']=='delete') {
	if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
		$dataDelete  =  $objPages->deleteRecode();
		if($dataDelete == '1'){
			$objPages->redirect($label.'.php?msg=del&type='.$_REQUEST['type']);
		}else{
			$_GET['msg'] = $dataDelete;			
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
					$objPages->redirect($label.".php?msg=multidel&type=".$_REQUEST['type']);
				}else{
					$_GET['msg'] = $dataDelete;			
					break;
				}
}

?>
<?php   
  //  Header Section Must  Include 
$load->includeother('header');
?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2_metro.css" />
<link rel="stylesheet" href="assets/plugins/data-tables/DT_bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2_metro.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/clockface/css/clockface.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker/css/datepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-timepicker/compiled/timepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-colorpicker/css/colorpicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datetimepicker/css/datetimepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/jquery-multi-select/css/multi-select-metro.css" />
<link href="assets/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/jquery-tags-input/jquery.tagsinput.css" />
<!-- END PAGE LEVEL STYLES -->

<style type="text/css">
.hideshow {
	width: 100%;
	float: left
}
.searchtgl {
	margin: 13px 0 5px 0;
	border: 1px solid #999;
	padding: 10px;
	border-radius: 5px ! important;
	width: 98%;
	float: left;
}
.tglbtn {
	width: 100%;
	float: left;
	cursor: pointer;
}
</style>
<?php 
$load->includeother('left_sidebar');?>

<div class="page-content"> 
  <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
  <div id="portlet-config" class="modal hide">
    <div class="modal-header">
      <button data-dismiss="modal" class="close" type="button"></button>
      <h3>portlet Settings</h3>
    </div>
    <div class="modal-body">
      <p>Here will be a configuration form</p>
    </div>
  </div>
  <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM--> 
  <!-- BEGIN PAGE CONTAINER-->
  <div class="container-fluid"> 
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
      <div class="span12"> 
        <!-- BEGIN STYLE CUSTOMIZER -->
        <div class="color-panel hidden-phone">
          <div class="color-mode-icons icon-color"></div>
          <div class="color-mode-icons icon-color-close"></div>
          <div class="color-mode">
            <p>THEME COLOR</p>
            <ul class="inline">
              <li class="color-black current color-default" data-style="default"></li>
              <li class="color-blue" data-style="blue"></li>
              <li class="color-brown" data-style="brown"></li>
              <li class="color-purple" data-style="purple"></li>
              <li class="color-grey" data-style="grey"></li>
              <li class="color-white color-light" data-style="light"></li>
            </ul>
            <label> <span>Layout</span>
              <select class="layout-option m-wrap small">
                <option value="fluid" selected>Fluid</option>
                <option value="boxed">Boxed</option>
              </select>
            </label>
            <label> <span>Header</span>
              <select class="header-option m-wrap small">
                <option value="fixed" selected>Fixed</option>
                <option value="default">Default</option>
              </select>
            </label>
            <label> <span>Sidebar</span>
              <select class="sidebar-option m-wrap small">
                <option value="fixed">Fixed</option>
                <option value="default" selected>Default</option>
              </select>
            </label>
            <label> <span>Footer</span>
              <select class="footer-option m-wrap small">
                <option value="fixed">Fixed</option>
                <option value="default" selected>Default</option>
              </select>
            </label>
          </div>
        </div>
        <!-- END BEGIN STYLE CUSTOMIZER -->
        <h3 class="page-title">&nbsp; </h3>
      </div>
    </div>
    <!-- END PAGE HEADER--> 
    <!-- BEGIN PAGE CONTENT-->
    
    <?php if(isset($_GET['msg'])){
				
					if($_GET['msg'] == 'invalid' || $_GET['msg'] == 'required' || $_GET['msg'] == 'passwordmismatch' || $_GET['msg'] == 'filesizeBig' || $_GET['msg'] == 'invalidFormat'){
						$cls = 'alert-error';
					}else{
						$cls = 'alert-success';
					}
					?>
    <div class="alert <?php echo $cls; ?>">
      <button data-dismiss="alert" class="close"></button>
      <?php echo $mess[$_GET['msg']];	?> </div>
    <?php } ?>
<?php  if (isset($_REQUEST['script'])  ||  $_REQUEST['script'] ==  'add' ||  $_REQUEST['script'] == 'edit'){   ?>    
    <div class="row-fluid">
      <div class="span12"> 
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box light-grey">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>
              <?php 
			if($_GET['type'] == "company"){
				$label2 = 'Company user';
			}else{
				$label2 = 'Member(s)';
			}
			
                           if(isset($_REQUEST['script'])){
                                $title = $_REQUEST['script'].' '.$label2 ;
                                echo  ucwords(str_replace('_',' ',$title)); 
                            }else{
                                
                                echo  ucwords(str_replace('_',' ',$label2)); 
                            }
                        ?>
            </div>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
          </div>
          <div class="portlet-body form">
            <div class="row-fluid profile">
              <div class="span12"> 
                
                <!--BEGIN TABS--> 
                <!-- overview tab start -->
                <div class="row-fluid">
                          <div class="accordion collapse in" id="accordion1-1" style="height: auto;">
                            <form action="" name="perinfo" id="perinfo" method="post">
                              <input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>"  />
                              <input type="hidden" name="script" value="<?php echo $_REQUEST['script']?>"  />
                              <div class="control-group">
                                <label class="control-label valiclrBold">First Name *</label>
                                <div class="controls">
                                  <input type="text" maxlength="150" name="user_first_name"  id= "user_first_name" size=30 value="<?php echo $row[0]['fname'];?>" class="validate[required,minSize[2]] span6 m-wrap">
                                </div>
                              </div>
                              <div class="control-group">
                                <label class="control-label valiclrBold">Last name *</label>
                                <div class="controls">
                                  <input type="text" maxlength="150" name="user_last_name"  id= "user_last_name" size=30 value="<?php echo $row[0]['lname'];?>" class="validate[required,minSize[2]] span6 m-wrap">
                                </div>
                              </div>
                              <div class="control-group">
                                <label class="control-label valiclrBold">Email *</label>
                                <div class="controls">
                                  <input type="text" maxlength="150" name="email"  id= "email" size=30 value="<?php echo $row[0]['email'];?>" class="validate[required,custom[email]] span6 m-wrap">
                                </div>
                              </div>
                              <div class="control-group">
                                <label class="control-label valiclrBold">Password *</label>
                                <div class="controls">
                                  <input type="password" maxlength="150" name="password"  id= "password" size=30 value="<?php echo base64_decode($row[0]['password']);?>" class="validate[required] span6 m-wrap">
                                </div>
                              </div>
                              
                              <div class="form-actions">
                                <?php 
									$button_title  = 'Save Changes';
									$btn_cancel = "Back";
									echo '<input type="hidden" name="id" id="id" value="'.$_REQUEST['id'].'" />';
									?>
                                <input type="submit" name="Submit" class="btn blue" value="<?php echo  $button_title;?>" />
                                <input type="button" name="Cancel" class="btn"  value="<?php echo $btn_cancel;?>" onclick="window.location.href='<?php echo $label;?>.php?type=<?php echo $_REQUEST['type'];?>'" />
                              </div>
                            </form>
                          </div>
                    <!--end span9--> 
                </div>
                <!--END TABS--> 
              </div>
            </div>
          </div>
        </div>
        <!-- END SAMPLE FORM PORTLET--> 
      </div>
    </div>
<?php }else{ ?>    

    <div class="row-fluid">
      <div class="span12"> 
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        
        <div class="portlet box light-grey">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>
              <?php 
            if($_GET['type'] == "system"){
				$label2 = 'System';
			}else{
				$label2 = 'Member(s)';
			}
				 if(isset($_REQUEST['script'])){
                                $title = $_REQUEST['script'].' '.$label2 ;
                                echo  ucwords(str_replace('_',' ',$title)); 
                            }else{
                                echo  ucwords(str_replace('_',' ',$label2)); 
                            }
                        ?>
            </div>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> <!--a href="#portlet-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a--> </div>
          </div>
          <div class="portlet-body">
            <form class="" name="frm" id="frm" method="post" >
              <div class="table-toolbar">
              </div>
              <table class="table table-striped table-bordered table-hover" id="sample_1_demo">
                <thead>
                  <tr>
                    <th class="hidden-480" style="width:8px;"><input type="checkbox" data-set="#sample_1_demo .checkboxes" class="group-checkable"></th>
                    <th class="hidden-480">Name</th>
                    <th class="center hidden-480">Email</th>
                   
          					<th class="center hidden-480">Company Name</th>
          					<th class="center hidden-480">Account created</th>
          				<?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'company' ){ ?>
                     <th class="center hidden-480">Subscription Type</th>
                     <th class="center hidden-480">Account Status</th>
                    <?php } ?> 
                    <th class="hidden-480" width="15%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				 // $label = "member";
				
      //AND  t2.current='1' 			
				if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'company' ){
					$datalist = $dclass->select("t1.user_id,t1.fname,t1.lname,t1.email,t1.user_status,t1.r_id,t1.register_date,t2.subscrib_type,t2.sub_plan_id,t2.payment_status,t3.sub_title,t2.expire_date", 
            'tbl_user t1 
			LEFT JOIN tbl_user_subscrib_detail t2 ON t1.user_id = t2.user_id 
			LEFT JOIN tbl_subscription_plan t3 ON t2.sub_plan_id = t3.sub_id',
			" AND t1.r_id='2' AND  t2.current='1'
             ORDER BY t2.user_id,t2.sub_plan_id desc"); 
          /* t2.sub_plan_id = (SELECT MAX(t2.sub_plan_id)
                 FROM tbl_user_subscrib_detail t2
                 WHERE t2.user_id = t1.user_id) */
                  
				}else if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'manager' ){                            
					$datalist = $dclass->select("*",'tbl_user'," AND r_id='3' ORDER BY user_id desc"); 

				}else{
					$datalist = $dclass->select("*",'tbl_user'," AND r_id='4' ORDER BY user_id desc"); 
				}
					//echo "<pre>"; print_r($datalist); echo "</pre>";
					foreach($datalist as $rows){
						if( (isset($_REQUEST['type']) && $_REQUEST['type'] == 'manager') || (isset($_REQUEST['type']) && $_REQUEST['type'] == 'employee') ){
							$company_name = $dclass->select("company_name",'tbl_user'," AND user_id=".$rows['user_comp_id']." ");
						}else{
              $company_name = $dclass->select("company_name",'tbl_user'," AND user_id=".$rows['user_id']." ");
            }
						
				  ?>
                  <tr class="odd gradeX">
                    <td class="hidden-480"><input type="checkbox" name="chk[]" class="checkboxes" value="<?php echo $rows['user_id']; ?>" /></td>
                    <td class="hidden-480"><a href="<?php echo 'user_profile.php?script=edit&id='.$rows['user_id'];?>&type=<?php echo $_REQUEST['type'];?>" title="Edit user detail"><?php echo $rows['fname'].' '.$rows['lname'];?></a></td>
                    <td class="hidden-480" ><?php echo $rows['email'];?></td>
                    
					<td class="hidden-480" ><?php echo $company_name[0]['company_name'];?></td>
					<td class="hidden-480" ><?php echo $rows['register_date'];?></td>
					
					
					<?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'company' ){ ?>
                      <td class="hidden-480" ><?php if($rows['sub_plan_id'] == 0){ echo "free"; }else{ echo $rows['sub_title'];}?></td>
                   
                      <?php 
                      $curdate = date('Y-m-d 00:00:00');
                        $hold='';
                      if( $rows['user_status'] == 'active' AND $rows['expire_date']>=$curdate){
                          if($rows['subscrib_type'] == 'paid'){
                            if($rows['payment_status'] == 'Completed'){
                              $activetitle = 'Active Paid Account';
                            }else{
                              $activetitle = 'Paid Account on hold';
                              $hold='hold';
                            }
                            
                          }else{
                            if($rows['payment_status'] == 'Completed'){
                              $activetitle = 'Active Fee Trial';
                            }else{
                              $activetitle = 'Free Trial on hold';
                              $hold='hold';
                            }
                            
                          }

                       ?>
                        <td class="hidden-480" ><?php echo $activetitle;?></td>
                      <?php }else{
                          if($rows['subscrib_type'] == 'paid'){
                            $deactivetitle = 'Paid Account on hold';
                             $hold='hold';
                          }else{
                            $deactivetitle = 'Free Trial on hold';
                            $hold='hold';
                          }
					
                       ?>
                        <td class="hidden-480" ><?php echo $deactivetitle;?></td>
                      <?php } ?>
                    <?php } ?>
                    <td class="center hidden-480">
                      
                     
                      
                      <?php if( $rows['user_status'] == 'active' && $hold == ''){ ?>
                      <span> <a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['user_id'];?>&type=<?php echo $_REQUEST['type'];?>" onclick="return confirm('Are you sure you want to deactivate the selected records ?');" title="Deactivate User" class="badge badge-roundless badge-success">Active</a> </span>
                      <?php }else{ ?>
                      <span> <a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['user_id'];?>&type=<?php echo $_REQUEST['type'];?>" onclick="return confirm('Are you sure you want to activate the selected records ?');" title="Activate User"  class="badge badge-roundless badge-important">Inactive</a> </span>
                      <?php } ?>
                    



                      <span><a href="<?php echo 'user_profile.php?script=edit&id='.$rows['user_id'];?>&type=<?php echo $_REQUEST['type'];?>"  title="Edit User Detail"><img src="<?php echo  IMAGEPATH; ?>edit-icon.png"  name="Edit" alt=""></a></span>
                      <?php //if($rows['user_type'] == 'user'){ ?>
                      <span><a href="<?php echo  $label.'.php?script=delete&id='.$rows['user_id'];?>&type=<?php echo $_REQUEST['type'];?>" onclick="return confirm('Are you sure you want to delete this record?');" class="delfset" id="del1"  title="Delete User"> <img src="<?php echo  IMAGEPATH; ?>delete-icon.png"  name="Delete User" alt=""></a></span>
                      <?php //} ?></td>
                  </tr>
                  <?php }  ?>
                </tbody>
              </table>
            </form>
          </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET--> 
      </div>
    </div>
<?php	}?>    
    <!-- END PAGE CONTENT--> 
  </div>
  <!-- END PAGE CONTAINER--> 
</div>

<?php $load->includeother('footer'); ?>
<?php if (isset($_REQUEST['script'])  ||  $_REQUEST['script'] ==  'add' ||  $_REQUEST['script'] == 'edit'){ ?>
<!-- BEGIN PAGE LEVEL PLUGINS --> 

<script src="assets/scripts/jquery.validationEngine.js"></script> 
<script src="assets/scripts/jquery.validationEngine-en.js"></script> 
<script type="text/javascript" src="assets/scripts/jquery.maskedinput.js"></script> 
<script type="text/javascript" src="assets/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script> 
<script type="text/javascript" src="assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js"></script> 
<script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script> 
<script type="text/javascript" src="assets/plugins/clockface/js/clockface.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/date.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script> 
<script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script> 
<script type="text/javascript" src="assets/plugins/jquery.input-ip-address-control-1.0.min.js"></script> 
<script type="text/javascript" src="assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script> 
<script src="assets/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script> 
<script src="assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 
<script src="assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js" type="text/javascript" ></script> 
<script src="assets/plugins/bootstrap-switch/static/js/bootstrap-switch.js" type="text/javascript" ></script> 
<script src="assets/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript" ></script> 
<!-- END PAGE LEVEL PLUGINS --> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="assets/scripts/app.js"></script> 
<script src="assets/scripts/form-components.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>

	//jQuery(function($){
//   $("#user_contact_no").mask("9 999 99999999");
//});
	

		jQuery(document).ready(function() {       

		   // initiate layout and plugins

		   App.init();

		   FormComponents.init();
		    var form_id = "<?php echo $label;?>";
				$("#logcre").validationEngine({promptPosition:'bottomRight'});
				$("#perinfo").validationEngine({promptPosition:'bottomRight'});
				$("#contfrm").validationEngine({promptPosition:'bottomRight'});
				$("#user_profile_avatar").validationEngine({promptPosition:'bottomRight'});
				$("#stsfrm").validationEngine({promptPosition:'bottomRight'});
				$("#behaviur").validationEngine({promptPosition:'bottomRight'});
			
			$('#user_dob').datepicker({
					language: 'en',
					format: 'yyyy-mm-dd'
				});
			
			
			
			$(".getText").live('click',function(){
					var chk = $('#manually_user_dob').css("display");
					if(chk == 'none'){
							$('#manually_user_dob').show();
					}else{
						$('#manually_user_dob').hide();
					}
			});




			
			$("#email").live('change',function(){              
				 var email=$("#email").val(); 
				 var checkScript = '<?php echo $_REQUEST['script'];?>';
							 
								if(checkScript == 'edit')
								{
								  var script='edit'; 
								  var user_id='<?php echo $_REQUEST['id'];?>'; 
								} else if(checkScript == 'add')
								{
									  var script='add'; 
								}
				 
				 
				  $.ajax({
						type:"post",
						url:"ajax_call/check_email.php",
						data:{email:email,script:script,user_id:user_id},
							success:function(data){							
						
							if(data=='1'){	
								$('#email').validationEngine('showPrompt','Email Already Exist', 'pass');
								$('#email').val('');
							}
						}
					 });
	 
				});
		
		
		
		
		
		
		
				 <?php /*?>$("#username").live('change',function(){                   
							 var username=$("#username").val(); 
							 var checkScript = '<?php echo $_REQUEST['script'];?>';
							 
								if(checkScript == 'edit')
								{
								  var script='edit'; 
								  var user_id='<?php echo $_REQUEST['id'];?>'; 
								} else if(checkScript == 'add')
								{
									  var script='add'; 
								}
							 
							  $.ajax({
									type:"post",
									url:"../check_uname.php",
									data:{username:username,script:script,user_id:user_id},
										success:function(data){							
									
										if(data=='1'){	
											$('#username').validationEngine('showPrompt','Username Already Exist', 'pass');
											$('#username').val('');
										}
									}
								 });
				 
							});<?php */?>
		
		
		
			
			
			
			
			//$("#user_country_id").live('change',function(){
//				var country_id = $("#user_country_id").val();
//				$.ajax({
//					url:"getCity.php",
//					data:{country_id:country_id},
//					success:function(result){
//					$('#user_city_id').removeAttr('disabled');
//					$("#user_city_id").html(result);
//			
//				 }
//	 });
//});
			
			
			
			

		});

	</script>
<?php }else{ ?>
<!-- BEGIN PAGE LEVEL PLUGINS --> 
<script type="text/javascript" src="inc/js/common.js"></script> 
<script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="assets/plugins/data-tables/jquery.dataTables.js"></script> 
<script type="text/javascript" src="assets/plugins/data-tables/DT_bootstrap.js"></script> 
<!-- END PAGE LEVEL PLUGINS --> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="assets/scripts/app.js"></script> 
<script src="assets/scripts/table-managed.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript" src="assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script> 
<script>

		jQuery(document).ready(function() {       

		   App.init();

		   TableManaged.init();
		   
		   
			$('#user_dob1').datepicker({
				language: 'en',
				format: 'yyyy-mm-dd'
			});
		   
	 
		  $(".tglbtn").click(function(){
    		 $(".searchtgl").toggle();
  		 });

//$('.dataTables_filter').hide();	
		   

		});

	</script>
<?php } ?>
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>