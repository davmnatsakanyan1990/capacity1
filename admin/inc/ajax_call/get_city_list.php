<?php 
// Configration 
include('../../../config/configuration.php');
include('../../../classes/database.class.php');
$dclass=new database();

if(isset($_REQUEST['country']) && $_REQUEST['country'] !='')
{
$country = $dclass->select('*','tbl_city'," AND country_id =  '".$_REQUEST['country']."'");
 $menu = '<option value="">-Select City-</option>';
 foreach($country as $st) {
		 
        $menu .= '<option value="'.$st['city_id'].'">' . $st['city_title'] . '</option>';
    }
   
    echo $menu;

}

   

?>