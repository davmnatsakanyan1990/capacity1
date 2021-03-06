<?php
	class designation extends general {
		var  $_tableName  = "tbl_designation";
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

			if(($_POST['des_title'] == '')) {
				return($_GET['msg'] ='required');
			}else{
				
					$ins = array(
								"des_title" => $_POST['des_title'],
								"des_status" => $_POST['des_status']															
							);
					$id = parent::insert($this->_tableName,$ins);
			return '1';
			}
}
		//  Update Data 
		public  function updateData(){
			
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['des_title'] == '')) {
					return($_GET['msg'] ='required');
				}
				else
				{	
					
					$ins = array(
					"des_title" => $_POST['des_title'],
					"des_status" => $_POST['des_status']															
					);
					parent::update($this->_tableName,$ins," des_id = '".$id."'");					
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
				$row = parent::select('*',$this->_tableName," AND des_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND des_id = '".$id."'");
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
			$ins = array("des_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"des_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("des_status"=>'active',);
			parent::update($this->_tableName,$ins,"des_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," des_id = '".$id."'");			
			return  ('1');	
		}
		
		
	}
?>