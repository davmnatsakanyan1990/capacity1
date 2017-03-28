<?php
include_once("../config/configuration.php");
	$load  =  new loader();	
	$dclass   =  new  database();
	$gnrl =  new general;

if(isset($_REQUEST['country_id']) && $_REQUEST['country_id'] !='')
{
	
$city = $dclass->select('*','tbl_city'," AND city_status='active' AND country_id =  '".$_REQUEST['country_id']."'");
	$menu .= '<option value="">Select City</option>';
	foreach($city as $ct) {
         $menu .= '<option value="' . $ct['city_id'] . '">' . $ct['city_name'] . '</option>';
    }
    echo $menu;
}else if(isset($_REQUEST['city_id']) && $_REQUEST['city_id'] != ''){
    $area= $dclass->select('*','tbl_area'," AND area_status = 'active' AND city_id =  '".$_REQUEST['city_id']."'");
 	$menu .= '<option value="">Select Area</option>';
	foreach($area as $st) {
        $menu .= '<option value="' . $st['area_id'] . '">' . $st['area_title'] . '</option>';
    }
    echo $menu;
}else if(isset($_REQUEST['location_id']) && $_REQUEST['location_id'] != ''){
	
	$area= $dclass->select('*','tbl_area'," AND area_status = 'active' AND area_id =  '".$_REQUEST['location_id']."'");
	
	echo $area[0]['lat'].':::'.$area[0]['lon'];
}
?>
