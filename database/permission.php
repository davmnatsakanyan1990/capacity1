<?php
	class permission extends general {
		var  $_tableName  = "tbl_role_access";
		var  $_feilds_name = '';
		var  $_tempVar = '';
		//  Default Constructor 
		public function language($label  = ''){
		}		
		//  Redirect To Url		
		public  function redirect($url){
			if($url != '')
				parent::redirectTo($url);
		}
		
		//  Update Data 
		public  function updateData(){
			
			
			// Update Records in database starts
					$tasklist = array(
			  						"1" => 'TASK_ADD',
									"2" => 'TASK_UPDATE',
									"3" => 'TASK_DELETE',
									"4" => 'TASK_VIEW',
									"5" => 'USER_ADD',
									"6" => 'USER_UPDATE',
									"7" => 'USER_DELETE',
									"8" => 'USER_VIEW',
			  						); 
									
					parent::delete($this->_tableName,'1');
					$role = parent::select("*","tbl_role"," AND r_status='active'");
					foreach($tasklist as $tkey=>$tval){
						foreach($role as $rl){
							if(isset($_POST['check'][$tkey][$rl['r_id']])){
								$ins = array(
									"r_id"=>$rl['r_id'],
									"v_name"=>$tval,
									"l_value"=>$_POST['check'][$tkey][$rl['r_id']],
								);
								echo '<pre>'; print_r($ins); echo '</pre>';
								parent::insert($this->_tableName,$ins);
								
							}else{
								continue;
							}
						}
					}
				
					return('1');
				
		}
		//get All
		public function getAll(){
				
				$row = parent::select('*',$this->_tableName);				
				return($row);			
		}
		
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND cms_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND cms_id = '".$id."'");
				return($page_info);			
		}
		//Get info by Field
		public function getInfoByfield($field='',$fieldvalue=''){
				
				$page_Data=  parent::select('*',$this->_tableName," AND ".$field."= '".$fieldvalue."'");
				return($page_Data);			
		}
		//  Deactive Recode
		public function deactiveRecode(){
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("cms_publish"=>'0',);
			parent::update($this->_tableName,$ins,"cms_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("cms_publish"=>'1',);
			parent::update($this->_tableName,$ins,"cms_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," cms_id = '".$id."'");			
			return  ('1');	
		}	
		
	}
?>