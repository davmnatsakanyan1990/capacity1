<?php
	class user extends general {
		var  $_tableName  = "tbl_user";
		var  $_tableEmailName  = "tbl_email_template";
		var  $_tablePlan  = "tbl_user_subscrib_detail";
		
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
			
			if(($_POST['username'] == '' && $_POST['email'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
				
				$user_access =implode(':::',$_POST['user_access']);
				if($_POST['user_type'] == "system"){
					$user_type = 'super_admin';
				}else{
					$user_type = 'user';
				}
				$ins = array(
							"user_email" => $_POST['user_email'],
							"username" => $_POST['username'],
							"user_password" => base64_encode($_POST['user_password']),											
							"user_first_name" => $_POST['user_first_name'],
							"user_last_name" => $_POST['user_last_name'],
							"user_status" => $_POST['user_status'],
							"user_occupation"=>$_POST['user_occupation'],
							"user_city_id" => $_POST['user_city_id'],
							"user_country_id" => $_POST['user_country_id'],
							"user_state" => $_POST['user_state'],
							"user_fav_books" => $_POST['user_fav_books'],
							"user_fav_musics" => $_POST['user_fav_musics'],
							"user_fav_drinks"=>$_POST['user_fav_musics'],
							"user_fav_movies" => $_POST['user_fav_movies'],
							"user_fav_food"=>$_POST['user_fav_food'],
							"user_created_date"=>date('Y-m-d h:i:s'),
							"user_type"=>$user_type,
							);
				$id = parent::insert($this->_tableName,$ins);
				
				return 1;
				
			}
		}
				//  User Change Profile 
		public  function updateprofData(){
			
				$id = $_REQUEST['id'];		
				
			// Update Records in database starts
				if(($_POST['email'] == '') ) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
				
					$ins = array(
						
							"username" => $_POST['email'],
							"password" => base64_encode($_POST['password']),
							"fname" => $_POST['user_first_name'],
							"lname" => $_POST['user_last_name'],
							"email" => $_POST['email'],
							);
				
					parent::update($this->_tableName,$ins," user_id = '".$id."'");
					return('1');
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
		}
		
		//user Change Password
		public  function updateProfile(){
	
				$id = $_SESSION['user_id'];		
				
			// Update Records in database starts
				if(($_POST['user_first_name'] == '' && $_POST['user_email'] == '') ) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
					if(isset($_POST['user_fb_post_allow']) && $_POST['user_fb_post_allow'] != ''){
						$allow = $_POST['user_fb_post_allow'];
					}else{
						$allow = 'no';
					}
				
					$ins = array(
							"user_first_name" => $_POST['user_first_name'],
							"user_dob" => $_POST['user_dob'],
							"user_gender" => $_POST['user_gender'],											
							"user_country_id" =>$_POST['user_country_id'],
							"user_city_id" => $_POST['user_city_id'],
							"user_last_name" => $_POST['user_last_name'],
							"user_contact_no"=>$_POST['user_contact_no'],
							"user_alt_contact_no"=>$_POST['user_alt_contact_no'],
							"user_addressline1"=>$_POST['user_addressline1'],
							"user_addressline2"=>$_POST['user_addressline2'],
							"user_email" => $_POST['user_email'],
							"user_description" => $_POST['user_description'],
							"user_fav_books" => $_POST['user_fav_books'],
							"user_fav_musics" => $_POST['user_fav_musics'],
							"user_fav_drinks"=>$_POST['user_fav_drinks'],
							"user_fav_movies" => $_POST['user_fav_movies'],
							"user_fav_food"=>$_POST['user_fav_food'],
							"user_fb_post_allow" => $allow
							);
				
					parent::update($this->_tableName,$ins," user_id = '".$id."'");
					
					if(isset($_FILES['user_avatar']) && $_FILES['user_avatar']!='')	
						{
							if($_FILES['user_avatar']['name'] != '')
							{
								$ext = end(explode('/',$_FILES['user_avatar']['name']));
								$filename=$id.'_'.$_FILES['user_avatar']['name'];
								move_uploaded_file($_FILES['user_avatar']["tmp_name"],MAIN_DIR.UPLOAD.'/user/'.$filename);
								$insthumbimage = array(
										'user_avatar'=>$filename,
										);
							parent::update($this->_tableName,$insthumbimage,"user_id = '".$id."'");
							}
						}
					
					
					
					
					
					
					
					return('1');
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
		}
		
		public  function extenddate(){
	
				
			// Update Records in database starts
				if(($_POST['expire_date'] == '') ) 
				{
					return($_GET['msg'] ='required');
				}
				else
				{	
					$id = $_REQUEST['id'];
					
					$today = date("Y-m-d");
					$checkdate = date("Y-m-d", strtotime($_POST['expire_date'])); 
					
					if(strtotime($today) > strtotime($checkdate)){
						$ins = array(
							"user_status"=>'inactive',
						);	
					}else{
						$ins = array(
							"user_status"=>'active',
						);
					}	
					
				
					parent::update($this->_tableName,$ins," user_id = '".$id."'");

					$insPlan = array(
							"expire_date" => $checkdate,
							"trial_expire_date" => $checkdate,
							);
		
					parent::update($this->_tablePlan,$insPlan," user_id = '".$id."'");
	
					return('1');
				}
		}


		public function  userTabRegister(){
			
			
			if(($_POST['user_email'] == '' && $_POST['password'] == '' ) ) {
				return($_GET['msg'] ='required');
			}else{
				if($_POST['type'] == "company"){
					$user_type = 'company_user';
				}else{
					$user_type = 'employee';
				}
				
				
				
				$ins = array(
							"email" => $_POST['user_email'],
							"username" => $_POST['user_email'],
							"password" => base64_encode($_POST['password']),											
							"fname" =>$_POST['user_first_name'],
							"lname" => $_POST['user_last_name'],
							"city" => '',	
							"state" => '',	
							"address" => '',	
							"gender" => $_POST['user_gender'],	
							"user_type"=>$user_type,
							"user_status" => $_POST['user_status'],
							"user_comp_id"=>'0',	
							"register_date"=>date('Y-m-d h:i:s'),
				);
		
				$id = parent::insert($this->_tableName,$ins);
				
				$insPlan = array(
							"user_id" => $id,
							"sub_plan_id" => $_POST['plan_id'],
							"subscrib_date" => date('Y-m-d h:i:s'),											
							"expire_date" => '',
							"trial_expire_date" => '',
							"payment_status" => 'pending',	
							);
		
					$order_id = parent::insert($this->_tablePlan,$insPlan);
				
				return 1;
				
			}
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
		
		
		//get All
		public function getAll($condition = ''){
				
				$row = parent::select('*',$this->_tableName, $condition);				
				return($row);			
		}
		
		//  Get  Specific  Data 
		public function getData(){
				$id = $_REQUEST['id'];	
				$row = parent::select('*',$this->_tableName," AND user_id = '".$id."'");				
				return($row);			
		}
		
		public function changeAvatar(){
			$id = $_REQUEST['id'];	
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");	

			if($_FILES['user_avatar']['name'] != '')
			{
				$name = $_FILES['user_avatar']['name'];
           		$size = $_FILES['user_avatar']['size'];
				$ext = end(explode('.',$_FILES['user_avatar']['name']));
				if(in_array($ext,$valid_formats)){
//					if($size<(295*230)){
						$filename=$id.'_'.$_FILES['user_avatar']['name'];
						move_uploaded_file($_FILES['user_avatar']["tmp_name"],MAIN_DIR.UPLOAD.'/user/'.$filename);
						$_SESSION['user_avatar'] = $id.'_'.$_FILES['user_avatar']['name'];
						$insthumbimage = array(
								'user_avatar'=>$filename,
								);
					parent::update($this->_tableName,$insthumbimage,"user_id = '".$id."'");
	//				}else{
//						return 'filesizeBig';
//					}
				}else{
					return 'invalidFormat';
				}
			}
			
			return('1');
		}
		public  function changePassword(){
			
			
			
				$id = $_SESSION['user_id'];			
			// Update Records in database starts
				if(($_POST['confirm_password'] == '' && $_POST['new_password'] == '' ) ) 
				{
					return($_GET['msg'] ='required');
				}else if($_POST['confirm_password'] != $_POST['new_password'] ){
					return($_GET['msg'] ='notMatch');
				}
				else
				{	
						
					$condition = " AND user_id ='".$_SESSION['user_id']."' AND user_password='".base64_encode($_POST['current_password'])."' ";
					$id = parent::select(' * ',$this->_tableName,$condition);
										
					$numRows =  count($id);
					if($numRows > 0 ){
					$ins = array(
							"user_password"=>base64_encode($_POST['new_password']),
							);
												
					parent::update($this->_tableName,$ins," user_id = '".$_SESSION['user_id']."'");
					
					return('1');
					}else{
						return($_GET['msg'] ='invalid');
					}
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
		} 
		
		
		
		public  function AdminchangePassword(){
			
				$id = $_REQUEST['id'];			
			// Update Records in database starts
				if(($_POST['confirm_password'] == '' && $_POST['new_password'] == '' ) ) 
				{
					return($_GET['msg'] ='required');
				}else if($_POST['confirm_password'] != $_POST['new_password'] ){
					return($_GET['msg'] ='notMatch');
				}
				else
				{	
						
					//$condition = " AND user_id ='".$id."' AND user_password='".base64_encode($_POST['current_password'])."' ";
					
					$condition = " AND user_id ='".$id."'";
					
					$id = parent::select(' * ',$this->_tableName,$condition);
										
										
					$numRows =  count($id);
					if($numRows > 0 ){
					$ins = array(
							"user_password"=>base64_encode($_POST['new_password']),
							);
												
					parent::update($this->_tableName,$ins," user_id = '".$_REQUEST['id']."'");
					
					return('1');
					}else{
						return($_GET['msg'] ='passwordmismatch');
					}
					//$gnrl->redirectTo($label.'.php?msg=edit&script=edit&id='.$id);
				}
			// Update Records in database ends
		} 
		
		// Get Info Data
		//Get info by Field
		public function getInfoByfield($field='',$fieldvalue=''){
				
				$page_Data=  parent::select('*',$this->_tableName," AND ".$field."= '".$fieldvalue."'");
				return($page_Data);			
		}
		//  Deactive Recode
		public function deactiveRecode(){
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("user_status"=>'inactive',);
			parent::update($this->_tableName,$ins,"user_id = '".$id."'");

			$insplan = array("payment_status"=>'Pending',);
			//parent::update('tbl_user_subscrib_detail',$insplan,"user_id = '".$id."'");

			$childeUser = parent::select("user_id","tbl_user","AND user_comp_id=".$id);
			foreach($childeUser as $child){
				$insChi = array("user_status"=>'inactive',);
				parent::update($this->_tableName,$insChi,"user_id = '".$child['user_id']."'");
			}
			return  ('1');
		}
		// Active  Recode
		public  function  activeRecode(){
			
			$id = $_REQUEST['id'];			
			// Update Records in database starts
			$ins = array("user_status"=>'active',);
			parent::update($this->_tableName,$ins,"user_id = '".$id."'");
			$insplan = array("payment_status"=>'Complete',);
			//parent::update('tbl_user_subscrib_detail',$insplan,"user_id = '".$id."'");
			return  ('1');
		}
		//  Delete Recode
		public function  deleteRecode($idD = ''  ){
			if ($idD  !=  ''){
				$id  = $idD;
			}else{			
				$id = $_REQUEST['id'];		
			}
			$childeUser = parent::select("user_id","tbl_user","AND user_comp_id=".$id);
			foreach($childeUser as $child){
				parent::delete($this->_tableName," user_id = '".$child['user_id']."'");
			}
			parent::delete($this->_tableName," user_id = '".$id."'");
			
			return  ('1');	
		}
		
		
		public function getUserData(){
				$id = $_SESSION['user_id'];	
				$row = parent::select('*',$this->_tableName," AND user_id = '".$id."'");				
				return($row);			
		}
		
}
?>