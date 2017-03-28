<?php 
include_once("../config/dbconfig.php");
include_once("../classes/database.class.php");
include_once("../classes/general.class.php");
$dclass  =  new  database();
$gnrl =  new general();

// Option Of the  Product Rewards  Calculation 

$ProductInfo = '' ;  //  Procuct value Set  On Edit
if($_REQUEST['option_value'] != ''){	
	$rsProduct  =  $dclass->select(" * " ,"tbl_product_option"," AND  product_id =".$_REQUEST['option_value']);
	if(!empty($rsProduct)){
		foreach($rsProduct as  $rowProduct){
			$ProductInfo[$rowProduct['v_name']] =  $rowProduct['l_values'];
		}
	}else{
		$ProductInfo = '';
	}
}
if((!empty($ProductInfo)) &&  $ProductInfo != '')
	extract($ProductInfo);
?>
<style type="text/css"  >
 .settings_info{
						background: none !important;
						border: none !important;
						padding-right: 0px !important;
					 }
</style>
<!--  Rewards Values  All  Option -->
		<table width="100%" id="table">
			 <tr>
            	<td class="left_form">Gas  :</td>
                <td class="settings_info"></td>
                   <td class="right_form"><input type="text" name="gas" id="gas" value="<?php echo  $gas;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Dining  :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="dinning" id="dinning" value="<?php echo  $dinning;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Grocery  :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="grocery" id="grocery" value="<?php echo  $grocery;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Airfare  :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="airfare" id="airfare" value="<?php echo  $airfare;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Hotel :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="hotel" id="hotel" value="<?php echo  $hotel;?>" /></td>
            </tr>
             <tr>
            	<td class="left_form">Telco :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="Telco" id="Telco" value="<?php echo  $Telco;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Other Saving :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="othersaving" id="othersaving" value="<?php echo $othersaving;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Remaining :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="remaining" id="remaining" value="<?php echo  $remaining;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Free Bag :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="freebag" id="freebag" value="<?php echo  $freebag;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Foreign Purchase :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="foreign_purchase" id="foreign_purchase" value="<?php echo  $foreign_purchase;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Louges Access :</td>
                  <td class="settings_info"></td>
                <td class="right_form">
                	<input type="radio" name="other_saving" id="other_saving"  <?php  if($other_saving == "YES"){echo 'checked="checked"';}?> value="YES"  />YES <br/>
                    <input type="radio" name="other_saving" id="other_saving" <?php  if($other_saving == "NO"){echo 'checked="checked"';}?> value="NO"  />NO
                </td>
            </tr>
            <tr>
            	<td class="left_form">Monthly Spend :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="monthlyspend" id="monthlyspend" value="<?php echo  $monthlyspend;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Annual Fee First Year :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="annualFeeFirstYear" id="annualFeeFirstYear" value="<?php echo  $annualFeeFirstYear;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Annual Fee Ongoing Year :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="annualFeeOngoingYear" id="annualFeeOngoingYear" value="<?php echo  $annualFeeOngoingYear;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Value Per Point Flight (Default) :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="value_per_point_default" id="value_per_point_default" value="<?php echo  $value_per_point_default;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Value Per Point Hotel :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="value_per_point_hotel" id="value_per_point_hotel" value="<?php echo  $value_per_point_hotel;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Value Per Point Cash :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="value_per_point_cash" id="value_per_point_cash" value="<?php echo  $value_per_point_cash;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Sign On Point :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="signon" id="signon" value="<?php echo  $signon;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Threshold :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="threshold" id="threshold" value="<?php echo  $threshold;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Threshold Period :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="thresholdperiod" id="thresholdperiod" value="<?php echo  $thresholdperiod;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Annual Rewards :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="annualRewards" id="annualRewards" value="<?php echo  $annualRewards;?>" /></td>
            </tr>
			<tr>
            	<td class="left_form">Annual Divident :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="annualDivident" id="annualDivident" value="<?php echo  $annualDivident;?>" /></td>
            </tr>
            <tr>
            	<td class="left_form">Annual Threshold :</td>
                  <td class="settings_info"></td>
                <td class="right_form"><input type="text" name="annualThreshold" id="annualThreshold" value="<?php echo  $annualThreshold;?>" /></td>
            </tr>
      </table>