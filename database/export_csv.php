<?php
	class export_csv extends general {
		var  $_tableName  = "tbl_newsletter_subscriber";
		var  $_feilds_name = '';
		var  $_tempVar = '';
		//  Default Constructor 
		//  Redirect To Url		
		public  function redirect($url){
			if($url != '')
				parent::redirectTo($url);
		}

		//get All
		public function getAll($condition = ''){
				
				$row = parent::select('*',$this->_tableName, $condition);				
				return($row);			
		}
		
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND newsletter_subscriber_id = '".$id."'");				
				return($row);			
		}

		//Get info by Field
		public function getInfoByfield($field='',$fieldvalue=''){
				
				$page_Data=  parent::select('*',$this->_tableName," AND ".$field."= '".$fieldvalue."'");
				return($page_Data);			
		}		
		
	}
?>