<?php 
include('config/configuration.php');
$flag = 'false';
global $mail;
$load->includeother('header_home');
 

 $txn = (object)$_POST;

?>

<div class="row welcome profile">
    <div class="container">
    <div class="col-sm-12 title">Thank you!</div>




      <div class="col-sm-12 info"> Thanks for subscribing to Capacity.<br/>To get started, login at the top of the page<br/> 
      and we'll take you on a quick tour of the app.<br/>Welcome aboard.
      <br/>
      Payment process will take a while. Please log in after few minutes.
      </div>
      
    </div>
</div>
</div>
<?php 
$load->includeother('footer_home');
?>
<style type="text/css">
  .alert-success{float: left;width: 100%}
.row.welcome.profile {
    background: url("images/know-bg.png") no-repeat scroll center bottom / cover transparent;
    min-height: 516px;
}
</style>


</body>
</body>
</html>
