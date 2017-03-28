<?php 
require_once("../config/configuration.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");

$gnrl =  new general();


if(isset($_REQUEST['category_id']) && $_REQUEST['category_id']!='')
{
	$pro_menu = $dclass->select('category_addmenu','tbl_category',' AND category_id='.$_REQUEST['category_id']);

	
	echo $pro_menu[0]['category_addmenu'];
	
}
?>

