<?php
	class slideshow extends general {
		var  $_tableName  = "tbl_slideshow";
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

			if(($_POST['ss_title'] == ''  ) ) {
				return($_GET['msg'] ='required');
			}else{
					$ins = array(
								"ss_title"=>$_POST['ss_title'],
								"ss_desc"=>$_POST['ss_desc'],
								"ss_filename"=>'',		
								"ss_discount_text"=>$_POST['ss_discount_text'],
								"ss_status"=>$_POST['ss_status']
							);
					$id = parent::insert($this->_tableName,$ins);
				
			if($_FILES['ss_filename']['name'] != '')
			{
				$ext = end(explode('/',$_FILES['ss_filename']['name']));
				$filename=$id.'_'.$_FILES['ss_filename']['name'];
				move_uploaded_file($_FILES['ss_filename']["tmp_name"],MAIN_DIR.UPLOAD.'/slideshow/'.$filename);
				$insthumbimage = array(
						'ss_filename'=>$filename,
				);
			
				parent::update($this->_tableName,$insthumbimage,"ss_id = '".$id."'");
			}
				
				
				return '1';
				//$gnrl->redirectTo($label.".php?msg=add");
			}
			
			

}
		//  Update Data 
		public  function updateData(){
			
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['ss_title'] == ''  ) ) {
				
					return($_GET['msg'] ='required');
				}
				else
				{	
					$ins = array(
								"ss_title"=>$_POST['ss_title'],
								"ss_desc"=>$_POST['ss_desc'],
								"ss_discount_text"=>$_POST['ss_discount_text'],
								"ss_status"=>$_POST['ss_status']
							);
					parent::update($this->_tableName,$ins," ss_id = '".$id."'");
					
			if($_FILES['ss_filename']['name'] != '')
			{
				$ext = end(explode('/',$_FILES['ss_filename']['name']));
				$filename=$id.'_'.$_FILES['ss_filename']['name'];
				move_uploaded_file($_FILES['ss_filename']["tmp_name"],MAIN_DIR.UPLOAD.'/slideshow/'.$filename);
				$insthumbimage = array(
						'ss_filename'=>$filename,
				);
			
				parent::update($this->_tableName,$insthumbimage,"ss_id = '".$id."'");
			}
					return('1');
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
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
				$row = parent::select('*',$this->_tableName," AND ss_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND ss_id = '".$id."'");
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
			$ins = array("ss_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"ss_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("ss_status"=>'active',);
			parent::update($this->_tableName,$ins,"ss_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," ss_id = '".$id."'");			
			return  ('1');	
		}	
		
	}
?>