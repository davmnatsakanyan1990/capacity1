<?php 
require_once("../../../config/configuration.php");  
require_once('../../../classes/database.class.php');
$dclass = new database();
include_once("../../../classes/general.class.php");

$gnrl =  new general();

$table =  "tbl_fieldsets";
$table2 = "tbl_fields";
$table3 =  "tbl_product_category";
$table4 = "tbl_product_option";

//  tASK dEFAULT  ADD
if($_POST['task']  ==  '')
	$task =  'add';
else 
	$task =  $_POST['task'];


	
	//  Fileds  Set Get 
	$rs_id =  $dclass->query(" SELECT category_attribute_id FROM tbl_category WHERE  cat_id = ".$_POST['category_id']);
	$rowFielsSet =  mysql_fetch_assoc($rs_id);
	$filedsset_id	=  explode(':::',$rowFielsSet['category_attribute_id']);
	

// Set Quetion List  
/*$quetion_ids  =  '';
foreach  ($filedsset_id	as $val){
	$rsFields =  $dclass->query(" SELECT id FROM ".$table2." WHERE FIND_IN_SET (".$val.", fieldset )" );
	while ($rowQuetion = mysql_fetch_assoc($rsFields)){
		$quetion_ids[] =  $rowQuetion['id'];
	}
}*/
// Remove  Duplicate Values  
$quetion_ids_final =  array_unique($filedsset_id,SORT_NUMERIC); 
//  Set The post Array 

		
		if($_REQUEST['product_for'] == 'sell'){
			$user_type = "'both','seller'";
		}else{
			$user_type = "'both','buyer'";
		}
		
		$rs_question_detail  =  $dclass->query(" SELECT * FROM ".$table2." WHERE FIND_IN_SET( id, '".implode(',',$quetion_ids_final)."') AND user_type IN (".$user_type.")");
		
		

$html  =  ' <table cellpadding=0 cellspacing=0 width=100%>';

