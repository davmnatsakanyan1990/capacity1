<?php 
global $load;
$dclass = new  database(); 
global $basefile;
?>
<?php $dtformat = $dclass->select("*","tbl_dateformate"); ?>
<script src='<?php echo SITE_URL; ?>fullcalendar_2.2.1/lib/moment.min.js'></script>
<script src='<?php echo SITE_URL; ?>fullcalendar_2.2.1/fullcalendar.js'></script>
<script src="<?php echo SITE_URL; ?>assets/Responsive-Tabs-master/js/jquery.responsiveTabs.js" type="text/javascript"></script>
<script src="<?php echo SITE_URL; ?>assets/js/jquery.collapse.js"></script>
<script src="<?php echo SITE_URL; ?>js_ui/select2.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/bootstrap-filestyle.js"> </script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>js_ui/intro.js"></script>
<script src="<?php echo SITE_URL; ?>js_ui/jquery.simplecolorpicker.js"></script>
<script type="text/javascript">
$(window).load(function() {
  $(".loader").fadeOut("slow");
})
  $(document).ready(function(){
    
     $("#reminder_date").datetimepicker({
      format:'dd M yy h:i:s',
      //format:"<?php echo $dtformat[0]['dtformate'].' h:i:s';  ?>",
      autoclose: true,
      todayBtn: true,
      startDate: new Date(),
      minuteStep: 10
    });

    $('#report_start_date').datepicker({
         format:'dd M yy',
         //format:"<?php echo $dtformat[0]['dtformate'];  ?>",
         weekStart: 1,
         autoclose: true
        }).on('changeDate', function(selected){
             startDate = new Date(selected.date.valueOf());
             startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
             $('#report_end_date').datepicker('setStartDate', startDate);
        });
    $( "#report_end_date" ).datepicker({
        format:'dd M yy',
        weekStart: 1,
        autoclose: true,
        onClose: function( selectedDate ) {
        $( "#report_start_date" ).datepicker( "option", "maxDate", selectedDate );
        }
      });

    $( "#userids" ).select2({ placeholder: "Search team members",width: '100%'});
    $(".select2-no-results").hide();
    $('.add_notify').click(function(){
        if($('.notify-div').css("display") == "none"){
             $('.notify-div').show();
             $('.task-form-div').hide();
             $('.report-div').hide();
             $('.nav-main li a').css({ "color": "#fff"});
             $(this).css({ "color": "#9cd778"});
             $('.nav-main li a span.add-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/add-icon.png)","width":"11px"});
             $('.nav-main li a span.report-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/report-icon.png)","width":"15px"});
             $('.nav-main li a span.notify-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/notify-icon-hover.png)","width":"18px"});
        }else{
            $('.notify-div').hide();
            $(this).css({ "color": "#fff"});
            $('.nav-main li a span.notify-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/notify-icon.png)","width":"18px"});
        }
    });
    $('.add_task').click(function(){
      if($('.task-form-div').css("display") == "none"){
            $('.task-form-div').show();
            $('#horizontalTab').responsiveTabs('activate', 0);
            $('.notify-div').hide();
            $('.report-div').hide();
            $('.nav-main li a').css({ "color": "#fff"});
            $(this).css({ "color": "#9cd778"});
            $('.nav-main li a span.notify-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/notify-icon.png)","width":"18px"});
            $('.nav-main li a span.report-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/report-icon.png)","width":"15px"});
            $('.nav-main li a span.add-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/add-icon-hover.png)","width":"11px"});
        }else{
            $('.task-form-div').hide();
            $(this).css({ "color": "#fff"});
            $('.nav-main li a span.add-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/add-icon.png)","width":"11px"});
        }
         //$("html, body").animate({ scrollTop: 0 });
    });
    $('.add_report').click(function(){
        if($('.report-div').css("display") == "none"){
            $('.report-div').show();
            $('.notify-div').hide();
            $('.task-form-div').hide();
            $('.nav-main li a').css({ "color": "#fff"});
            $(this).css({ "color": "#9cd778"});
            $('.nav-main li a span.notify-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/notify-icon.png)","width":"18px"});
            $('.nav-main li a span.add-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/add-icon.png)","width":"11px"});
            $('.nav-main li a span.report-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/report-icon-hover.png)","width":"15px"});
        }else{
            $('.report-div').hide();
            $(this).css({ "color": "#fff"});
            $('.nav-main li a span.report-icon').css({ "background": "url(<?php echo SITE_URL; ?>/images/report-icon.png)","width":"15px"});
        }
    });
    $('#t_start_time').timepicker({
        minuteStep: 30,
        showInputs: false,
        disableFocus: true,
        defaultTime: 'current'
      }).on('changeTime.timepicker', function(e) {
    });
    $( "#t_start_date" ).datepicker({format:'dd M yy'});
    $( "#t_deadline_date" ).datepicker({format:'dd M yy'});
var startDate = new Date('01/01/2012');
var FromEndDate = new Date();
var ToEndDate = new Date();
ToEndDate.setDate(ToEndDate.getDate()+365);
$('#pr_start_date').datepicker({
    weekStart: 1,
    startDate: '01/01/2012',
    format:'dd M yy',
    //format:"<?php echo $dtformat[0]['dtformate'];  ?>",
    autoclose: true
}).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#pr_end_date').datepicker('setStartDate', startDate);
 }); 
$('#pr_end_date').datepicker({
        
        weekStart: 1,
        startDate: startDate,
        endDate: ToEndDate,
        format:'dd M yy',
        autoclose: true
    }).on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#pr_start_date').datepicker('setEndDate', FromEndDate);
});

    $('select[name="pr_color"]').simplecolorpicker({theme: 'fontawesome'});
    $("#t_operators").select2();
    $('#new_task').validationEngine({validateNonVisibleFields: true,scroll:false,autoHidePrompt: true,
autoHideDelay: 3000,
fadeDuration: 0.3}); 
    $('#new_client').validationEngine({validateNonVisibleFields: true,scroll:false}); 
    $('#new_project').validationEngine({validateNonVisibleFields: true,scroll:false});
    $('#new_team').validationEngine({validateNonVisibleFields: true,scroll:false});

    var str_blank_project_div = '<div class=""><h4 class="">Choose Project</h4><select class="form-control col-lg-1 mb" name="project_id" id="project_id" ><option value="">Choose Project</option></select><hr><div class="new_project_div"><a href="javascript:void(0);" id="add_new_project"><i class="fa fa-plus"></i> Add new</a></div></div>';
    var str_blank_task_div = '<div class="col-lg-3"><h4 class="">Task Details</h4><select class="form-control col-lg-1 mb" name="t_id" id="t_id" ><option value="">Choose Task</option></select><hr><div class="new_task_div"><a href="javascript:void(0);" id="add_new_task"><i class="fa fa-plus"></i> Add new</a></div></div>';
    //code responsive tab
  hash = window.location.hash;
   <?php if(isset($_REQUEST['multipage']) && $_REQUEST['multipage'] == 'true' ){ ?>
    if(hash == '#tab-1' || hash == '#tab-2' || hash == '#tab-3' || hash == '#tab-4'){
     $('#horizontalTab').responsiveTabs('activate', 2);
     $('.task-form-div').show();
      $("html, body").animate({ scrollTop: 0 });

      $('.nav-main li a').css({ "color": "#fff"});
      $('.add_task').css({ "color": "#9cd778"});
      $('.nav-main li a span.notify-icon').css({ "background": "url(images/notify-icon.png)","width":"18px"});
      $('.nav-main li a span.report-icon').css({ "background": "url(images/report-icon.png)","width":"15px"});
      $('.nav-main li a span.add-icon').css({ "background": "url(images/add-icon-hover.png)","width":"11px"});
    }
    <?php } ?>
    
    
    if(hash == '#tab-1' || hash == '#tab-2' || hash == '#tab-3' || hash == '#tab-4'){
      }else if(hash == '#notify'){
        $('.notify-div').show();
        $('.task-form-div').hide();
        $('.report-div').hide();
        //$('.nav-main li a').css({ "color": "#fff"});
        $(this).css({ "color": "#9cd778"});
        $('.nav-main li a span.add-icon').css({ "background": "url(images/add-icon.png)","width":"11px"});
        $('.nav-main li a span.report-icon').css({ "background": "url(images/report-icon.png)","width":"15px"});
        $('.nav-main li a span.notify-icon').css({ "background": "url(images/notify-icon-hover.png)","width":"18px"});
    }

    $("#cl_company_name").focus();
    $('#horizontalTab').responsiveTabs({
                rotate: false,
                startCollapsed: 'accordion',
                collapsible: 'accordion',
                setHash: true,
            });

    $('#horizontalTab').on('click','.select-tab', function() {
        $('#horizontalTab').responsiveTabs('activate', $(this).attr('rel'));
        var test = $(this).attr('rel');
           if(test == '1'){
               $( ".add_new_project_btn" ).trigger( "click" );
               $('#pr_title').focus();
           }
           if(test == '2'){
               $( ".add_new_client_btn" ).trigger( "click" );
               $('#cl_company_name').focus();
           }
           
        return false;
      });
    $('#horizontalTab').on('click','.r-tabs-anchor', function(){
          $("body").animate({ scrollTop: 0 }, 0);
          var test = $(this).attr('href');
           if(test == '#tab-2'){
               $('#pr_title').focus();
               $(".project-detail").html('');
               var tsk = "get_project_box";
               $.ajax({
                  type:"post",
                  url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                  data:{tsk:tsk},
                      success:function(data){             
                      $(".projectmaster").html(data);
                      $('.selectpicker').selectpicker();
                  }
               }); 
           }
           if(test == '#tab-3'){
               $('#cl_company_name').focus();
               $(".client-detail").html('');
               var tsk = "get_client_box";
               $.ajax({
                  type:"post",
                  url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                  data:{tsk:tsk},
                      success:function(data){             
                      $(".clientmater").html(data);
                      $('.selectpicker').selectpicker();
                      
                  }
               }); 
           }
           if(test == '#tab-4'){
               $('#tm_title').focus();
               $(".team_detail").html('');
               var tsk = "get_team_box";
               $.ajax({
                  type:"post",
                  url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                  data:{tsk:tsk},
                      success:function(data){             
                      $(".teammaster").html(data);
                      $('.selectpicker').selectpicker();
                  }
               });
           }
      });
    //mycustom code
    //client id on change get project list
    $('#horizontalTab').on('change','#client_id', function(){
     var client_id = $(this).val();
      if(client_id == ''){
         $(".tasklistbox").remove();
      }
      var tsk = 'get_project_list';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{client_id:client_id,tsk:tsk},
                success:function(data){             
                $(".projectBox").removeClass("hide");
                $(".project_div").html(data);
                $('.projList').selectpicker();
                $("#cl_company_name").focus();
                $('#new_task').validationEngine({validateNonVisibleFields: true});
            }
           });
    });
    // code for add blank client form
    $('#horizontalTab').on('click','.add_new_client_btn', function(){
         var tsk = 'add_new_client_form';
          $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{tsk:tsk},
              success:function(data){             
                $(".client-detail").html(data);
                $("#cl_company_name").focus();
                $('#new_task').validationEngine({validateNonVisibleFields: true});
            }
           });
    });
    $('#horizontalTab').on('click','#client_cancel', function(){
         var tsk = 'add_new_client_form';
          $(".client-detail").html('');
    });
    $('#horizontalTab').on('change','#client_id_add', function(){
      var client_id = $(this).val();
      var tsk = 'get_client_detail';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{client_id:client_id,tsk:tsk},
              success:function(data){             
                $(".client-detail").html(data);
                $("#cl_company_name").focus();
                $('#new_client').validationEngine({validateNonVisibleFields: true});
            }
           });
    });
    $('#horizontalTab').on('click','#client_save', function(){
      var validateActionCle = $('#new_client').validationEngine('validate'); 
      if(validateActionCle  ==  true  ||   validateActionCle  ==  'true' ||  validateActionCle ==  'TRUE'){
        var tsk = 'client_save';
        $(this).attr('disabled','disabled');
        $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:$("#new_client").serialize() + "&tsk="+tsk,
            success:function(data){             
               $(".msgDiv").removeClass("hide");
               $(".msgDiv").text("Client saved successfully.");

               hash = window.location.hash;
               if(hash == '#tab-1'){
                  $('#horizontalTab').responsiveTabs('activate', 0);
                  $(".taskboxfirst").html(data);
                  $('.clientlist').selectpicker();
                  $('.projList').selectpicker();
               }else{
                setTimeout(function(){ window.location.reload(); },1000);
               }
            }
           });
     }
    });
    // code for client div end
    //code for project tab
    $('#horizontalTab').on('click','.add_new_project_btn', function(){
         var tsk = 'add_new_project_form';
          $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{tsk:tsk},
              success:function(data){             
                $(".project-detail").html(data);
                $('#pr_title').focus();
                 $('select[name="pr_color"]').simplecolorpicker({theme: 'fontawesome'});
                 $('#reminder_date').datetimepicker({
                      format:'dd M yy h:i:h',
                      autoclose: true
                });
                $('#pr_start_date').datepicker({
    weekStart: 1,
    format:'dd M yy',
    //format:"<?php echo $dtformat[0]['dtformate'];  ?>",
    autoclose: true
}).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#pr_end_date').datepicker('setStartDate', startDate);
 }); 
    $('#pr_end_date').datepicker({
        weekStart: 1,
        startDate: startDate,
        endDate: ToEndDate,
        format:'dd M yy',
        autoclose: true
    }).on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#pr_start_date').datepicker('setEndDate', FromEndDate);
});
                $('.selectpicker').selectpicker();
                $('#new_project').validationEngine({validateNonVisibleFields: true});
            }
           });
      
    });

    $('#horizontalTab').on('click','#project_cancel', function(){
        $(".project-detail").html('');    
    });
    $('#horizontalTab').on('change','#project_id_add', function(){
      var project_id = $(this).val();
      var tsk = 'get_project_detail';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{project_id:project_id,tsk:tsk},
              success:function(data){             
                $(".project-detail").html(data);
                 $('select[name="pr_color"]').simplecolorpicker({theme: 'fontawesome'});
                $('#reminder_date').datetimepicker({
                      format:'dd M yy h:i:s',
                      autoclose: true
                });
                $('#pr_start_date').datepicker({
                    weekStart: 1,
                    startDate: '01/01/2012',
                    format:'dd M yy',
                    autoclose: true
                }).on('changeDate', function(selected){
                        startDate = new Date(selected.date.valueOf());
                        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                        $('#pr_end_date').datepicker('setStartDate', startDate);
                 }); 
                $('#pr_end_date').datepicker({
                        weekStart: 1,
                        startDate: startDate,
                        endDate: ToEndDate,
                        format:'dd M yy',
                        autoclose: true
                    }).on('changeDate', function(selected){
                        FromEndDate = new Date(selected.date.valueOf());
                        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                        $('#pr_start_date').datepicker('setEndDate', FromEndDate);
                    });
                $('.selectpicker').selectpicker();
                $('#new_project').validationEngine({validateNonVisibleFields: true});
            }
           });
    });
    $('#horizontalTab').on('click','#project_save', function(){
      var validateActionPro = $('#new_project').validationEngine('validate'); 
      if(validateActionPro  ==  true  ||   validateActionPro  ==  'true' ||  validateActionPro ==  'TRUE'){
        var tsk = 'project_save';
        var clid = $('#client_id').val();
       $(this).attr('disabled','disabled');
        $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:$("#new_project").serialize() + "&tsk="+tsk+"&clid="+clid,
               success:function(data){             
               $(".msgDiv").removeClass("hide");
               $(".msgDiv").text("Project saved successfully.");
               
               hash = window.location.hash;
               if(hash == '#tab-1'){
                  $('#horizontalTab').responsiveTabs('activate', 0);
                  $(".taskboxfirst").html(data);
                  $('.clientlist').selectpicker();
                  $('.projList').selectpicker();
                  $( "#project_id" ).trigger( "change" );
               }else{
                setTimeout(function(){ window.location.reload(); },1000);
               }
            }
           });
     }
    });
    // project tab code end
    //code for team add
    $('#horizontalTab').on('click','.add_new_team_btn', function(){
         var tsk = 'add_new_team_form';
         $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{tsk:tsk},
              success:function(data){             
                $(".team_detail").html(data);
                $('#tm_title').focus();
                 $( "#userids" ).select2({ placeholder: "Search team members",
            width: '100%'});
                 $(".select2-no-results").hide();
                $('#new_team').validationEngine({validateNonVisibleFields: true});
            }
           });
    });
    $('#horizontalTab').on('click','#team_cancel', function(){
         $(".team_detail").html('');
    });
    $('#horizontalTab').on('change','#team_id_add', function(){
      var team_id = $(this).val();
      var tsk = 'get_team_detail';
      $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{team_id:team_id,tsk:tsk},
              success:function(data){             
                $(".team_detail").html(data);
                $( "#userids" ).select2({ placeholder: "Search team members",width: '100%'});
                $(".select2-no-results").hide();
                $('#new_team').validationEngine({validateNonVisibleFields: true});
            }
           });
    });
    $('#horizontalTab').on('click','#team_save', function(){
      var validateActiontm = $('#new_team').validationEngine('validate'); 
      if(validateActiontm  ==  true  ||   validateActiontm  ==  'true' ||  validateActiontm ==  'TRUE'){
        var tsk = 'team_save';
        $(this).attr('disabled','disabled');
        $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:$("#new_team").serialize() + "&tsk="+tsk,
            success:function(data){             
               $(".msgDiv").removeClass("hide");
                $(".msgDiv").text("Team saved successfully.");
                setTimeout(function(){ window.location.reload(); },1000);
            }
           });
     }
    });
    // code end for team add
    $('#horizontalTab').on('click','.mycheckbox', function(){
      if($('.mycheckbox').is(':checked') == true){
          $(".reminder_box").removeClass('hide');
      }else{
          $(".reminder_box").addClass('hide');
      }
    });
    $('#horizontalTab').on('click','.mycheckbox_pro', function(){
      if($('.mycheckbox_pro').is(':checked') == true){
          $(".pro_reminder_box").removeClass('hide');
      }else{
          $(".pro_reminder_box").addClass('hide');
      }
    });

    $('#horizontalTab').on('change','#project_id', function(){
      var project_id = $(this).val();
     if(project_id == ''){
        $(".tasklistbox").remove();
     }else{
      var tsk = 'get_task_list';
       $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{project_id:project_id,tsk:tsk},
              success:function(data){             
              $(".tasklistbox").remove();
              $(".taskbox").append(data);
              $('#t_id').selectpicker();
            }
           });
     }
    });

    $('#horizontalTab').on('click','#task_cancel', function(){
        $(".tasklistbox").remove();
        var project_id = $('#project_id').val();
        var tsk = 'get_task_list';
       $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{project_id:project_id,tsk:tsk},
              success:function(data){             
              $(".taskbox").append(data);
              $('#t_id').selectpicker();
            }
           });
    });
    // code for project div end
    // code for task div start
    $('#horizontalTab').on('click','.add_new_task', function(){
      var tsk = 'add_new_task';
      var ctype = $(this).attr('ctype');
       $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{tsk:tsk,ctype:ctype},
              success:function(data){             
                $(".taskBox").removeClass("hide");
                $(".tasklistbox").remove();
                $(".taskbox").append(data);
                $('#t_title').focus();
                $('.selectpicker').selectpicker();
                $('#t_start_time').timepicker({
                    minuteStep: 30,
                    showInputs: false,
                    disableFocus: true,
                    defaultTime: 'current'
                  }).on('hide.timepicker', function(e) {
                      var team_id = $('#t_team_id').val();
                       var tsk = 'check_team_time';
                       var tm_value = $(this).val();
                       $.ajax({
                          type:"post",
                          url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
                          data:{team_id:team_id,tsk:tsk,tm_value:tm_value},
                          success:function(data){             
                            if(data != 1){
                                if(data == 0){
                                  $('#t_start_time').validationEngine('showPrompt', 'Please choose a time that is within working hours.', 'pass');
                                }else{
                                  $('#t_team_id').validationEngine('showPrompt', 'Please select a team before setting time.', 'pass');
                                }
                              $('#t_start_time').timepicker('setTime', '');              
                            }
                          }
                      });  
                });
                 $('#t_start_date').datepicker({
                      weekStart: 1,
                      startDate: '01/01/2012',
                      format:'dd M yy',
                      autoclose: true
                  }).on('changeDate', function(selected){
                          startDate = new Date(selected.date.valueOf());
                          startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_deadline_date').datepicker('setStartDate', startDate);
                   }); 
                  $('#t_deadline_date').datepicker({
                          weekStart: 1,
                          startDate: startDate,
                          endDate: ToEndDate,
                          format:'dd M yy',
                          autoclose: true
                      }).on('changeDate', function(selected){
                          FromEndDate = new Date(selected.date.valueOf());
                          FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_start_date').datepicker('setEndDate', FromEndDate);
                  }); 
                  $('#reminder_date').datetimepicker({
                       format:'dd M yy h:i:s',
                        autoclose: true,
                        startDate: new Date(),
                  });    

                $('#new_task').validationEngine({validateNonVisibleFields: true});
            }
           });
    });

    $('#horizontalTab').on('change','#t_id', function(){
      var t_id = $(this).val();
      var tsk = 'get_task_detail';
       $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{t_id:t_id,tsk:tsk},
              success:function(data){             
                $(".taskBox").removeClass("hide");
                $(".tasklistbox").remove();
                $(".taskbox").append(data);
                $('.selectpicker').selectpicker();
                  $('#t_start_time').timepicker({
                    minuteStep: 30,
                    showInputs: false,
                    disableFocus: true
                  });
          
                  $('#t_start_date').datepicker({
                      weekStart: 1,
                      startDate: '01/01/2012',
                      format:'dd M yy',
                      autoclose: true
                  }).on('changeDate', function(selected){
                          startDate = new Date(selected.date.valueOf());
                          startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_deadline_date').datepicker('setStartDate', startDate);
                   }); 
                  $('#t_deadline_date').datepicker({
                          weekStart: 1,
                          startDate: startDate,
                          endDate: ToEndDate,
                          format:'dd M yy',
                          autoclose: true
                      }).on('changeDate', function(selected){
                          FromEndDate = new Date(selected.date.valueOf());
                          FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                          $('#t_start_date').datepicker('setEndDate', FromEndDate);
                  }); 
                   $('#reminder_date').datetimepicker({
                       format:'dd M yy h:i:s',
                        startDate: dateToday,
                        autoclose: true
                  });       
                  $('#new_task').validationEngine({validateNonVisibleFields: true});
            }
           });
    });
    $('#horizontalTab').on('change','#t_team_id', function(){
      var tsk = 'get_operator';
      var team_id = $(this).val();
      var ctype = $('#ctype').val();
       $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
            data:{team_id:team_id,tsk:tsk,ctype:ctype},
              success:function(data){             
                $(".operators").html(data);
                $('.selectpicker').selectpicker();
                 $('#new_task').validationEngine({validateNonVisibleFields: true});
              }
           });
    });

    $('#horizontalTab').on('click','#task_save', function(){
      var validateAction = $('#new_task').validationEngine('validate'); 
      if(validateAction  ==  true  ||   validateAction  ==  'true' ||  validateAction ==  'TRUE'){
        var drvl = $("#t_duration").val();
        if(drvl > 99){
          if(!confirm("Your task is more then 99 hr, Are you sure, you want to proceed")){
            return false;   
          }
        }
         var tsk = 'task_save';
         $(this).attr('disabled','disabled');
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
              data:$("#new_task").serialize() + "&tsk="+tsk,
              beforeSend: function() {
                    $('#tab-1').block({ 
                        message: '<div id="loaderImage" style="margin:-11% 35%"></div>',  
                    });
                    new imageLoader(cImageSrc, 'startAnimation()');
                },
              success:function(data){
                if(data.trim() == 'Deadline_date'){
                    alert("here is no available time slots for this task, it will be added to the queue.")
                }
                $(".msgDiv").removeClass("hide");
                $(".msgDiv").text("Task saved successfully.");
                stopAnimation();
                $('#tab-1').unblock();
                setTimeout(function(){ window.location.reload(); },1000);
              }
             });
      }
    });
    // delete task when user click on 'X' button from task  and also calander task edit popup
    $('#horizontalTab,#new_user_modal').on('click','#task_delete', function(){

      if(confirm("Are you sure to delete this task")  ==  true){
         var tsk = 'task_delete';
         var taskid = $(this).attr("taskid");
         var operator_id = $("#t_operatorss").val();
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/ajax_task.php",
              data:{tsk:tsk,taskid:taskid},
              beforeSend: function() {
                $('.modal-content').block({ 
                        message: '<div id="loaderImage" style="margin:-19% 37%"></div>', 
                });
                 new imageLoader(cImageSrc, 'startAnimation()');
              },
              success:function(data){    
                
                $(".taskmsgDiv").removeClass("hide");
                    $(".taskmsgDiv").text("Task successfully save.");  
                    $(".taskmsgDiv").fadeTo(2000, 500).slideUp(500, function(){
                    //$(".taskmsgDiv").alert('close');
                    $(".taskmsgDiv").text("");
                    $(".taskmsgDiv").addClass("hide");
                  });

                $("#new_user_modal").modal('hide');
                $('.modal-content').unblock();
                stopAnimation();
                 var wurl =  window.location.href.split('#')[0];
                 wurl += "#main-calender"+operator_id;
                 // location.reload();
                 $('.calender-right-box .rem'+taskid).remove();
                 //$('.calender-section .calender-border #queue-task-box').css("top","8px");
                 //$('.calender-section .calender-border .full-box').css("top","8px");
                 var tsk1 = 'get_user_event';
                  $.ajax({
                            type:"post",
                            url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                            data:{tsk:tsk1,user_id:operator_id},
                            dataType: 'json',
                            success:function(data1){             
                                $('#calendar'+operator_id).fullCalendar( 'removeEvents' );
                                $('#calendar'+operator_id).fullCalendar( 'addEventSource', data1 );     
                                // code for refresh free hours
                                var view = $('#calendar'+operator_id).fullCalendar('getView');
                                var p = moment(view.start);
                                var gsdate = p.format("YYYY-MM-DD  HH:mm:ss");           
                                var team_id = "<?php echo $team_id; ?>";    
                                var client_id = "<?php echo $_REQUEST['cl_id']; ?>";    
                                var project_id = "<?php echo $_REQUEST['pr_id']; ?>";   
                                var gtsk = 'get_free_hours';
                                var q = moment(view.end);
                                var gedate = q.format("YYYY-MM-DD  HH:mm:ss");
                                var gviewmode = view.name;
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo SITE_URL; ?>ajax_call/ajax_view.php",
                                    data:{user_id:operator_id,tsk:gtsk,viewmode:gviewmode,team_id:team_id,client_id:client_id,project_id:project_id,sdate:gsdate,edate:gedate},
                                        success:function(data){    
                                            var arr = data.split(':::');
                                            $("#mycustom"+operator_id).css("height",arr[1]);
                                            $("#mycustom"+operator_id).css("background",arr[2]);
                                            $("#free_hours_vl"+operator_id).html(arr[0]);
                                            $(".free_hours").width('74px');
                                            var dw = $(".fc-mon").width() - 2;
                                            $(".fc-content .fc-time span").css("display","none");
                                           
                                            //$('.calender-section .calender-border #queue-task-box').css("top","8px");
                                           // $('.calender-section .calender-border .full-box').css("top","8px");
                                        }
                                   });
                            }
                        });
              }
             });
      }
    });
    
    $('#horizontalTab').on('click','#task_cancel', function(){
      $(".taskBox").html(str_blank_task_div);
    });
    
    // checked 'Get all notification if all other checkbox are checked on edit'
    var checkboxes = $('#notify-div').find(':checkbox');
    var checkall = 'true';
    $.each(checkboxes, function(i, item) {
        if(item.value != 'get_all' && $('#'+item.value).prop('checked'))
        {
           checkall = 'true';
        }
        else if(item.value != 'get_all')
        {
            checkall = 'false';
            $('#get_all').prop('checked', false);
            return false;
        }
    });
    if(checkall == 'true')
        $('#get_all').prop('checked', true);
   
   // check uncheck checkboxes 
   /*****************************************/
   $('#notify-div').on('change','#get_all', function(){
        $('.chknotify').prop('checked',this.checked);
    });
   $('#notify-div').on('change','.chknotify', function(){ 
        if($(".chknotify").length == $(".chknotify:checked").length)
        {
            $('#get_all').prop('checked', true);
        }
        else{
            $('#get_all').prop('checked', false);
        }
    });
  // Save user Notification 
  $('#notify-div').on('click','#save-notifications', function(){
        var tsk = 'notification_save';
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/ajax_notification.php",
              data:$("#notify-form").serialize() + "&tsk="+tsk,
              success:function(data){             
                $(".msgDiv").removeClass("hide");
                $(".msgDiv").text("Notification saved Successfully.");
                  var wurl =  window.location.href.split('#')[0];
                  wurl += "#notify";
                  //location.reload();
                  window.location.hash = '#notify';
                  window.location.reload(true);
              }
        });return false;
    });
  
  // delete notification message
  $('#notify-div').on('click','.dismiss_msg', function(){
       var conf = confirm("Are you sure you want to dismiss this message?");
        if(conf){
        var tsk = 'delete_msg';
        var msg_id = $(this).attr('msgid');
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/ajax_notification.php",
              data:{msg_id:msg_id,tsk:tsk},
              success:function(data){                  
                $('.msgDiv').removeClass('hide');
                $('.msgDiv').html('Notification message dismiss successfully.');
                $('#tbtr'+msg_id).remove();
                var cnt = $(".note-box").attr("cnt");
                cnt --;

                $(".note-box").attr("cnt",cnt);
                $(".note-box").html(cnt);
                if(cnt == 0){
                  $(".note-box").hide();
                  var noexit = '<tr><td colspan="3">No notifications at this time.</td></tr>';
                  $(".notify-inbox").html(noexit);
                }

              }
        });
       }
    });
  // Populate employee name based on team
  $('#report-div').on('change','#team_id', function(){
        var tsk = 'get_employee';
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/generate_report.php",
              data:{team_id:$(this).val(),tsk:tsk},
              success:function(data){                  
                $('.report_employee').html(data);
                $('.selectpicker').selectpicker();
              }
        });return false;
    });
    $('#horizontalTab').on('click','.gotoproject', function(){
        var wurl =  '<?php echo SITE_URL ?>setting#project';
        location.href=wurl;
        $("#ui-id-2").css("display","none");
        $("#ui-id-16").css("display","block"); 
    });
    
  $('body').on('click','#download_report', function(){
        var tsk = 'generate_report';
        if($("#report_start_date").val() != '' && $("#report_end_date").val() == '')
        {
            $('#report_end_date').validationEngine('showPrompt', 'Please select end date.', 'pass');
            return false;
        }
        if($("#report_end_date").val() != '' && $("#report_start_date").val() == '')
        {
            $('#report_start_date').validationEngine('showPrompt', 'Please select start date.', 'pass');
            return false;
        }
        else if($("#report_end_date").val() < $("#report_start_date").val())
        {
            $('#report_end_date').validationEngine('showPrompt', 'End Date should be after Start Date.', 'fail');
            return false;
        }
        $("#download_link").html('');
         $.ajax({
              type:"post",
              url:"<?php echo SITE_URL; ?>ajax_call/generate_report.php",
              data:$("#report-form").serialize() + "&tsk="+tsk,
              success:function(data){
                  $("#download_link").hide();
                  $("#download_link").html(data);
                  window.location = $('#dwnreport').attr('href');
              }
        });return false;
    });    
    //code  popup reminder
    /*window.setTimeout(function(){
      var tsk = 'popup_reminder'
         $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_popup.php",
            data:{tsk:tsk},
              success:function(data){ 

              if($.trim(data) != '0'){

                var mystr = data.split(":::");   
                var unique_id = $.gritter.add({
                  title: mystr[0],
                  text: mystr[1],
                  sticky: true,
                  class_name: 'my-sticky-class'
                });
                setTimeout(function(){
                $.gritter.remove(unique_id, {
                  fade: true,
                  speed: 'slow'
                });
                }, 5000);
              }
            }
           });
    },1000);*/


  });
  </script> 
  <?php $guide = $dclass->select("*","tbl_user_guide_prompt"," AND user_id =".$_SESSION['user_id']);
