<?php 
// Configration 
	include("../config/configuration.php"); 
	$load  =  new loader();	
	$objPages = $load->includeclasses('export_csv');
	$label = "export_csv";
	$dclass   =  new  database();
	$gnrl =  new general;

$downloadlink = '';
	// check for login
if(!$gnrl->checkLogin())
{
	$gnrl->redirectTo("index.php?msg=logfirst");
	
}
$gnrl->checkLoginExpire();

$menuAccess = $dclass->select("*","tbl_system_group"," AND system_group_member LIKE '%,".$_SESSION['adminid'].",%' AND system_group_status ='active' ");

if(!empty($menuAccess))
{
	if(!$gnrl->checkGroupAccess('Export'))
	{
		$gnrl->redirectTo("home.php?msg=permissiondenied");
		
	}
}

if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Export Newsletter'){
		$dataGetall  =  $objPages->getAll();
		$filename = "../newsletter_csv/newsletter_".time().".csv";
		$handle = fopen($filename, 'w+');
		fputcsv($handle, array('Newsletter Id','Newsletter Email'));
		foreach($dataGetall as $row)
		{
			fputcsv($handle, array($row['newsletter_subscriber_id'], $row['newsletter_subscriber_email']));
			//print '"' . stripslashes(implode('","',$row)) . "\"\n";
		}
		fclose($handle);	
		$downloadlinkNews = 'Please <a href="'.$filename.'"> Click here </a> For download';

}

if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Export Place'){
		$dataPlace  =  $dclass->select("t1.*,t2.category_name","tbl_product t1 LEFT JOIN tbl_category t2 ON t1.category_id=t2.category_id"," AND t1.status='1'");
		
		$filename = "../newsletter_csv/placelist_".time().".csv";
		$handle = fopen($filename, 'w+');
		fputcsv($handle, array('Place Title','Category','Product Address','City','Contact No'));
		foreach($dataPlace as $row)
		{
			$city = $dclass->select("city_name","tbl_city"," AND city_id=".$row['city_id']);
			fputcsv($handle, array($row['product_name'],$row['category_name'],$row['product_address_1'].' '.$row['product_address_2'],$city[0]['city_name'],$row['product_contact_no']));
			//print '"' . stripslashes(implode('","',$row)) . "\"\n";
		}
		fclose($handle);	
		$downloadlinkPlace = 'Please <a href="'.$filename.'"> Click here </a> For download Placelist';

}


if(isset($_REQUEST['Submit']) && $_REQUEST['Submit']=='Export Member'){
		$dataPlace  =  $dclass->select("*","tbl_user"," AND user_status='Active' AND user_type='user'");
		
		$filename = "../newsletter_csv/memberlist_".time().".csv";
		$handle = fopen($filename, 'w+');
		fputcsv($handle, array('First Name','Last Name','User Email','City','Contact No'));
		foreach($dataPlace as $row)
		{
			$city = $dclass->select("city_name","tbl_city"," AND city_id=".$row['user_city_id']);
			fputcsv($handle, array($row['user_first_name'],$row['user_last_name'],$row['user_email'],$city[0]['city_name'],$row['user_contact_no']));
			//print '"' . stripslashes(implode('","',$row)) . "\"\n";
		}
		fclose($handle);	
		$downloadlinkMember = 'Please <a href="'.$filename.'"> Click here </a> For download Memberlist';

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
                              
            <div class="portlet-body form">
              <!-- BEGIN FORM-->
              <form name="<?php echo $label;?>" id="<?php echo $label;?>" method="post"  enctype="multipart/form-data">
                
                
                
                <div class="control-group">
                  <label class="control-label">Please Click Below Blue button for Export csv file of your Newsletter Subscriber List.  </label>
                
                </div>
                <div class="control-group">
                   <input type="submit" name="Submit" class="btn blue" value="Export Newsletter" />
                   
                </div>
                <?php if($downloadlinkNews != ''){
					?><div class="control-group">
                  <label class="control-label"><?php echo $downloadlinkNews; ?>  </label>
              
                </div>
                    <?php
					
				}?>
              </form>
              
             
              <!-- END FORM-->
            </div>
            
            <div class="portlet-body form">
              <!-- BEGIN FORM-->
              <form name="<?php echo $label;?>" id="<?php echo $label;?>" method="post"  enctype="multipart/form-data">
                
                
                
                <div class="control-group">
                  <label class="control-label">Please Click Below Blue button for Export csv file of Place List.  </label>
                
                </div>
                <div class="control-group">
                   <input type="submit" name="Submit" class="btn blue" value="Export Place" />
                   
                </div>
                <?php if($downloadlinkPlace != ''){
					?><div class="control-group">
                  <label class="control-label"><?php echo $downloadlinkPlace; ?>  </label>
              
                </div>
                    <?php
					
				}?>
              </form>
              
             
              <!-- END FORM-->
            </div>
            
            <div class="portlet-body form">
              <!-- BEGIN FORM-->
              <form name="<?php echo $label;?>" id="<?php echo $label;?>" method="post"  enctype="multipart/form-data">
                
                
                
                <div class="control-group">
                  <label class="control-label">Please Click Below Blue button for Export csv file of Member List.  </label>
                
                </div>
                <div class="control-group">
                   <input type="submit" name="Submit" class="btn blue" value="Export Member" />
                   
                </div>
                <?php if($downloadlinkMember != ''){
					?><div class="control-group">
                  <label class="control-label"><?php echo $downloadlinkMember; ?>  </label>
              
                </div>
                    <?php
					
				}?>
              </form>
              
             
              <!-- END FORM-->
            </div>
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
