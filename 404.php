<?php 
include('config/configuration.php');
?>
<!DOCTYPE html>
<html lang="en">
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
  <div class="row welcome">
    <div class="container">
      <div class="col-sm-6 col-xs-6 notfound-wrapper">
        <div class="col-sm-4"><img src="<?php echo SITE_URL; ?>images/404-img.png" /></div>
        <div class="col-sm-8"><h1>Oh no!</h1>Sorry, we couldn't load this page...<br/>
        Try the back button, or one of the links below.
            <span class="bottomlink">
              <a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/capacity-logo.png" /></a>
              <a href="<?php echo SITE_URL; ?>">Home</a>
              <a href="<?php echo SITE_URL; ?>contact-us">Contact us</a>
            </span>
        </div>
      </div>
    </div>
  </div>
</div>
<style>

.notfound-wrapper {padding-top: 20%;position: relative;margin:0 auto 40px !important;display: table;float: none;line-height: 25px;}
.notfound-wrapper h1{ color: #000; font-weight: bold;}
.bottomlink{ float: left;margin-top: 15px;position: relative;width: 100%;}
.bottomlink a{ margin-right: 15px;color: #61676c;text-decoration: underline;}
</style></body>
</html>

