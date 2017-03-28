<?php
	class email_template extends general {
		var  $_tableName  = "tbl_email_template";
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
			
			if(($_POST['email_title'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
                            
                            $ins = array(
                                "email_title"=>$_POST['email_title'],
                                "email_subject" => $_POST['email_subject'],
                                "email_body" => $_POST['email_body'],
                                "email_status" => $_POST['email_status']
                            );
				
				$id = parent::insert($this->_tableName,$ins);
				
				return '1';
			}

}
		//  Update Data 
		public  function updateData(){
		
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['email_title'] == '' )) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
				
                                    $ins = array(
                                    "email_title"=>$_POST['email_title'],
                                    "email_subject" => $_POST['email_subject'],
                                    "email_body" => $_POST['email_body'],
                                    "email_status" => $_POST['email_status']
                                    );
                                    parent::update($this->_tableName,$ins," email_id = '".$id."'");

                                    return('1');
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
				$row = parent::select('*',$this->_tableName," AND email_id = '".$id."'");				
				return($row);			
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
			$ins = array("email_status"=>'0',);
			parent::update($this->_tableName,$ins,"email_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("email_status"=>'1',);
			parent::update($this->_tableName,$ins,"email_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," email_id = '".$id."'");
			
			return  ('1');	
		}
		
		
	}
?>