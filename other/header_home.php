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

if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
    $userDetail = $dclass->select("subscrib_type","tbl_user_subscrib_detail"," AND user_id=".$_SESSION['user_id']); 
    if($userDetail[0]['subscrib_type']=="paid"){
     // $objPages->redirect('view');
    }
    
}
if(isset($_COOKIE['username']) && isset($_COOKIE['password'])){
    $dataInsert  =  $objLogin->LoginUser();
		if($dataInsert == '1' ){
        $objPages->redirect('view');
    }
}



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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,700italic,400italic&subset=latin,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
<link href="<?php echo SITE_URL; ?>css_ui/owl.carousel.css" rel="stylesheet">
<link href="<?php echo SITE_URL; ?>css_ui/owl.theme.css" rel="stylesheet">
<link href="<?php echo SITE_URL; ?>css_ui/owl.transitions.css" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo SITE_URL; ?>images/favicon(1).ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE_URL; ?>images/favicon(1).ico" type="image/x-icon">
<script src="<?php echo SITE_URL; ?>js_ui/jquery-1.11.2.js"></script>
<script src="<?php echo SITE_URL; ?>js_ui/owl.carousel.js"></script>
<link href="<?php echo SITE_URL; ?>css_ui/style_home.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid wrapper">
  <div class="row header">
    <div class="container">
      <div class="col-sm-2 col-md-2 pull-left">
        <div class="logo  pull-left"><a href="<?php echo SITE_URL; ?>"> <img src="<?php echo SITE_URL; ?>images/logo.svg" /></a> </div>
      </div>
      <div class="col-sm-10 col-md-8 pull-right">
        <ul class="links pull-right">
          <li><a href="<?php echo SITE_URL; ?>#section1">Why Capacity?</a></li>
          <li><a href="<?php echo SITE_URL; ?>#section2">FEATURES</a></li>
          <li><a href="<?php echo SITE_URL; ?>#section3">PRiCING</a></li>
          <li><a href="<?php echo SITE_URL; ?>#section4">FAQ</a></li>
          <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){ ?>
            <li><a href="<?php echo SITE_URL; ?>view">Go to App</a></li>
            <li><a href="<?php echo SITE_URL; ?>logout">LOGOUT</a></li>
          <?php }else{ ?>
          <li><a href="javascript:void(0)" class="loginbtn">LOGIN</a></li>         
 <?php } ?>

        </ul>
         <?php if(!isset($_SESSION['user_id'])){ ?>
            <div class="clr"></div>
            <form class="form-login" id="form_login" name="form_login" method="post" action="<?php echo SITE_URL.'login.php' ?>">
            <div class="login-box">
                <div class="login-fild-box">
                  <input type="text" name="username" id="username" placeholder="EMAIL" class="login-fild validate[required]" >
                  <input type="password" name="password" id="password" value="" placeholder="PASSWORD" class="login-fild validate[required]">
                  <input type="submit" value="GO" name="login" class="login-fild-btn">
                    <div class="clr"></div>
                    <div class="login-text"><input type="checkbox" name="remember" id="remember" value="on">Remember me</div>
                    <div class="login-text"><a data-toggle="modal"  href="javascript:void(0)" id="forgotopen">Forgot password</a></div>
                </div> 
              </div>
            </form>  
          <?php } ?>
      </div>
    </div>
  </div>
  <div class="row header hero">
    <div class="container">
      <div class="col-sm-2 col-xs-4 pull-left">
        <div class="logo pull-left"><a href="<?php echo SITE_URL; ?>"> <img src="<?php echo SITE_URL; ?>images/logo.svg" /> </a></div>
      </div>
      <div class="col-sm-10 col-xs-8 pull-right">
        <ul class="links pull-right">
        <li class="first"><a href="<?php echo SITE_URL; ?>stepfirst" >get started - try it for free</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
               <li><a href="#section2">FEATURES</a></li>
                    <li><a href="#section3">PRICING</a></li>
                    <li><a href="#section4">FAQ</a></li>
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){ ?>
                      <li><a href="<?php echo SITE_URL; ?>logout">LOGOUT</a></li>
                    <?php }else{ ?>
                    <li><a href="javascript:void(0)" class="loginbtn">LOGIN</a></li> 
                    <?php } ?>
              </ul>
  </li>
           <li><a href="#section1">Why Capacity?</a></li>
           <li><a href="#section2">FEATURES</a></li>
          <li><a href="#section3">PRiCING</a></li>
          <li><a href="#section4">FAQ</a></li>
          <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){ ?>
            <li><a href="<?php echo SITE_URL; ?>logout">LOGOUT</a></li>
          <?php }else{ ?>
          <li><a href="javascript:void(0)" class="loginbtn">LOGIN</a></li>
          <?php } ?>
        </ul>
        <?php if(!isset($_SESSION['user_id'])){ ?>
            <div class="clr"></div>
            <form class="form-login" id="form_login1" name="form_login" method="post" action="<?php echo SITE_URL.'login.php' ?>">
            <div class="login-box">
                <div class="login-fild-box">
                  <input type="text" name="username" id="username" placeholder="EMAIL" class="login-fild validate[required]" >
                  <input type="password" name="password" id="password" value="" placeholder="PASSWORD" class="login-fild validate[required]">
                  <input type="submit" value="GO" name="login" class="login-fild-btn">
                    <div class="clr"></div>
                    <div class="login-text"><input type="checkbox">Remember me</div>
                    <div class="login-text"><a data-toggle="modal"  href="javascript:void(0)" id="forgotopen">Forgot password</a></div>
                </div> 
              </div>
            </form>  
          <?php } ?>
      </div>
    </div>
  </div>
  <?php
     if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){ ?>
        <div class="alert <?php echo $_SESSION['msg-type']; ?>"><button data-dismiss="alert" class="close"></button>
      <?php  
      echo $mess[$_SESSION['msg']]; $_SESSION['msg'] = '';  ?>
            </div>
   <?php } ?>
