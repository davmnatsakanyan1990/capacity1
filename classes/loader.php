<?php
	class loader{
		function  loader(){
			//  Defaults Include 
			if(file_exists(MAIN_DIR.CLASSES.'database.class.php')){
				require_once(MAIN_DIR.CLASSES.'database.class.php') ;
				$dclass = new database();
			}else{
				die('Database Class File not Exists.');
			}
			if(file_exists(MAIN_DIR.CONFIG.'message.php')){
				require_once(MAIN_DIR.CONFIG.'message.php') ;
			}else{
				die('Message File not Exists.');
			}
			if(file_exists(MAIN_DIR.CLASSES.'paging.class.php')){
				require_once(MAIN_DIR.CLASSES.'paging.class.php');
			}else{
				die('Paging Class File not Exists.');
			}
			if(file_exists(MAIN_DIR.CLASSES.'general.class.php')){
				require_once(MAIN_DIR.CLASSES.'general.class.php');
				$gnrl =  new  general;
			}else{
				die('Genearl Class File not Exists.');
			}
		}
		//  Classess Include
		public  function  includeclasses($label =  ''){
			if($label !=  ''){		
				if(file_exists(MAIN_DIR.DATABASE.$label.".php")){
					@require_once(MAIN_DIR.DATABASE.$label.".php");					
					$objCustmer =  new $label($label);
					return($objCustmer);
				}else {					
					die($label.'.php'.' Class File not Exists.');
				}
			}
		}
		//  Css Add
		public  function  includecss($label = ''){
			if($label !=  ''){		
				if(is_array($label)){
					foreach($label as $labArr){
					echo  '<link href="'.SITE_URL.CSSPATH.$labArr.'.css" rel="stylesheet" type="text/css" media="screen" />';	
					}
				}else{
				echo  '<link href="'.SITE_URL.CSSPATH.$label.'.css" rel="stylesheet" type="text/css" media="screen" />';	
				}
			}else{
				$fileAll  =  scandir(CSSPATH);	
				foreach($fileAll  as $filename){
					$ext  = end(explode('.',$filename));				
					if($ext ==  'css'  ||  $ext ==  'CSS')
						echo  '<link href="'.SITE_URL.CSSPATH.$filename.'" rel="stylesheet" type="text/css" media="screen" />';
				}					
			}
		}
		// Js  Add
		public function  includejs($label = '' ){
			if($label !=  ''){
				if(is_array($label))
				{
					foreach($label as $labArr)
					{
					echo  '<script src="'.SITE_URL.JSPATH.$labArr.'.js"  type="text/javascript"></script>';							
					}
				}else{
					echo  '<script src="'.SITE_URL.JSPATH.$label.'.js"  type="text/javascript"></script>';						
				}
			}else{
				$fileAll  =  scandir(JSPATH);	
				foreach($fileAll  as $filename){
					$ext  = end(explode('.',$filename));				
					if($ext ==  'js'  ||  $ext ==  'JS')
					echo  '<script src="'.SITE_URL.JSPATH.$filename.'"  type="text/javascript"></script>';		
				}
			}
		}
		//  Add Other Like footer,header,navigation Or Any...
		public function  includeother($label = ''){
			if($label !=  ''  ){
				if(file_exists(OTHERPATH.$label.".php")){
					require_once(OTHERPATH.$label.".php");		
				}else{
					die($label.".php File not exists.");
					
				}
			}else{
					$fileAll  =  scandir(OTHERPATH);	
				foreach($fileAll  as $filename){
					$ext  = end(explode('.',$filename));				
					if($ext ==  'php'  ||  $ext ==  'PHP')
						require_once(OTHERPATH.$filename);		
				}

			}
				
		}
		// Ramain Compressed All Any...  Don't  Use it
		public  function  includecompresscss(){
			$styles  =  scandir(CSSPATH);
			if(!empty($styles)){
				foreach ($styles as $style) {
					$ext =  end(explode('.',$style));
					if($ext  ==  'css' ||  $ext  ==  'CSS')
					echo  '<link href="'.CSSPATH.$style.'" rel="stylesheet" type="text/css" media="screen" />';
				}
			}		
		}
	}
?>