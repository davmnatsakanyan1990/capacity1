<?php
if (!empty($_FILES)  ||  $_REQUEST['folder'] !=  '' ) {
	$tempFile = $_FILES['Filedata']['tmp_name'];

    switch ($_FILES['Filedata']['error'])
    {
         case 0:
                //$msg = "No Error (" . print_r($_GET) .")"; // comment this out if you don't want a message to appear on success.
                break;
         case 1:
                $msg = "The file is bigger than this PHP installation allows";
                break;
          case 2:
                $msg = "The file is bigger than this form allows";
                break;
           case 3:
                $msg = "Only part of the file was uploaded";
                break;
           case 4:
                $msg = "No file was uploaded";
                break;
           case 6:
                $msg = "Missing a temporary folder";
                break;
           case 7:
                $msg = "Failed to write file to disk";
                break;
           case 8:
                $msg = "File upload stopped by extension";
                break;
           default:
                $msg = "unknown error ".$_FILES['Filedata']['error'];
                break;
    }

    if ($msg)
        $stringData = "Error: ".$_FILES['Filedata']['error']." Error Info: ".$msg;
    else{
		
		$targetFolder = '/upload/'.$_REQUEST['folder']; // Relative to the root
			if (!empty($_FILES)) {
				$tempFile = $_FILES['Filedata']['tmp_name'];
				$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
				$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
				
				// Validate the file type
				$fileTypes = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG'); // File extensions
				$fileParts = pathinfo($_FILES['Filedata']['name']);
				
				if (in_array($fileParts['extension'],$fileTypes)) {
					move_uploaded_file($tempFile,$targetFile);
					//echo '1';
					echo $_FILES['Filedata']['name'];
				} else {
					echo 'Invalid file type.';
				}
			}
       $stringData = "1"; // This is required for onComplete to fire on Mac OSX
	}
    //echo $stringData;

}
//echo "1";
?>