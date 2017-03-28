<?php 
include("../config/configuration.php"); require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
$table =  "tbl_fieldsets";
$table2 = "tbl_fields";
$table3 =  "tbl_category";
$table4 = "tbl_product_field";

//  tASK dEFAULT  ADD
if($_POST['task']  ==  '')
	$task =  'add';
else 
	$task =  $_POST['task'];


	
	//  Fileds  Set Get 
	$rs_id =  $dclass->query(" SELECT category_field FROM ".$table3." WHERE  category_id = ".$_POST['category_id']);
	$rowFielsSet =  mysql_fetch_assoc($rs_id);
	$filedsset_id	=  explode(':::',$rowFielsSet['category_field']);
	

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

		
		
		
		$rs_question_detail  =  $dclass->query(" SELECT * FROM ".$table2." WHERE FIND_IN_SET( id, '".implode(',',$quetion_ids_final)."') ");
		
		

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
									<div class="control-group Attribute" >
										  <label class="control-label">'.$row_question_detail['caption']. ' </label>
										 <div class="controls"><input type="text" name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  value="'.$pro_values.'"  '.$class_required.' /></div>
										</div>';
									
							
		}elseif($option_value ==  'textarea'){
				if  ($pro_find  ==  1) 
					$pro_values  =  $pro_field_value[$row_question_detail['id']];
				else 
					$pro_values  =  $row_question_detail['default_value'];
								
									$html .= '
									 <div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls"><textarea name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  '.$class_required.' >'.$pro_values.'</textarea></div>
										</div>';
									
							
		}elseif($option_value ==  'select'){
				if  ($pro_find  ==  1) 
					$pro_values  =  $pro_field_value[$row_question_detail['id']];
				else 
					$pro_values  =  $row_question_detail['default_value'];
				$option   = ''; 
							$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';
										 if($row_question_detail['default_value']  !=''    ){
												
											 	$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												$option  .= '<option value="" >Select</option>';
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if($pro_values == $rowDeatial  )
													 	$select = 'selected="selected"';
													else
														$select = ''; 
													$option  .= '<option value="'.$rowDeatial.'" '.$select.'>'.$rowDeatial.'</option>';
												}
												//  Set Varetity Type
												
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
					
										 //  Set Varetity Type
								
							$html .= $option.'</div>
										</div>';
				
			
		}elseif($option_value ==  'checkbox'){
				if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];

								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										  <div class="controls">';										 
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
							$html .= $option.'</div>
										</div>';
									 
			
		}elseif($option_value ==  'radio' ){
			if  ($pro_find  ==  1) 
						$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];
								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';										 
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
							$html .= $option.'</div>
										</div>';
							
		}elseif($option_value ==  'multiselect'){
				if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];
							$option   = ''; 
						
							$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption'].'</label>
										 <div class="controls">';
										 if($row_question_detail['default_value']  !=''    ){
											 	$option  .= '<select name="'.$row_question_detail['id'].'[]" id= "'.$row_question_detail['id'].'" multiple="multiple" style="height:150px" >';
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													if(in_array($rowDeatial,$pro_values)){
													 	$selected = 'selected="selected"';
													}else{
														$selected = ''; 
													}
													
													$option  .= '<option value="'.$rowDeatial.'" '.$selected.' >'.$rowDeatial.'</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</div>
										</div>';
		}elseif($option_value ==  'checkboxgroup'){
			if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =  $row_question_detail['default_value'];
								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										  <div class="controls">';										 
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
							$html .= $option.'</div>
										</div>';			
		}elseif($option_value ==  'radiogroup' ){
			if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				else 
					$pro_values  =   explode(':::',$row_question_detail['default_value']);

								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 
										 <div class="controls">';										 
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
							$html .= $option.'</div>
										</div>';						
		}elseif($option_value ==  'range'){	
		if  ($pro_find  ==  1) 
					$pro_values  = explode(',',$pro_field_value[$row_question_detail['id']]);
				
				else 
					$pro_values  =  $row_question_detail['default_value'];
			
								$option  = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';										 
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
													
								$html .= $option.'</div>
										</div>';			
			
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
									 <div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">
										 <input type="text" name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  value="'.$row_question_detail['default_value'].'"  '.$class_required.' /></div>
										</div>';
									
							
		}elseif($option_value ==  'textarea'){
									
									$html .= '
									 <div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">
										 <textarea name="'.$row_question_detail['id'].'"  id="'.$row_question_detail['id'].'"  '.$class_required.' > '.$row_question_detail['default_value'].' </textarea> </div>
										</div>';
									
		}elseif($option_value ==  'select'){
				if  ($pro_find  ==  1) 
					$pro_values  =  $pro_field_value[$row_question_detail['id']];
				else 
					$pro_values  =  $row_question_detail['default_value'];
					
					
							
							$option   = ''; 
							$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';
										 if($row_question_detail['default_value']  !=''    ){
											 //  Set Varetity Type
												if(in_array($row_question_detail['id'],$dataOfAt))
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'" onChange="changeVariety($(this).val())">';
												else 
											 		$option  .= '<select name="'.$row_question_detail['id'].'" id= "'.$row_question_detail['id'].'"  >';
											 	
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
													$option  .= '<option value="" >Select</option>';
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
							
								
							$html .= $option.$tempData.'</div>
										</div>';
				
			}
		elseif($option_value ==  'checkbox'){
								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="checkbox" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</div>
										</div>';
			
		}elseif($option_value ==  'radio' ){
								
								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</div>
										</div>';
								
		}elseif($option_value ==  'multiselect'){

							$option   = ''; 
							$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';
										 if($row_question_detail['default_value']  !=''    ){
											 	$option  .= '<select name="'.$row_question_detail['id'].'[]" id= "'.$row_question_detail['id'].'" multiple="multiple" style="height:150px" >';
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<option value="'.$rowDeatial.'">'.$rowDeatial.'</option>';
												}
												$option  .= '</select>';
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</div>
										</div>';
		}elseif($option_value ==  'checkboxgroup'){
								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="checkbox" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</div>
										</div>';			
		}elseif($option_value ==  'radiogroup' ){

								$option   = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';										 
										 if($row_question_detail['default_value']  !=''    ){
												$row_question_detail['default_value'] =  explode(':::',$row_question_detail['default_value']);
												foreach ($row_question_detail['default_value']  as  $rowDeatial   ){
													$option  .= '<input type="radio" name="'.$row_question_detail['id'].'[]"   value="'.$rowDeatial.'" />'.$rowDeatial.'<br/>';
												}
										 }else {
												$option  = 'No option Found.';
											 
										 }
							$html .= $option.'</div>
										</div>';						
		}elseif($option_value ==  'range'){				
								$option  = ''; 
								$html .= '<div class="control-group Attribute">
										  <label class="control-label">'.$row_question_detail['caption']. '</label>
										 <div class="controls">';										 
											$option  .= '<input type="text" name="'.$row_question_detail['id'].'[]"   value="'.$row_question_detail['min'].'" /></br/>';
											$option  .= '<input type="text" name="'.$row_question_detail['id'].'[]"   value="'.$row_question_detail['max'].'" /></br/>';
													
								$html .= $option.'</div>
										</div>';			
			
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