<?php 
require_once("../../config/dbconfig.php");
require_once('../../classes/database.class.php');
$dclass = new database();
include_once("../../classes/general.class.php");
$gnrl =  new general();


if(isset($_POST['email']) && $_POST['email'] != '' )
{ 
	
	
	$rowInserted = $dclass->select(' * ','tbl_user'," AND email = '".$_POST['email']."' ");
	if(count($rowInserted) > 0){
		echo 1;
	}else{
		echo 0;
	}
	
}
?>