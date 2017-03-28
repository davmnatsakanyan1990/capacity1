<?php
	class home extends general {
		var  $_tableName  =  'tbl_user';
		var  $_feilds_name = '';
		var  $_tempVar = '';
		public function home($label  = ''){
		}		
		public function printData( ){
			$varTemp =  $this->_tableName;
			$userData =  parent::select(" * ",$this->_tableName);
			
		}		
		public  function redirect($url){
			if($url != '')
				parent::redirectTo($url);
		}
		
	}
?>