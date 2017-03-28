<!DOCTYPE html>
<html lang="en">
<?php 
global $load; 
$objPages = $load->includeclasses('user');	
$objLogin = $load->includeclasses('login');	
$dclass   =  new  database();
global $basefile;
global $gnrl;
global $mess;
$userDetail = $dclass->select("fname,lname,user_avatar,r_id","tbl_user"," AND user_id=".$_SESSION['user_id']); 
if($_SESSION['r_id'] != $userDetail[0]['r_id']){
    $role = $dclass->select(' * ','tbl_role',' AND r_id='.$userDetail[0]['r_id']);  
    $_SESSION['user_type'] = $role[0]['r_type'];
    $_SESSION['r_id'] = $userDetail[0]['r_id'];
}
?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Capacity</title>
<link href="<?php echo SITE_URL; ?>css_ui/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,700italic,400italic&subset=latin,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
<link href="<?php echo SITE_URL; ?>css_ui/owl.carousel.css" rel="stylesheet">
<link href="<?php echo SITE_URL; ?>css_ui/owl.theme.css" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo SITE_URL; ?>images/favicon(1).ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE_URL; ?>images/favicon(1).ico" type="image/x-icon">
<script src="<?php echo SITE_URL; ?>js_ui/jquery-1.11.2.js"></script>
<script src="<?php echo SITE_URL; ?>js_ui/owl.carousel.min.js"></script>
<link href="<?php echo SITE_URL; ?>css_ui/style_home.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js_ui/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid wrapper">
  <div class="row header step">
    <div class="container">
      <div class="col-sm-2 col-md-2 pull-left">
        <div class="logo  pull-left"> <a href="<?php echo SITE_URL; ?>"> <img src="<?php echo SITE_URL; ?>images/logo.svg" /> </a> </div>
      </div>
      <div class="col-sm-10 col-md-8 pull-right">
        <ul class="links pull-right">
        </ul>
      </div>
    </div>
  </div>
  <div id="<?php echo $_SESSION['msg-type']; ?>"></div>
   <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){ ?>
          <div class="alert <?php echo $_SESSION['msg-type']; ?>"><button data-dismiss="alert" class="close"></button>
      <?php  echo $mess[$_SESSION['msg']]; $_SESSION['msg'] = '';$_SESSION['msg-type'] = '';  ?>
            </div>
   <?php } ?>
   
      <?php if(isset($_SESSION['msg1']) && $_SESSION['msg1'] != ''){ ?>
          <div class="alert alert-danger"><button data-dismiss="alert" class="close"></button>
      <?php  echo $_SESSION['msg1']; $_SESSION['msg1'] = '';  ?>
            </div>
   <?php } ?>