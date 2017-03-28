<?php
include("../config/configuration.php"); $load  =  new loader();	

	$label = "bank_detail";
	$dclass   =  new  database();
	$gnrl =  new general;




$result = mysql_query("SELECT ".$_REQUEST['fieldname']." FROM tbl_banking_detail GROUP BY ".$_REQUEST['fieldname']." ORDER BY ".$_REQUEST['fieldname']." asc ");
while ($row = mysql_fetch_assoc($result)) {
   		$colors[]=$row[$_REQUEST['fieldname']];
}
mysql_free_result($result);
// check the parameter
if(isset($_REQUEST['term']) and $_REQUEST['term'] != '')
{
	// initialize the results array
	$results = array();

	// search colors
	foreach($colors as $color)
	{
		// if it starts with 'part' add to results
		if( strpos(strtolower($color), strtolower($_REQUEST['term'])) === 0 ){
			$results[] = $color;
		}
	}
	
	// return the array as json with PHP 5.2
	$resSet = implode(',',$results);
	$responce = '[';
	foreach($results as $res){
		$responce .= '"'.$res.'",';	
	}
	
	$responce = trim($responce,',');
	$responce .= ']';
	echo $responce;
	
	
}
?>