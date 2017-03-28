<?php
	class userlist extends general {
		var  $_tableName  = "tbl_user";
		var  $_tableTeamName  = " tbl_team_detail";
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
			if(($_POST['email'] == '' && $_POST['f_name'] == ''  && $_POST['l_name'] == ''  ) ) {
				return($_GET['msg'] ='required');
			}else{
					$condition = " AND  email='".$_POST['email']."' ";
					$id = parent::select(' * ',$this->_tableName,$condition);
					$numRows =  count($id);
				if( $numRows > 0 ){
					return($_GET['msg'] ='notavailable');
				}else{

				$ins = array(
							"username" => $_POST['email'],
							"password" => base64_encode($_POST['password']),
							"fname" => $_POST['f_name'],
							"lname" => $_POST['l_name'],
							"user_avatar" => '',
							"user_dob" => '',
							"city"=>'',
							"state"=>'',
							"address"=>'',
							"gender" => $_POST['gender'],
							"user_type" => 'manager',
							"r_id" => $_POST['r_id'],
							"email" => $_POST['email'],
							"user_status" => 'active',
							"user_comp_id" => $_SESSION['company_id'],
							"register_date" => date("Y-m-d"),
							);
				$id = parent::insert($this->_tableName,$ins);
				
				if(isset($_POST['user_team_id']) && $_POST['user_team_id'] != ''){
					$teamArr = array(
							'tm_id'=>$_POST['user_team_id'],
							'user_id'=>$id,
							);
					parent::insert($this->_tableTeamName,$teamArr);
				}

				if($_FILES['user_avatar']['name'] != '')
				{
						//$ext = end(explode('.',$_FILES['user_avatar']['name']));
						$filename=$id.'_'.$_FILES['user_avatar']['name'];
						move_uploaded_file($_FILES['user_avatar']["tmp_name"],MAIN_DIR.UPLOAD.'/user/'.$filename);
						$insimage = array('user_avatar'=>$filename);						
						parent::update($this->_tableName,$insimage," user_id = '".$id."'");

				}

				return 1;
				}
				
			}

		}
		//  User Change Profile 
		public  function updateData(){
				$id = $_REQUEST['id'];				
			// Update Records in database starts
				if(($_POST['email'] == '' && $_POST['f_name'] == ''  && $_POST['l_name'] == ''  ) ) {
				
					return($_GET['msg'] ='required');
				}
				else
				{	
					$ins = array(
							"username" => $_POST['email'],
							"password" => base64_encode($_POST['password']),
							"fname" => $_POST['f_name'],
							"lname" => $_POST['l_name'],
							"gender" => $_POST['gender'],
							"r_id" => $_POST['r_id'],
							"email" => $_POST['email'],
							);
					parent::update($this->_tableName,$ins," user_id = '".$id."'");

					if(isset($_POST['user_team_id']) && $_POST['user_team_id'] != ''){
						$teamArr = array(
								'tm_id'=>$_POST['user_team_id'],
								'user_id'=>$id,
								);
						parent::update($this->_tableTeamName,$teamArr," user_id = '".$id."'");
					}

					if($_FILES['user_avatar']['name'] != ''){

							//$ext = end(explode('.',$_FILES['user_avatar']['name']));
							$filename=$id.'_'.$_FILES['user_avatar']['name'];
							move_uploaded_file($_FILES['user_avatar']["tmp_name"],MAIN_DIR.UPLOAD.'/user/'.$filename);
							
							$insimage = array('user_avatar'=>$filename);						
							parent::update($this->_tableName,$insimage," user_id = '".$id."'");

							
					}
					//echo '<pre>'; print_r($_FILES);
					//die();
					return('1');
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
		}
		//user Change Password
		public  function changePassword(){
				$id = $_SESSION['user_id'];			
			// Update Records in database starts
				if(($_POST['cur_password'] == '' && $_POST['new_password'] == '' ) ) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
					$condition = " AND user_id ='".$_SESSION['user_id']."' AND password='".$_POST['cur_password']."' ";
					$id = parent::select(' * ',$this->_tableName,$condition);
					$numRows =  count($id);
					if($numRows > 0 ){
					$ins = array(
							"password"=>base64_encode($_POST['new_password']),
							);
					parent::update($this->_tableName,$ins," user_id = '".$id."'");
					return('1');
					}else{
						return($_GET['msg'] ='invalid');
					}
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
		} 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND user_id = '".$id."'");				
				return($row);			
		}
		public  function getUserdetail(){
			$id = $_SESSION['user_id'];	
			$condition = " AND user_id ='".$_SESSION['user_id']."' ";
			$id = parent::select(' * ',$this->_tableName,$condition);
			return $id;
		}
		public  function getAllUser($condition=''){
			
			$id = parent::select(' * ',$this->_tableName,$condition);
			return $id;
		}
		//  Deactive Recode
		public function deactiveRecode(){
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("user_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"user_id = '".$id."'");
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("user_status"=>'active',);
			parent::update($this->_tableName,$ins,"user_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			parent::delete($this->_tableName," user_id = '".$id."'");
			
			return  ('1');	
		}
}
?>