$ids_all = '';
if($task ==  'edit'){
	$product_id =  $_POST['product_id']; 
	$pro_filed_id  =  '';
	$pro_field_value =  '';
	//  Get Option  Value Set  Show 
	$product_info =$dclass->query(" SELECT * FROM ".$table4." WHERE  product_id = '".$product_id."'");
	
	
	while($rowPro  =  mysql_fetch_assoc($product_info)){
		$pro_filed_id[]  = $rowPro['product_option_id'];
		$pro_field_value[$rowPro['product_option_id']] = $rowPro['product_option_lvalue'];
	}
	
	//  Set the  Option  html   Add  Question  
	while($row_question_detail =  mysql_fetch_assoc($rs_question_detail)){
		$option_value  =  $row_question_detail['type'];
		//  Search area  Set 
		
		if($row_question_detail['required'] ==  1){
			if($row_question_detail['validation_type'] != ''){
				$class_required  =  'class="validate[required,custom['.$row_question_detail['validation_type'].']]"';
			}else{
				$class_required  =  'class="validate[required]"';
			}
		}else{
			$class_required  =  '';
		}
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
					
					$dataOfAt =  array(55,49,83,84,85,89,90);
			//  Set Varetity Type
			if(in_array($row_question_detail['id'],$dataOfAt)){
				$optionSetAll   =    '<div><input type="text" name="varetity_type" id="varetity_type" value="" /></div> ';
				$setOther  =  '<option value="other" >other</option>';
			}
					if($row_question_detail['id'] == '93'){
							$option   = ''; 
							$html .= '<tr id="payment_option">
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 //  Set Varetity Type
												
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												//  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt)){
													$option  .=  '<option value="other" >other</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
					
					}else if($row_question_detail['id'] == '95'){
						
						
							$option   = ''; 
							$html .= '<tr id="racidential_area">
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 //  Set Varetity Type
												
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												//  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt)){
													$option  .=  '<option value="other" >other</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
					
					
					}else if($row_question_detail['id'] == '222'){
						
						
							$option   = ''; 
							$html .= '<tr id="spare_part_product_type_post">
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 //  Set Varetity Type
												
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'_post"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												//  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt)){
													$option  .=  '<option value="other" >other</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
					
					
					}else{
						$option   = ''; 
							$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 //  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt))
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'" onChange="changeVariety($(this).val())">';
												else 
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												//  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt)){
													$option  .=  '<option value="other" >other</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
					}
										 //  Set Varetity Type
							$tempData  = '';
							if(in_array($row_question_detail['id'],$dataOfAt)){
							 	$tempData =  '<div id="div_varetity_type" style="display:none;"><input type="text" name="varetity_type" id="varetity_type" value="" /></div> ';
							}	
							$html .= $option.$tempData.'</td>
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
							if($row_question_detail['id'] == '45' || $row_question_detail['id'] == '91' ){
									
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												$ind = 1;
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'checked="checked"';
													else
														$select = ''; 
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]" id="radioOpt'.$ind.'"  value="'.$rowDeatial.'" '.$select.' />'.$rowDeatial.'<br/>';
												$ind++;
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
							
							}else if($row_question_detail['id'] == '221'  ){
									
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												$ind = 1;
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'checked="checked"';
													else
														$select = ''; 
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'" id="TypeOpt_post'.$ind.'"  value="'.$rowDeatial.'" '.$select.' />'.$rowDeatial.'<br/>';
												$ind++;
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
							
							}else{
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												$ind = 1;
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
														if(in_array($rowDeatial,$pro_values))
													 	$select = 'checked="checked"';
													else
														$select = ''; 
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]"    value="'.$rowDeatial.'" '.$select.' />'.$rowDeatial.'<br/>';
												$ind++;
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
							}
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
if($task ==  'add'){
	///  Set  the  Option html For Edit  Question  
	while($row_question_detail =  mysql_fetch_assoc($rs_question_detail)){
		$option_value  =  $row_question_detail['type'];
		//  Search area  Set 
		
		if($row_question_detail['required'] ==  1){
			if($row_question_detail['validation_type'] != ''){
				$class_required  =  'class="validate[required,custom['.$row_question_detail['validation_type'].']] "';
			}else{
				$class_required  =  'class="validate[required]"';
			}
			
		}else{
			$class_required  =  '';
		}
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
				if  ($pro_find  ==  1) 
					$pro_values  =  $pro_field_value[$row_question_detail['id']];
				else 
					$pro_values  =  $row_question_detail['default_value'];
					
					$dataOfAt =  array(55,49,83,84,85,89,90);
			//  Set Varetity Type
			if(in_array($row_question_detail['id'],$dataOfAt)){
				$optionSetAll   =    '<div><input type="text" name="varetity_type" id="varetity_type" value="" /></div> ';
				$setOther  =  '<option value="other" >other</option>';
			}
							if($row_question_detail['id'] == '93'){
							$option   = ''; 
							$html .= '<tr id="payment_option">
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 //  Set Varetity Type
											 	$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												//  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt)){
													$option  .=  '<option value="other" >other</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							}else{
								$option   = ''; 
							$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';
										 if($row_question_detail['default_value']  !=''    ){
											 //  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt))
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'" onChange="changeVariety($(this).val())">';
												else 
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												//  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt)){
													$option  .=  '<option value="other" >other</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							}
										 //  Set Varetity Type
							$tempData  = '';
							if(in_array($row_question_detail['id'],$dataOfAt)){
							 	$tempData =  '<div id="div_varetity_type" style="display:none;"><input type="text" name="varetity_type" id="varetity_type" value="" /></div> ';
							}	
							$html .= $option.$tempData.'</td>
									 </tr>';
				
			}
		/*elseif($option_value ==  'select'){
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
				
			
		}*/elseif($option_value ==  'checkbox'){
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
								if($row_question_detail['id'] == '45' || $row_question_detail['id'] == '91' ){
								
								$option   = ''; 
								$html .= '<tr>
										 <td class="left_form"> '.$row_question_detail['caption']. '  <span class="required_sbm">*</span></td>
										 <td class="settings_info"></td>
										 <td class="right_form">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												$ind = 1;
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]" id="radioOpt'.$ind.'"  value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												$ind++;
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</td>
									 </tr>';
								}else{
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
								}
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