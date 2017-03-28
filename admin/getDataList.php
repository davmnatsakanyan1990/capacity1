<?php 
require_once("../config/configuration.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();


if(isset($_POST['ids']) && $_POST['ids'] != '' )
{ 
	
	
	$rowInserted = $dclass->select(' * ','tbl_city'," AND country_id = ".$_POST['ids']." ORDER BY city_name asc");
		$html = '';
		$html .= '<option value="">Select City</option>';
		foreach($rowInserted as $row){
			
			$html .='<option value="'.$row['city_id'].'" '.$selected.' >'.$row['city_name'].'</option>';
			
		}
		echo $html;
	
}
?>