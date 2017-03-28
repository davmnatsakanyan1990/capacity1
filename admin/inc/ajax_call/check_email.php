<?php 
// Configration 
require_once("../../../config/configuration.php");  
require_once('../../../classes/database.class.php');
$dclass=new database();


if(isset($_REQUEST['email']) && $_REQUEST['email'] !='')
{
$email = $dclass->select('*','tbl_user'," AND email =  '".$_REQUEST['email']."'");
$email=count($email);

if( $email > 0 )

	{

		echo '1';

		

	}else{

		echo '0';

		

	}

}


   

?>