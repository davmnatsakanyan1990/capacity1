<?php
	class userlist extends general {
		var  $_tableName  = "tbl_user";
		var  $_tableTeamName  = "tbl_team_detail";
		var  $_tableSubscribe  = "tbl_user_subscrib_detail";
		
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
			global $mail;
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
							//"gender" => $_POST['gender'],
							"job_title" => $_POST['job_title'],
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
					$link = '<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'">Click Here</a>';
						
					$company = parent::select('company_name','tbl_user',' AND user_id = "'.$_SESSION['company_id'].'" ');
					
					$email_template = parent::select('*','tbl_email_template',' AND email_title = "User Invitation Add new"');

					$message = str_replace('{NAME}',$_POST['f_name'].' '.$_POST['l_name'],$email_template[0]['email_body']);
					
					$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
                    $message = str_replace('{LOGO}',$logo,$message);

					$message = str_replace('{EMAIL}',$_POST['email'],$message);
					$message = str_replace('{PASSWORD}',$_POST['password'],$message);
						
					$message = str_replace('{LINK}',$link,$message);
					$message = str_replace('{COMPANYNAME}',$company[0]['company_name'],$message);

	                    
					$mail->Subject  =   $email_template[0]['email_subject'];
					$mail->addAddress($_POST['email']);
					
					$mail->msgHTML($message);
					$mail->send();

				return 1;
				}
				
			}

		}

		//step 2 regisatration
		public function  insertDatastep2(){
			global $mail;
			if(($_POST['email'] == '' && $_POST['fullname'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
					$condition = " AND  email='".$_POST['email']."' ";
					$id = parent::select(' * ',$this->_tableName,$condition);
				$numRows =  count($id);

				if( $numRows > 0 ){
					return($_GET['msg'] ='notavailable');
				}else{
					$uname = explode(' ',$_POST['fullname']);

					$ins = array(
							"username" => $_POST['email'],
							"password" => base64_encode($_POST['password']),
							"fname" => $uname[0],
							"lname" => $uname[1],
							"user_avatar" => '',
							"user_dob" => '',
							"city"=>'',
							"state"=>'',
							"address"=>'',
							//"gender" => $_POST['gender'],
							"job_title" => $_POST['job_title'],
							"company_name" => $_POST['company_name'],
							"user_type" => 'company_user',
							"r_id" => '2',
							"email" => $_POST['email'],
							"user_status" => 'active',
							"user_comp_id" => '',
							"register_date" => date("Y-m-d"),
					);

					
				$id = parent::insert($this->_tableName,$ins);
				if(isset($_POST['department']) && $_POST['department'] != ''){
					$teamArr = array(
							'tm_title'=>$_POST['department'],
							'company_user_id'=>$id,
							'tm_status' =>'active'
							);
					$team_id = parent::insert('tbl_team',$teamArr);
					$teamArr = array(
							'tm_id'=>$team_id,
							'user_id'=>$id,
							);
					parent::insert($this->_tableTeamName,$teamArr);
				}
				
				if($_FILES['user_avatar']['name'] != ''){
						//$ext = end(explode('.',$_FILES['user_avatar']['name']));
						$filename=$id.'_'.$_FILES['user_avatar']['name'];
						move_uploaded_file($_FILES['user_avatar']["tmp_name"],MAIN_DIR.UPLOAD.'/user/'.$filename);
						$insimage = array('user_avatar'=>$filename);						
						parent::update($this->_tableName,$insimage," user_id = '".$id."'");

				}

				$expiredate = date('Y-m-d', strtotime("+7 day"));
				 			
					$subArr = array(
						'user_id'=>$id,
						'sub_plan_id'=>'0',
						'subscrib_date'=>date("Y-m-d"),
						'subscrib_price'=>0,
						'subscrib_type'=>'free',
						'expire_date'=> $expiredate,
						'trial_expire_date'=>$expiredate,
						'payment_status'=>'complete',
					);

					parent::insert($this->_tableSubscribe,$subArr);
					// code for set default day time for calender

					$defaulttime = parent::select("*","tbl_working_day_time"," AND type = 'general'");
					$timearr = array(
						'company_user_id'=>$id,
						'team_id'=>'',
						'working_time'=>$defaulttime[0]['working_time'],
						'working_days'=> $defaulttime[0]['working_days'],
						'type'=>'perticular',
					);

					parent::insert("tbl_working_day_time",$timearr);


					$defaultlunch = parent::select("*","tbl_lunch_hours"," AND type = 'general'");
					$luncharr = array(
						'company_user_id'=>$id,
						'team_id'=>$team_id,
						'lunch_hours'=>$defaultlunch[0]['lunch_hours'],
						'show_in_calender'=> $defaultlunch[0]['show_in_calender'],
						'type'=>'perticular',
					);
					parent::insert("tbl_lunch_hours",$luncharr);

					$defaultpermission = parent::select("*","tbl_role_access"," AND company_user_id = '0'");
					foreach($defaultpermission as $per){
						$perarr = array(
                          'company_user_id'=>$id,
                          'r_id'=>$per['r_id'],
                          'v_name'=>$per['v_name'],
                          'l_value'=> $per['l_value'],
                        );
						parent::insert("tbl_role_access",$perarr);
					}

					$_SESSION['register_id'] = $id;

					    $email_template = parent::select('*','tbl_email_template',' AND email_title = "User Confirmation"');
						$link = '<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'">Here</a>';
						
						$message = str_replace('{EMAIL}',$_POST['email'],$email_template[0]['email_body']);
						$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
            			$message = str_replace('{LOGO}',$logo,$message);

						$message = str_replace('{PASSWORD}',$_POST['password'],$message);
						$message = str_replace('{NAME}',$_POST['fullname'],$message);
						$message = str_replace('{LINK}',$link,$message);
	                    
						$mail->Subject  =   $email_template[0]['email_subject'];
					 	$mail->addAddress($_POST['email']);
					 	$mail->msgHTML($message);
						$mail->send();

				return 1;
				}
				
			}

		}
		//step 3 regisatration
		public function  insertUserlist(){

			global $mail;
			if(($_POST['userlist'] == '' ) ) {
				return 'required';
			}else{
					$text = trim($_POST['userlist']); 
					$userlist1 = explode("\n", $text);;
					  $fnuser = array();
					  foreach($userlist1 as $ui){
					    $myuser = explode(",", $ui);
					    foreach($myuser as $my){
					      $fnuser[] = $my;
					    }
					}
		
				if(count($fnuser) > 9){

					return 'maximum';
				
				}else{

					foreach($fnuser as $fn){
						$condition = " AND  email='".$fn."' ";
						$id = parent::select(' * ',$this->_tableName,$condition);
						$numRows =  count($id);

						if( $numRows > 0 ){
							continue;
						}

						if (!filter_var($fn, FILTER_VALIDATE_EMAIL) === false) {
						
						$ins = array(
								"username" => $fn,
								"password" => '',
								"fname" => '',
								"lname" => '',
								"user_avatar" => '',
								"user_dob" => '',
								"city"=>'',
								"state"=>'',
								"address"=>'',
								"job_title" => '',
								"user_type" => 'employee',
								"r_id" => '4',
								"email" => $fn,
								"user_status" => 'inactive',
								"user_comp_id" => $_SESSION['register_id'],
								"register_date" => date("Y-m-d"),
						);
						
						$id = parent::insert($this->_tableName,$ins);
						$ilnk = '<a  style="color: #0000FF;text-decoration: underline;" href="'.SITE_URL.'step2.php?tp=create&uid='.base64_encode($id).'">Click Here</a>';
						
						$email_template = parent::select('*','tbl_email_template',' AND email_title = "User Invitation"');
						$company = parent::select('company_name','tbl_user',' AND user_id = "'.$_SESSION['register_id'].'" ');

						
						$message = str_replace('{LINK}',$ilnk,$email_template[0]['email_body']);
						$logo = '<img src="'.SITE_URL.'images/capacity-logo.png">';
                        $message = str_replace('{LOGO}',$logo,$message);

						$message = str_replace('{COMPANYNAME}',$company[0]['company_name'],$message);
	                    
						$mail->Subject  =   $email_template[0]['email_subject'];
					 	$mail->addAddress($fn);
					 	$mail->msgHTML($message);
						$mail->send();

						}

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
							//"gender" => $_POST['gender'],
							"job_title" => $_POST['job_title'],
							"r_id" => $_POST['r_id'],
							"email" => $_POST['email'],
							);
					parent::update($this->_tableName,$ins," user_id = '".$id."'");

					if(isset($_POST['user_team_id']) && $_POST['user_team_id'] != ''){
						$chkteam = parent::select("id",$this->_tableTeamName," AND user_id=".$id);
						if(count($chkteam) > 0){
							$teamArr = array(
									'tm_id'=>$_POST['user_team_id'],
									'user_id'=>$id,
									);
							parent::update($this->_tableTeamName,$teamArr," user_id = '".$id."'");
						}else{
							$teamArr = array(
									'tm_id'=>$_POST['user_team_id'],
									'user_id'=>$id,
									);
							
							parent::insert($this->_tableTeamName,$teamArr);
						}
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
		//  User Change Profile 
		public  function updateDatastep2(){
				$id = $_REQUEST['id'];				
			// Update Records in database starts
				if(($_POST['email'] == '' && $_POST['fullname'] == '' ) ) {
				
					return($_GET['msg'] ='required');
				}
				else
				{	
					$uname = explode(' ',$_POST['fullname']);

					$ins = array(
							"password" => base64_encode($_POST['password']),
							"fname" => $uname[0],
							"lname" => $uname[1],
							"job_title" => $_POST['job_title'],
							"user_status" => 'active',
							);
					parent::update($this->_tableName,$ins," user_id = '".$id."'");

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