<?php 
require_once("../../../config/configuration.php");  
require_once('../../../classes/database.class.php');
$dclass = new database();
include_once("../../../classes/general.class.php");

$gnrl =  new general();


if(isset($_POST['sec_id']) )
{
	print_r($_POST);
	die();
	if($_POST['scp'] == 'remove_bg')
	{
		$row = $dclass->select(' * ','tbl_section'," AND sec_id = '".$_POST['sec_id']."'");
	
		if(isset($row[0]['sec_bg_image 	'])){
			unlink('../../../upload/section/'.$row[0]['sec_bg_image']);
		}

		 $insimage = array('sec_bg_image'=>$filename);
		 $dclass->update('tbl_section',$insimage," sec_id = '".$_POST['sec_id']."'");

	}
	
		echo "yes, Image has been Removed.";

	
}
?>