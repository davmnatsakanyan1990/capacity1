<?php 
require_once("../../../config/configuration.php");  
require_once('../../../classes/database.class.php');
$dclass = new database();
include_once("../../../classes/general.class.php");

$gnrl =  new general();

$table =  "tbl_fieldsets";
$table2 = "tbl_fields";
$table3 =  "tbl_product_category";
$table4 = "tbl_new_car_condition";

//  tASK dEFAULT  ADD
/*if($_POST['task']  ==  '')
	$task =  'add';
else 
	$task =  $_POST['task'];*/
	//  Fileds  Set Get 
	/*$rs_id =  $dclass->query(" SELECT category_attribute_id FROM ".$table3." WHERE  category_id = ".$_POST['category_id']);
	$rowFielsSet =  mysql_fetch_assoc($rs_id);
	$filedsset_id	=  explode(':::',$rowFielsSet['category_attribute_id']);
	*/

// Set Quetion List  
/*$quetion_ids  =  '';
foreach  ($filedsset_id	as $val){
	$rsFields =  $dclass->query(" SELECT id FROM ".$table2." WHERE FIND_IN_SET (".$val.", fieldset )" );
	while ($rowQuetion = mysql_fetch_assoc($rsFields)){
		$quetion_ids[] =  $rowQuetion['id'];
	}
}*/
// Remove  Duplicate Values  
//$quetion_ids_final =  array_unique($filedsset_id,SORT_NUMERIC); 
//  Set The post Array 


		//$rs_question_detail  =  $dclass->query(" SELECT * FROM ".$table2." WHERE FIND_IN_SET( id, '".implode(',',$quetion_ids_final)."')");
$rs_question_detail  =  $dclass->select(' * ','tbl_car_condition','AND FIND_IN_SET(id, "'.$_POST['option_ids'].'")');
$html  =  ' <table cellpadding=0 cellspacing=0 width=100%>';

