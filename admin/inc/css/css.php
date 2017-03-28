<?php
header("Content-type: text/css");
    $styles  =  scandir(CSSPATH."css/");
    if(!empty($style)){
		foreach ($styles as $style) {
			$ext =  end(explode('.',$style));
			if($ext  ==  'css' ||  $ext  ==  'CSS')
				include CSSPATH . $style . '.css';
		}
	}
?>