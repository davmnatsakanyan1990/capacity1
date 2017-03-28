<?php 
// Configration 
	include("../config/configuration.php"); $load  =  new loader();	
	$objPages = $load->includeclasses('user');
	//$objRes = $load->includeclasses('reservation');
	$label = "user_profile";
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
	$dataInsert  =  $objPages->insertData();
		if($dataInsert == '1' ){
			$objPages->redirect($label.".php?msg=add");
		}else {
			$_GET['msg']  =  $dataInsert;
		}
}

if(isset($_REQUEST['Submit_Avatar']) && $_REQUEST['Submit_Avatar']=='Submit'){
	$id = $_REQUEST['id'];	
	$dataInsert  =  $objPages->changeAvatar();
		if($dataInsert == '1' ){
			$objPages->redirect($label.'.php?msg=addimg&script=edit&type='.$_REQUEST['type'].'&id='.$id.'#tab_1_2');
		}else {
			
			$_GET['msg']  =  $dataInsert;
			$objPages->redirect($label.'.php?msg='.$dataInsert.'&script=edit&type='.$_REQUEST['type'].'&id='.$id.'#tab_1_2');
		}
}
if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Change Password'){
	$id = $_REQUEST['id'];	
	$dataInsert  =  $objPages->AdminchangePassword();
		if($dataInsert == '1' ){
			$objPages->redirect($label.'.php?msg=chpass&script=edit&type='.$_REQUEST['type'].'&id='.$id.'#tab_1_2');
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
			$dataUpdate =  $objPages->extenddate();
			if($dataUpdate == '1'){
				$objPages->redirect('user.php?msg=edit&type='.$_REQUEST['type']);
			}else{
				$_GET['msg']  =  $dataUpdate;
			}
		}
		// Update Records in database ends
		else {
			$row  =  $objPages->getData();
			//if(!empty($row))	extract($row);
		}	
	}
}
// Edit Process Ends
//record status deactivate 
if(isset($_REQUEST['script']) && $_REQUEST['script']=='deactivate') {
	if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
		$dataDeactive  =  $objPages->deactiveRecode();
		if($dataDeactive == '1'){
			$objPages->redirect($label.'.php?msg=deactive');
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
			$objPages->redirect($label.'.php?msg=active');
		}else{
			$_GET['msg'] = $dataActive;			
		}
	}
}



// Delete Record from the database starts

