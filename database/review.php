<?php
	class review extends general {
		var  $_tableName  = "tbl_review";
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

			if(($_POST['review_title'] == '')) {
				return($_GET['msg'] ='required');
			}else{
				
				$rateAvg = ($_POST['rating_ambiance'] + $_POST['rating_food'] + $_POST['rating_services'] + $_POST['rating_price']) / 4;
				
					$ins = array(
								"review_title" => $_POST['review_title'],
								"user_id" => $_POST['user_id'],
								"product_id" => $_POST['product_id'],
								"rating_ambiance" => $_POST['rating_ambiance'],
								"rating_food" => $_POST['rating_food'],
								"rating_service" => $_POST['rating_services'],
								"rating_price" => $_POST['rating_price'],
								"rating_avg" => $rateAvg,
								"review_desc" => $_POST['review_desc'],
								"review_date" => date('Y-m-d'),
								"review_status" => 'pending'															
							);
					$id = parent::insert($this->_tableName,$ins);
			return '1';
			}
}
		//  Update Data 
		public  function updateData(){
			
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['sector_title'] == '')) {
					return($_GET['msg'] ='required');
				}
				else
				{	
				
					$rateAvg = ($_POST['rating_ambiance'] + $_POST['rating_food'] + $_POST['rating_services'] + $_POST['rating_price']) / 4;
					
					
					$ins = array(
								"review_title" => $_POST['review_title'],
								"user_id" => $_POST['user_id'],
								"product_id" => $_POST['product_id'],
								"rating_ambiance" => $_POST['rating_ambiance'],
								"rating_food" => $_POST['rating_food'],
								"rating_service" => $_POST['rating_services'],
								"rating_price" => $_POST['rating_price'],
								"rating_avg" => $rateAvg,
								"review_desc" => $_POST['review_desc'],
								"review_status" => $_POST['review_status']															
					);
					parent::update($this->_tableName,$ins," review_id = '".$id."'");					
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
				$row = parent::select('*',$this->_tableName," AND review_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND review_id = '".$id."'");
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
			$ins = array("review_status"=>'reject',);
			parent::update($this->_tableName,$ins,"review_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		
		public  function  featureRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("review_featured"=>'yes',);
			parent::update($this->_tableName,$ins,"review_id = '".$id."'");
			return  ('1');
		}
		
		public  function  unfeatureRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("review_featured"=>'no',);
			parent::update($this->_tableName,$ins,"review_id = '".$id."'");
			return  ('1');
		}
		
		
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("review_status"=>'approve',);
			parent::update($this->_tableName,$ins,"review_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," review_id = '".$id."'");			
			return  ('1');	
		}
		
		
	}
?>