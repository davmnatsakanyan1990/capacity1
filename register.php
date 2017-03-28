<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('user');	

$load->includeother('header');
$flag = 'false';

if(isset($_SESSION['user_id']) && $_SESSION['user_id']!='')
{
	$objPages->redirect("myaccount.php");
}

if(isset($_POST['register'])){

$dataInsert  =  $objPages->userTabRegister();
		if($dataInsert == '1' ){
				$objPages->redirect('index.html');
		}else {
			$_REQUEST['msg'] = $dataInsert;
		}
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>DASHGUM - Bootstrap Admin Template</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
      
	  	<div class="container">
	  		
		      <form class="form-login" id="form-register" name="form-register" action="" method="post">
		        <h2 class="form-login-heading">sign up now</h2>
                <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != ''){ ?>
		        	<div class="alert alert-danger"><?php echo $mess[$_REQUEST['msg']]; ?></div>
                <?php } ?>
                <div class="login-wrap">
		            <input type="hidden" name="plan_id" value="<?php echo $_REQUEST['id']; ?>" class="form-control"  >
                    <input type="hidden" name="type" value="company" class="form-control"  >
                    <input type="hidden" name="user_status" value="active" class="form-control"  >
                    <input type="text" name="user_email" class="form-control validate[required,custom[email]]" placeholder="User Email" autofocus>
		            <br>
		            <input type="password" name="password" id="password" class="form-control validate[required]" placeholder="Password">
		            <br>
                    <input type="text" name="user_first_name" class="form-control validate[required]" placeholder="First name">
                    <br>
                    <input type="text" name="user_last_name" class="form-control validate[required]" placeholder="Last name">
		           
                   <br>
                    <input type="radio"  value="M" name="user_gender" class="validate[required] radio" style="float:left">
                    &nbsp;&nbsp;<span style="float:left"> Male</span>
                    
                    &nbsp;&nbsp;<input type="radio"  value="F" name="user_gender" class="validate[required] radio" style="float:left">&nbsp;&nbsp;<span style="float:left"> Female</span>
                    <br>
                 
                    <button class="btn btn-theme btn-block" name="register"  type="submit"><i class="fa fa-lock"></i> SIGN UP</button>
		            <hr>
		       
		        </div>
		
		      </form>	  	
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script type="text/javascript" src="js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="js/jquery.validationEngine.js"></script>
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
    <script>
        var jQuery = jQuery.noConflict();
		jQuery.backstretch("assets/img/login-bg.jpg", {speed: 500});
		jQuery(document).ready(function() {
            jQuery('#form-register').validationEngine();
        });
    </script>

    


  </body>
</html>
