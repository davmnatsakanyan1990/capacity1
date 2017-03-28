<?php
	class pages extends general {
		var  $_tableName  = "tbl_cms";
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

			if(($_POST['cms_name'] == ''  ) ) {
				return($_GET['msg'] ='required');
			}else{
					$ins = array(
								"cms_name"=>$_POST['cms_name'],
								"cms_desc"=>$_POST['cms_desc'],
								"cms_external_url"=>$_POST['cms_external_url'],
								"cms_position"=>$_POST['cms_position'],
								"cms_display_order"=>$_POST['cms_display_order'],
								"cms_meta_title"=>$_POST['cms_meta_title'],
								"cms_meta_keyword"=>$_POST['cms_meta_keyword'],
								"cms_meta_description"=>$_POST['cms_meta_description'],
								"cms_publish"=>$_POST['cms_publish']							
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
				if(($_POST['cms_name'] == ''  ) ) {
				
					return($_GET['msg'] ='required');
				}
				else
				{	
					$ins = array(
								"cms_name"=>$_POST['cms_name'],
								"cms_desc"=>$_POST['cms_desc'],
								"cms_external_url"=>$_POST['cms_external_url'],
								"cms_position"=>$_POST['cms_position'],
								"cms_display_order"=>$_POST['cms_display_order'],
								"cms_meta_title"=>$_POST['cms_meta_title'],
								"cms_meta_keyword"=>$_POST['cms_meta_keyword'],
								"cms_meta_description"=>$_POST['cms_meta_description'],
								"cms_publish"=>$_POST['cms_publish']							
							);
					parent::update($this->_tableName,$ins," cms_id = '".$id."'");
					
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