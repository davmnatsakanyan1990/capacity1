<?php
	class banner extends general {
		var  $_tableName  =  'tbl_banner';
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

                    if(($_POST['title'] == ''  ) )
                    {
                        return($_GET['msg'] ='required');
                    }
                    else
                    {
                        $ins = array(
                                "banner_title"=>$_POST['title'],
                                "banner_description"=>$_POST['description'],
                                "banner_img"=>'',
                                "banner_status"=>$_POST['status'],
                                "banner_order" => $_POST['order']
                            );
                            $id = parent::insert($this->_tableName,$ins);
                            
                            if($_FILES['banner_image']['name'] != '')
                            {
                                    $ext = end(explode('.',$_FILES['banner_image']['name']));
                                    $filename=$id.'_banner.'.$ext;
                                    move_uploaded_file($_FILES["banner_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/banner/'.$filename);
                                    $insimage = array('banner_img'=>$filename);						
                                    parent::update($this->_tableName,$insimage," banner_id = '".$id."'");
                            }

                        return '1';
                        //$gnrl->redirectTo($label.".php?msg=add");
                    }

                }
                
                //  Update Data 
		public  function updateData()
                {
			
                    $id = $_REQUEST['id'];			
           
                    if(($_POST['title'] == ''  ) ) 
                    {
                        return($_GET['msg'] ='required');
                    }
                    else
                    { 
                        if($_FILES['banner_image']['name'] != '')
                        {
                            $ext = end(explode('.',$_FILES['banner_image']['name']));
                            $filename=$id.'_banner.'.$ext;
                            move_uploaded_file($_FILES["banner_image"]["tmp_name"],MAIN_DIR.UPLOAD.'/banner/'.$filename);

                        }
                        else
                        {
                            $filename = $_POST['banner_image_edit'];
                        }
                        
                        $ins = array(
                                "banner_title"=>$_POST['title'],
                                "banner_description"=>$_POST['description'],
                                "banner_img"=>$filename,
                                "banner_status"=>$_POST['status'],
                                "banner_order" => $_POST['order']							
                                );
                        parent::update($this->_tableName,$ins," banner_id = '".$id."'");

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
				$row = parent::select('*',$this->_tableName," AND banner_id = '".$id."'");				
				return($row);			
		}
		// Get Info Data
		public function getInfo($id){
				//$id = $_REQUEST['id'];	
				$page_info =  parent::select('*',$this->_tableName," AND banner_id = '".$id."'");
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
			$ins = array("status"=>'0',);
			parent::update($this->_tableName,$ins,"banner_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("status"=>'1',);
			parent::update($this->_tableName,$ins,"banner_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," banner_id = '".$id."'");			
			return  ('1');	
		}
                
		
	}
?>