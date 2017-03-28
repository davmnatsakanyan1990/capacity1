<?php 
// Configration 
	include("../config/configuration.php"); $load  =  new loader();	
	$objPages = $load->includeclasses('permission');
	$label = "permission";
	$dclass   =  new  database();
	$gnrl =  new general;


	// check for login
if(!$gnrl->checkLogin())
{
	$gnrl->redirectTo("index.php?msg=logfirst");
	
}
$gnrl->checkLoginExpire();

if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Save Changes') 
{
			$dataUpdate =  $objPages->updateData();
			if($dataUpdate == '1'){
				$objPages->redirect($label.'.php?msg=edit');
			}else{
				$_GET['msg']  =  $dataUpdate;
			}
		}
		// Update Records in database ends
		else {
			$row  =  $objPages->getData();
			if(!empty($row))	extract($row);
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
                              
  <?php  //if (isset($_REQUEST['script'])  ||  $_REQUEST['script'] ==  'add' ||  $_REQUEST['script'] == 'edit'){
	  
	  ?>
            <div class="portlet-body form">
              <!-- BEGIN FORM-->
              <?php $tasklist = array(
			  						"1" => 'TASK_ADD',
									"2" => 'TASK_UPDATE',
									"3" => 'TASK_DELETE',
									"4" => 'TASK_VIEW',
									"5" => 'USER_ADD',
									"6" => 'USER_UPDATE',
									"7" => 'USER_DELETE',
									"8" => 'USER_VIEW',
			  						); 
									
				$role = $dclass->select("*","tbl_role"," AND r_status='active'");
				
				?>
              
              <form name="<?php echo $label;?>" id="<?php echo $label;?>" method="post"  enctype="multipart/form-data">
                
                <table class="table table-striped table-bordered table-hover" >
                <thead>
                  <tr>
                    <th class="center hidden-480 span3">Module / Users </th>
                    <?php foreach($role as $rl){ ?>
                  			<th class="center hidden-480 span3"><?php echo $rl['r_title']; ?></th>
                    <?php } ?>        
                    
                  </tr>
                  <?php foreach($tasklist as $tkey=>$tval){ ?>
                  		<tr>
                    		<th class="center hidden-480 span3"><?php echo ucfirst(strtolower(str_replace('_',' ',$tval))); ?></th>
                        	<?php foreach($role as $rl){ 
							$check_peram = $dclass->select("*","tbl_role_access"," AND r_id='".$rl['r_id']."' AND v_name = '".$tval."'");
								if(count($check_peram) > 0 && $check_peram[0]['l_value'] == 'yes' ){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
							?>
                  			<th class="center hidden-480 span3"><input type="checkbox" <?php echo $checked; ?> id="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" name="check[<?php echo $tkey; ?>][<?php echo $rl['r_id']; ?>]" value="yes" class="group-checkable"></th>
				  		<?php  } ?>
                          </tr>  
				  <?php } ?>
                  <!--<tr>
                    <th class="center hidden-480 span3" style="width:8px;">Add Task</th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                  </tr>
                  
                  <tr>
                    <th class="center hidden-480 span3" style="width:8px;">Upadate Task</th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                  </tr>
                  
                  <tr>
                    <th class="center hidden-480 span3" style="width:8px;">Delete Task</th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                  </tr>
                  
                  <tr>
                    <th class="center hidden-480 span3" style="width:8px;">Add User</th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                  </tr>
                  
                  <tr>
                    <th class="center hidden-480 span3" style="width:8px;">Upadate User</th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                  </tr>
                  
                  <tr>
                    <th class="center hidden-480 span3" style="width:8px;">Delete User</th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                    <th class="center hidden-480 span3"><input type="checkbox" class="group-checkable"></th>
                  </tr>-->
                  
                  
                </thead>
                
                </table>
                
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
    <?php 
	//}else{ ?>
	        <?php /*?><div class="portlet-body">
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
                    <th class="center hidden-480">Action</th>
                   
                  </tr>
                </thead>
                <tbody>
                  <?php  $datalist = $dclass->select("*",'tbl_cms',"ORDER BY cms_id desc"); 
				  		foreach($datalist as $rows){
				  ?>
                  <tr class="odd gradeX">
                    <td class="hidden-480"><input type="checkbox" name="chk[]" class="checkboxes" value="<?php echo $rows['cms_id']; ?>" /></td>
                    <td class="hidden-480"><a href="<?php echo $label ?>.php?script=edit&id=<?php echo $rows['cms_id']; ?>"><?php echo $rows['cms_name']; ?></a></td>
                    <td class="center hidden-480">
                    <?php
            if( $rows['cms_publish'] == '0')
			{
				
				?>
                <span><a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['cms_id'];?>" onclick="return confirm('Are you sure you want to activate the selected records ?');" title="deactivate"class="badge badge-roundless badge-important">Inactive</a></span>
                <?php 	
			}
			else{
				?>	
            	<span><a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['cms_id'];?>" onclick="return confirm('Are you sure you want to deactivate the selected records ?');" title="activate" class="badge badge-roundless badge-success">Active</a></span>	    
                <?php
			}
			?>
            <span><a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['cms_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif"  name="Edit" alt=""></a> </span> 
            <span><a href="<?php echo  $label.'.php?script=delete&id='.$rows['cms_id'];?>" onclick="return confirm('Are you sure you want to delete this record?');" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete-icon.png"  name="Delete" alt=""></a></span>
                    
                    </td>
                  </tr>
                  <?php }  ?>
                </tbody>
              </table>
              </form>
            </div><?php */?>
  <?php	//}?>
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
<script src="assets/scripts/form-components.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>

		jQuery(document).ready(function($) {       

		   // initiate layout and plugins

		   App.init();

		   FormComponents.init();
		  var form_id = "<?php echo $label;?>";
		   $("#"+form_id).validationEngine(); 
		   
		$("#country_id").live('change',function(){
		// code for reviewre
		var ids = $(this).val();
		$.ajax({
			type:"post",
			url:"getDataList.php",
			data:{ids:ids},
				success:function(data){							
					$('#city_id').html(data);
					
			}
		 });
		
		
	});
	$("#area_title").live('blur',function(){
		// code for reviewre
		var country_id = $("#country_id").val();
		var city_id = $("#city_id").val();
		var area = $(this).val();
		$.ajax({
			type:"post",
			url:"getLatLon.php",
			data:{country_id:country_id,city_id:city_id,area:area},
				success:function(data){							
					if(data != ''){
					var latlong = data.split(":::");
					var lat = latlong[0];
					var lon = latlong[1];
					$('#lat').val(lat);
					$('#lon').val(lon);
					}
			}
		 });
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
