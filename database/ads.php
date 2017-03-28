<?php
	class ads extends general {
		var  $_tableName  = "tbl_ads";
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
			
			if(($_POST['ads_title'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
				$ins = array(
					"ads_title"=>$_POST['ads_title'],
					"ads_url" => $_POST['ads_url'],
					"ads_display_order" => $_POST['ads_display_order'],
					"ads_content"=>$_POST['ads_content'],
					"ads_image"=>'',
					/*"ads_start_date"=>$_POST['ads_start_date'],
					"ads_end_date"=>$_POST['ads_end_date'],*/
					"ads_status"=>$_POST['ads_status']
					);
				
				$id = parent::insert($this->_tableName,$ins);
				
					if($_FILES['ads_image']['name'] != '')
					{
						$ext = end(explode('/',$_FILES['ads_image']['name']));
						$filename=$id.'_ads.'.$ext;
						move_uploaded_file($_FILES["ads_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/ads/'.$filename);
						$insimage = array('ads_image'=>$filename);						
						parent::update($this->_tableName,$insimage," ads_id = '".$id."'");
					}
				
				return '1';
				//$gnrl->redirectTo($label.".php?msg=add");
			}

}
		//  Update Data 
		public  function updateData(){
		
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['ads_title'] == '' )) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
					$ins = array(
					"ads_title"=>$_POST['ads_title'],
					"ads_content"=>$_POST['ads_content'],
					"ads_url" => $_POST['ads_url'],
					"ads_display_order" => $_POST['ads_display_order'],
					/*"ads_start_date"=>$_POST['ads_start_date'],
					"ads_end_date"=>$_POST['ads_end_date'],*/
					"ads_status"=>$_POST['ads_status']
					);
					parent::update($this->_tableName,$ins," ads_id = '".$id."'");
					
					if($_FILES['ads_image']['name'] != '')
					{
						$ext = end(explode('/',$_FILES['ads_image']['name']));
						$filename=$id.'_ads.'.$ext;
						move_uploaded_file($_FILES["ads_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/ads/'.$filename);
						$insimage = array('ads_image'=>$filename,"ads_content"=>'');						
						parent::update($this->_tableName,$insimage," ads_id = '".$id."'");
					}
					
					
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
				$row = parent::select('*',$this->_tableName," AND ads_id = '".$id."'");				
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
			$ins = array("ads_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"ads_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("ads_status"=>'active',);
			parent::update($this->_tableName,$ins,"ads_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," ads_id = '".$id."'");
			
			return  ('1');	
		}
		
		
	}
?>