<?php
	class timing extends general {
		var  $_tableName  = "tbl_working_day_time";
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
			
			if(($_POST['working_start_time'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
				
					$h_user_id = $_SESSION['adminid'];
					$h_type = 'general';
				
				$working_time = $_POST['working_start_time'].':::'.$_POST['working_end_time'];
				$working_day = implode(':::', $_POST['chk_day']);	
				
				$ins = array(
					"company_user_id"=>$h_user_id,
					"team_id" => '',
					"working_time" => $working_time,
					"working_days"=>$working_day,
					"type"=>$h_type,
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
				if(($_POST['working_start_time'] == '' )) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
						$h_user_id = $_SESSION['adminid'];
						$h_type = 'general';
					$working_time = $_POST['working_start_time'].':::'.$_POST['working_end_time'];
				$working_day = implode(':::', $_POST['chk_day']);	
					
					$ins = array(
						"team_id" => '',
						"working_time" => $working_time,
						"working_days"=>$working_day,
						"type"=>$h_type,
					);
					parent::update($this->_tableName,$ins," company_user_id = '".$_SESSION['adminid']."'");
					
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