<?php 
session_start();
$_SESSION['adminid'] ='';
$_SESSION['user_type'] ='';

unset($_SESSION['adminid']); 
unset($_SESSION['user_type']); 

//session_destroy();
header("Location:index.php");
exit;
?>