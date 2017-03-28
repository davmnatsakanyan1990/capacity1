<?php
	class team extends general {
		var  $_tableName  = "tbl_team";
		var  $_tableDetailName  = "tbl_team_detail";
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

			if(($_POST['tm_title'] == '')) {
				return($_GET['msg'] ='required');
			}else{
				
					$ins = array(
								"tm_title" => $_POST['tm_title'],
								"company_user_id" => $_SESSION['user_id'],
								"tm_status" => $_POST['tm_status']															
							);
					$id = parent::insert($this->_tableName,$ins);
					foreach($_POST['user_ids'] as $uid){
					$insDetail = array(
								"tm_id" => $id,
								"user_id" => $uid
							);
						$usid = parent::insert($this->_tableDetailName,$insDetail);
					}
					
			return '1';
			}
}
		//  Update Data 
		public  function updateData(){
			
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['tm_title'] == '')) {
					return($_GET['msg'] ='required');
				}
				else
				{	
					
					$ins = array(
						"tm_title" => $_POST['tm_title'],
						"tm_status" => $_POST['tm_status']	
					);
					parent::update($this->_tableName,$ins," tm_id = '".$id."'");	
					
					parent::delete($this->_tableDetailName," tm_id = '".$id."'");
					foreach($_POST['user_ids'] as $uid){
					$insDetail = array(
								"tm_id" => $id,
								"user_id" => $uid
							);
						$usid = parent::insert($this->_tableDetailName,$insDetail);
					}
									
				return '1';					
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
				$row = parent::select('*',$this->_tableName," AND tm_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND tm_id = '".$id."'");
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
			$ins = array("tm_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"tm_id = '".$id."'");
			
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("tm_status"=>'active',);
			parent::update($this->_tableName,$ins,"tm_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," tm_id = '".$id."'");			
			return  ('1');	
		}
		
		
	}
?>