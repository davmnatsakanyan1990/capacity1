<?php 
// Configration 
	include("config/configuration.php"); 
	$load  =  new loader();	
	$objPages = $load->includeclasses('team');
	$label = "team";
	$dclass   =  new  database();
	$gnrl =  new general;
$load->includeother('header');

	// check for login
	
if(!$gnrl->checkUserLogin())
{
	$gnrl->redirectTo("index.php?msg=logfirst");
	
}

// Insert Record in database starts
if(isset($_POST['Submit']) && $_POST['Submit'] == 'Submit'){
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

<!-----------------------------////content////---------------------------------->
<!--main content start-->
<link rel="stylesheet" type="text/css" href="admin/assets/plugins/select2/select2_metro.css" />
<link rel="stylesheet" href="admin/assets/plugins/data-tables/DT_bootstrap.css" />
<section id="main-content">
          <section class="wrapper site-min-height">
          	<h3><i class="fa fa-angle-right"></i> Team</h3>
          
		        <?php if(isset($_REQUEST['msg'])){ ?>
      		<div class="alert alert-success">
			<button data-dismiss="alert" class="close"></button>
			<?php echo $mess[$_REQUEST['msg']];	?>
            </div>
            <?php } ?>
		  <?php  if (isset($_REQUEST['script'])  ||  $_REQUEST['script'] ==  'add' ||  $_REQUEST['script'] == 'edit'){
			    ?>

            <div class="row mt">
          		<div class="col-lg-12">
            

                  <div class="form-panel">
                  	  <h4 class="mb"><i class="fa fa-angle-right"></i> Add Team</h4>
                       
                       <form name="<?php echo $label;?>" class="form-horizontal style-form" id="<?php echo $label;?>" method="post"  enctype="multipart/form-data">
                             <input type="hidden" name="script" value="<?php echo $_REQUEST['script']; ?>"  />
                             
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Title</label>
                              <div class="col-sm-4">
                                  <input type="text" name="tm_title" class="form-control validate[required]" value="<?php echo $row[0]['tm_title'] ?>"  >
                              </div>
                          </div>
                          <?php $memberlist = $dclass->select("user_id,fname,lname","tbl_user"," AND user_comp_id=".$_SESSION['user_id']); 
						  
						  $memlist = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id=".$_REQUEST['id']); 
						 
						 $chkUids = explode(',',$memlist[0]['uid']);
					
						  ?>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Team Members</label>
                              <div class="col-sm-4">
                                  <select  name="user_ids[]" id="user_ids" multiple="multiple" placeholder="Choose Members" class="span6 m-wrap select2_category" >           								 								
								  <?php foreach($memberlist as $mem){ 
										if(in_array($mem['user_id'],$chkUids)){
											 $selected="selected='selected'";
										}else{
											 $selected="";
										}
								?>
                                
                               <option value="<?php echo $mem['user_id'] ?>" <?php echo $selected; ?>  ><?php echo $mem['fname'].' '.$mem['lname']; ?></option>
								<?php } ?>
                               </select>
                                   
                              </div>
                          </div>
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Status</label>
                              <div class="col-sm-4">
                                    <select name="tm_status" class="validate[required] span6 m-wrap" id="tm_status">
                                    <option value="" selected="selected" >Select Status</option>
                                    <option value="active"  <?php if($row[0]['tm_status']=='active') {echo 'selected="selected"';} ?>  >Active</option>
                                    <option value="inactive"  <?php if($row[0]['tm_status']=='inactive') {echo 'selected="selected"';} ?> >Deactive</option>
                                  </select>  
                              </div>
                          </div>
                          
                         
                          <?php 
						if($_REQUEST['script'] ==  'edit'){
							$button_title  = 'Save Changes';
							$btn_cancel = "Back";
							echo '<input type="hidden" name="id" id="id" value="'.$_REQUEST['id'].'" />';
						
						}else{
							$button_title  = 'Submit';
							$btn_cancel = "Cancel";
						}
						?>
                                    
                                    <input type="submit" name="Submit" class="btn  btn-success" value="<?php echo  $button_title;?>" />
                                     <input type="button" name="Cancel" class="btn"  value="<?php echo $btn_cancel;?>" onclick="window.location.href='<?php echo $label;?>.php'" />
                          
                      </form>
                  </div>
          		
                
                </div><!-- col-lg-12-->      	
          	</div>
          
          <?php 
		  }else{ ?>   
            
            <div class="row">
				      <div class="col-md-12">
                      <div class="content-panel" style="padding:15px">
                          <form class="" name="frm" id="frm" method="post" > 
              <div class="table-toolbar">
                <div class="btn-group">
                  <a href="<?php echo $label ?>.php?script=add" id="sample_editable_1_new" class="btn btn-success"> Add New <i class="icon-plus"></i> </a>
                  
                  <!--a href="javascript:;" id="sample_editable_1_new" class="btn btn-danger" onClick="onDeleteAll('Are you sure you want to delete the selected records ?',document.frm)"> Delete <i class="icon-remove"></i></a-->
                  
                  
                </div>
              </div>
            		<table class="table table-striped table-bordered table-hover" id="sample_1_demo">
                <thead>
                  <tr>
                    <th class="hidden-480" style="width:8px;"><input type="checkbox" data-set="#sample_1_demo .checkboxes" class="group-checkable"></th>
                    <th class="hidden-480">Team Title</th>
                    <th class="hidden-480">No of Member</th>
                    <th class="center hidden-480">Action</th>
                   
                  </tr>
                </thead>
                <tbody>
                  <?php  $datalist = $dclass->select("*",'tbl_team',"AND company_user_id = '".$_SESSION['user_id']."' ORDER BY tm_id desc"); 
				  		foreach($datalist as $rows){ 
						$no_of_comp = $dclass->select('COUNT(*) as cnt',"tbl_team_detail"," AND tm_id=".$rows['tm_id']);
						  ?>
                  <tr class="odd gradeX">
                    <td class="hidden-480">
                   
                    <input type="checkbox" name="chk[]" class="checkboxes" value="<?php echo $rows['tm_id']; ?>" />
                   
                    </td>
                    <td class="hidden-480"><a href="<?php echo $label ?>.php?script=edit&id=<?php echo $rows['tm_id']; ?>"><?php echo $rows['tm_title']; ?></a></td>
                    <td class="hidden-480"><?php echo $no_of_comp[0]['cnt']; ?></td>
                    <td class="center hidden-480">
                    <?php
            if( $rows['tm_status'] == 'inactive')
			{
				
				?>
                <span><a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['tm_id'];?>" onclick="return confirm('Are you sure you want to activate the selected records ?');" title="deactivate"class="badge badge-roundless badge-important">Inactive</a></span>
                <?php 	
			}
			else{
				?>	
            	<span><a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['tm_id'];?>" onclick="return confirm('Are you sure you want to deactivate the selected records ?');" title="activate" class="badge badge-roundless badge-success">Active</a></span>	    
                <?php
			}
			?>
            <span><a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['tm_id'];?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a> </span> 
            
            <span><a href="<?php echo  $label.'.php?script=delete&id='.$rows['tm_id'];?>" onclick="return confirm('Are you sure you want to delete this record?');" class="delfset btn btn-danger btn-xs" id="del1"> <i class="fa fa-trash-o "></i></a></span>
            
        
          

                    </td>
                  </tr>
                  <?php }  ?>
                </tbody>
              </table>
              </form>
                          
                      </div><!-- /content-panel -->
                  </div><!-- /col-md-12 -->
				</div>
           
		   <?php } ?>     
            
		</section><! --/wrapper -->
      </section><!-- /MAIN CONTENT -->
<!--main content end-->

<?php $load->includeother('footer');?>
<?php if(isset($_REQUEST['script']) || $_REQUEST['script'] == 'add' || $_REQUEST['script'] == 'edit'){ ?>
<script type="text/javascript" src="admin/assets/plugins/select2/select2.min.js"></script> 
<script>
    	$(document).ready(function() {
			var id = '<?php echo $label;?>';
            $('#'+id).validationEngine();
			$("#user_ids").select2();
        });
    </script>
<?php }else{ ?>   
<script type="text/javascript" src="admin/assets/plugins/data-tables/jquery.dataTables.js"></script>
<script src="admin/assets/scripts/table-managed.js"></script>

<script>
    	$(document).ready(function() {
			 TableManaged.init();
        });
    </script> 
<?php } ?>