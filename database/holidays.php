<?php
	class holidays extends general {
		var  $_tableName  = "tbl_holidays";
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
			
			if(($_POST['holi_title'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
				if($_SESSION['user_type'] == 'super_admin'){
					$h_user_id = $_SESSION['adminid'];
					$h_type = 'general';
				}else{
					$h_user_id = $_SESSION['user_id'];
					$h_type = 'perticuler';
				}
				
				$ins = array(
					"holi_title"=>$_POST['holi_title'],
					"holi_start_date" => $_POST['holi_start_date'],
					"holi_end_date" => $_POST['holi_end_date'],
					"holi_user_id"=>$h_user_id,
					"holi_type"=>$h_type,
					"holi_status"=>$_POST['holi_status']
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
				if(($_POST['holi_title'] == '' )) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
					if($_SESSION['user_type'] == 'super_admin'){
						$h_user_id = $_SESSION['adminid'];
						$h_type = 'general';
					}else{
						$h_user_id = $_SESSION['user_id'];
						$h_type = 'perticuler';
					}
					
					$ins = array(
					"holi_title"=>$_POST['holi_title'],
					"holi_start_date" => $_POST['holi_start_date'],
					"holi_end_date" => $_POST['holi_end_date'],
					"holi_user_id"=>$h_user_id,
					"holi_type"=>$h_type,
					"holi_status"=>$_POST['holi_status']
					);
					parent::update($this->_tableName,$ins," holi_id = '".$id."'");
					
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
				$row = parent::select('*',$this->_tableName," AND holi_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		//Get info by Field
		public function getInfoByfield($field='',$fieldvalue=''){
				
				$page_Data=  parent::select('*',$this->_tableName," AND ".$field."= '".$fieldvalue."'");
				return($page_Data);			
		}
		//  Deactive Recode
		public function deactiveRecode(){
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("holi_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"holi_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("holi_status"=>'active',);
			parent::update($this->_tableName,$ins,"holi_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," holi_id = '".$id."'");
			
			return  ('1');	
		}
		
		
	}
?>