<?php
class general extends database{

function redirectTo($redirect_url){
	@header("location: {$redirect_url}");
	echo "<script type=\"text/javascript\">location.href = \"{$redirect_url}\"</script>";
	die();
}
function dateDiff($dformat, $endDate, $beginDate){
	$date_parts1=explode($dformat, $beginDate);
	$date_parts2=explode($dformat, $endDate);
	$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
	$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
	return $end_date - $start_date;
}

function convertDateToDb($date,$sep){
	$explode = explode($sep,$date);
	$yy = $explode[2];
	$dd = $explode[0];
	$mm = $explode[1];
	
	$newdate = $yy.$sep.$mm.$sep.$dd;
	return $newdate;
}
function convertDbToFormat($date,$sep){
	$explode = explode($sep,$date);
	$yy = $explode[0];
	$mm = $explode[1];
	$dd = $explode[2];
	
	$newdate = $dd.$sep.$mm.$sep.$yy;
	return $newdate;
}



	

function createThumb($name,$filename,$new_w,$new_h,$path=""){

	$wh = getimagesize($path.$name);
	
	if($wh[0] < $new_w)
		$new_w = $wh[0];
		
	if($wh[1] < $new_h)
		$new_h = $wh[1];	

	$gd2=1;
	
	$system=explode(".",$name);

   	if(preg_match("/jpg|jpeg|JPG|JPEG/",$system[1])){
	
       $src_img=imagecreatefromjpeg($path.$name);
   	}

    if (preg_match("/gif|GIF/",$system[1])){
	
       $src_img=imagecreatefromgif($path.$name);
	}
	
	if (preg_match("/png|PNG/",$system[1])){
	
	   $src_img=imagecreatefrompng($path.$name);
	}
   	
	$old_x=imageSX($src_img);

   	$old_y=imageSY($src_img);

   	if ($old_x > $old_y){
	
       $thumb_w=$new_w;

       $thumb_h=$new_w*($old_y/$old_x);
   }

   if ($old_x < $old_y){

      $thumb_w=$new_h*($old_x/$old_y);

      $thumb_h=$new_h;
   }

   if ($old_x == $old_y){

       $thumb_w=$new_w;

       $thumb_h=$new_h;
    }

    if ($gd2==1){

       $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

       imagefill($dst_img,0,0,imagecolorallocate($dst_img,255,255,255));

       imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

   	}
	else{

       $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

       imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
   }

   if (preg_match("/gif|GIF/",$system[1])){

       imagegif($dst_img,$path.$filename);
	   
       chmod($path.$filename,0777);
   }
   else{
   
       imagejpeg($dst_img,$path.$filename);

       chmod($path.$filename,0777);
   }

   imagedestroy($dst_img);

   imagedestroy($src_img);

}

////////////////////// function to get the name field from the id field of a table ///////////////////////////////////

function getName($id,$idValue,$table,$name){
	
	$sqlSelect = "SELECT `".$name."`
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."'";		
	//echo $sqlSelect;			 
	$relSelect = mysql_query($sqlSelect);
	$nameValue = "";
	while ($row = mysql_fetch_array($relSelect)){		
		$nameValue = $row[$name];
	}
	
	if($nameValue)
		return $nameValue;
	else
		return '-- Root --';	
}
function getNameFindInSet($id,$idValue,$table,$name){
	
	$idValue =  str_replace(',',"','",$idValue);
	$sqlSelect = "SELECT `".$name."`
					 FROM `".$table."`
					 WHERE  `".$id."` IN ('".$idValue."')";		
	//echo $sqlSelect;			 
	$relSelect = mysql_query($sqlSelect);
	$nameValue = "";
	while ($row = mysql_fetch_array($relSelect)){		
		$nameValue[] = $row[$name];
	}
	
	if($nameValue)
		return implode(' , ',$nameValue);
	else
		return '-- Root --';	
}

function getNameLanguage($id,$idValue,$table,$name,$language_id){
	
	$sqlSelect = "SELECT `".$name."`
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."'
					 	AND i_language_id = '".$language_id."'";		
	//echo $sqlSelect;			 
	$relSelect = mysql_query($sqlSelect);
	$nameValue = "";
	while ($row = mysql_fetch_array($relSelect)){		
		$nameValue = $row[$name];
	}
	
	if($nameValue)
		return $nameValue;
	else
		return '-- Root --';	
}


function getNames_old($id,$idValue,$table,$name){
	
	$sqlSelect = "SELECT `".$name."`
   	 			 FROM `".$table."`
			 	 WHERE `".$id."` = '".$idValue."'
				 ";		
				 
	$relSelect = mysql_query($sqlSelect);
	$nameValue = array();
	while ($row = mysql_fetch_array($relSelect)){		
		$nameValue[] = $row[$name];
	}
	
	//if ($idValue == 0)
	//	$nameValue = "----";
	
	return $nameValue;
}


function getNames($id,$idValue,$table,$name){
	if($name == '*'){
		$sqlSelect = "SELECT *
   	 			 FROM `".$table."`
			 	 WHERE `".$id."` = '".$idValue."'
				 ";
		$relSelect = mysql_query($sqlSelect);
		$nameValue = array();
		while ($row = mysql_fetch_array($relSelect)){		
			$nameValue[] = $row;
		}		 
	}
	else{
		$sqlSelect = "SELECT `".$name."`
   	 			 FROM `".$table."`
			 	 WHERE `".$id."` = '".$idValue."'
				 ";	
		$relSelect = mysql_query($sqlSelect);
		$nameValue = array();
		while ($row = mysql_fetch_array($relSelect)){		
			$nameValue[] = $row[$name];
		}		 	
	}			 
	
	
	//if ($idValue == 0)
	//	$nameValue = "----";
	
	return $nameValue;
}


function getLocationTree_old($parentId = '0', $level='', $selected_value ){		
	
	$sqlCountry ="SELECT * 
			 FROM jp_location
			 WHERE `lc_cmbstatus` = 'active'
			 AND `lc_parentid` = '".$parentId."'
			 ";	

	$relCountry = mysql_query($sqlCountry);	

	while ( $rowCountry = mysql_fetch_array($relCountry)){				
		
		//$options[] = array("value" => $rowCountry['lc_locid'], "text" => $level.$rowCountry['lc_txtname']);
		
		if ($rowCountry['lc_parentid'] == 0)
			$level = "" ;
		
		$option_value = $rowCountry['lc_locid'];
		
		$option_text = $level.$rowCountry['lc_txtname'];				
		
		if($option_value==$selected_value)
			$selection = "selected='selected'";
		else
			$selection = "";
		
		echo "<option value=\"$option_value\" $selection>$option_text</option>\n";
		
		$sqlLocation ="SELECT *
			 FROM jp_location
			 WHERE `lc_cmbstatus` = 'active'
			 AND `lc_parentid` = '".$rowCountry['lc_locid']."'
			 ";

		$relLocation = mysql_query($sqlLocation);				
		
		$numLocation = mysql_num_rows($relLocation);
		
		if ($numLocation>0){
			$level .= '&nbsp;&nbsp;';
			$this->getLocationTree_old($rowCountry['lc_locid'],$level,$selected_value);
		}		
	
	}

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////// Check email ////////////////////////////////////////////

function checkEmail($email){	   

  // checks proper syntax
  if(!preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/", $email)){
  
    return false;
  }
  else{
  	return true;
  }
}



//////////////////////////////////Function for uploading images//////////////////////////////////////////
function uploadImage($file_name, $file_path, $table_name, $field_name, $primary_key, $insert_id, $action, $loop=""){
	
	global $dclass;	
	
	$sSql ="SELECT * from `".$table_name."` WHERE `".$primary_key."` = '".$insert_id."'";
			 $rs1 = $dclass->query($sSql);
			 while ( $row = $dclass->fetchArray($rs1) ){
			 	echo $file_org = $row[$field_name];
				if (strstr('?~~',$file_org))
				  $flname =	explode('?~~',$file_org);
				else
				  $flname[0] =$file_org;
			 }
	$files = "";
	
	if ($loop == "")
		$loop = 1;
	
	$filepath = $file_path;		
		
	$allowed = array('image/pjpeg','image/jpeg','image/gif','image/png');

if ($loop == 1){

	if(!isset($_FILES[$file_name]['name']) || $_FILES[$file_name]['name'] != ""){
	
	$filename = $_FILES[$file_name]['name'];

    if ( in_array($_FILES[$file_name]['type'],$allowed)){
		
         if ($_FILES[$file_name]['size'] <= 1000000){
		 						
                 $filename_db = $insert_id."_".$filename;
				 
                 $filedestination = $filepath.$filename_db;				 
				 				 
                 if (move_uploaded_file($_FILES[$file_name]['tmp_name'], $filedestination)){
				 							
							$files .= $filename_db;
					
				 }	
             }
         }
    }
}
else{	
for ($i=0; $i<$loop; $i++){
	
  //if(!isset($_FILES[$file_name]['name'][$i]) || $_FILES[$file_name]['name'][$i] != ""){
	if (isset($flname[$i]) && $flname[$i] !="" && $_FILES[$file_name]['name'][$i] == ""){
		if ($i == 0)
			$files .= $flname[$i];
		else							
			$files .= "?~~".$flname[$i];
	}
	if(!isset($_FILES[$file_name]['name'][$i]) || $_FILES[$file_name]['name'][$i] != ""){
	
	$filename = $_FILES[$file_name]['name'][$i];

    if ( in_array($_FILES[$file_name]['type'][$i],$allowed)){
		
         if ($_FILES[$file_name]['size'][$i] <= 1000000){
		 						
                 $filename_db = $insert_id."_".$filename;
				 
                 echo $filedestination = $filepath.$filename_db;				 
				 				 
                 if (move_uploaded_file($_FILES[$file_name]['tmp_name'][$i], $filedestination)){
				 		
						if ($i == 0)
							$files .= $filename_db;
						else
							$files .= "?~~".$filename_db;
						}	
                     }
               }
         }
	}
}						
						$dbUpdate[$field_name]=$files;
						$where = "`".$primary_key."` = '".$insert_id."'";
						$dclass->update($table_name,$dbUpdate,$where);

	  if ($action != "edit"){
	  
	      if(!isset($_FILES[$file_name]['name'][0]) || $_FILES[$file_name]['name'][0] != ""){
			 
              $where = " `".primary_key."` = '".$insert_id."' ";

	          $dclass->delete($table_name,$where);
		 
      	  }
	  }
  //}

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function thumb($name,$filename,$new_w,$new_h,$path=""){
	
	$wh = getimagesize($path.$name);
	if($wh[0] < $new_w)
		$new_w = $wh[0];
		
	if($wh[1] < $new_h)
		$new_h = $wh[1];	

	$gd2=1;
	
	//echo "</br>".$name;
	//echo "</br>".$filename;
	//echo "</br>".$new_w;
	//echo "</br>".$new_h;
	//exit;

	$system=explode(".",$name);
	if(count($system)>2){
		$system1=$system[count($system)-1];
	}else{
		$system1=$system[1];
	}
	
   	if(preg_match("/jpg|jpeg|JPG|JPEG/",$system1))
    	$src_img=imagecreatefromjpeg($path.$name);

	if (preg_match("/gif|GIF/",$system1))
       	$src_img=imagecreatefromgif($path.$name);

	if (preg_match("/png|PNG/",$system1))	
	   	$src_img=imagecreatefrompng($path.$name);
	
   	$old_x=imagesx($src_img);

   	$old_y=imagesy($src_img);

   	if ($old_x > $old_y){
	
       $thumb_w=$new_w;

       $thumb_h=$new_w*($old_y/$old_x);
   	}
	
   	if ($old_x < $old_y){
	
    	$thumb_w=$new_h*($old_x/$old_y);

       	$thumb_h=$new_h;

   	}

   	if ($old_x == $old_y){

    	$thumb_w=$new_w;

       	$thumb_h=$new_h;
    }

    if ($gd2==1){

    	$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);

		imagefill($dst_img,0,0,imagecolorallocate($dst_img,255,255,255));
     
		imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

   	}
	else{

       	$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
     
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
        
   }


   	if (preg_match("/gif|GIF/",$system1)){
   
    	imagegif($dst_img,$path.$filename);
       	chmod($path.$filename,0777);
   	}
   	else{

       	imagejpeg($dst_img,$path.$filename);

       	chmod($path.$filename,0777);
   	}

	imagedestroy($dst_img);

   	imagedestroy($src_img);

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function getSettings($field){
	global $dclass;
	$row = $dclass->select("*","tbl_sys_info");
	return $row[0]['sys_info_name'];
}

function get_payment_settings($payment_id,$field){
	global $dclass;
	$row = $dclass->fetchArray($dclass->query("SELECT l_value FROM tbl_payment_settings WHERE i_payment_id = '".$payment_id."' AND v_option = '".$field."'"));
	return $row['l_value'];
}

function email_mime($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format=""){
	$smtp_yes="";
    // Instantiate a new HTML Mime Mail object
    $mail = new htmlMimeMail();		
	if($smtp_yes){
		
	}
	//$mail->setSMTPParams('fusion-sr2', 25, 'example.com');
	//$mail->setReturnPath('test@fusion.in');


    // Set the sender address
    $mail->setFrom($email_from);
    
    // Set the reply-to address
    $mail->setReturnPath($email_from);
  
    // Set the mail subject
    $mail->setSubject($email_subject);
	
	if ($email_bcc != "")
		$mail->setBcc($email_cc);
	
	if ($email_cc != "")
		$mail->setCc($email_cc);

    // Set the mail body text
	$email_message = str_replace("\n","",$email_message);
	$email_message = str_replace("\r","",$email_message);
	
	if ($email_format == "html"){	
		//$content_type = "text/html";		
		$email_message = str_replace("{site_logo}","<img src='".$this->site_path."/images/logo.jpg' />",$email_message);
		$mail->setHTML($email_message);
	}
	else{
		//$content_type = "text/plain";
		$mail->setText($email_message);
	}
    
	if (is_array($email_to)){
		// Send the email!
	    $mail->send($email_to,'smtp');	
	}
	else{
		// Send the email!
	    $mail->send(array($email_to),'smtp');
	}
	//echo "Error:";
	//print_r($mail->errors);
	//exit;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function email($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format=""){
	
	if($email_format=='html'){
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	}
	if($email_from!=''){$headers .= 'From: '.$email_from. "\r\n";}
	if($email_cc!=''){$headers .= 'Cc: '.$email_cc. "\r\n";}
	if($email_bcc!=''){$headers .= 'Bcc: '.$email_bcc. "\r\n";}
	$email_message = str_replace("{site_logo}","<img src='".$this->site_path."/images/logo.jpg' />",$email_message);
	$email_message = str_replace("{site_url}",$this->site_path,$email_message);
	$email_message = str_replace("\n","",$email_message);
	$email_message = str_replace("\r","",$email_message);
	$email_message = stripslashes($email_message);
	//echo $email_message; die();
	mail($email_to, $email_subject, $email_message, $headers);
}

function Sendemail($email_to,$cen_no){
	
	$email_subject = 'Equirey Detail';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	$headers .= 'From:admin@gmail.com \r\n';
	
	$html ='<table border="0" cellpadding="0" cellspacing="10" style="width:698px; font-family:calibri,verdana,geneva,sans-serif; font-size: 13px;" >
	<tbody>
		<tr>
			<td colspan="2">
				<img alt="" src="'.SITE_URL.'inc/image/logo.jpg" /></td>
		</tr>
		<tr>
			<td colspan="2" >
				Dear Employee,<br />
               	there is one enquirey for you with CEN NO '.$cen_no.', please login for proceeds<br/>
				<a href="'.SITE_URL.'/admin">Click here</a>
			</td>
		</tr>';
		
		$html .=' <tr>
			<td bgcolor="#2192DO" colspan="2" style=" text-align:center; height:47px; color:#fff; font-size:13px; font-family:calibri,verdana,geneva,sans-serif;">
				Copyright &copy; 2013 examplde.com. All rights reserved.</td>
		</tr>
	</tbody>
</table>';
	
	$html = str_replace("\n","",$html);
	$html = str_replace("\r","",$html);
	$html = stripslashes($html);
	mail($email_to, $email_subject, $html, $headers);
}

function randompassword($length=32,$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'){
                             $chars_length = (strlen($chars) - 1);
                      		$string = $chars{rand(0, $chars_length)};
							for ($i = 1; $i < $length; $i = strlen($string))
							{
								$r = $chars{rand(0, $chars_length)};
								if ($r != $string{$i - 1}) $string .=  $r;

							}
							return $string;
			}

function printdate($date)
{
	$date1=explode("-",$date);
	$year=$date1[0];
	//echo $year."<br>";
	$month=$date1[1];
	$day=$date1[2];
	$day1=explode(":",$day);
	$aday=$day1[0];
	$aday=substr($aday,0,3);
	$hr=$day1[0];
	$month_array = array("Jan","Feb","Mar","Apr","May","June","Jul","Aug","Sep","Oct","Nov","Dec");
	$month = $month_array[$month-1];
	//$hr=substr($hr,3,5);
	//$min=$day1[1];
	//echo $month."<br>";
	//echo $hr."<br>";
	//print_r($day1);
	//$newdate=$month."-".$aday."-".$year."(".$hr.":".$min.")";
	$newdate=$month."-".$aday."-".$year;
	return $newdate;
}

function getLocationTree1($parentId = '0', $level='', $selected_value ){		
	
	$sqlCountry ="SELECT tblcategory.intid as cntid,tblcategory .*
				 FROM tblcategory
				 WHERE tblcategory.intparantlocation = '".$parentId."'
				 ";	
	//echo $sqlCountry;
	$relCountry = mysql_query($sqlCountry);	

	while ( $rowCountry = mysql_fetch_array($relCountry)){				
		
		//$options[] = array("value" => $rowCountry['lc_locid'], "text" => $level.$rowCountry['lc_txtname']);
		
		if ($rowCountry['intparantlocation'] == 0)
			$level = "" ;
		
		$option_value = $rowCountry['cntid'];
		
		$option_text = $level.$rowCountry['varcatname'];				
		
		if($option_value==$selected_value)
			$selection = "selected='selected'";
		else
			$selection = "";
		
		echo "<option value=\"$option_value\" $selection>$option_text</option>\n";
		
		$sqlLocation ="SELECT tblcategory.intid as cntid,tblcategory.*
				 FROM tblcategory
				 WHERE tblcategory.intparantlocation = '".$rowCountry['cntid']."'";

		$relLocation = mysql_query($sqlLocation);				
		
		$numLocation = mysql_num_rows($relLocation);
		
		if ($numLocation>0){
			$level .= '- -';
			$this->getLocationTree1($rowCountry['cntid'],$level,$selected_value);
		}		
	
	}

}

function getLocationTree2($parentId = '0', $level='', $selected_value ){		
	
	$sqlCountry ="SELECT tblcategory.intid as cntid,tblcategory .*
				 FROM tblcategory
				 WHERE tblcategory.intparantlocation = '".$parentId."'
				 ";	
	//echo $sqlCountry;
	$relCountry = mysql_query($sqlCountry);	
	
	while ( $rowCountry = mysql_fetch_array($relCountry)){				
		
		//$options[] = array("value" => $rowCountry['lc_locid'], "text" => $level.$rowCountry['lc_txtname']);
		
		if ($rowCountry['intparantlocation'] == 0)
			$level = "" ;
		
		$option_value = $rowCountry['cntid'];
		
		$option_text = $level.$rowCountry['varcatname'];				
		
		if(is_array($selected_value)){
			if($option_value==$selected_value[$v])
				$selection = "selected='selected'";
			else
				$selection = "";
		}else if($option_value==$selected_value){
			$selection = "selected='selected'";
		}
		?>
		<option value="<?php echo $option_value;?>" 
		<?php 
		for($q=0; $q<count($selected_value); $q++){	
			if($option_value==$selected_value[$q]){echo "selected";}
		}	
		?>><?php echo $option_text;?></option>
		<?php
		//echo "<option value=\"$option_value\" $selection>$option_text</option>\n";
		
		$sqlLocation ="SELECT tblcategory.intid as cntid,tblcategory.*
				 FROM tblcategory
				 WHERE tblcategory.intparantlocation = '".$rowCountry['cntid']."'";

		$relLocation = mysql_query($sqlLocation);				
		
		$numLocation = mysql_num_rows($relLocation);
		
		if ($numLocation>0){
			$level .= '- -';
			$this->getLocationTree2($rowCountry['cntid'],$level,$selected_value);
		}		
	
	}

}

function getLocationTree3($parentId = '0', $level='', $selected_value ){		
	
	$sqlCountry ="SELECT tblcategory.intid as cntid,tblcategory .*
				 FROM tblcategory
				 WHERE tblcategory.intparantlocation = '".$parentId."'
				 ";	
	//echo $sqlCountry;
	$relCountry = mysql_query($sqlCountry);	
	$i=1;
	while ( $rowCountry = mysql_fetch_array($relCountry)){				
		
		//$options[] = array("value" => $rowCountry['lc_locid'], "text" => $level.$rowCountry['lc_txtname']);
		
		if ($rowCountry['intparantlocation'] == 0)
			$level = "" ;
		
		$option_value = $rowCountry['cntid'];
		
		$option_text = $level.$rowCountry['varcatname'];				
		
		if($option_value==$selected_value)
			$selection = "selected='selected'";
		else
			$selection = "";
		
		echo "<a href='products.html?category_id=".$option_value."' class='normallinktxt'>$option_text</a><br />";
		
		$sqlLocation ="SELECT tblcategory.intid as cntid,tblcategory.*
				 FROM tblcategory
				 WHERE tblcategory.intparantlocation = '".$rowCountry['cntid']."'";

		$relLocation = mysql_query($sqlLocation);				
		
		$numLocation = mysql_num_rows($relLocation);
		
		if ($numLocation>0){
			$level .= '&raquo;&nbsp;';
			$this->getLocationTree3($rowCountry['cntid'],$level,$selected_value);
			
		}		
	$i++;
	}

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getCombo($id,$idValue,$table,$name,$selctval)
{
	if(isset($idValue) && $idValue!="")
	{
		$sqlSelect = "SELECT *
   	 			 FROM `".$table."`
			 	 WHERE `".$id."` = '".$idValue."'
				 ";			
	}
	else
	{
		$sqlSelect = "SELECT *
   	 			 FROM `".$table."`
				 ";		
	}
	 
	$relSelect = mysql_query($sqlSelect);
	$option = "";
	//echo $selctval;
	while ($row = mysql_fetch_array($relSelect))
	{
		if($row[$id] == $selctval)
		{
			$select = "selected = 'selected'";
			$option .= '<option value="'.$row[$id].'" '.$select.'>'.$row[$name].'</option>';
		}
		else
		{
			$option .= '<option value="'.$row[$id].'">'.$row[$name].'</option>';
		}
	}
	echo $option;
	//return $option;
}

function imgWatermark($img_src,$watermark_src){	
		header('content-type: image/jpeg');
		$path = "";
		$imagesource =  $path.$img_src;
		$watermarkPath = $watermark_src;
		$filetype = substr($imagesource,strlen($imagesource)-4,4);
		$filetype = strtolower($filetype);
		$watermarkType = substr($watermarkPath,strlen($watermarkPath)-4,4);
		$watermarkType = strtolower($watermarkType);
		
		if($filetype == ".gif")  
			$image = @imagecreatefromgif($imagesource);
		else  
			if($filetype == ".jpg" || $filetype == "jpeg")  
				$image = @imagecreatefromjpeg($imagesource);
			else
				if($filetype == ".png")  
					$image = @imagecreatefrompng($imagesource);
				else
					die();  
		
		if(!$image)
			die();
		
		if($watermarkType == ".gif")
			$watermark = @imagecreatefromgif($watermarkPath);
		else
			if($watermarkType == ".png")
				$watermark = @imagecreatefrompng($watermarkPath);
			else
				die();
			
		if(!$watermark)
			die();
			
		$imagewidth = imagesx($image);
		$imageheight = imagesy($image);  
		$watermarkwidth =  imagesx($watermark);
		$watermarkheight =  imagesy($watermark);
		$startwidth = (($imagewidth - $watermarkwidth)/2);
		$startheight = (($imageheight - $watermarkheight)/2);
		imagecopy($image, $watermark,  $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
		imagejpeg($image);
		imagedestroy($image);
		imagedestroy($watermark);
	}
	
	
function subString($string,$length){
		$string = strip_tags($string);
		if(strlen($string) > $length){
			$string1 = substr($string,0,$length)."...";
		}
		else{
			$string1 = $string; 
		}
		return $string1;
	}
	
function correctURL($url){
	$test_arr=split("//",$url);
	if($test_arr[0]=="http:" || $test_arr[0]=="https:")
		return $url;
	else
		return "http://".$url;
}

///////////////////////////////////////////////////////////////////////////////


////////////// funtion to fetch to content of the given url ////////////////////////////

function fetchURL($address){
	$host = $address;
	$contents = '';
	$handle = @fopen($host, "rb");
	if($handle){
		while (!@feof($handle)) {
		  $contents .= @fread($handle, 8192);
		}
		@fclose($handle);   
	}
	//print_r($contents);
	return $contents;
}

function disableRightclick(){
	echo '<script language="javascript" type="text/javascript" src="js/disablerightkey.js"></script>';
}

function removeChars($string){
	$arra = array(" ","@","#","?","&","&amp;");
	for($i=0;$i<count($arra);$i++){
		if(strstr($string,$arra[$i])){
			$string = str_replace($arra[$i],"",$string);
		}
	}
	return $string;
}

function upload_image($file_name,$apend_id,$dest,$thumb_path,$gen_th_flag,$height,$width){
	if($file_name != "" && $dest != ""){
		if(isset($_FILES[$file_name]['name']) && $_FILES[$file_name]['name'] != ""){
			$file_src = $_FILES[$file_name]['tmp_name'];
			$file_name_db = $apend_id.$this->removeChars($_FILES[$file_name]['name']);
			$destination = $dest.$apend_id.$this->removeChars($_FILES[$file_name]['name']);
			move_uploaded_file($file_src,$destination) or die('There is an error in upload');
			if($gen_th_flag == 1){
				$thumb_path = $thumb_path.$file_name_db;
				$this->thumb($destination,$thumb_path,$height,$width,$path="");
			}
			return $file_name_db;
		}
	}
}

function back(){
	return "<a href='javascript:history.go(-1);'>Back</a>";
}



function getLocationTree($parentId = '0', $level='', $selected_value, $old_pid ){
	$sqlCountry1 ="SELECT * 
			 FROM tblcategories
			 WHERE  `parent_id` = '".$parentId."'
			 ORDER BY intid ASC
			 ";	
	//echo $sqlCountry
	$relCountry1 = mysql_query($sqlCountry1);

	while ( $rowCountry1 = mysql_fetch_array($relCountry1)){						
		//$options[] = array("value" => $rowCountry['lc_locid'], "text" => $level.$rowCountry['lc_txtname']);
		//echo "old_pid  ".$old_pid."<br>";	
		//echo "rowCountry['intparantlocation']  ".$rowCountry['intparantlocation']."<br>";
		
		if ($rowCountry1['parent_id']  == $old_pid && $rowCountry1['parent_id'] != 0){
			//echo "levelbefore".$level;
			$level = substr($level,0,strlen($level)-2);
			//echo "level".$level;
			$old_pid = $rowCountry1['parent_id'];
		}
		if ($rowCountry1['parent_id'] == 0)
			$level = "" ;
		$option_value = $rowCountry1['intid'];
		$option_text = $level.$rowCountry1['name'];
		
		if($option_value==$selected_value)
			$selection = "selected='selected'";
		else
			$selection = "";		
		
		echo "<option value=\"$option_value\" $selection>$option_text</option>\n";
		$sqlLocation1 ="SELECT *
			 FROM tblcategories
			 WHERE `parent_id` = '".$rowCountry1['intid']."'
			 ORDER BY intid ASC
			 ";
		$relLocation1 = mysql_query($sqlLocation1);				
		$numLocation1 = mysql_num_rows($relLocation1);
		//echo $option_text.$old_pid."=>".$rowCountry1['intparantlocation']."<br>";
		if (($old_pid <= $rowCountry1['parent_id']) || $old_pid == 0){
				$level .= '- ';
				$old_pid = $rowCountry1['parent_id'];
			}
		if ($numLocation1>0){		
			$this->getLocationTree($rowCountry1['intid'],$level,$selected_value,$rowCountry1['parent_id']);
		}		
	
	}

}

function getCats($parentId = '0', $level='', $selected_value, $old_pid){
	$sqlCountry1 ="SELECT * 
			 FROM tblcategories
			 WHERE  `parent_id` = '".$parentId."'
			 ORDER BY intid ASC
			 ";	
	//echo $sqlCountry
	$relCountry1 = mysql_query($sqlCountry1);
	$templevel = $level;
	$level++;
	
	echo $level." - ".$templevel.'<br />';
	while ( $rowCountry1 = mysql_fetch_array($relCountry1)){
		if($level>$templevel){
			//echo "<br />" .$rowCountry1['intid'] ;
		}						
		$s = '';
		$str.='<li class="subcat"><a href="#">'.$rowCountry1['name'].'</a>';	
		$str.='</li>';
		$this->getCats($rowCountry1['intid'],$level,$selected_value,$rowCountry1['parent_id']);		
	}	
	
	return $str;
	
}

function getBanner(){
	global $dclass;
	$q = $dclass->select("*","tblbanners"," AND status='active' ORDER BY RAND() LIMIT 0,1");
	return $q[0];
}

function makefilename($file_name){
	$file_name = str_replace("&","",$file_name);
	$file_name = str_replace("?","",$file_name);
	$file_name = str_replace("/","",$file_name);
	$file_name = str_replace(">","",$file_name);
	$file_name = str_replace("<","",$file_name);
	$file_name = str_replace("&","",$file_name);
	$file_name = str_replace("#","",$file_name);
	$file_name = str_replace(" ","",$file_name);
	$file_name = stripslashes($file_name);
	return $file_name;
}

function mailToUsers($winning_prize,$cylinder){
	global $dclass;
	$email_from = $this->getSettings("varsenderemial");
	$memid = $_SESSION['memberid'];
	$mem_details = $dclass->select("current_safe_id,current_cylinder_id,varfname,varlname","tblmembers"," AND intid='$memid'");
	
	$send_mems = $dclass->select("varusername,varemail","tblmembers"," AND intid != '$memid' AND current_safe_id='".$mem_details[0]['current_safe_id']."' AND current_cylinder_id='".$mem_details[0]['current_cylinder_id']."'");
	
	$safe_name = $this->getName("intid",$mem_details[0]['current_safe_id'],"tblsafe","safe_name");
	
	$email_subject = $mem_details[0]['varfname']." has won - Safecracker";
	if(count($send_mems)>0){
		for($i=0;$i<count($send_mems);$i++){
			$email_to = $send_mems[$i]['varemail'];
			$email_message = "hi,<br />".$send_mems[$i]['varusername']."<br />";
			$email_message.= "Safe $safe_name is opened<br />";
			$email_message.= $mem_details[0]['varfname']." has won $winning_prize &pound;<br />";
			$email_message.= "<br />
			Regards,
			Safecracker
			";
			$this->email($email_from, $email_to, "", "", $email_subject, $email_message, "html");
		}
	}
}

function checkLogin(){
if(isset($_SESSION['adminid'])){
	if($_SESSION['adminid'] == '' || $_SESSION['adminid'] == NULL){		
		return 0;
	}else{
			/*$now = time(); // checking the time now when home page starts
		    if($now > $_SESSION['expire'])
            {
				
                session_destroy();
               return 0;
			   //echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
            }
            else
            {
                return 1;
				//echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
            }*/
		return 1;
	}
}else{
	return 0;
}
}





function checkLoginExpire(){
	global $dclass;
						$expire =  $dclass->select(" * ",'tbl_setting'," AND v_name='SESSION_EXPIRE_LIMIT_IN_MINUTE'");
						if(count($expire) > 0){
							$ex_time = $expire[0]['l_values'];
						}else{
							$ex_time = '30';
						}
						// set session for login expire
						$_SESSION['start'] = time(); // taking now logged in time
						$_SESSION['expire'] = $_SESSION['start'] + (60 * $ex_time) ; 
						return 1;

}

function checkReminderRun(){
	global $dclass;
		

		if(isset($_SESSION['cron_expire']) && strtotime($_SESSION['cron_expire']) < time() ){
			return 1;
		}else{
			$ex_time = '5';
				
			$_SESSION['cron_start'] = time(); 
			$_SESSION['cron_expire'] = $_SESSION['cron_start'] + (60 * $ex_time) ; 
			return 0;
		}

}

function checkUserAccess($chk){
global $dclass;

	if($_SESSION['user_type'] != 'company_admin'){
		$menuAccess = $dclass->select("*","tbl_role_access"," AND r_id = '".$_SESSION['r_id']."' AND company_user_id = '".$_SESSION['company_id']."' AND v_name ='".$chk."'");
		
		if(count($menuAccess) > 0 && $menuAccess[0]['l_value'] == 'yes'){
				return 1;
		}else{
				return 0;
		}
	}else{
		return 1;
	}

}

function gettotalcompanyuser(){
  global $dclass;

  $getplan = $dclass->Select("COUNT(*) as total","tbl_user"," AND user_comp_id = '".$_SESSION['company_id']."' OR user_id = '".$_SESSION['company_id']."' "); 
  
  return $getplan[0]['total'];
}
function checkmaxallowuser(){
  global $dclass;
  $getplan = $dclass->Select("t1.sub_available_user","tbl_subscription_plan t1 LEFT JOIN tbl_user_subscrib_detail t2 ON t1.sub_id = t2.sub_plan_id"," AND t2.user_id = ".$_SESSION['company_id']." AND payment_status='Completed' AND current='1' ORDER BY id desc limit 0,1"); 
  if(count($getplan) > 0){
  	return $getplan[0]['sub_available_user'];
  }else{
  	$getlowplan = $dclass->Select("sub_available_user","tbl_subscription_plan"," ORDER BY sub_price asc limit 0,1"); 	
  	return $getlowplan[0]['sub_available_user'];
  }
}

function gettotalcompanyproject(){
  global $dclass;

  $getplan = $dclass->Select("COUNT(*) as total","tbl_project"," AND pr_company_id = '".$_SESSION['company_id']."'  "); 
  
  return $getplan[0]['total'];
}
function checkmaxallowproject(){
  global $dclass;

  /*$getplan = $dclass->Select("t1.sub_available_project","tbl_subscription_plan t1 LEFT JOIN tbl_user_subscrib_detail t2 ON t1.sub_id = t2.sub_plan_id"," AND t2.user_id = ".$_SESSION['company_id']." AND payment_status='Completed' ORDER BY id desc limit 0,1");*/ 

  $getplan = $dclass->select("t1.*,t2.sub_title,t2.sub_price,t2.sub_available_project","tbl_user_subscrib_detail t1 LEFT JOIN tbl_subscription_plan t2 ON t1.sub_plan_id = t2.sub_id"," AND t1.user_id=".$_SESSION['company_id']." AND (payment_status='Completed' AND current=1) order by t1.id desc limit 0,1");

  if(count($getplan) > 0){
  	return $getplan[0]['sub_available_project'];	
  }else{
  	$getlowplan = $dclass->Select("sub_available_project","tbl_subscription_plan"," ORDER BY sub_price asc limit 0,1"); 	
  	return $getlowplan[0]['sub_available_project'];
  }
  

}










function checkGroupAccess($chk){
global $dclass;
$menuAccess = $dclass->select("*","tbl_system_group"," AND system_group_member LIKE '%".$_SESSION['adminid']."%' AND system_group_status ='active' ");
$grp_retrive = explode(':::',$menuAccess[0]['system_group_access']);



	if(in_array($chk,$grp_retrive)){
		return 1;
	}else{
		return 0;
	}

}











function checkUserLogin(){
global $dclass;	
if(isset($_SESSION['user_id'])){
	if($_SESSION['user_id'] == '' || $_SESSION['user_id'] == NULL){		
		return 0;
	}else{
		global $dclass;
		$chkexist = $dclass->select("username","tbl_user"," AND user_id=".$_SESSION['user_id']);
		if(count($chkexist) > 0){
			return 1;	
		}else{
			return 0;
		}
		
	}
}else{
	return 0;
}
}

function getStatus($status_arr,$selval){
	$str = '';
	for($i=0;$i<count($status_arr);$i++){
		if($selval==$status_arr[$i]){$sel = 'selected="selected"';}
		else{$sel = '';}
		$str.= '<option value="'.$status_arr[$i].'" '.$sel.'>'.ucwords($status_arr[$i]).'</option>';
	}
	echo $str;
}

function getLangLat($address){
	$google_address = urlencode($address);
	$geocode1 = "http://maps.google.com/maps/geo?q=$google_address&output=csv&key=".GOOGLE_API_KEY;
	$handle = @fopen($geocode1, "r");
	$contents = '';			
	if ( $handle != "" ){
		while (!feof($handle) ) {
		  $contents .= fread($handle, 8192);
	
		}
		fclose($handle);
	}	
	$coord_array = explode(",",$contents);		
	$latlog[0] = $coord_array[2];
	$latlog[1] = $coord_array[3];
	return $latlog;
}


function ZipcodeInRadius($zipcode, $distance) {
	global $dclass;
	$state_concat = 1;
	$rs_city = $dclass->query("select latitude,longitude from tblzipcodes where zipcode='".$zipcode."'");
	$Data=@mysql_fetch_object($rs_city);
	
	$latitude1=$Data->latitude;
	$longitude1=$Data->longitude;

//================================= SELECT ID OF CURRENT CITY============================
			
	$City_id_list='';			
	$rs_allcity = $dclass->query("select zipcode,latitude,longitude,state from tblzipcodes where zipcode!='".$zipcode."'");
	while($Data3=@mysql_fetch_object($rs_allcity)){
		$latitude2=$Data3->latitude;
		$longitude2=$Data3->longitude;
		$City_dis =round($this->distance($latitude1, $longitude1, $latitude2, $longitude2, 'M')); 
			
//====================================== SELECT CITY CODE LIST===========================
		if($City_dis<=$distance){
			if($Data3->zipcode){
				if(!$state_concat){$City_id_list.=$Data3->zipcode.',';}				
				else{$City_id_list.="'".$Data3->state." ".$Data3->zipcode."',";}
			}	
		}			
	}
					
	$City_id_list=substr($City_id_list, 0, -1);  			
	if($City_id_list=='')
		$City_id_list='-1';
							
	return $City_id_list;
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
	
	if ($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
}

function getSortingArrow($field){
	$str = "";
	$sb = $_REQUEST['sb'];
	$st = $_REQUEST['st'];
	if($field == $sb){
		if($st == 0){
			$str = '<img src="images/up.png" alt="" />';
		}else if($st == 1){
			$str = '<img src="images/down.png" alt="" />';
		}
	}
	return $str;
}

function getRating($id){
	global $dclass;
	$r = $dclass->fetchArray($dclass->query("SELECT ROUND(AVG(rate_weight)) AS rate FROM tblcomments WHERE article_id='$id' AND type='rate' AND status='approved'"));
	$rate_str = "";
	if($r['rate']>0){
		$rate = $dclass->fetchArray($dclass->query("select * from tblratingtype where intid = '".$r['rate']."'"));
		//_p($rate);
		if($rate['type']){
			$rate_str = '<span class="fl"><img alt="" src="images/'.$rate['icon'].'"></span><span class="fl fontlucida font10">'.$rate['type'].' Experience</span>';
		}
	}
	return $rate_str;
}

function getRateTypes($id){
	global $dclass;
	$str = "";
	$rate_q = $dclass->query("select * from tblratingtype");		
	while($rate = $dclass->fetchArray($rate_q)){
		//$str.='<span class="fl"> <img src="images/'.$rate['icon'].'" alt=""></span> <a href="article.php?rate_weight='.base64_encode($rate['intid']).'&amp;rate='.base64_encode($rate['type']).'&amp;id='.$id.'" class="fl"><strong>'.$rate['type'].' Experience</strong><br/>'.$rate['title'].'.</a><div class="clear"></div>';
		
		$str.='<span class="fl"> <img src="images/'.$rate['icon'].'" alt=""></span> <a href="javascript:void(0);" class="fl" onclick="rateArticle('.$id.',\''.base64_encode($rate['intid']).'\')"><strong>'.$rate['type'].' Experience</strong><br/>'.$rate['title'].'.</a><div class="clear"></div>';
	}		
	return $str;
}

function checkMemLogin(){
	if(isset($_SESSION['memberid'])){
		return true;
	}else{
		return false;
	}
}

function logMemActivity($activity,$page_url){
	global $dclass;
	$ins['activity'] = $activity;
	$ins['member_id'] = $_SESSION['memberid'];
	$ins['page_url'] = $page_url;
	$ins['dtadd'] = date('Y-m-d H:i:s');
	$ins['ip'] = $_SERVER['REMOTE_ADDR'];
	
	$dclass->insert("tblactivity",$ins);
	
}


function getParentId($id){
	global $dclass;
	$q = $dclass->query("SELECT parent_id,intid FROM tblcategories WHERE intid='$id'");
	//$lastid = $id;
	if(mysql_num_rows($q)>0){
		$d = $dclass->fetchArray($q);
		//_p($d);
		$this->getParentId($d['parent_id']);
		
		if($d['parent_id']==0){
			$lastid = $d['intid'];
			$_SESSION['last_parent_id'] = $lastid;
		}	
	}

}


function processContent($content){
	if($content!=''){
		preg_match_all("~href=\"(.*?)\"~i", $content, $urls);
		$all = $urls[1];
		if(is_array($all)){
			for($i=0;$i<count($all);$i++){
				$object = strtolower($all[$i]);
				if(strstr($object,".jpg") || strstr($object,".png") || strstr($object,".gif")){
					$image = "<img src=".$all[$i].">";
					$content = str_replace('href="'.$all[$i].'"',"href='".$all[$i]."' onmouseover=\"showTooltip(event,'$image')\" onmouseout=''",$content);
				}
				else if(strstr($object,"youtube.com")){
					$vidurl = "http://www.youtube.com/v/".$this->getYouTubeURL($all[$i]);
					$emb =  addslashes("<object width='440' height='285'><param name='movie' value='".$vidurl."?fs=1&amp;hl=en_US&amp;autoplay=1'></param><param name='allowFullScreen' value='true'><param name='autoplay' value='1'></param><param name='allowscriptaccess' value='always'></param><embed src='".$vidurl."?fs=1&amp;hl=en_US&amp;autoplay=1' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' autoplay='1' width='440' height='285'></embed></object>");
					
					$content = str_replace('href="'.$all[$i].'"','href="#" onmouseout="hideTooltip()" onmouseover="showTooltip(event,\''.$emb.'\');"',$content);
					
				}
			}
		}
		
		return $content;
	}
}

function getYouTubeURL($string) {
	$splitString =	$string;
	$splitString = explode("=",$splitString);
	$videoID = $splitString[1];
	return $videoID;
}




	/*
	*	Added by Roger on 06-01
	*	Forum Category parent-child Combo
	*/
	function getParentChildCombo($tbl,$tbl2,$id,$name,$parentid,$parentIdVal = '0', $level='', $selected_value, $old_pid,$statusfield='e_status',$statusval='active',$condition=''){
		
		$foreign = substr($tbl,4);
		
		$sql1 = "SELECT t1.*, t2.".$name."
					 FROM ".$tbl." t1
					 LEFT JOIN ".$tbl2." t2
					 	ON t1.".$id." = t2.i_".$foreign."_id
					 WHERE  t1.".$parentid." = '".$parentIdVal."'
					 	AND t1.".$statusfield." = '".$statusval."'
						".$condition."
					 GROUP BY t2.i_".$foreign."_id
					 ORDER BY t1.".$id." ASC";	
		//echo $sql;
		$rel1 = mysql_query($sql1);
	
		while ( $row1 = mysql_fetch_array($rel1)){						
			//$options[] = array("value" => $rowCountry['lc_locid'], "text" => $level.$rowCountry['lc_txtname']);
			//echo "old_pid  ".$old_pid."<br>";	
			//echo "rowCountry['intparantlocation']  ".$rowCountry['intparantlocation']."<br>";
			
			if ($row1[$parentid]  == $old_pid && $row1[$parentid] != 0){
				//echo "levelbefore".$level;
				$level = substr($level,0,strlen($level)-2);
				//echo "level".$level;
				$old_pid = $row1[$parentid];
			}
			if ($row1[$parentid] == 0)
				$level = "" ;
			$option_value = $row1[$id];
			$option_text = $level.$row1[$name];
			
			if($option_value==$selected_value)
				$selection = "selected='selected'";
			else
				$selection = "";		
			
			echo "<option value=\"$option_value\" $selection>$option_text</option>\n";
			
			$sql2 = "SELECT t1.*, t2.v_title
						 FROM ".$tbl." t1
						 LEFT JOIN ".$tbl2." t2
					 		ON t1.".$id." = t2.i_".$foreign."_id
						 WHERE t1.".$parentid." = '".$row1[$id]."'
					 		AND t1.".$statusfield." = '".$statusval."'
							".$condition."
						 GROUP BY i_".$foreign."_id
						 ORDER BY t1.".$id." ASC";
			$rel2 = mysql_query($sql2);				
			$num2 = mysql_num_rows($rel2);
			//echo $option_text.$old_pid."=>".$rowCountry1['intparantlocation']."<br>";
			if (($old_pid <= $row1[$parentid]) || $old_pid == 0){
					$level .= '- ';
					$old_pid = $row1[$parentid];
				}
			if ($num2>0){		
				$this->getParentChildCombo($tbl,$tbl2,$id,$name,$parentid,$row1[$id],$level,$selected_value,$row1[$parentid],$statusfield,$statusval,$condition);
			}		
		
		}
	}
	
	// for simple dropdown box
	function getSimpleCombo($tbl,$id,$name,$selected='',$condition='',$statusfield='e_status',$statusval='active',$orderby='')
	{
		$sql = "SELECT $id, $name FROM `".$tbl."` 
						WHERE 1 ".$condition."
							AND ".$statusfield." = '".$statusval."'";

		if($orderby)
			$sql .= $orderby;

		//exit;				
		$rel = mysql_query($sql);
		
		$option = "";
		//echo $selctval;
		while ($row = mysql_fetch_array($rel))
		{
			$select = '';
			if($row[$id] == $selected)
			{
				$select = "selected = 'selected'";
			}
			$option .= '<option value="'.$row[$id].'" '.$select.'>'.ucwords(str_replace('_',' ',$row[$name])).'</option>';
		}
		echo $option;
		//return $option;
	}
	
	// for simple dropdown box
	function getSimpleComboLanguage($tbl,$id,$name,$selected='',$condition='',$statusfield='e_status',$statusval='active',$orderby='')
	{
		$foreign = substr($tbl,4);
		
		$sql = "SELECT t1.".$id.", t2.".$name." 
					FROM `".$tbl."` t1
					LEFT JOIN ".$tbl."_details t2
						ON t1.id = t2.i_".$foreign."_id
					WHERE 1 ".$condition."
						AND t1.".$statusfield." = '".$statusval."'
					GROUP BY i_".$foreign."_id ";
					
		if($orderby)
			$sql .= $orderby;
		else
			$sql .= " ORDER BY t1.".$id." ASC";
		//exit;				
		$rel = mysql_query($sql);
		
		$option = "";
		//echo $selctval;
		while ($row = mysql_fetch_array($rel))
		{
			$select = '';
			if(is_array($selected)) {
				if(in_array($row[$id],$selected)) {
					$select = "selected = 'selected'";
				}
			}
			else {
				if($row[$id] == $selected) {
					$select = "selected = 'selected'";
				}
			}
			$option .= '<option value="'.$row[$id].'" '.$select.'>'.$row[$name].'</option>';
		}
		echo $option;
		//return $option;
	}
	
	// For radio buttons
	function getRadioList($name,$radarray,$selval){
		$str = '';
		foreach($radarray as $key => $val){
			if($selval == $val) { $sel = 'checked="checked"'; }
			else { $sel = ''; }
			$str .= '<input type="radio" name="'.$name.'" value="'.$val.'" '.$sel.' />'.ucwords($val).' &nbsp; ';
		}
		echo $str;
	}
	
	// for checkbox lists
	function getCheckboxListFromTable($name,$tbl,$tblid,$selval){
		
		global $dclass;
		$chkarray = $dclass->select("*",$tbl,""," ORDER BY ".$tblid." DESC"," GROUP BY ".$tblid."");		
		
		
		$str = '';
		$cnt = 1;
		foreach($chkarray as $key => $val){
			$selvalarr = explode(',',$selval);
			$sel = '';
			foreach($selvalarr as $v) {
				if($v == $val[$tblid]) { 
					$sel = 'checked="checked"'; 
				}
			}
			$str .= '<input type="checkbox" name="'.$name.'" value="'.$val[$tblid].'" '.$sel.' />'.ucwords($val['v_title']).' &nbsp; ';
			
			if($cnt%3 == 0) {
				$str .= '<br /><br />';
			}
			
			$cnt++;
		}
		echo $str;
	}
	
	
	// For dropdown
	function getDropdownList($droparray,$selval){
		$str = '';
		foreach($droparray as $key => $val){
			if($selval == $val) { $sel = 'selected="selected"'; }
			else { $sel = ''; }
			$str .= '<option value="'.$val.'" '.$sel.' />'.ucwords($val).'</option>';
		}
		echo $str;
	}
	
	function getLinkUsingTable($table,$id) {
		global $dclass;
		if(SEO_FRIENDLY===true)
		{
			if($table == 'tbl_blog') {
				$row = $dclass->select("v_url",$table," AND id = '".$id."'");
				return substr($table,4).'/'.$row[0]['v_url'];
			}
			else {
				$row = $dclass->select("v_title",$table," AND id = '".$id."'");
			
				return substr($table,3).'/'.$this->seoText($row[0]['vName']).'-'.$id;
			}	
		}
		else
		{
			if($table == 'tblpage') {
				$row = $dclass->select("vPageUrl",$table," AND iId = '".$id."'");
				return substr($table,3).'.php?id='.$row[0]['vPageUrl'];
			}
			else {
				return substr($table,3).'.php?id='.$id;
			}	
		}
	}
	
	function getLinkUsingTableLanguage($table,$id) {
		global $dclass;
		global $language_id;
		if(SEO_FRIENDLY===true)
		{
			if($table == 'tbl_blog') {
				$row = $dclass->select("v_url,v_external_url,v_blog_category_id",$table," AND id = '".$id."'");
				
				
				if($row[0]['v_external_url'] == '' ) {
					$cat_id_arr = explode(",",$row[0]['v_blog_category_id']);
										
					$current_sql = $dclass->query("SELECT t1.*, t2.v_title FROM  tbl_menu t1 LEFT JOIN tbl_menu_details t2 ON t1.id = t2.i_menu_id WHERE t1.v_type = 'blog_category' AND t1.i_menu_id='".$cat_id_arr[0]."' AND t2.i_language_id = '".$language_id."'");
					$current_data = $dclass->fetchArray($current_sql);
					$cat_title = $dclass->select("*","tbl_blog_category_details"," AND i_blog_category_id = '".$current_data['i_menu_id']."' AND i_language_id='".$language_id."'");
					$hreflink = SITE_URL.'blog/'.strtolower($this->seoText($cat_title[0]['v_title'])).'-'.$current_data['i_menu_id'].'-'.$current_data['id'].'/'.$row[0]['v_url'].'/';
				}
				else {
					$hreflink = $row[0]['v_external_url'];
				}
		
				return $hreflink;
			}
			else if($table == 'tbl_cms') {
				return 'page/'.$id.'/';
			}	
			else {
				$foreign = substr($table,4);
				$row = $dclass->select("v_title",$table.'_details'," AND i_".$foreign."_id = '".$id."' AND i_language_id = '".$language_id."'");
				return str_replace('_','/',substr($table,4)).'/'.$this->seoText($row[0]['v_title']).'-'.$id;
			}
		}
		else
		{
			if($table == 'tbl_blog') {
				$row = $dclass->select("v_url",$table," AND id = '".$id."'");
				return 'post.php?url='.$row[0]['v_url'];
			}
			else if($table == 'tbl_cms') {
				return 'page.php?id='.$id;
			}	
			else {
				$foreign = substr($table,4);
				$row = $dclass->select("v_title",$table.'_details'," AND i_".$foreign."_id = '".$id."' AND i_language_id = '".$language_id."'");
				return str_replace('_','-',substr($table,4)).'.php?id='.$id;
			}
		}
	}
	
	function getLinkUsingTitle($title) {
		global $dclass;
		if(SEO_FRIENDLY===true)
		{
			return $this->seoText($title).'/';
		}
	}
	
	function getLinkUsingTitleFilename($table,$id,$title) {
		global $dclass;
		if(SEO_FRIENDLY===true) {
			return str_replace('_','/',substr($table,4)).'/'.$this->seoText($title).'-'.$id;
		}
		else {
			return str_replace('_','-',substr($table,4)).'.php?id='.$id;	
		}
	}

	
	function seoText($str) {
		$str=trim(strtolower($str));
		$special_array=array('#','$','\'','"','?','&',':','!','%','&reg;','&trade;','(',')','/',',');
		$str=str_replace(' ','-',$str);
		foreach($special_array as $item)
		{
			$str=str_replace($item,'-',$str);
		}
		$str=str_replace('--','-',$str);
		$str=str_replace('---','-',$str);
		$str=str_replace('--','-',$str);
		$str=str_replace('--','-',$str);
		$str=str_replace('--','-',$str);
		return trim($str,'-');
		
	}
	function get_total_rec($table) {
		global $dclass;
		$row = $dclass->select("*",$table,"");	
		
		return count($row);
	}
	function get_active_rec($table) {
		global $dclass;
		$row = $dclass->select("*",$table," AND e_status = 'active'");	

		return count($row);
	}
	function get_inactive_rec($table) {
		global $dclass;
		$row = $dclass->select("*",$table," AND e_status = 'inactive'");	

		return count($row);
	}
	
	function recently_view($id)
	{
		$noduplicate = 1;
		//check for available package
		if (in_array($id,$_SESSION['r_view']))
		{	
			//allow maximum	condition order		
				$noduplicate = 0;
			//	$_SESSION['r_view'][$id]['qty']++;
		}
		// check for available package ends
		
		if($noduplicate) {
			if (!session_is_registered("r_view")) 
				session_register("r_view");
	
		
			$_SESSION['r_view'][$id] = array('product_id'=>$id);
			$_SESSION['r_cnt'] = count($_SESSION['r_view']);	
			
			/*********************************/
		}
	}
	
	// shopping cart add function starts
	function add_to_shopping_cart($id,$imagename,$title,$status,$hkd_price,$rmb_price)
	{
		global $_POST;
		
		$noduplicate = 1;
		//check for available package
		for ($k=0;$k<=$_SESSION['cnt'];$k++)
		{
			if ($_SESSION['id_array'][$k] == $id)
			{	
				//allow maximum	condition order		
				if($_SESSION['qty_array'][$k] ==  2){
					$noduplicate = 0;
				}else{ 
					$noduplicate = 0;
					$_SESSION['qty_array'][$k]++;
				}
			}
		}	
		// check for available package ends
		
		if($noduplicate) {
			if (!session_is_registered("id_array")) 
				session_register("id_array");	
			
			if (!session_is_registered("image_array")) 
				session_register("image_array");
	
			if (!session_is_registered("name_array")) 
				session_register("name_array");
			
			if (!session_is_registered("status_array")) 
				session_register("status_array");
	
			if (!session_is_registered("hkd_price_array")) 
				session_register("hkd_price_array");
				
			if (!session_is_registered("rmb_price_array")) 
				session_register("rmb_price_array");
		
			if (!session_is_registered("qty_array")) 
				session_register("qty_array");
	

			if (!session_is_registered("cnt")) 
				session_register("cnt");
		
				
			$cnt = $_SESSION['cnt'];
		
					
			if(!isset($_SESSION['cnt']))
				$cnt = 0; 
			else
				$cnt++;				
		
			$_SESSION['id_array'][$cnt] = $id;
			$_SESSION['image_array'][$cnt] = $imagename;
			$_SESSION['name_array'][$cnt] = $title;
			$_SESSION['status_array'][$cnt] = $status;
			$_SESSION['hkd_price_array'][$cnt] = $hkd_price;			
			$_SESSION['rmb_price_array'][$cnt] = $rmb_price;
			$_SESSION['qty_array'][$cnt] = 1;		
			$_SESSION['cnt'] = $cnt;
			
			/*********************************/
		}
	}
	// shopping cart add function ends
	
	/********************************* xSendding Mail To User For Login Info ***********************************/
	function send_mail($to,$subject,$from,$message){
		//$to =  echo TO;
		//$subject = echo SUBJECT_MAIL;
		//$from = echo FROM;
		
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>'.$subject.'</title>
				</head>
				
				<body>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td align="left" valign="top"><img src="'.SITE_URL.'images/logo.gif" width="180" align="absbottom" /></td>
				  </tr>
				  <tr>
					<td style="border:1px solid #999">
						<table width="100%" border="0" cellspacing="0" cellpadding="8">
						  <tr>
							<td align="left" valign="top"></td>
						  </tr>
						  '.$message.'
						  <tr>
							<td align="left" valign="top" style="color:#666666">'.TO_ENSURE.'</td>
						  </tr>
						  <tr>
							<td align="left" valign="top" style="color:#666666">'.LIVECOAL_ADDRESS.'</td>
						  </tr>
						</table>
				
					</td>
				  </tr>
				</table>
				</body>
				</html>';
				
		$headers = "From: " . $from . "\r\n";
		$headers .= "Reply-To: ". $from . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		
		/*echo $to.'<br />';
		echo $msg;
		exit;*/
		
		
		if (mail($to, $subject, $msg, $headers))
				return(1);
		else
				return(1);
		
	}
	// Random Password Generator
	function assign_rand_value($num)
	{
	// accepts 1 - 36
		switch($num)
		{
			case "1":
			 $rand_value = "a";
			break;
			case "2":
			 $rand_value = "b";
			break;
			case "3":
			 $rand_value = "c";
			break;
			case "4":
			 $rand_value = "d";
			break;
			case "5":
			 $rand_value = "e";
			break;
			case "6":
			 $rand_value = "f";
			break;
			case "7":
			 $rand_value = "g";
			break;
			case "8":
			 $rand_value = "h";
			break;
			case "9":
			 $rand_value = "i";
			break;
			case "10":
			 $rand_value = "j";
			break;
			case "11":
			 $rand_value = "k";
			break;
			case "12":
			 $rand_value = "l";
			break;
			case "13":
			 $rand_value = "m";
			break;
			case "14":
			 $rand_value = "n";
			break;
			case "15":
			 $rand_value = "o";
			break;
			case "16":
			 $rand_value = "p";
			break;
			case "17":
			 $rand_value = "q";
			break;
			case "18":
			 $rand_value = "r";
			break;
			case "19":
			 $rand_value = "s";
			break;
			case "20":
			 $rand_value = "t";
			break;
			case "21":
			 $rand_value = "u";
			break;
			case "22":
			 $rand_value = "v";
			break;
			case "23":
			 $rand_value = "w";
			break;
			case "24":
			 $rand_value = "x";
			break;
			case "25":
			 $rand_value = "y";
			break;
			case "26":
			 $rand_value = "z";
			break;
			case "27":
			 $rand_value = "0";
			break;
			case "28":
			 $rand_value = "1";
			break;
			case "29":
			 $rand_value = "2";
			break;
			case "30":
			 $rand_value = "3";
			break;
			case "31":
			 $rand_value = "4";
			break;
			case "32":
			 $rand_value = "5";
			break;
			case "33":
			 $rand_value = "6";
			break;
			case "34":
			 $rand_value = "7";
			break;
			case "35":
			 $rand_value = "8";
			break;
			case "36":
			 $rand_value = "9";
			break;
		}
		return $rand_value;
	}
	function get_rand_id($length)
	{
		if($length>0) 
		{ 
			$rand_id="";
			for($i=1; $i<=$length; $i++)
			{
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,36);
				$rand_id .= $this->assign_rand_value($num);
			}
		}
		return $rand_id;
	} 
	//add to general count of the Home Page Start
	function common_count($table,$field ="",$type=""){
		
//MAKE QUERY TO WHICH IS BASE ON INPUT FIELD TYPE COND 
$currentInfo =  getdate(); // current date as array 
		if($field =='amount'){
			// Provide Total Amount the no of recode in month year or total
			if($type =='total'){
				 $sqlcount = "SELECT  SUM(f_amount + f_payment_charge + f_shipping ) AS sent , v_currency  FROM ".$table." WHERE 1 "." GROUP BY v_currency ";		
			}else if($type == 'month'){				
			  $sqlcount = "SELECT  SUM(f_amount + f_payment_charge + f_shipping ) AS sent , v_currency  FROM ".$table." WHERE 1 AND MONTH(d_date) ='".$currentInfo['mon']."' GROUP BY v_currency";
			
			}else if($type == 'year'){
			 $sqlcount = "SELECT  SUM(f_amount + f_payment_charge + f_shipping ) AS sent , v_currency FROM ".$table." WHERE 1 AND YEAR(d_date) ='".$currentInfo['year']."' GROUP BY v_currency";	
			
			}
		}else if($type != ''){
			// Only Count the no of recode in month year or total
			if($type =='total'){
				 $sqlcount = "SELECT  count(*) AS sent FROM ".$table." WHERE 1 ";		
			}else if($type == 'month'){
					 $sqlcount = "SELECT  count(*) AS sent FROM ".$table." WHERE 1 AND MONTH(d_date) ='".$currentInfo['mon']."'";		
			}else if($type == 'year'){
				 $sqlcount = "SELECT  count(*) AS sent FROM ".$table." WHERE 1 AND YEAR(d_date) ='".$currentInfo['year']."'";		
			}			
		}else{
				 $sqlcount = "SELECT  count(*) AS sent FROM ".$table." WHERE 1 ";
		}
//EXCUTE qUERY TO WHICH ISD GENERATED
			$dataCount = mysql_query($sqlcount);	
				$result = array();
				$resultType = MYSQL_ASSOC;				
				while($row = mysql_fetch_array($dataCount,$resultType)){
					$result[] = $row;
					
				}	
					
				return($result);
	}
	function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

//  Calculate  The Rewards At  That  Product  Base  on User Consumption  
function calcaluteTotalRewardsPoint ($proId,$fieldsset_id){
	global $dclass;	
	//  set the  User Input 
	//print_r($fieldsset_id); 
	$user_input  = '';
	foreach  ($fieldsset_id	as $key  => $val){
		$rsFields =  $dclass->query(" SELECT * FROM tbl_fields WHERE  id  =  ".$key." AND refine_search='1'");
		if(!empty($rsFields)){
			$rowQuetion = mysql_fetch_assoc($rsFields);
			$user_input[$rowQuetion['caption']] = $val;
		}
	}
	//print_r($user_input);
	//print_r($user_input);
	//echo $proId."<br>";
	
//  Result Set Of Calculation 
$result_set =  array();

 	
	//  GEt  the  Product Info 
	$ProductInfo = '' ;  //  Procuct value Set  On Edit
if($proId != ''){	
	$rsProduct  =  $dclass->select(" * " ,"tbl_product_option"," AND  product_id =".$proId);
	if(!empty($rsProduct)){
		foreach($rsProduct as  $rowProduct){
			$ProductInfo[$rowProduct['v_name']] =  $rowProduct['l_values'];
		}
	}else{
		$ProductInfo = '';
	}
}
	$proiduct_points_config  = ''; //  Product Rewards On User Spend 
	$proiduct_points_config['gas'] =  $ProductInfo['gas'];
	$proiduct_points_config['dinning']= $ProductInfo['dinning'];
	$proiduct_points_config['grocery']=$ProductInfo['grocery'];
	$proiduct_points_config['airfare']= $ProductInfo['airfare'];
	$proiduct_points_config['hotel'] =  $ProductInfo['hotel'];
	$proiduct_points_config['othersaving'] = $ProductInfo['othersaving'];
	$proiduct_points_config['remaining'] =  $ProductInfo['remaining'];
	
	$proiduct_points_config['freebag'] = $ProductInfo['freebag'];
	$proiduct_points_config['foreign_purchase'] =  $ProductInfo['foreign_purchase'];
	$proiduct_points_config['other_saving'] =  $ProductInfo['other_saving'];  //  Fix Saving Other Then Spending area
	
	$proiduct_points_config['annualFeeFirstYear'] =  $ProductInfo['annualFeeFirstYear'];
	$proiduct_points_config['annualFeeOngoingYear'] =  $ProductInfo['annualFeeOngoingYear'];
	
	$proiduct_points_config['value_per_point_default'] = $ProductInfo['value_per_point_default'];
	$proiduct_points_config['value_per_point_hotel'] = $ProductInfo['value_per_point_hotel'];
	$proiduct_points_config['value_per_point_cash'] = $ProductInfo['value_per_point_cash'];

//print_r($proiduct_points_config);

// Point Earned Via Spending 
  $user_input['remaining'] = $user_input['Monthly Spend'] - ($user_input['> Gas'] 
							+ $user_input['> Dining'] 
							+ $user_input['> Grocery'] 
							+ $user_input['> Airfare'] 
							+ $user_input['> Hotel'] 
							+ $user_input['othersaving']) ;


	$monthly_spend =  '';//  Monthly Rewards
	$monthly_spend['gas'] =  $proiduct_points_config['gas'] *  $user_input['> Gas'];
	$monthly_spend['dinning']= $proiduct_points_config['dinning'] * $user_input['> Dining'];
	$monthly_spend['grocery']= $proiduct_points_config['grocery'] *  $user_input['> Grocery'];
	$monthly_spend['airfare']= $proiduct_points_config['airfare'] *  $user_input['airfare'];
	$monthly_spend['hotel'] =  $proiduct_points_config['hotel'] * $user_input['> Hotel'];
	$monthly_spend['othersaving'] = $proiduct_points_config['othersaving'] *  $user_input['othersaving'];
	$monthly_spend['remaining'] =  $proiduct_points_config['remaining'] *  $user_input['remaining'];
	 $total_monthly_spend  =   $monthly_spend['gas'] 
							+ $monthly_spend['dinning'] 
							+ $monthly_spend['grocery'] 
							+ $monthly_spend['airfare'] 
							+ $monthly_spend['hotel'] 
							+ $monthly_spend['othersaving'] 
							+ $monthly_spend['remaining'];
							
$result_set['total_monthly_spend']	 =  $total_monthly_spend;						

	$yearly_spend =  '';//  Monthly Rewards
	  $yearly_spend['gas'] =  $proiduct_points_config['gas'] *  $user_input['> Gas'] * 12;
	  $yearly_spend['dinning']= $proiduct_points_config['dinning'] * $user_input['> Dining'] * 12;
	  $yearly_spend['grocery']= $proiduct_points_config['grocery'] *  $user_input['> Grocery'] * 12;
	  $yearly_spend['airfare']= $proiduct_points_config['airfare'] *  $user_input['> Airfare'] * 12;
	  $yearly_spend['hotel'] =  $proiduct_points_config['hotel'] * $user_input['> Hotel'] * 12;
	  $yearly_spend['othersaving'] = $proiduct_points_config['othersaving'] *  $user_input['othersaving'] * 12;
	  $yearly_spend['remaining'] =  $proiduct_points_config['remaining'] *  $user_input['remaining'] * 12;
		$total_yearly_spend = 	 $yearly_spend['gas'] 
												  +	 $yearly_spend['dinning'] 
												  +  $yearly_spend['grocery'] 
												  +  $yearly_spend['airfare'] 
												  +  $yearly_spend['hotel'] 
												  +  $yearly_spend['othersaving'] 
												  +  $yearly_spend['remaining'] ;
$result_set['total_yearly_spend']  = $total_yearly_spend;
							
// Point Earned Via Bonus 
$proiduct_points_config['signon']	=  $ProductInfo['signon'];
$proiduct_points_config['threshold'] = $ProductInfo['threshold']; 	
$proiduct_points_config['thresholdperiod'] = $ProductInfo['thresholdperiod'];
if($proiduct_points_config['threshold'] !=  0 && $proiduct_points_config['thresholdperiod'] != 0)
	$monthly_threshold =  $proiduct_points_config['threshold'] / $proiduct_points_config['thresholdperiod'];
else 
	$monthly_threshold =  0;
	
$result_set['monthly_threshold']=  $monthly_threshold;
	//  Bouns  Earned 
	$b_onus_earned =  '';
	if( ( $total_monthly_spend >=  $monthly_threshold  ||  $proiduct_points_config['threshold'] == 0 ) && ($proiduct_points_config['signon'] !=  ''))
		$b_onus_earned =  $proiduct_points_config['signon'];
	else 
		$b_onus_earned =  0 ;
$result_set['b_onus_earned']=  $b_onus_earned;	
	//  Annual Default Rewards
	$proiduct_points_config['annualRewards'] =  $ProductInfo['annualRewards'];
	$proiduct_points_config['annualThreshold'] =  $ProductInfo['annualThreshold'];
	if( $proiduct_points_config['annualRewards']  < 100)
		$annual_rewards =  $total_yearly_spend * $proiduct_points_config['annualRewards'];	
	else 
		$annual_rewards =   $proiduct_points_config['annualRewards'];	
		
	$result_set['annual_rewards']=  $annual_rewards;	
	
	if($proiduct_points_config['annualThreshold']  != 0   &&  $proiduct_points_config['annualThreshold']   != '' ){
		
		$temp =  $total_monthly_spend * 12; 	

		if($temp >= $proiduct_points_config['annualThreshold'] )
			$annual_b_onus_earned = $annual_rewards;
		else
			$annual_b_onus_earned = 0;
	}else {
			$annual_b_onus_earned = $annual_rewards;
	}		
	$result_set['annual_b_onus_earned'] = $annual_b_onus_earned;
//  Total Earned  Point First Year
	$total_point_earned_first_year =  $annual_b_onus_earned + $b_onus_earned + $total_yearly_spend   ;
	$result_set['total_point_earned_first_year'] = $total_point_earned_first_year;
//  Total Earned  Point OnGoing Year
	$total_point_earned_ongoing_year = $annual_rewards + $total_yearly_spend ;
	$result_set['total_point_earned_ongoing_year'] = $total_point_earned_ongoing_year;
// Bag  Saving 
	$bag_saving  =  $proiduct_points_config['freebag'] *  $user_input['freebag'];
	$result_set['bag_saving'] =  $bag_saving;
// Foreign purchase Saving 
	$foreign_purchase_saving = $proiduct_points_config['foreign_purchase'] *  $user_input['Foreign Purchases (per year)'];
	$result_set['foreign_purchase_saving'] =  $foreign_purchase_saving;
//Other Saving  Lounge access
	if($proiduct_points_config['other_saving'] != 0  &&  $proiduct_points_config['other_saving'] != ''){
		if( $user_input['other_saving'] == 'YES'  ){
			if($proiduct_points_config['other_saving'] ==  'YES')			
				$other_saving  =  100;
			else 
				$other_saving  =  100;
		}
		else
			$other_saving  = 100;
	}else
		$other_saving = 100;
		
	
$result_set['other_saving']  =  $other_saving;

//  Total Saving First  Year 
	$total_default_saving_first_year  =  ($total_point_earned_first_year *  $proiduct_points_config['value_per_point_default']) + $bag_saving + $foreign_purchase_saving + $other_saving - $proiduct_points_config['annualFeeFirstYear'];
	$total_hotel_saving_first_year  =  ($total_point_earned_first_year *  $proiduct_points_config['value_per_point_hotel']) + $bag_saving + $foreign_purchase_saving + $other_saving  - $proiduct_points_config['annualFeeFirstYear'];
	$total_cash_saving_first_year  =   ($total_point_earned_first_year *  $proiduct_points_config['value_per_point_cash']) + $bag_saving + $foreign_purchase_saving  - $proiduct_points_config['annualFeeFirstYear'];

$result_set['total_default_saving_first_year']  =  $total_default_saving_first_year;
$result_set['total_hotel_saving_first_year']  =  $total_hotel_saving_first_year;
$result_set['total_cash_saving_first_year']  =  $total_cash_saving_first_year;

//  Total Saving Ongoing Year 
	$total_default_saving_ongoing_year  =  ($total_point_earned_ongoing_year *  $proiduct_points_config['value_per_point_default']) + $bag_saving + $foreign_purchase_saving + $other_saving - $proiduct_points_config['annualFeeOngoingYear'];
	$total_hotel_saving_ongoing_year  =  ($total_point_earned_ongoing_year *  $proiduct_points_config['value_per_point_hotel']) + $bag_saving + $foreign_purchase_saving + $other_saving - $proiduct_points_config['annualFeeOngoingYear'];
	$total_cash_saving_ongoing_year  =   ($total_point_earned_ongoing_year *  $proiduct_points_config['value_per_point_cash']) + $bag_saving + $foreign_purchase_saving  - $proiduct_points_config['annualFeeOngoingYear'];

$result_set['total_default_saving_ongoing_year']  =  $total_default_saving_ongoing_year;
$result_set['total_hotel_saving_ongoing_year']  =  $total_hotel_saving_ongoing_year;
$result_set['total_cash_saving_ongoing_year']  =  $total_cash_saving_ongoing_year;
	

// End Calculation
//print_r($result_set);
return ($result_set);
}
function calcaluteTotalRewardsPoint2 ($proId,$fieldsset_id){
	global $dclass;	
	//  set the  User Input 
	//print_r($fieldsset_id); 
	$user_input  = '';
	foreach  ($fieldsset_id	as $key  => $val){
		$rsFields =  $dclass->query(" SELECT * FROM tbl_fields WHERE  id  =  ".$key." AND refine_search='1'");
		if(!empty($rsFields)){
			$rowQuetion = mysql_fetch_assoc($rsFields);
			$user_input[$rowQuetion['caption']] = $val;
		}
	}
	//print_r($user_input);
	//print_r($user_input);
	//echo $proId."<br>";
	
//  Result Set Of Calculation 
$result_set =  array();

 	
	//  GEt  the  Product Info 
	$ProductInfo = '' ;  //  Procuct value Set  On Edit
if($proId != ''){	
	$rsProduct  =  $dclass->select(" * " ,"tbl_product_option"," AND  product_id =".$proId);
	if(!empty($rsProduct)){
		foreach($rsProduct as  $rowProduct){
			$ProductInfo[$rowProduct['v_name']] =  $rowProduct['l_values'];
		}
	}else{
		$ProductInfo = '';
	}
}
	$proiduct_points_config  = ''; //  Product Rewards On User Spend 
	$proiduct_points_config['gas'] =  $ProductInfo['gas'];
	$proiduct_points_config['dinning']= $ProductInfo['dinning'];
	$proiduct_points_config['grocery']=$ProductInfo['grocery'];
	$proiduct_points_config['airfare']= $ProductInfo['airfare'];
	$proiduct_points_config['hotel'] =  $ProductInfo['hotel'];
	$proiduct_points_config['othersaving'] = $ProductInfo['othersaving'];
	$proiduct_points_config['remaining'] =  $ProductInfo['remaining'];
	
	$proiduct_points_config['freebag'] = $ProductInfo['freebag'];
	$proiduct_points_config['foreign_purchase'] =  $ProductInfo['foreign_purchase'];
	$proiduct_points_config['other_saving'] =  $ProductInfo['other_saving'];  //  Fix Saving Other Then Spending area
	
	$proiduct_points_config['annualFeeFirstYear'] =  $ProductInfo['annualFeeFirstYear'];
	$proiduct_points_config['annualFeeOngoingYear'] =  $ProductInfo['annualFeeOngoingYear'];
	
	$proiduct_points_config['value_per_point_default'] = $ProductInfo['value_per_point_default'];
	$proiduct_points_config['value_per_point_hotel'] = $ProductInfo['value_per_point_hotel'];
	$proiduct_points_config['value_per_point_cash'] = $ProductInfo['value_per_point_cash'];

//print_r($proiduct_points_config);

// Point Earned Via Spending 
  $user_input['remaining'] = $user_input['Monthly Spend'] - ($user_input['> Gas'] 
							+ $user_input['> Dining'] 
							+ $user_input['> Grocery'] 
							+ $user_input['> Airfare'] 
							+ $user_input['> Hotel'] 
							+ $user_input['othersaving']) ;


	$monthly_spend =  '';//  Monthly Rewards
	$monthly_spend['gas'] =  $proiduct_points_config['gas'] *  $user_input['> Gas'];
	$monthly_spend['dinning']= $proiduct_points_config['dinning'] * $user_input['> Dining'];
	$monthly_spend['grocery']= $proiduct_points_config['grocery'] *  $user_input['> Grocery'];
	$monthly_spend['airfare']= $proiduct_points_config['airfare'] *  $user_input['airfare'];
	$monthly_spend['hotel'] =  $proiduct_points_config['hotel'] * $user_input['> Hotel'];
	$monthly_spend['othersaving'] = $proiduct_points_config['othersaving'] *  $user_input['othersaving'];
	$monthly_spend['remaining'] =  $proiduct_points_config['remaining'] *  $user_input['remaining'];
	 $total_monthly_spend  =   $monthly_spend['gas'] 
							+ $monthly_spend['dinning'] 
							+ $monthly_spend['grocery'] 
							+ $monthly_spend['airfare'] 
							+ $monthly_spend['hotel'] 
							+ $monthly_spend['othersaving'] 
							+ $monthly_spend['remaining'];
							
$result_set['total_monthly_spend']	 =  $total_monthly_spend;						

	$yearly_spend =  '';//  Monthly Rewards
	  $yearly_spend['gas'] =  $proiduct_points_config['gas'] *  $user_input['> Gas'] * 12;
	  $yearly_spend['dinning']= $proiduct_points_config['dinning'] * $user_input['> Dining'] * 12;
	  $yearly_spend['grocery']= $proiduct_points_config['grocery'] *  $user_input['> Grocery'] * 12;
	  $yearly_spend['airfare']= $proiduct_points_config['airfare'] *  $user_input['> Airfare'] * 12;
	  $yearly_spend['hotel'] =  $proiduct_points_config['hotel'] * $user_input['> Hotel'] * 12;
	  $yearly_spend['othersaving'] = $proiduct_points_config['othersaving'] *  $user_input['othersaving'] * 12;
	  $yearly_spend['remaining'] =  $proiduct_points_config['remaining'] *  $user_input['remaining'] * 12;
		$total_yearly_spend = 	 $yearly_spend['gas'] 
												  +	 $yearly_spend['dinning'] 
												  +  $yearly_spend['grocery'] 
												  +  $yearly_spend['airfare'] 
												  +  $yearly_spend['hotel'] 
												  +  $yearly_spend['othersaving'] 
												  +  $yearly_spend['remaining'] ;
$result_set['total_yearly_spend']  = $total_yearly_spend;
							
// Point Earned Via Bonus 
$proiduct_points_config['signon']	=  $ProductInfo['signon'];
$proiduct_points_config['threshold'] = $ProductInfo['threshold']; 	
$proiduct_points_config['thresholdperiod'] = $ProductInfo['thresholdperiod'];
if($proiduct_points_config['threshold'] !=  0 && $proiduct_points_config['thresholdperiod'] != 0)
	$monthly_threshold =  $proiduct_points_config['threshold'] / $proiduct_points_config['thresholdperiod'];
else 
	$monthly_threshold =  0;
	
$result_set['monthly_threshold']=  $monthly_threshold;
	//  Bouns  Earned 
	$b_onus_earned =  '';
	if( ( $total_monthly_spend >=  $monthly_threshold  ||  $proiduct_points_config['threshold'] == 0 ) && ($proiduct_points_config['signon'] !=  ''))
		$b_onus_earned =  $proiduct_points_config['signon'];
	else 
		$b_onus_earned =  0 ;
$result_set['b_onus_earned']=  $b_onus_earned;	
	//  Annual Default Rewards
	$proiduct_points_config['annualRewards'] =  $ProductInfo['annualRewards'];
	$proiduct_points_config['annualThreshold'] =  $ProductInfo['annualThreshold'];
	if( $proiduct_points_config['annualRewards']  < 100)
		$annual_rewards =  $total_yearly_spend * $proiduct_points_config['annualRewards'];	
	else 
		$annual_rewards =   $proiduct_points_config['annualRewards'];	
		
	$result_set['annual_rewards']=  $annual_rewards;	
	
	if($proiduct_points_config['annualThreshold']  != 0   &&  $proiduct_points_config['annualThreshold']   != '' ){
		
		$temp =  $total_monthly_spend * 12; 	

		if($temp >= $proiduct_points_config['annualThreshold'] )
			$annual_b_onus_earned = $annual_rewards;
		else
			$annual_b_onus_earned = 0;
	}else {
			$annual_b_onus_earned = $annual_rewards;
	}		
	$result_set['annual_b_onus_earned'] = $annual_b_onus_earned;
//  Total Earned  Point First Year
	$total_point_earned_first_year =  $annual_b_onus_earned + $b_onus_earned + $total_yearly_spend   ;
	$result_set['total_point_earned_first_year'] = $total_point_earned_first_year;
//  Total Earned  Point OnGoing Year
	$total_point_earned_ongoing_year = $annual_rewards + $total_yearly_spend ;
	$result_set['total_point_earned_ongoing_year'] = $total_point_earned_ongoing_year;
// Bag  Saving 
	$bag_saving  =  $proiduct_points_config['freebag'] *  $user_input['freebag'];
	$result_set['bag_saving'] =  $bag_saving;
// Foreign purchase Saving 
	$foreign_purchase_saving = $proiduct_points_config['foreign_purchase'] *  $user_input['Foreign Purchases (per year)'];
	$result_set['foreign_purchase_saving'] =  $foreign_purchase_saving;
//Other Saving  Lounge access
	if($proiduct_points_config['other_saving'] != 0  &&  $proiduct_points_config['other_saving'] != ''){
		if( $user_input['other_saving'] == 'YES'  ){
			if($proiduct_points_config['other_saving'] ==  'YES')			
				$other_saving  =  100;
			else 
				$other_saving  =  100;
		}
		else
			$other_saving  = 100;
	}else
		$other_saving = 100;
		
	
$result_set['other_saving']  =  $other_saving;

//  Total Saving First  Year 
	$total_default_saving_first_year  =  ($total_point_earned_first_year *  $proiduct_points_config['value_per_point_default']) + $bag_saving + $foreign_purchase_saving + $other_saving - $proiduct_points_config['annualFeeFirstYear'];
	$total_hotel_saving_first_year  =  ($total_point_earned_first_year *  $proiduct_points_config['value_per_point_hotel']) + $bag_saving + $foreign_purchase_saving + $other_saving  - $proiduct_points_config['annualFeeFirstYear'];
	$total_cash_saving_first_year  =   ($total_point_earned_first_year *  $proiduct_points_config['value_per_point_cash']) + $bag_saving + $foreign_purchase_saving  - $proiduct_points_config['annualFeeFirstYear'];

$result_set['total_default_saving_first_year']  =  $total_default_saving_first_year;
$result_set['total_hotel_saving_first_year']  =  $total_hotel_saving_first_year;
$result_set['total_cash_saving_first_year']  =  $total_cash_saving_first_year;

//  Total Saving Ongoing Year 
	$total_default_saving_ongoing_year  =  ($total_point_earned_ongoing_year *  $proiduct_points_config['value_per_point_default']) + $bag_saving + $foreign_purchase_saving + $other_saving - $proiduct_points_config['annualFeeOngoingYear'];
	$total_hotel_saving_ongoing_year  =  ($total_point_earned_ongoing_year *  $proiduct_points_config['value_per_point_hotel']) + $bag_saving + $foreign_purchase_saving + $other_saving - $proiduct_points_config['annualFeeOngoingYear'];
	$total_cash_saving_ongoing_year  =   ($total_point_earned_ongoing_year *  $proiduct_points_config['value_per_point_cash']) + $bag_saving + $foreign_purchase_saving  - $proiduct_points_config['annualFeeOngoingYear'];

$result_set['total_default_saving_ongoing_year']  =  $total_default_saving_ongoing_year;
$result_set['total_hotel_saving_ongoing_year']  =  $total_hotel_saving_ongoing_year;
$result_set['total_cash_saving_ongoing_year']  =  $total_cash_saving_ongoing_year;
	

// End Calculation
//print_r($result_set);
return ($result_set);
}
	
	/***PArent  Child  Combo  

$id  Which All Id You Want to Select
$table  =  Table NAme  DB
$parent_id =  Parent id Field NAme db 
$parent_id_value  = Parent Id Value   
$selected_value  Value MAy  be  array  Or Sigle One 
$selectIdSet  =  Value Of Id Select Box
$eventSet  =  value Of Id Select Box

**/
function  getParentChildCombo3 ($table,$id,$print_id,$parent_id,$parent_value,$ORDERBY = '',$selected_id,$selectIdSet  ,$eventSet  ){
	if($selectIdSet ==  '')
		$selectIdSet =  "CategoryList";
	
$tree = '<select name="'.$selectIdSet.'" id="'.$selectIdSet.'" '.$eventSet.' class="validate[required] span6 m-wrap" >';

if ($ORDERBY != '' )
 	  $result= mysql_query("SELECT * FROM $table WHERE $parent_id = $parent_value ORDER BY $ORDERBY ASC");
else 
	$result= mysql_query("SELECT * FROM $table WHERE $parent_id = $parent_value");



	$tree .= '<option value="">Choose a category</option>';

		while ($row = mysql_fetch_assoc($result))
		{
			$checkSub= mysql_query("SELECT * FROM $table WHERE $parent_id =".$row[$id]);
			$num_rows = mysql_num_rows($checkSub);
			if($num_rows > 0){
				$disable = 'disabled="disabled"';
			}else{
				$disable = '';
			}
			//  Selected Value  Check 
		if($selected_id != ''){
			 if(is_array($selected_id) ){				 
				if(in_array($row[$id], $selected_id) ){
						$selectedText = 'selected="selected"';
				}else {
						$selectedText = '';
				}
			  }else{
					if ($selected_id ==  $row[$id])
						$selectedText = 'selected="selected"';
					else
						$selectedText = '';					
				}
			}else {
				$selectedText =  '';
			}
			//  Selected Check End 
			
			  $tree .= '<option value="'.$row[$id].'" '.$selectedText.'   >';
			  $tree .= $row[$print_id];
			  $tree .= '</option>';
			  $tree .= $this->getChild3($table,$id,$print_id,$parent_id,$row[$id],1,$ORDERBY,$selected_id);
		}
		
		
	$tree .= '</select>';

return ($tree);
	
}
	
function getChild3($table,$id,$print_id,$parent_id,$parent_id_value,$level,$ORDERBY = '',$selected_id)
{       
    $response = '';
	if($ORDERBY != '' )
    	   $query = "SELECT * FROM ".$table." where $parent_id = $parent_id_value ORDER BY $ORDERBY ASC" ;
	else 
	  	  $query = "SELECT * FROM ".$table." where $parent_id = $parent_id_value ";
		 
    $result = mysql_query($query) or die ('Database Error (' . mysql_errno() . ') ' . mysql_error());

    while ($row = mysql_fetch_assoc($result))
    {
       $checkSub= mysql_query("SELECT * FROM $table WHERE $parent_id =".$row[$id]);
			$num_rows = mysql_num_rows($checkSub);
			if($num_rows > 0){
				$disable = 'disabled="disabled"';
			}else{
				$disable = '';
			}
	   
	   if($selected_id != ''){
			 if(is_array($selected_id) ){				 
				if(in_array($row[$id], $selected_id) ){
						$selectedText = 'selected="selected"';
				}else {
						$selectedText = '';
				}
			  }else{
					if ($selected_id ==  $row[$id])
						$selectedText = 'selected="selected"';
					else
						$selectedText = '';					
				}
			}else {
				$selectedText =  '';
			}
		
		
		
		$response .= '<option value="'.$row[$id].'" '.$selectedText.' >';
        for ($i = 0; $i < ($level-1); $i++) $response .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        $response .= '&nbsp;&nbsp;-&nbsp;'.$row[$print_id];
        $response .= '</option>';
        $response .= $this->getChild2($table,$id,$print_id,$parent_id,$row[$id],$level+1,$ORDERBY,$selected_id);
    }
    return $response;      
}


function  getParentChildCombo2 ($table,$id,$print_id,$parent_id,$parent_value,$ORDERBY = '',$selected_id,$selectIdSet  ,$eventSet  ){
	if($selectIdSet ==  '')
		$selectIdSet =  "CategoryList";
	
$tree = '<select name="'.$selectIdSet.'" id="'.$selectIdSet.'" '.$eventSet.' class="span4 m-wrap" >';

if ($ORDERBY != '' )
 	  $result= mysql_query("SELECT * FROM $table WHERE $parent_id = $parent_value AND category_publish='1' ORDER BY $ORDERBY ASC");
else 
	$result= mysql_query("SELECT * FROM $table WHERE $parent_id = $parent_value AND category_publish='1' ");



	$tree .= '<option value="">Choose a category</option>';

		while ($row = mysql_fetch_assoc($result))
		{
			$checkSub= mysql_query("SELECT * FROM $table WHERE $parent_id =".$row[$id]." AND category_publish='1' ");
			$num_rows = mysql_num_rows($checkSub);
			if($num_rows > 0){
				$disable = 'disabled="disabled"';
			}else{
				$disable = '';
			}
			//  Selected Value  Check 
		if($selected_id != ''){
			 if(is_array($selected_id) ){				 
				if(in_array($row[$id], $selected_id) ){
						$selectedText = 'selected="selected"';
				}else {
						$selectedText = '';
				}
			  }else{
					if ($selected_id ==  $row[$id])
						$selectedText = 'selected="selected"';
					else
						$selectedText = '';					
				}
			}else {
				$selectedText =  '';
			}
			//  Selected Check End 
			
			  $tree .= '<option value="'.$row[$id].'" '.$selectedText.'  '.$disable.' >';
			  $tree .= $row[$print_id];
			  $tree .= '</option>';
			  $tree .= $this->getChild2($table,$id,$print_id,$parent_id,$row[$id],1,$ORDERBY,$selected_id);
		}
		
		
	$tree .= '</select>';

return ($tree);
	
}
	
function getChild2($table,$id,$print_id,$parent_id,$parent_id_value,$level,$ORDERBY = '',$selected_id)
{       
    $response = '';
	if($ORDERBY != '' )
    	   $query = "SELECT * FROM ".$table." where $parent_id = $parent_id_value AND category_publish='1' ORDER BY $ORDERBY ASC" ;
	else 
	  	  $query = "SELECT * FROM ".$table." where $parent_id = $parent_id_value AND category_publish='1' ";
		 
    $result = mysql_query($query) or die ('Database Error (' . mysql_errno() . ') ' . mysql_error());

    while ($row = mysql_fetch_assoc($result))
    {
       $checkSub= mysql_query("SELECT * FROM $table WHERE $parent_id =".$row[$id]);
			$num_rows = mysql_num_rows($checkSub);
			if($num_rows > 0){
				$disable = 'disabled="disabled"';
			}else{
				$disable = '';
			}
	   
	   if($selected_id != ''){
			 if(is_array($selected_id) ){				 
				if(in_array($row[$id], $selected_id) ){
						$selectedText = 'selected="selected"';
				}else {
						$selectedText = '';
				}
			  }else{
					if ($selected_id ==  $row[$id])
						$selectedText = 'selected="selected"';
					else
						$selectedText = '';					
				}
			}else {
				$selectedText =  '';
			}
		
		
		
		$response .= '<option value="'.$row[$id].'" '.$selectedText.' '.$disable.'>';
        for ($i = 0; $i < ($level-1); $i++) $response .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        $response .= '&nbsp;&nbsp;-&nbsp;'.$row[$print_id];
        $response .= '</option>';
        $response .= $this->getChild2($table,$id,$print_id,$parent_id,$row[$id],$level+1,$ORDERBY,$selected_id);
    }
    return $response;      
}
	

/* 
* -------- function arguments -------- 
*   $array ........ array of objects
*   $sortby ....... the object-key to sort by
*   $direction ... 'asc' = ascending
* --------
*/
function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = $this->object_to_array($value);
        }
        return $result;
    }
    return $data;
}


function sort_arr($array, $sortby, $direction='asc') {
     
    $sortedArr = array();
    $tmp_Array = array();
     
    foreach($array as $k => $v) {
        $tmp_Array[] = $v[$sortby];
    }
     
    if($direction=='asc'){
        asort($tmp_Array);
    }else{
        arsort($tmp_Array);
    }
     
    foreach($tmp_Array as $k=>$tmp){
        $sortedArr[] = $array[$k];
    }
     
    return $sortedArr;
 
}
	function getAge($dob){
	    return floor((time() - strtotime($dob))/31556926); 
		
   
}
	function trunc_string($str,$len){
	    if( strlen($str) >= $len ){
	        return substr($str,0,$len).'...';
	    }else{
	        return substr($str,0,$len);
	    }
	}

	function checkpaymentstatus(){
	global $dclass;
		$today = date("Y-m-d");
		//echo $_SESSION['company_id'];
	//echo "select id,sub_plan_id,user_id,subscrib_type,payment_status,expire_date from tbl_user_subscrib_detail where user_id=".$_SESSION['company_id']." AND payment_status='Completed' ORDER BY id desc limit 0,1";
		$pay_status =  $dclass->select("id,sub_plan_id,user_id,subscrib_type,payment_status,expire_date",'tbl_user_subscrib_detail'," AND user_id=".$_SESSION['company_id']." AND payment_status='Completed' ORDER BY id desc limit 0,1");
		$user_status =  $dclass->select("user_status",'tbl_user'," AND user_id=".$_SESSION['user_id']."");

	//echo '<pre>';	print_r($pay_status); echo '</pre>';
		if($pay_status[0]['payment_status'] == 'Completed' && $pay_status[0]['subscrib_type'] == 'paid'){
			if($user_status[0]['user_status'] == 'inactive'){
				return 0;	
			}else{
				return 1;
			}
				
		}else{
			if(($pay_status[1]['payment_status'] == 'Completed' || $pay_status[1]['payment_status'] == '') && $pay_status[0]['subscrib_type'] == 'free' && strtotime($pay_status[0]['expire_date'] ) >  strtotime($today) ){
				if($user_status[0]['user_status'] == 'inactive'){
					return 0;	
				}else{
					return 1;
				}
			}else{
				return 0;	
			}
		}
	}

}
//echo time()-24*60*60*1;
?>