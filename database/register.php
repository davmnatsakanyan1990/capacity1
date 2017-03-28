<?php
	class register extends general {
		var  $_tableName  = "tbl_user";
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
			if(($_POST['user_email'] == '' && $_POST['user_first_name'] == ''  && $_POST['user_last_name'] == ''  ) ) {
				return($_GET['msg'] ='required');
			}else{
					$condition = " AND  email='".$_POST['user_email']."' ";
					$id = parent::select(' * ',$this->_tableName,$condition);
					$numRows =  count($id);
				if( $numRows > 0 ){
					return($_GET['msg'] ='notavailable');
				}else{
				$ins = array(
							"username" => $_POST['user_email'],
							"password" => base64_encode($_POST['password']),
							"fname" => $_POST['user_first_name'],
							"lname" => $_POST['user_last_name'],
							"user_avatar" => '',
							"user_dob" => '',
							"city"=>'',
							"state"=>'',
							"address"=>'',
							"gender" => $_POST['user_gender'],
							"user_type" => $_POST['user_type'],
							
							"email" => $_POST['user_email'],
							"user_status" => 'active',
							"user_comp_id" => $_SESSION['user_id'],
							"dep_id" => $_POST['dep_id'],
							"register_date" => date("Y-m-d H:i:s"),
							);
				$id = parent::insert($this->_tableName,$ins);
				
				/*$to = $_POST['email'];
				$from = "sales@carjini.com";
				$subject="Activation Email";
				$message= 'Welcome To Our Site carjini.com!';
				
				$headers = "From: " . $from . "\r\n";
				$headers .= "Reply-To: ". $from . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				mail($to, $subject, $message, $headers);*/
				
				return 1;
				}
				//$gnrl->redirectTo($label.".php?msg=add");
			}

		}
		//  User Change Profile 
		public  function updateData(){
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['user_email'] == '' && $_POST['user_first_name'] == ''  && $_POST['user_last_name'] == ''  ) ) {
				
					return($_GET['msg'] ='required');
				}
				else
				{	
					$ins = array(
							"username" => $_POST['user_email'],
							"password" => base64_encode($_POST['password']),
							"fname" => $_POST['user_first_name'],
							"lname" => $_POST['user_last_name'],
							"gender" => $_POST['user_gender'],
							"user_type" => $_POST['user_type'],
							"dep_id" => $_POST['dep_id'],
							"email" => $_POST['user_email'],
							);
					parent::update($this->_tableName,$ins," user_id = '".$id."'");
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
}
?>