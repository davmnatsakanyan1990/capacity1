<?php
	class dateformat extends general {
		var  $_tableName  = "tbl_dateformate";
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
			
			if(($_POST['dtformate'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
				
				$ins = array(
					"dtformate"=>$_POST['dtformate'],
					);
				
				$id = parent::insert($this->_tableName,$ins);
				
				return '1';
				//$gnrl->redirectTo($label.".php?msg=add");
			}

}
		//  Update Data 
		public  function updateData(){
		
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['dtformate'] == '' )) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
					$ins = array(
					"dtformate"=>$_POST['dtformate'],
					);
					parent::update($this->_tableName,$ins," id = '".$id."'");
					
					return('1');
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
					
		}
		//get All
		public function getAll($condition = ''){
				
				$row = parent::select('*',$this->_tableName, $condition);				
				return($row);			
		}
		
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
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
			parent::delete($this->_tableName," id = '".$id."'");
			
			return  ('1');	
		}
		
		
	}
?>