<?php 
require_once("../../../config/configuration.php");  
require_once('../../../classes/database.class.php');
$dclass = new database();
include_once("../../../classes/general.class.php");


if(isset($_REQUEST['year']) && $_REQUEST['year'] !='')
{

$datedis=date('Y-m-d');
$days_ago = date('Y-m-d', strtotime('-18 years', strtotime($datedis)));
$birthdate = $_REQUEST['year'].'-'.$_REQUEST['month'].'-'.$_REQUEST['day'];
if( $days_ago < $birthdate )
{
		echo '1';
}else{
		echo '0';
}

}


   

?>