if(count($guide) == 0  || $guide[0]['status'] == 'on'){ ?>
<script>
  function userguidepromt(){
    <?php if($_SESSION['user_type'] == 'employee'){ ?>
      var dtmsg = 'User guides are now turned off. You can turn them back on in the Users tab.';
    <?php }else{ ?>
      var dtmsg = 'User guides are now turned off. You can turn them back on in the Settings tab.';
    <?php } ?>  
     var tsk = 'user_guide_propmt_setting_by_skip';
      var from = 'skipbtn';
       $.ajax({
            type:"post",
            url:"<?php echo SITE_URL; ?>ajax_call/ajax_setting.php",
            data:{tsk:tsk,from:from},
            success:function(data){             
              //window.location.reload();
              $(".msgDiv").removeClass('hide');
              $(".msgDiv").html(dtmsg);
            }
       });

  }
  
  $(document).on('click',".introjs-skipbutton1",function(){
      var lablevl = $(this).html();
      if(lablevl=="Don't show me again"){
        userguidepromt();  
      }
  });
</script>
<?php   }  ?>
<script type="text/javascript">
    var cSpeed=4;
    var cWidth=66;
    var cHeight=66;
    var cTotalFrames=20;
    var cFrameWidth=66;
    var cImageSrc='<?php echo SITE_URL; ?>images/loader_green_sprite.png';
    var cImageTimeout=false;
    var cIndex=0;
    var cXpos=0;
    var cPreloaderTimeout=false;
    var SECONDS_BETWEEN_FRAMES=0;
    function startAnimation(){
        
        document.getElementById('loaderImage').style.backgroundImage='url('+cImageSrc+')';
        document.getElementById('loaderImage').style.width=cWidth+'px';
        document.getElementById('loaderImage').style.height=cHeight+'px';
        
        //FPS = Math.round(100/(maxSpeed+2-speed));
        FPS = Math.round(100/cSpeed);
        SECONDS_BETWEEN_FRAMES = 1 / FPS;
        cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES/1000);
    }
    
    function continueAnimation(){
        cXpos += cFrameWidth;
        //increase the index so we know which frame of our animation we are currently on
        cIndex += 1;
        //if our cIndex is higher than our total number of frames, we're at the end and should restart
        if (cIndex >= cTotalFrames) {
            cXpos =0;
            cIndex=0;
        }
        
        if(document.getElementById('loaderImage'))
            document.getElementById('loaderImage').style.backgroundPosition=(-cXpos)+'px 0';
        
        cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES*1000);
    }
    
    function stopAnimation(){//stops animation
        clearTimeout(cPreloaderTimeout);
        cPreloaderTimeout=false;
    }
    
    function imageLoader(s, fun)//Pre-loads the sprites image
    {
        clearTimeout(cImageTimeout);
        cImageTimeout=0;
        genImage = new Image();
        genImage.onload=function (){cImageTimeout=setTimeout(fun, 0)};
        genImage.onerror=new Function('alert(\'Could not load the image\')');
        genImage.src=s;
    }
    
    //The following code starts the animation
   
</script>
  </body>
</html>