<?php
	class login extends general {
		var  $_tableName  =  'tbl_user';
		var  $_tableRole  =  'tbl_role';
//		var  $label =  'login';
		var  $_feilds_name = '';
		var  $_tempVar = '';
		public function login($label  = ''){
			$this->_tableName  =  'tbl_user';
			
		}		
		public function printData( ){
			$varTemp =  $this->_tableName;
			$userData =  parent::select(" * ",$this->_tableName);
			
		}
		public function  LoginAdmin(){
			if($_SESSION['adminid'] == ''){

				//$chkAttemp = parent::select(" * ",'tbl_login_attempt'," AND ip='".$_SERVER['REMOTE_ADDR']."'");
				//$No_of_Attempt = parent::select(" * ",'tbl_setting'," AND v_name='NO_OF_LOGIN_ATTEMPT'");
				//echo $No_of_Attempt[0]['l_values'];
				//die();
				// if login attemp greate then 3 then bane ip
				//if(count($chkAttemp) > 0 && $chkAttemp[0]['attempt'] >= $No_of_Attempt[0]['l_values']){
				//	return 'ipBan';
				//}else{
				//proceed to login	
				// check user credintial	
				$condition = " AND email ='".$_POST['username']."' AND password ='".base64_encode($_POST['password'])."' AND user_type= 'super_admin'";
				$id = parent::select(' * ',$this->_tableName,$condition);
			
				
				$numRows =  count($id);
				// valid credintial 
					if($numRows  ==  1){
						
						
						
						if($id[0]['user_status'] == "deactive"){
								return  'tillinactive';
						}else{
						
						session_start();
							$_SESSION['adminid'] = $id[0]['user_id'];
							$_SESSION['r_id'] = $id[0]['r_id'];
							$_SESSION['user_type'] = $id[0]['user_type'];
						
						return '1';			
							}
					
					}else{
						
						return  'invalid';			
					}
				
				//}
			
			}else{
				return  '0';
			}
		}
		public function  LoginUser(){
			//if($_SESSION['user_id'] == ''){
            if(!isset($_POST['username'])){
            	$_POST['username'] = $_POST['email'];
            }        
			if($_POST['username'] != '' || $_POST['password'] != '' || isset($_COOKIE['username']) && isset($_COOKIE['password'])){
						
                                    if($_COOKIE['username']!='' && $_COOKIE['password']!='')
                                    {
                                        $username=$_COOKIE['username'];
                                        $password=$_COOKIE['password'];
                                    }
                                    else {
                                        $username=$_POST['username'];
                                        $email=$_POST['email'];
                                        $password=$_POST['password'];
                                    }
						$condition = " AND email ='".$username."' AND password ='".base64_encode($password)."' AND user_type != 'super_admin'";
						$id = parent::select('*',$this->_tableName,$condition);
						$numRows =  count($id);
						if($numRows  ==  1){
							if($id[0]['user_status'] == "inactive"){
								if($id[0]['r_id'] == '2'){

									//return  'tillinactive';
									session_start();
									$_SESSION['user_id'] = $id[0]['user_id'];
									$_SESSION['r_id'] = $id[0]['r_id'];
									$role = parent::select(' * ',$this->_tableRole,' AND r_id='.$id[0]['r_id']);	
									$_SESSION['user_type'] = $role[0]['r_type'];
									
									if($id[0]['user_comp_id'] != 0){
										$_SESSION['company_id'] = $id[0]['user_comp_id'];
									}else{
										$_SESSION['company_id'] = $id[0]['user_id'];
									}
									
									$uparr = array("last_login"=>date("Y-m-d H:i:s"));
									parent::update($this->_tableName,$uparr,'  user_id='.$id[0]['user_id']);	
									
									
									if($id[0]['last_login'] == '0000-00-00 00:00:00' || $id[0]['last_login'] == ''){
										return '2';
									}else{
										return '1';
									}
								
								
								}else{
									return  'tillinactive';	
								}
								
							}else{
								
								if($id[0]['user_comp_id'] != 0){
									
									/*$pay_status =  parent::select("user_id,payment_status",'tbl_user_subscrib_detail'," AND user_id=".$id[0]['user_comp_id']." ORDER BY id desc limit 0,1");
									if($pay_status[0]['payment_status'] == 'Completed'){*/
											session_start();
											$_SESSION['user_id'] = $id[0]['user_id'];
											$_SESSION['r_id'] = $id[0]['r_id'];
											$role = parent::select(' * ',$this->_tableRole,' AND r_id='.$id[0]['r_id']);	
											$_SESSION['user_type'] = $role[0]['r_type'];


											$_SESSION['company_id'] = $id[0]['user_comp_id'];
											$uparr = array("last_login"=>date("Y-m-d H:i:s"));
											parent::update($this->_tableName,$uparr,'  user_id='.$id[0]['user_id']);	
											if($id[0]['last_login'] == '0000-00-00 00:00:00' || $id[0]['last_login'] == ''){
												return '2';
											}else{
												return '1';
											}

									/*}else{
										return  'tillinactive';	
									}*/

									
								}else{
									session_start();
									$_SESSION['user_id'] = $id[0]['user_id'];
									$_SESSION['r_id'] = $id[0]['r_id'];
									$role = parent::select(' * ',$this->_tableRole,' AND r_id='.$id[0]['r_id']);	
									$_SESSION['user_type'] = $role[0]['r_type'];
											
									$_SESSION['company_id'] = $id[0]['user_id'];
									$uparr = array("last_login"=>date("Y-m-d H:i:s"));
									parent::update($this->_tableName,$uparr,'  user_id='.$id[0]['user_id']);	
									if($id[0]['last_login'] == '0000-00-00 00:00:00' || $id[0]['last_login'] == ''){
										return '2';
									}else{
										return '1';
									}
								}
								
								

							}
						}else{
							return  'invalid';
						}
					
			
				}else{
					return  'required';
				}
			
			
			/*}else{
				return  '0';
			}*/	
		}

		public function  LoginUserAfterpayment(){
			//if($_SESSION['user_id'] == ''){
						
					$condition = " AND user_id ='".$_SESSION['temp_user']."' ";
					$id = parent::select(' * ',$this->_tableName,$condition);
				
					$numRows =  count($id);
						
					
								session_start();
								$_SESSION['user_id'] = $id[0]['user_id'];
								$_SESSION['r_id'] = $id[0]['r_id'];
								$role = parent::select(' * ',$this->_tableRole,' AND r_id='.$id[0]['r_id']);	
								$_SESSION['user_type'] = $role[0]['r_type'];



								if($id[0]['user_comp_id'] != 0){
									$_SESSION['company_id'] = $id[0]['user_comp_id'];
								}else{
									$_SESSION['company_id'] = $id[0]['user_id'];
								}
								
								$uparr = array("last_login"=>date("Y-m-d H:i:s"));
								parent::update($this->_tableName,$uparr,'  user_id='.$id[0]['user_id']);	
								
								if($id[0]['last_login'] == '0000-00-00 00:00:00' || $id[0]['last_login'] == ''){
									return '2';
								}else{
									return '1';
								}
			
		}
		
		public  function redirect($url){
			if($url != '')
				parent::redirectTo($url);
		}
		
	}
?>