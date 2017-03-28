<?php
	class advertisement extends general {
		var  $_tableName  = "tbl_advertisement";
		var  $_feilds_name = '';
		var  $_tempVar = '';
		//  Default Constructor 
				
		//  Redirect To Url		
		public  function redirect($url){
			if($url != '')
				parent::redirectTo($url);
		}
		//  Update Data 
		public  function updateData(){
			
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['username'] == '')) {
					return($_GET['msg'] ='required');
				}
				else
				{	
					
					$ins = array(
								"username" => $_POST['username'],
								"email"=>$_POST['email'],
								"desc"=>$_POST['desc'],
								"phone"=>$_POST['phone'],
					);
					parent::update($this->_tableName,$ins," advertisement_id = '".$id."'");					
					return('1');					
				}
			// Update Records in database ends					
		}
		//get All
		public function getAll(){
				
				$row = parent::select('*',$this->_tableName);				
				return($row);			
		}
		
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND advertisement_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND advertisement_id = '".$id."'");
				return($page_info);			
		}
		//Get info by Field
		public function getInfoByfield($field='',$fieldvalue=''){
				
				$page_Data=  parent::select('*',$this->_tableName," AND ".$field."= '".$fieldvalue."'");
				return($page_Data);			
		}
		
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," advertisement_id = '".$id."'");			
			return  ('1');	
		}
		
		
	}
?>