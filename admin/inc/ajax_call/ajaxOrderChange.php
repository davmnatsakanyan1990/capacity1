<?php
//echo '<pre>';print_r($_REQUEST);
include_once("../config/dbconfig.php");
		global $glob;
		$host = $glob['dbhost'];
		$user = $glob['dbusername'];
		$pass = $glob['dbpassword'];
		$db = $glob['dbdatabase'];
		if ($link= @mysql_connect( $host, $user, $pass )) {
			mysql_select_db($db) or die('Cant select database'.mysql_error());
			//echo "in";
		}
		else{
			echo "Could not connect to the database!";
			exit;
		}				

	$action 				= mysql_real_escape_string('updateRecordsListings'); 

	$updateRecordsArray 	=  $_REQUEST["nicetable"];
	$table  =  $_REQUEST["table"];
	$id =  $_REQUEST['id'];
	$id_order =  $_REQUEST['id_order'];

if ($action == "updateRecordsListings"){
	
	$listingCounter = 1;
	foreach ($updateRecordsArray as $recordIDValue) {
		if ($recordIDValue == 'tab_header')
			continue;
		else{
		$query = "UPDATE ".$_REQUEST['table']." SET ".$id_order." = " . $listingCounter . " WHERE ".$id." = " . $recordIDValue;
		mysql_query($query) or die('Error, insert query failed');
		$listingCounter = $listingCounter + 1;	
		}
	
	}
	
	/*echo '<pre>';
	print_r($updateRecordsArray);
	echo '</pre>';*/
	echo 'If you refresh the page, you will see that records will stay just as you modified.';
}

?>
