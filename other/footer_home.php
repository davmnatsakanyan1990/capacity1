<?php 
global $load;
$dclass   =  new  database(); 
global $basefile;
?>
<div class="clr"></div>
<div class="row footer">
    <div class="container">
      <div class="col-sm-1 col-md-3 col-xs-1 pull-left">
        <div class="logo-footer pull-left"> <img src="<?php echo SITE_URL; ?>images/favicon.png" /> </div>
      </div>
      <div class="col-sm-11 col-md-9 col-xs-11  pull-right">
        <ul class="links pull-right">
          <li class="back"><a href="#">back to top</a></li>
          <li><a href="<?php echo SITE_URL ?>#section1">Why Capacity?</a></li>
          <li><a href="<?php echo SITE_URL ?>#section2">features</a></li>
          <li><a href="<?php echo SITE_URL ?>#section3">pricing</a></li>
          <li><a href="<?php echo SITE_URL ?>#section4">FAQ</a></li>
          <li><a href="<?php echo SITE_URL ?>contact-us">contact</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                  <div class="modal-dialog">
                      <div class="modal-content">
                        <form class="form-forgot" id="form_forgot" name="form_forgot" method="post" action="<?php echo SITE_URL.'login.php'; ?>">
                              <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title">Forgot Password ?</h4>
                          </div>
                          <div class="modal-body">
                              <p>Enter your e-mail address below to reset your password.</p>
                              <input type="text" name="email" id="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix validate[required,custom[email]]">
                          </div>
                          <div class="modal-footer">
                              <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                              <button class="btn btn-success" name="forgot" type="submit">Submit</button>
                          </div>
                      </form>             
                      </div>
                  </div>
              </div>
<script type="text/javascript">
$(document).ready( function() {
    $('#myCarousel').carousel({
      interval: 5000
  });
  
  var clickEvent = false;
  $('#myCarousel').on('click', '.nav a', function() {
      clickEvent = true;
      $('.nav li').removeClass('active');
      $(this).parent().addClass('active');    
  }).on('slid.bs.carousel', function(e) {
    if(!clickEvent) {
      var count = $('.nav').children().length -1;
      var current = $('.nav li.active');
      current.removeClass('active').next().addClass('active');
      var id = parseInt(current.data('slide-to'));
      if(count == id) {
        $('.nav li').first().addClass('active');  
      }
    }
    clickEvent = false;
  });
  $(".loginbtn").click(function(){
    $(".login-box").toggle();  
  });
});
</script>
<script type="text/javascript">
$(window).scroll(function(){    
    var scroll = $(window).scrollTop();
    if (scroll >= 480) {
     $(".header").css("display","none");
        $(".hero.header").css("display","block");
    }else{
     $(".header").css("display","block");
    $(".hero.header").css("display","none");
  }
  
}); 
$(window).resize(function(){
  if ($(window).width() >= 320 && $(window).width() <=479){ 
     $(".header.hero .container").addClass("row");
  } 
  else
  {
    $(".header.hero .container").removeClass("row");
  }
});
</script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/jquery.validationEngine.js"></script>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css_ui/validationEngine.jquery.css" type="text/css" />
<script>
    jQuery(document).ready(function() {
            jQuery('#form_login').validationEngine();
            jQuery('#form_login1').validationEngine();
            jQuery('#form_forgot').validationEngine();
      jQuery("body").on('click','#forgotopen',function(){
        jQuery("#myModal").modal('show');
      });
    });
</script>
<script type="text/javascript">
 jQuery(document).ready(function(){
        var pxShow = 100;//height on which the button will show
        var fadeInTime = 1000;//how slow/fast you want the button to show
        var fadeOutTime = 1000;//how slow/fast you want the button to hide
        var scrollSpeed = 1000;//how slow/fast you want the button to scroll to top. can be a value, 'slow', 'normal' or 'fast'
        jQuery('.back').click(function(){
            jQuery('html, body').animate({scrollTop:0}, scrollSpeed);
            return false;
        });
    jQuery(".dropdown li a").click(function(){
    jQuery(".dropdown").removeClass("open");
  });
});
</script>
<script src="<?php echo SITE_URL; ?>js_ui/SmoothScroll.js?ver=4.0.1"></script>
<script>
$(function() {
    $('a[href*=#]:not([href=#])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
         var st = target.offset().top - 35;
        if (target.length) {
          $('html,body').animate({
            scrollTop: st
          }, 1500);
          return false;
        }
      }
    });
  });
</script>
</body>
</body>
</html>