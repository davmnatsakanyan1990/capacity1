<?php 

//  Set The post Array 
extract($_REQUEST);
$arrayKeys  = explode('||',$arrayKey);
$arrayValues  = explode('||',$arrayValue);
for($i=0;$i< count($arrayKeys);$i++){
	 	$arr[$arrayKeys[$i]] =  	$arrayValues[$i];
}
extract($arr); 


$html  = ' <table cellpadding=0 cellspacing=0 width=100%>';
	//  Set the  Option  html  
	if($option_value != ''){
		if($option_value ==  'textbox'){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="text" name="default_value"  id="default_value" value="'.$default_value.'" class="validate[required] " /></td>
                              </tr>
							  
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
		}elseif($option_value ==  'textarea'){

				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><textarea  name="default_value"  id="default_value" class="validate[required] " >'.$default_value.'</textarea></td>
                              </tr>	
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
		}elseif($option_value ==  'select'){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><textarea  name="default_value"  id="default_value"  class="validate[required] " cols="86" rows="5" >'.str_replace(':::','|',$default_value).'</textarea>
								*You can add multiple elements to the list separated by | sign .</td>
                              </tr>
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
			
		}elseif($option_value ==  'checkbox'){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><textarea  name="default_value"  id="default_value"  class="validate[required] ">'.str_replace(':::','|',$default_value).'</textarea>
								*You can add multiple elements to the list separated by | sign  .</td>
                              </tr>
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
			
		}elseif($option_value ==  'radio' ){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><textarea  name="default_value"  id="default_value" class="validate[required] " cols="86" rows="5" >'.str_replace(':::','|',$default_value).'</textarea>
								*You can add multiple elements to the list separated by | sign  .</td>
                              </tr>
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
			
		}elseif($option_value ==  'multiselect'){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><textarea  name="default_value"  id="default_value" class="validate[required] ">'.str_replace(':::','|',$default_value).'</textarea>
								*You can add multiple elements to the list separated by | sign  .</td>
                              </tr>
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
			
		}elseif($option_value ==  'checkboxgroup'){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><textarea  name="default_value"  id="default_value" class="validate[required] "  cols="86" rows="5" >'.str_replace(':::','|',$default_value).'</textarea>
								*You can add multiple elements to the list separated by | sign  .</td>
                              </tr>
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>	';
			
		}elseif($option_value ==  'radiogroup' ){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><textarea  name="default_value"  id="default_value" class="validate[required] " cols="86" rows="5" >'.str_replace(':::','|',$default_value).'</textarea>
								*You can add multiple elements to the list separated by | sign  .</td>
                              </tr>
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
			
		}
		elseif($option_value ==  'range'){
				$html  .=  '<tr>
                              	 <td class="left_form">Deaful Value From  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="text"  name="min"  id="min" class="validate[required] "  value="'.$min.'" />
								(In Input use for numeric values or date fields only)</td>
                              </tr>							 
							  <tr>
                              	 <td class="left_form">Deaful Value To  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="text"  name="max"  id="max" class="validate[required] " value="'.$max.'"/>
								(In Input use for numeric values or date fields only)</td>
                              </tr>
							  <tr>
                              	 <td class="left_form">Required  <span class="required_sbm">*</span></td>
			                     <td class="settings_info"></td>
            		            <td class="right_form"><input type="checkbox" name="required"  id="required"  value="'.$required.'" /></td>
                              </tr>';
			
		}else{
			$html  =  "0";
		}
	}
	if ($html == 0 )
		echo  $html;
	else{
		$html .= '</table>';
		echo  $html;
	}
?>  