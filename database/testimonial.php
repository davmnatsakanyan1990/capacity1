<?php
	class testimonial extends general {
		var  $_tableName  =  'tbl_testimonial';
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

                    if(($_POST['tmn_description'] == ''  ) )
                    {
                        return($_GET['msg'] ='required');
                    }
                    else
                    {
                        $ins = array(
                                "tmn_user" => $_POST['tmn_user'],
                                "tmn_description" => $_POST['tmn_description'],
                                "tmn_img" => $filename,
                                "tmn_status" => $_POST['tmn_status'],
                                "tmn_order" => $_POST['tmn_order']
                            );
                            $id = parent::insert($this->_tableName,$ins);
                         
                        if($_FILES['tmn_image']['name'] != '')
                        {
                            $ext = end(explode('.',$_FILES['tmn_image']['name']));
                            $filename=$id.'_testimonial.'.$ext;
                            move_uploaded_file($_FILES["tmn_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/testimonial/'.$filename);
                            $insimage = array('tmn_img'=>$filename);						
                            parent::update($this->_tableName,$insimage," tmn_id = '".$id."'");
                        }  
                            

                        return '1';
                        //$gnrl->redirectTo($label.".php?msg=add");
                    }

                }
                
                //  Update Data 
		public  function updateData()
                {
			
                    $id = $_REQUEST['id'];			
           
                    if(($_POST['tmn_description'] == ''  ) ) {

                        return($_GET['msg'] ='required');
                    }
                    else
                    { 
                        if($_FILES['tmn_image']['name'] != '')
                        {
                            $ext = end(explode('.',$_FILES['tmn_image']['name']));
                            $filename=$id.'_testimonial.'.$ext;
                            move_uploaded_file($_FILES["tmn_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/testimonial/'.$filename);
                        }
                        else
                        {
                           // take existing image name hidden field
                           $filename = $_POST['tmn_image_edit'];
                        }
                        
                        $ins = array(
                                "tmn_user"=>$_POST['tmn_user'],
                                "tmn_description"=>$_POST['tmn_description'],
                                "tmn_img"=>$filename,
                                "tmn_status"=>$_POST['tmn_status'],
                                "tmn_order" => $_POST['tmn_order']							
                                );
                        parent::update($this->_tableName,$ins," tmn_id = '".$id."'");

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
				$row = parent::select('*',$this->_tableName," AND tmn_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND tmn_id = '".$id."'");
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
			$ins = array("tmn_status"=>'0',);
			parent::update($this->_tableName,$ins,"tmn_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("tmn_status"=>'1',);
			parent::update($this->_tableName,$ins,"tmn_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," tmn_id = '".$id."'");			
			return  ('1');	
		}
                
		
	}
?>