if(isset($_REQUEST['script']) && $_REQUEST['script']=='delete') {
	if(isset($_REQUEST['res_id']) && $_REQUEST['res_id']!="") {
		$dataDelete  =  $objRes->deleteRecode($_REQUEST['res_id']);
		if($dataDelete == '1'){
			$objRes->redirect($label.'.php?msg=del');
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
					$objPages->redirect($label.".php?msg=multidel");	
				}else{
					$_GET['msg'] = $dataDelete;			
					break;
				}
}


//  Header Section Must  Include 
$load->includeother('header');
?>

<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/chosen-bootstrap/chosen/chosen.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/pages/profile.css" rel="stylesheet" type="text/css" />
    <link href="assets/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
    
    
<script src='../rating/jquery.js' type="text/javascript"></script>
<script src='../rating/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='../rating/jquery.rating.js' type="text/javascript" language="javascript"></script>
<link href='../rating/jquery.rating.css' type="text/css" rel="stylesheet"/>



<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker/css/datepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-timepicker/compiled/timepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datetimepicker/css/datetimepicker.css" />

  <style type="text/css">
  	
form {
    margin: 20px;
}
  </style>
	<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN BODY -->
<?php 
$load->includeother('left_sidebar');?>

	  <div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal hide" id="portlet-config">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"></button>
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
									<li data-style="default" class="color-black current color-default"></li>
									<li data-style="blue" class="color-blue"></li>
									<li data-style="brown" class="color-brown"></li>
									<li data-style="purple" class="color-purple"></li>
									<li data-style="grey" class="color-grey"></li>
									<li data-style="light" class="color-white color-light"></li>
								</ul>
								<label>
									<span>Layout</span>
									<select class="layout-option m-wrap small">
										<option selected="" value="fluid">Fluid</option>
										<option value="boxed">Boxed</option>
									</select>
								</label>
								<label>
									<span>Header</span>
									<select class="header-option m-wrap small">
										<option selected="" value="fixed">Fixed</option>
										<option value="default">Default</option>
									</select>
								</label>
								<label>
									<span>Sidebar</span>
									<select class="sidebar-option m-wrap small">
										<option value="fixed">Fixed</option>
										<option selected="" value="default">Default</option>
									</select>
								</label>
								<label>
									<span>Footer</span>
									<select class="footer-option m-wrap small">
										<option value="fixed">Fixed</option>
										<option selected="" value="default">Default</option>
									</select>
								</label>
							</div>
						</div>
						<!-- END BEGIN STYLE CUSTOMIZER --> 
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						  <h3 class="page-title">&nbsp;  </h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<?php
				if(isset($_GET['msg'])){
				
					if($_GET['msg'] == 'invalid' || $_GET['msg'] == 'required' || $_GET['msg'] == 'passwordmismatch' || $_GET['msg'] == 'filesizeBig' || $_GET['msg'] == 'invalidFormat'){
						$cls = 'alert-error';
					}else{
						$cls = 'alert-success';
					}
					?>
      		<div class="alert <?php echo $cls; ?>">
			<button data-dismiss="alert" class="close"></button>
			<?php echo $mess[$_GET['msg']];	?>
            </div>
            <?php } ?>
                 <div class="portlet box light-grey">
          	<div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>
               User Profile
            </div>
           </div>
            <div class="portlet-body">
                <div class="row-fluid profile">
					<div class="span12">
				
                
                        <!--BEGIN TABS-->
						<div class="tabbable tabbable-custom tabbable-full-width">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#tab_1_1">View Profile</a></li>
                           </ul>
                            <form action="" name="perinfo" id="perinfo" method="post"> 
							<div class="tab-content">
								<!-- overview tab start -->
                                <div id="tab_1_1" class="tab-pane profile-classic row-fluid active">
									<div class="span2"> 
								     <?php if(isset($row[0]['user_avatar']) && $row[0]['user_avatar']!=''){ ?>
                                                                        
																		<img src="<?php echo SITE_URL; ?>timthumb.php?w=165&h=150&src=<?php echo UPLOAD; ?>/user/<?php echo $row[0]['user_avatar'] ?>&zc=0&q=100" />                                                                        
                                                                    <?php } else {?>
																		<img src="<?php echo SITE_URL; ?>timthumb.php?w=165&h=150&src=<?php echo SITE_URL;?>inc/image/user.png&zc=0&q=100" /> 
                                                                    <?php }?>   
                                     </div>
                                    <?php
                                    $getUserRole = $dclass->select("*","tbl_role"," AND r_id=".$row[0]['r_id']);
                                    $userteam = $dclass->select("t1.tm_title","tbl_team t1 LEFT JOIN tbl_team_detail t2 ON t1.tm_id = t2.tm_id "," AND t2.user_id='".$row[0]['user_id']."' ");
                                     ?>
                                    <ul class="unstyled span10">
										<?php /*?><li><span>User Name:</span> <?php echo $row[0]['username']; ?></li><?php */?>
										<li><span>First Name:</span> <?php echo ucfirst($row[0]['fname']); ?></li>
										<li><span>Last Name:</span> <?php echo ucfirst($row[0]['lname']); ?></li>
                                        <li><span>Email Id:</span> <?php echo $row[0]['email']; ?></li>
                                        <li><span>Job Title:</span> <?php echo $row[0]['job_title']; ?></li>
                                        <li><span>Team:</span> <?php echo $userteam[0]['tm_title']; ?></li>
                                        <li><span>User Role:</span> <?php echo $getUserRole[0]['r_title']; ?></li>
                                         <?php $expire_date = $dclass->select("expire_date,subscrib_type","tbl_user_subscrib_detail","AND current=1  AND user_id=".$_REQUEST['id']); 
                                         ?>
                                         <?php if($expire_date[0]['subscrib_type'] == 'free'){ ?>
                                        <li>
	                                    	<div class="control-group">
								                <label class="control-label">Expiry date</label>
								                <div class="controls">
								                	<input class="m-wrap m-ctrl-medium date-picker validate[required]" value="<?php if($expire_date[0]['expire_date'] != '0000-00-00 00:00:00') echo date('d-m-Y',strtotime($expire_date[0]['expire_date'])) ?>" data-date-format="dd-mm-yyyy" name="expire_date" id="expire_date" readonly size="16" type="text" value="" />
								                </div>
								            </div>
								        </li>
								         <?php }else if($expire_date[0]['subscrib_type'] == 'paid'){ ?>
								        <li>
                                        	<div class="control-group">
								                <label class="control-label ">Expiry date</label>
								                <div class="controls">
								                	<input class="m-wrap m-ctrl-medium date-picker validate[required]" value="<?php if($expire_date[0]['expire_date'] != '0000-00-00 00:00:00') echo date('d-m-Y',strtotime($expire_date[0]['expire_date'])) ?>" data-date-format="dd-mm-yyyy" name="expire_date" id="expire_date" readonly size="16" type="text" value="" />
								                </div>
								            </div>
								        </li>
								       <?php } ?>
                                        <!--li>
                                        	<div class="control-group">
						                  		<label class="control-label">Status</label>
								                <div class="controls">
									                <select name="user_status" class="validate[required] span6 m-wrap" id="user_status">
					                                    <option value="active"  <?php if($row[0]['user_status']=='active') {echo 'selected="selected"';} ?>  >Active</option>
					                                    <option value="inactive"  <?php if($row[0]['user_status']=='inactive') {echo 'selected="selected"';} ?> >Deactive</option>
									                </select>                 
								                </div>
                							</div>
                						</li-->
                                   </ul>
                  
                            <div class="submit-btn">
                                <?php 
									$button_title  = 'Save Changes';
									$btn_cancel = "Back";
									echo '<input type="hidden" name="id" id="id" value="'.$_REQUEST['id'].'" />';
									?>
                                <?php if($expire_date[0]['subscrib_type'] == 'free'){ ?>
                                <input type="submit" name="Submit" class="btn blue" value="<?php echo  $button_title;?>" />
                                <?php } ?>
                                <input type="button" name="Cancel" class="btn"  value="<?php echo $btn_cancel;?>" onclick="window.location.href='user.php?type=<?php echo $_REQUEST['type'];?>'" />
                              </div>
                                    
                                    
								</div>
                           </div>

                           	</form>
						</div>
						<!--END TABS-->
					</div>
				</div>
                </div>
             </div>   
                </div>
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER--> 
		<?php /*?></div><?php */?>
<?php $load->includeother('footer'); ?> 
<?php if (isset($_REQUEST['script'])  ||  $_REQUEST['script'] ==  'add' ||  $_REQUEST['script'] == 'edit'){ ?>
	<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/scripts/jquery.validationEngine.js"></script>
<script src="assets/scripts/jquery.validationEngine-en.js"></script>      
    
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

		jQuery(document).ready(function($) {       

		   // initiate layout and plugins

		   App.init();

		   FormComponents.init();
		    var form_id = "<?php echo $label;?>";
		  $("#"+form_id).validationEngine();
	
		$("#type2").click(function(){
	
			$('#textbox').css('display','block');
			$("#image").css('display','none');
			
	});
	
$("#type1").click(function(){
		

			$('#image').css('display','block');
			$("#textbox").css('display','none');
			
			
	});

		

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
<script>

		jQuery(document).ready(function() {       

		   App.init();

		   TableManaged.init();

		});

	</script>
<?php } ?> 
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>   
