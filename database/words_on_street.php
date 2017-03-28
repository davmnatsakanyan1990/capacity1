<?php
	class words_on_street extends general {
		var  $_tableName  =  'tbl_words_on_street';
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

                    if(($_POST['wos_heading'] == ''  ) )
                    {
                        return($_GET['msg'] ='required');
                    }
                    else
                    {
                        $ins = array(
                                "wos_heading" => $_POST['wos_heading'],
                                "wos_description" => $_POST['wos_description'],
                                "wos_img" => '',
                                "wos_status" => $_POST['wos_status'],
                                "wos_order" => $_POST['wos_order']
                            );
                            $id = parent::insert($this->_tableName,$ins);
                            
                        if($_FILES['wos_image']['name'] != '')
                        {
                            $ext = end(explode('.',$_FILES['wos_image']['name']));
                            $filename=$id.'_words_on_street.'.$ext;
                            move_uploaded_file($_FILES["wos_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/words_on_street/'.$filename);
                            $insimage = array('wos_img'=>$filename);						
                            parent::update($this->_tableName,$insimage," wos_id = '".$id."'");
                        }    
                            

                        return '1';
                        //$gnrl->redirectTo($label.".php?msg=add");
                    }

                }
                
                //  Update Data 
		public  function updateData()
                {
			
                    $id = $_REQUEST['id'];			
           
                    if(($_POST['wos_description'] == ''  ) ) {

                        return($_GET['msg'] ='required');
                    }
                    else
                    { 
                        if($_FILES['wos_image']['name'] != '')
                        {
                            $ext = end(explode('.',$_FILES['wos_image']['name']));
                            $filename=$id.'_word_on_street.'.$ext;
                            move_uploaded_file($_FILES["wos_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/words_on_street/'.$filename);
                        }
                        else
                        {
                           // take existing image name hidden field
                           $filename = $_POST['wos_image_edit'];
                        }
                        
                        $ins = array(
                                "wos_heading" => $_POST['wos_heading'],
                                "wos_description" => $_POST['wos_description'],
                                "wos_img" => $filename,
                                "wos_status" => $_POST['wos_status'],
                                "wos_order" => $_POST['wos_order']							
                                );
                        parent::update($this->_tableName,$ins," wos_id = '".$id."'");

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
				$row = parent::select('*',$this->_tableName," AND wos_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND wos_id = '".$id."'");
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
			$ins = array("wos_status"=>'0',);
			parent::update($this->_tableName,$ins,"wos_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("wos_status"=>'1',);
			parent::update($this->_tableName,$ins,"wos_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," wos_id = '".$id."'");			
			return  ('1');	
		}
                
		
	}
?>