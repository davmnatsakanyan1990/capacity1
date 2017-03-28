<?php
	class template extends general {
		var  $_tableName  = "tbl_template";
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
		//  Insert Recorde
		public function  insertData(){
			if(($_POST['title'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
				$ins = array(
							"template_name"=>$_POST['title'],
							"template_subject"=>$_POST['subject'],
							"template_source"=>$_POST['content'],
							"template_status"=>$_POST['status']
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
				if(($_POST['title'] == '' )) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
					$ins = array(
							"template_name"=>$_POST['title'],
							"template_subject"=>$_POST['subject'],
							"template_source"=>$_POST['content'],
							"template_status"=>$_POST['status']
							);
					parent::update($this->_tableName,$ins,"template_id = '".$id."'");
					return('1');
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
					
		}
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND template_id = '".$id."'");				
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
			$ins = array("template_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"template_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("template_status"=>'active',);
			parent::update($this->_tableName,$ins,"template_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," template_id = '".$id."'");
			
			return  ('1');	
		}
		
		
	}
?>