<?php 
include('config/configuration.php');
$load  =  new loader();	
$objPages = $load->includeclasses('user');	
$label = "logout";
$dclass   =  new  database();
$gnrl =  new general;
$_SESSION['user_id'] = '';
unset($_SESSION['user_id']);

setcookie("username", '', time() - 300);
setcookie("password", '', time() - 300); 
session_destroy();

header("Location:home");
?>
