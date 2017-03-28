<?php 
// Configration 
	include("config/configuration.php"); 
	$load  =  new loader();	
	$objPages = $load->includeclasses('register');
	$label = "user_account";
	$dclass   =  new  database();
	$gnrl =  new general;
	$load->includeother('header');
	global $mail;
	// check for login

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= "From: support@capacity.com"; //Initialise email from which emails will be sent

 $email_template = $dclass->select('*','tbl_email_template',' AND email_title = "Error Processing Payment"');
      
      $message = str_replace('{NAME}','Ashish Panchal',$email_template[0]['email_body']);
      $logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
      $message = str_replace('{LOGO}',$logo,$message);
   
      /*echo $message;
      $mail->Subject  =   $email_template[0]['email_subject'];
      //$mail->addAddress($userdetail[0]['email']);
      $mail->addAddress('aspanchal86@gmail.com');
      $mail->addAddress('sanjay@zealousys.com');
      $mail->msgHTML($message);
      $mail->send();
      */
     mail('aspanchal86@gmail.com',$email_template[0]['email_subject'],$message, $headers);
     mail('sanjay@zealousys.com',$email_template[0]['email_subject'],$message, $headers);
 die();         
  
?>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="TEMRC59R5VG2W">
<input type="image" src="https://www.sandbox.paypal.com/en_US/GB/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form> 

<?php


if(!$gnrl->checkUserLogin())
{
  $gnrl->redirectTo("index.php?msg=logfirst");
  
}
$seconds = strtotime("2015-04-15 15:30:00") - strtotime("2015-04-13 10:30:00");

$days    = floor($seconds / 86400);
$hours   = floor(($seconds - ($days * 86400)) / 3600);
$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
$seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));

echo "Day=>".$days.'<br>';
echo "hours=>".$hours.'<br>';
echo "minutes=>".$minutes.'<br>';
echo "seconds=>".$seconds.'<br>';

die();

function isSunday($day, $month, $year,$daynumber)
{
    if (date('w', $date = mktime(0,0,0,$month,$day,$year)) == $daynumber) {
        return $date;
    }
    return false;
}
function getWednesdays($month, $year,$daynumber)
{
    
    for ($day=1; $day<=7; $day++) {
        if ($date = isSunday($day, $month, $year,$daynumber)) {
            break;
        }
    }

    $wednesdays = array();

    while (date('m',$date) == $month) {
        $wednesdays[] = $date;
        $day += 7;
        $date = isSunday($day, $month, $year,$daynumber);
    }
    return $wednesdays;
}
$data = getWednesdays(04,2015,3);
echo count($data);

die();
// this is code mail sending and its working fine
/*$htmlll = "<table><tr><td>123</td><td>123</td><td>123</td><td>123</td></tr><tr><td>123</td><td>123</td><td>123</td><td>123</td></tr></table>";
$mail->addAddress('aspanchal86@gmail.com', 'Ashish Panchal');
$mail->Subject = 'PHPMailer GMail SMTP test';
$mail->msgHTML($htmlll);
//$mail->AltBody = 'This is a plain-text message body';
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}*/



