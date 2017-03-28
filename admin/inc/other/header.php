<?php 
global $load; 
$objLogin = $load->includeclasses('register');	
$dclass   =  new  database();
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8" />
<title> Admin Dashboard</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>

<link href="assets/validationEngine.jquery.css" rel="stylesheet" type="text/css" />

<!-- END GLOBAL MANDATORY STYLES -->

<!-- END PAGE LEVEL STYLES -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
    <div class="container-fluid traffic-logo">
      <!-- BEGIN LOGO -->
      <a class="brand" href="home.php"> <img src="../images/logo.png" alt="logo" width="86px" height="23px" /> </a>
      <!-- END LOGO -->
      <!-- BEGIN RESPONSIVE MENU TOGGLER -->
      <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse"> <img src="assets/img/menu-toggler.png" alt="" /> </a>
      <!-- END RESPONSIVE MENU TOGGLER -->
      <!-- BEGIN TOP NAVIGATION MENU -->
      <ul class="nav pull-right">
        <!-- BEGIN INBOX DROPDOWN -->
        
        <!-- BEGIN USER LOGIN DROPDOWN -->
        <li class="dropdown user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> 
        
        		<?php if($_SESSION['user_avatar'] != '' ){?>
                
               <img src="<?php echo SITE_URL; ?>timthumb.php?w=40&h=35&src=../upload/user/<?php echo $_SESSION['user_avatar'] ?>&zc=0&q=100" />
                
				<?php } else {?>

        		<img alt="" src="assets/img/avatar1_small.jpg" />
				
				<?php }?>
				
                <?php $userDetail = $dclass->select("fname,lname","tbl_user"," AND user_id=".$_SESSION['adminid']);
				 ?>
                 <span class="username"><?php echo ucfirst($userDetail[0]['fname'])." ".ucfirst($userDetail[0]['lname']);?></span> <i class="icon-angle-down"></i> </a>
          <ul class="dropdown-menu">
            <li><a href="user.php?script=edit&id=<?php echo $_SESSION['adminid']; ?>"><i class="icon-user"></i> My Profile</a></li>
            <li><a href="logout.php"><i class="icon-key"></i> Log Out</a></li>
          </ul>
        </li>
        <!-- END USER LOGIN DROPDOWN -->
        <!-- END USER LOGIN DROPDOWN -->
      </ul>
      <!-- END TOP NAVIGATION MENU -->
    </div>
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
