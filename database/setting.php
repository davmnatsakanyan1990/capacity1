<?php
	class setting extends general {
		var  $_tableName  = "tbl_setting";
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
			if(($_POST['v_name'] == '')) {
					return($_GET['msg'] ='required');
			}else{
					$ins = array(
								"v_name"=>$_POST['v_name'],
								"l_values"=>$_POST['l_values'],
								"type"=>$_POST['type']
								);
				$id = parent::insert($this->_tableName,$ins);
				return '1';
			}

		}
		//  Update Data 
		public  function updateData(){
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['v_name'] == '')) 
				{
					return($_GET['msg'] ='required');
				}else{	
					$ins = array(
							"v_name"=>$_POST['v_name'],
							"l_values"=>$_POST['l_values'],
							"type"=>$_POST['type']						
							);

				parent::update($this->_tableName,$ins," id = '".$id."'");
				return('1');
				//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
			}
			// Update Records in database ends
					
		}
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND id = '".$id."'");
				return($row[0]);		
		}
		
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," id ='".$id."'");
			return  ('1');	
		}
		
		
	}
?>