// tesing code end 
if(isset($_REQUEST['script'])){
  if($_REQUEST['script'] == 'add'){
    if(!$gnrl->checkUserAccess("USER_ADD")){
        $gnrl->redirectTo("index.php?msg=accessdenid");
    }

  }
  if($_REQUEST['script'] == 'edit'){
    if(!$gnrl->checkUserAccess("USER_UPDATE")){
        $gnrl->redirectTo("index.php?msg=accessdenid");
    }

  }
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
<link rel="stylesheet" href="admin/assets/plugins/data-tables/DT_bootstrap.css" />
<section id="main-content">
          <section class="wrapper site-min-height">
          	<h3><i class="fa fa-angle-right"></i> User Account</h3>
          
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
                  	  <h4 class="mb"><i class="fa fa-angle-right"></i> Add User Account</h4>
                       
                       <form name="<?php echo $label;?>" class="form-horizontal style-form" id="<?php echo $label;?>" method="post"  enctype="multipart/form-data">
                             <input type="hidden" name="script" value="<?php echo $_REQUEST['script']; ?>"  />
                             
                             <input type="hidden" name="user_status" value="active" class="form-control"  >
                    
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Email</label>
                              <div class="col-sm-4">
                                  <input type="text" name="user_email" class="form-control validate[required,custom[email]]" value="<?php echo $row[0]['email'] ?>" placeholder="User Email" >
                              </div>
                          </div>
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Password</label>
                              <div class="col-sm-4">
                                  <input type="password" name="password" id="password" value="<?php echo base64_decode($row[0]['password']); ?>" class="form-control validate[required]" >
                              </div>
                          </div>
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">First name</label>
                              <div class="col-sm-4">
                                  <input type="text" name="user_first_name" class="form-control validate[required]" value="<?php echo $row[0]['fname']; ?>" placeholder="First name">
                              </div>
                          </div>
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Last name</label>
                              <div class="col-sm-4">
                                  <input type="text" name="user_last_name" class="form-control validate[required]" value="<?php echo $row[0]['lname']; ?>" placeholder="Last name">
                              </div>
                          </div>
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Gender</label>
                              <div class="col-sm-4">
                                   <input type="radio"  value="M" name="user_gender" class="validate[required] radio" <?php if($row[0]['gender'] == 'M') 
								   echo 'checked="checked"';  ?> style="float:left">
                    &nbsp;&nbsp;<span style="float:left"> Male</span>
                    
                    &nbsp;&nbsp;<input type="radio"  value="F" name="user_gender" class="validate[required] radio" <?php if($row[0]['gender'] == 'F') 
								   echo 'checked="checked"';  ?> style="float:left">&nbsp;&nbsp;<span style="float:left"> Female</span>
                              </div>
                          </div>
                          <?php $dep_list = $dclass->select("*","tbl_company_department"," AND company_user_id =".$_SESSION['user_id']); ?>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Department</label>
                              <div class="col-sm-4">
                                    <select name="dep_id" class="validate[required] span6 m-wrap" id="dep_id">
                                    <option value="" selected="selected" >Select Department</option>
                                    
                                 <?php foreach($dep_list as $de){
									 	if($de['de_id'] == $row[0]['dep_id']){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
									  ?>
                                    <option value="<?php echo $de['de_id']; ?>"  <?php echo $selected; ?> ><?php echo $de['de_title']; ?></option>
                                    <?php } ?>
                       
                                  </select>  
                              </div>
                          </div>
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">User Type</label>
                              <div class="col-sm-4">
                                  <select name="user_type" class="validate[required] span6 m-wrap" id="user_type">
                                    <option value="" selected="selected" >Select Type</option>
                                    <option value="manager"  <?php if($row[0]['user_type']=='manager') {echo 'selected="selected"';} ?>  >Manager</option>
                                    <option value="employee"  <?php if($row[0]['user_type']=='employee') {echo 'selected="selected"';} ?> >Employee</option>
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
                  <?php if($gnrl->checkUserAccess("USER_ADD")){ ?>
                    <a href="<?php echo $label ?>.php?script=add" id="sample_editable_1_new" class="btn btn-success"> Add New <i class="icon-plus"></i> </a>
                  <?php } ?>
                  <!--a href="javascript:;" id="sample_editable_1_new" class="btn btn-danger" onClick="onDeleteAll('Are you sure you want to delete the selected records ?',document.frm)"> Delete <i class="icon-remove"></i></a-->
                  
                  
                </div>
              </div>
            		<table class="table table-striped table-bordered table-hover" id="sample_1_demo">
                <thead>
                  <tr>
                    <th class="hidden-480" style="width:8px;"><input type="checkbox" data-set="#sample_1_demo .checkboxes" class="group-checkable"></th>
                    <th class="hidden-480">Name</th>
                    <th class="hidden-480">Email</th>
                    <th class="hidden-480">User Type</th>
                    <th class="center hidden-480">Action</th>
                   
                  </tr>
                </thead>
                <tbody>
                  <?php  $datalist = $dclass->select("*",'tbl_user',"AND user_comp_id = '".$_SESSION['user_id']."' OR user_id = '".$_SESSION['user_id']."' ORDER BY user_id desc"); 
				  		foreach($datalist as $rows){ ?>
                  <tr class="odd gradeX">
                    <td class="hidden-480">                   
                    <input type="checkbox" name="chk[]" class="checkboxes" value="<?php echo $rows['user_id']; ?>" />
                    </td>
                    <td class="hidden-480">
                    <?php  if($gnrl->checkUserAccess("USER_UPDATE")){ ?>  
                    <a href="<?php echo $label ?>.php?script=edit&id=<?php echo $rows['user_id']; ?>"><?php echo $rows['fname'].' '.$rows['lname']; ?></a>
                    <?php }else{ ?>
                    <?php echo $rows['fname'].' '.$rows['lname']; ?>
                    <?php } ?>
                    </td>
                    
                    <td class="hidden-480"><?php echo $rows['email']; ?></td>
                    <td class="hidden-480"><?php echo $rows['user_type']; ?></td>
                    <td class="center hidden-480">
      <?php  if($gnrl->checkUserAccess("USER_UPDATE")){ ?>
          <?php  if( $rows['de_status'] == 'inactive'){ ?>
                  <span><a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['user_id'];?>" onclick="return confirm('Are you sure you want to activate the selected records ?');" title="deactivate"class="badge badge-roundless badge-important">Inactive</a></span>
          <?php 	}else{ ?>	
                	<span><a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['user_id'];?>" onclick="return confirm('Are you sure you want to deactivate the selected records ?');" title="activate" class="badge badge-roundless badge-success">Active</a></span>	    
          <?php } ?>
     
          <span><a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['user_id'];?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a> </span> 
       
      <?php } ?>

       <?php  if($gnrl->checkUserAccess("USER_DELETE")){ ?>
            <span><a href="<?php echo  $label.'.php?script=delete&id='.$rows['user_id'];?>" onclick="return confirm('Are you sure you want to delete this record?');" class="delfset btn btn-danger btn-xs" id="del1"> <i class="fa fa-trash-o "></i></a></span>
       <?php } ?>     
        
          

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
<script>
    	$(document).ready(function() {
			var id = '<?php echo $label;?>';
            $('#'+id).validationEngine();
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