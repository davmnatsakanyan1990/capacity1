<?php 
// Configration  
	include("config/configuration.php"); 
	$load  =  new loader();	
	
	$label = "setting";
	$dclass   =  new  database();
	$gnrl =  new general; 
  $load->includeother('header');
  $load->includeother('footer');
?>

<div class="tab-title">Choose Working Time</div>
                <div class="full-box">
                  
                    <table><tr><td>
                  <input type="text" value="" class="input-small timepicker validate[required] worktime" name="working_start_time" id="working_start_time"  placeholder="Time">
                  </td><td>
                    <label>to</label>
          
                    <input type="text" value="" class="input-small timepicker validate[required] worktime" name="working_end_time" id="working_end_time"  placeholder="Time">
                    </td>
                    </tr>
                    </table>
                </div>

                <script>
                $(".timepicker").timepicker();
                </script>