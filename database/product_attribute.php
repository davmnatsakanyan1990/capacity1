<?php
	class product_attribute extends general {
		var  $_tableName  = "tbl_fields";
		
		var  $_feilds_name = '';
		var  $_tempVar = '';
		//  Default Constructor 
		//  Redirect To Url		
		public  function redirect($url){
			if($url != '')
				parent::redirectTo($url);
		}
		//  Insert Recorde
		public function  insertData(){
			if(($_POST['type'] == '' ) ||  ($_POST['caption'] == '') ) {
					$_GET['msg'] ='required';
			}else{
		
				$ins  = array();
				$key = 'type'; 
				//  Default Multi Value Set 
					if($_POST[$key]  == 'select' ||  $_POST[$key] == 'multiselect' ||  $_POST[$key] == 'checkbox' ||  $_POST[$key] == 'checkboxgroup' ||  $_POST[$key]  == 'radio' ||  $_POST[$key]  == 'radiogroup' ){
				
				
					$ins ['default_value'] = str_replace('|',':::',$_POST['default_value']); 
					$ins ['default_value'] =  nl2br($ins['default_value']);
					
				
					}else {
						$ins ['default_value'] =  $_POST['default_value'];
					}
					foreach ($_POST as  $key=>$val){
						if($key ==  'Submit' || $key == 'filedsset_option'  || $key ==  'default_value' ){
							continue;
						}/*else if($key == 'add_for_search' ){
							if($val == 'on')
								$val  =  1;
								$ins [$key] = implode(',',$val);  
						}*/
						else 			{
							$ins [$key] = $val;  
						}
					}
					//$id = $dclass->insert($table,$ins);
					$id = parent::insert($this->_tableName,$ins);
					//$gnrl->redirectTo($label.".php?msg=add");
					return '1';
				}

		}
		//  Update Data 
		public  function updateData(){
		
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['type'] == '' )  ||  ($_POST['caption'] == '') ) {
						$_GET['msg'] ='required';
				}else{					
						$ins  = array();
						$key = 'type'; 
						//  Default Multi Value Set 
						if($_POST[$key]  == 'select' ||  $_POST[$key] == 'multiselect' ||  $_POST[$key] == 'checkbox' ||  $_POST[$key] == 'checkboxgroup' ||  $_POST[$key]  == 'radio' ||  $_POST[$key]  == 'radiogroup' ){
						
							$ins ['default_value'] = str_replace('|',':::',$_POST['default_value']); 
							$ins ['default_value'] =  nl2br($ins['default_value']);
						}else {
							$ins ['default_value'] =  $_POST['default_value'];
						}
						foreach ($_POST as  $key=>$val){
							if($key ==  'Submit' || $key == 'filedsset_option'  || $key ==  'default_value' ){
									continue;
							}/*else if($key == 'add_for_search' ){
								if($val == 'on')
									$val  =  1;
								$ins [$key] = implode(',',$val);  
							}*/
							else {
								$ins [$key] = $val;  
							}
						}
					
						parent::update($this->_tableName,$ins,"id = '".$id."'");
						return '1';
					}
			// Update Records in database ends
					
			}
		//get All
		public function getAll($condition=''){
				
				$row = parent::select('*',$this->_tableName,$condition);				
				return($row);			
		}
		
		public function getById($condition=''){
				
				$row = parent::select('*',$this->_tableName,$condition);				
				return($row);			
		}
		// get caption
		public function getCaptionName($id=''){
				$row = parent::select('caption',$this->_tableName," AND id = '".$id."'");				
				return($row);			
		}
		
		
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND id = '".$id."'");				
				return($row);			
		}
		
		//  Get  Specific  Data 
		
		
		//Get info by Field
		public function getInfoByfield($field='',$fieldvalue=''){
				
				$page_Data=  parent::select('*',$this->_tableName," AND ".$field."= '".$fieldvalue."'");
				return($page_Data);			
		}
		//  Deactive Recode
		/*public function deactiveRecode(){
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("bd_advert_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("bd_advert_status"=>'active',);
			parent::update($this->_tableName,$ins,"id = '".$id."'");
			return  ('1');
		}*/	
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName,"id = '".$id."'");
			
			return  ('1');	
		}
		
		
	}
?>