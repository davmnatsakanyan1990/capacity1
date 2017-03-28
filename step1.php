<?php 
include('config/configuration.php');
$objPages = $load->includeclasses('login'); 

$load->includeother('header_register');
$flag = 'false';
global $mail;
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != '' ){
    $objPages->redirect("view");
}
?>
<?php $welcome_video = $dclass->select("*","tbl_video"," AND video_status= '1' ORDER BY video_id desc limit 0,1"); ?>
  <div class="row welcome">
    <div class="container">
      <div class="col-sm-12"><ul class="number"><li class="selected">1</li><li>2</li><li>3</li></ul></div>
      <div class="col-sm-12 title">WELCOME, GOOD TO SEE YOU!</div>
      <div class="col-sm-12 info">It's not hard to get started, but this quick video <br/>will make it even easier.</div>
      <div class="col-sm-8 col-xs-8  video-wrapper"><?php echo $welcome_video[0]['video_code']; ?></div>
      <div class="col-sm-12 action">
      <!--input type="submit" class="btn-success" value="NEXT UP, CREATE YOUR PROFILE"/-->
      <a href="<?php echo SITE_URL; ?>stepsecond" class="btn-success" >NEXT UP, CREATE YOUR PROFILE</a>
      </div>
    </div>
  </div>
</div>
</body>
</body>
</html>
<style>

.video-wrapper {
  height: 0;
  padding-bottom: 37.5%; /* 16:9 */
  position: relative;
  margin:0 auto 40px !important;
  display: table;
  float: none;
}
.video-wrapper iframe, .video-wrapper object, .video-wrapper embed {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  width: 100%;
  height: 100%;
}

</style>