$ids_all = '';
if($task ==  'edit'){
	$car_id =  $_POST['car_id']; 
	$pro_filed_id  =  '';
	$pro_field_value =  '';
	//  Get Option  Value Set  Show 
	$product_info =$dclass->query(" SELECT * FROM ".$table4." WHERE  new_car_id = '".$car_id."'");
	
	
	while($rowPro  =  mysql_fetch_assoc($product_info)){
		$pro_filed_id[]  = $rowPro['car_condition_id'];
		$pro_field_value[$rowPro['car_condition_id']] = $rowPro['l_value'];
	}
	
	//  Set the  Option  html   Add  Question  
	foreach($rs_question_detail as $row_question_detail ){
		$option_value  =  $row_question_detail['type'];
		//  Search area  Set 
		
		
		//  Pro Has Quetion  Id  And Vale 
		if(in_array($row_question_detail['id'],$pro_filed_id))
			$pro_find  =  1;
		else 
			$pro_find  =  0;
		
		
		$ids_all[] =$row_question_detail['id'];
		
		if($option_value != ''){
			
			if($option_value ==  'textbox'){
				if  ($pro_find  ==  1) 
					$pro_values  =  $pro_field_value[$row_question_detail['id']];
				else 
					$pro_values  =  $row_question_detail['default_value'];
													
									$html .= '
									 <tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form"><input type="text" name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  value="'.$pro_values.'"  '.$class_required.' /></td>
									 </tr>';
									
							
		}elseif($option_value ==  'textarea'){
				if  ($pro_find  ==  1) 
					$pro_values  =  $pro_field_value[$row_question_detail['id']];
				else 
					$pro_values  =  $row_question_detail['default_value'];
								
									$html .= '
									 <tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form"><textarea name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  '.$class_required.' >'.$pro_values.'</textarea> </td>
									 </tr>';
									
							
		}elseif($option_value ==  'select'){
				if  ($pro_find  ==  1) 
					$pro_values  =  $pro_field_value[$row_question_detail['id']];
				else 
					$pro_values  =  $row_question_detail['default_value'];
							$option   = ''; 
							$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 	$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'" >';
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
				
			
		}elseif($option_value ==  'checkbox'){
				if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];

								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'checked="checked"';
													else
														$select = ''; 
													$option  .= '<input type="checkbox" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" '.$select.'/>'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
			
		}elseif($option_value ==  'radio' ){
			if  ($pro_find  ==  1) 
						$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'checked="checked"';
													else
														$select = ''; 
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" '.$select.' />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';			
		}elseif($option_value ==  'multiselect'){
				if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];
							$option   = ''; 
							$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 	$option  .= '<select name="'.$row_question_detail['id'].'[]" id= "'.$row_question_detail['id'].'" >';
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'seleted="selected"';
													else
														$select = ''; 
													
													$option  .= '<option value="'.$rowDeatial.'" '.$select.' >'.$rowDeatial.'</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
		}elseif($option_value ==  'checkboxgroup'){
			if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'checked="checked"';
													else
														$select = ''; 
													$option  .= '<input type="checkbox" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" '.$select.'/>'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';			
		}elseif($option_value ==  'radiogroup' ){
			if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =   explode(':::',$row_question_detail['default_value']);

								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'checked="checked"';
													else
														$select = ''; 
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'"  '.$select.'/>'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';						
		}elseif($option_value ==  'range'){	
		if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				
				else 
					$pro_values  =  $row_question_detail['default_value'];
			
								$option  = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if  ($pro_find  ==  1) {
											 if($pro_values[0] !=  '')
												$valMin  =  $pro_values[0];
											else  
												$valMin  =  $row_question_detail['min'];
											 if($pro_values[1] !=  '')
												$valMax  =  $pro_values[1];
											else  
												$valMax  =  $row_question_detail['max'];
										 
											
										}else{
											$valMin  =  $row_question_detail['min'];
											$valMax  =  $row_question_detail['max'];
										}
										$option  .= '<input type="text" name="'.$row_question_detail['id'].'[]"   value="'.$valMin.'" /></br/>';
											$option  .= '<input type="text" name="'.$row_question_detail['id'].'[]"   value="'.$valMax.'" /></br/>';
													
								$html .= $option.'</td>
									 </tr>';			
			
			}
		
		}else{
			$html  =  "Nothing  Found.";
		}
	}
}
if($_POST['task'] == 'add'){
	///  Set  the  Option html For Edit  Question  
	foreach($rs_question_detail as $row_question_detail ){
		$option_value  =  $row_question_detail['type'];
		//  Search area  Set 
		
		//print_r($row_question_detail);
		
		$ids_all[] =$row_question_detail['id'];
		
		if($option_value != ''){
			if($option_value ==  'textbox'){
												
									$html .= '
									 <tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form"><input type="text" name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  value="'.$row_question_detail['default_value'].'"  '.$class_required.' /></td>
									 </tr>';
									
							
		}elseif($option_value ==  'textarea'){
									
									$html .= '
									 <tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form"><textarea name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  '.$class_required.' > '.$row_question_detail['default_value'].' </textarea> </td>
									 </tr>';
									
		}elseif($option_value ==  'select'){
							$option   = ''; 
							$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 	$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'" >';
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<option value="'.$rowDeatial.'">'.$rowDeatial.'</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
				
			
		}elseif($option_value ==  'checkbox'){
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="checkbox" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
			
		}elseif($option_value ==  'radio' ){
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';			
		}elseif($option_value ==  'multiselect'){

							$option   = ''; 
							$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 	$option  .= '<select name="'.$row_question_detail['id'].'[]" id= "'.$row_question_detail['id'].'" >';
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<option value="'.$rowDeatial.'">'.$rowDeatial.'</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
		}elseif($option_value ==  'checkboxgroup'){
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="checkbox" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';			
		}elseif($option_value ==  'radiogroup' ){

								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';						
		}elseif($option_value ==  'range'){				
								$option  = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
											$option  .= '<input type="text" name="'.$row_question_detail['id'].'[]"   value="'.$row_question_detail['min'].'" /></br/>';
											$option  .= '<input type="text" name="'.$row_question_detail['id'].'[]"   value="'.$row_question_detail['max'].'" /></br/>';
													
								$html .= $option.'</td>
									 </tr>';			
			
			}
		
		}else{
			$html  =  "Nothing  Found.";
		}
	}
}
	//  Final  Data Display 	
	if ($html == 'Nothing  Found.' )
		echo  $html;
	else{
		$html .= '<input type="hidden" name="attribute_id" id="attribute_id" value="'.implode(',',$ids_all).'"/></table>';
		echo  $html;
	}
?>  