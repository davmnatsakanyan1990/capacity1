<?php
	class faq extends general {
		var  $_tableName  =  'tbl_faq';
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
                
                //  Insert Recorde
		public function  insertData()
                {

                    if(($_POST['faq_question'] == ''  ) )
                    {
                        return($_GET['msg'] ='required');
                    }
                    else
                    {
                        $ins = array(
                                "faq_question" => $_POST['faq_question'],
                                "faq_answer" => $_POST['faq_answer'],
                                "faq_status" => $_POST['faq_status'],
                                "faq_order" => $_POST['faq_order']
                            );
                            $id = parent::insert($this->_tableName,$ins);
                            
                            

                        return '1';
                        //$gnrl->redirectTo($label.".php?msg=add");
                    }

                }
                
                //  Update Data 
		public  function updateData()
                {
			
                    $id = $_REQUEST['id'];			
           
                    if(($_POST['faq_question'] == ''  ) ) {

                        return($_GET['msg'] ='required');
                    }
                    else
                    { 
                         $ins = array(
                                "faq_question" => $_POST['faq_question'],
                                "faq_answer" => $_POST['faq_answer'],
                                "faq_status" => $_POST['faq_status'],
                                "faq_order" => $_POST['faq_order']
                            );
                        parent::update($this->_tableName,$ins," faq_id = '".$id."'");

                        return('1');
                            //$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
                    }
								
		}
                //get All
		public function getAll(){
				
				$row = parent::select('*',$this->_tableName);				
				return($row);			
		}
		
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND faq_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND faq_id = '".$id."'");
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
			$ins = array("faq_status"=>'0',);
			parent::update($this->_tableName,$ins,"faq_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("faq_status"=>'1',);
			parent::update($this->_tableName,$ins,"faq_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," faq_id = '".$id."'");			
			return  ('1');	
		}
                
		
	}
?>