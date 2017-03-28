<?php 
// Configration 
	include("../config/configuration.php"); 
	$load  =  new loader();	
	$objPages = $load->includeclasses('subscription');
	$label = "subscription";
	$dclass   =  new  database();
	$gnrl =  new general;


	// check for login
if(!$gnrl->checkLogin())
{
	$gnrl->redirectTo("index.php?msg=logfirst");
	
}

$gnrl->checkLoginExpire();

$menuAccess = $dclass->select("*","tbl_system_group"," AND system_group_member LIKE '%,".$_SESSION['adminid'].",%' AND system_group_status ='active' ");

if(!empty($menuAccess))
{
	if(!$gnrl->checkGroupAccess('Slideshow'))
	{
		$gnrl->redirectTo("home.php?msg=permissiondenied");
		
	}
}

// Insert Record in database starts
if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Submit'){
	$dataInsert  =  $objPages->insertData();
		if($dataInsert == '1' ){
			$objPages->redirect($label.".php?msg=add");
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
			$dataUpdate =  $objPages->updateData();
			if($dataUpdate == '1'){
				$objPages->redirect($label.'.php?msg=edit&script=edit&id='.$id);
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
	if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
		$dataDelete  =  $objPages->deleteRecode();
		if($dataDelete == '1'){
			$objPages->redirect($label.'.php?msg=del');
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
<link rel="stylesheet" type="text/css" href="assets/plugins/gritter/css/jquery.gritter.css" />
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
          <!-- BEGIN PAGE TITLE & BREADCRUMB-->
          <h3 class="page-title"> &nbsp;<small></small> </h3>
          <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
      </div>
    <?php 	if(isset($_GET['msg'])){ ?>
      		<div class="alert alert-success">
			<button data-dismiss="alert" class="close"></button>
			<?php echo $mess[$_GET['msg']];	?>
            </div>
            <?php } ?>
      <!-- END PAGE HEADER-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row-fluid">
        <div class="span12">
          <!-- BEGIN EXAMPLE TABLE PORTLET-->

          <div class="portlet box light-grey">
            <div class="portlet-title">
            	
                <div class="caption"><i class="icon-globe"></i>
            <?php 
                           if(isset($_REQUEST['script'])){
                                $title = $_REQUEST['script'].' '.$label ;
                                echo  ucwords(str_replace('_',' ',$title)); 
                            }else{
                                
                                echo  ucwords(str_replace('_',' ',$label)); 
                            }
                        ?> 
                        
                
                </div>
              <div class="tools"> <a href="javascript:;" class="collapse"></a> <!--a href="#portlet-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a--> </div>
            </div>
                              
  <?php  if (isset($_REQUEST['script'])  ||  $_REQUEST['script'] ==  'add' ||  $_REQUEST['script'] == 'edit'){
	  ?>
            <div class="portlet-body form">
              <!-- BEGIN FORM-->
         
              
              <form name="<?php echo $label;?>" id="<?php echo $label;?>" method="post"  enctype="multipart/form-data">
                
                <div class="control-group">
                  <label class="control-label valiclrBold">Subscription Title *</label>
                  <div class="controls">
                  <input type="text" maxlength="150" name="sub_title"  id= "sub_title" size=30 value="<?php echo $row[0]['sub_title'];?>" class="validate[required] span6 m-wrap">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label valiclrBold">Strip Plan Id *</label>
                  <div class="controls">
                  <input type="text" maxlength="150" name="plan_strip_id"  id= "plan_strip_id" size=30 value="<?php echo $row[0]['plan_strip_id'];?>" class="validate[required] span6 m-wrap">
                  </div>
                </div>
                
                <div class="control-group">
                  <label class="control-label">Description</label>
                  <div class="controls">
                  <textarea name="sub_description" id="sub_description" class="span12 ckeditor m-wrap" rows="6"><?php echo $row[0]['sub_description'];?></textarea>
                  </div>
                </div>
                
                <div class="control-group">
                  <label class="control-label valiclrBold">No of Available User  *</label>
                  <div class="controls">
                 <input type="text" maxlength="150" name="sub_available_user"  id= "sub_available_user" size=30 value="<?php echo $row[0]['sub_available_user'];?>" class="validate[required,custom[number]] span6 m-wrap"> ( Enter value >= 10000 for Unlimited )
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label valiclrBold">No of Project  *</label>
                  <div class="controls">
                 <input type="text" maxlength="150" name="sub_available_project"  id= "sub_available_project" size=30 value="<?php echo $row[0]['sub_available_project'];?>" class="validate[required,custom[number]] span6 m-wrap"> ( Enter value >= 10000 for Unlimited )
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label valiclrBold">Price *</label>
                  <div class="controls">
                  <input type="text" maxlength="150" name="sub_price"  id= "sub_price" size=30 value="<?php echo $row[0]['sub_price'];?>" class="validate[required] span6 m-wrap"> USD
                  </div>
                </div>
                
                
                <div class="control-group">
                  <label class="control-label valiclrBold">Duration *</label>
                  <div class="controls">
                  <input type="text" maxlength="150" name="sub_duration"  id= "sub_duration" size=30 value="<?php echo $row[0]['sub_duration'];?>" class="validate[required,custom[number]] span3 m-wrap">
                	<select name="sub_duration_type" class="validate[required] span3 m-wrap" id="sub_duration_type">
                                    <option value="" selected="selected" >Duration type</option>
                                    <option value="day"  <?php if($row[0]['sub_duration_type']=='day') {echo 'selected="selected"';} ?>  >Days</option>
                                    <option value="week"  <?php if($row[0]['sub_duration_type']=='week') {echo 'selected="selected"';} ?> >Weeks</option>
                                    <option value="month"  <?php if($row[0]['sub_duration_type']=='month') {echo 'selected="selected"';} ?> >Months</option>
                                    <option value="year"  <?php if($row[0]['sub_duration_type']=='year') {echo 'selected="selected"';} ?> >Years</option>
                 </select>
                  </div>
                </div>
                
                	
                <div class="control-group">
                  <label class="control-label valiclrBold">Status *</label>
                  <div class="controls">
                 <select name="sub_status" class="validate[required] span6 m-wrap" id="sub_status">
                                    <option value="" selected="selected" >Select Status</option>
                                    <option value="active"  <?php if($row[0]['sub_status']=='active') {echo 'selected="selected"';} ?>  >Active</option>
                                    <option value="inactive"  <?php if($row[0]['sub_status']=='inactive') {echo 'selected="selected"';} ?> >Deactive</option>
                 </select>                 
                  </div>
                </div>
                <div class="form-actions">
                    <?php 
						if($_REQUEST['script'] ==  'add'){
							$button_title  = 'Submit';
							$btn_cancel = "Cancel";
						}else{
							$button_title  = 'Save Changes';
							$btn_cancel = "Back";
							echo '<input type="hidden" name="id" id="id" value="'.$_REQUEST['id'].'" />';
						}
						?>
                                    
                                    <input type="submit" name="Submit" class="btn blue" value="<?php echo  $button_title;?>" />
                                    
                                    <input type="button" name="Cancel" class="btn"  value="<?php echo $btn_cancel;?>" onclick="window.location.href='<?php echo $label;?>.php'" />
                                    
                 
                </div>
              </form>
              <!-- END FORM-->
            </div>
    <?php }else{ ?>
	        <div class="portlet-body">
                <form class="" name="frm" id="frm" method="post" > 
              <div class="table-toolbar">
                <div class="btn-group">
                  <a href="<?php echo $label ?>.php?script=add" id="sample_editable_1_new" class="btn green"> Add New <i class="icon-plus"></i> </a>
                  
                  <a href="javascript:;" id="sample_editable_1_new" class="btn red" onClick="onDeleteAll('Are you sure you want to delete the selected records ?',document.frm)"> Delete <i class="icon-remove"></i></a>
                  
                  
                </div>
              </div>
            <table class="table table-striped table-bordered table-hover" id="sample_1_demo">
                <thead>
                  <tr>
                    <th class="hidden-480" style="width:8px;"><input type="checkbox" data-set="#sample_1_demo .checkboxes" class="group-checkable"></th>
                    <th class="hidden-480">Title</th>
                    <th class="hidden-480">Price</th>
                    <th class="hidden-480">Duration</th>
                    <th class="hidden-480">No of User</th>
                    <th class="center hidden-480">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php  $datalist = $dclass->select("*",'tbl_subscription_plan',"ORDER BY sub_id desc"); 
				  		foreach($datalist as $rows){
				  ?>
                  <tr class="odd gradeX">
                    <td class="hidden-480"><input type="checkbox" name="chk[]" class="checkboxes" value="<?php echo $rows['sub_id']; ?>" /></td>
                    <td class="hidden-480"><a href="<?php echo $label ?>.php?script=edit&id=<?php echo $rows['sub_id']; ?>"><?php echo $rows['sub_title']; ?></a></td>
                    <td class="hidden-480">USD <?php echo $rows['sub_price']; ?> </td>
                    <td class="hidden-480"><?php echo $rows['sub_duration'].' '.$rows['sub_duration_type']; ?> </td>
                   <td class="hidden-480"><?php echo $rows['sub_available_user']; ?> </td>
                    <td class="center hidden-480">
                    <?php
            if( $rows['sub_status'] == 'inactive')
			{
				
				?>
                <span><a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['sub_id'];?>" onclick="return confirm('Are you sure you want to activate the selected records ?');" title="deactivate"class="badge badge-roundless badge-important">Inactive</a></span>
                <?php 	
			}
			else{
				?>	
            	<span><a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['sub_id'];?>" onclick="return confirm('Are you sure you want to deactivate the selected records ?');" title="activate" class="badge badge-roundless badge-success">Active</a></span>	    
                <?php
			}
			?>
            <span><a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['sub_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit-icon.png"  name="Edit" alt=""></a> </span> 
            <span><a href="<?php echo  $label.'.php?script=delete&id='.$rows['sub_id'];?>" onclick="return confirm('Are you sure you want to delete this record?');" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete-icon.png"  name="Delete" alt=""></a></span>
                    
                    </td>
                  </tr>
                  <?php }  ?>
                </tbody>
              </table>
              </form>
            </div>
  <?php	}?>
   </div>
          <!-- END SAMPLE FORM PORTLET-->
        </div>
      </div>
      
      <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER-->
  </div>                
            
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
<script src="assets/scripts/form-validation.js"></script> 
<script src="assets/scripts/form-components.js"></script>


<!-- END PAGE LEVEL SCRIPTS -->
<script>
		jQuery(document).ready(function($) {       
		   // initiate layout and plugins
		   App.init();
		   FormComponents.init();
		   var form_id = "<?php echo $label;?>";
		   $("#"+form_id).validationEngine();
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
