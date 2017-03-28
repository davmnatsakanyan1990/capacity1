<?php 
// Configration 
include('../../../config/configuration.php');
include('../../../classes/database.class.php');
$dclass=new database();


if(isset($_REQUEST['username']) && $_REQUEST['username'] !='')
{
$username = $dclass->select('*','tbl_user'," AND username =  '".$_REQUEST['username']."'");
$username=count($username);

if( $username > 0 )

	{

		echo '1';

		

	}else{

		echo '0';

		

	}

}


   

?>