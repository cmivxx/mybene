<?php

class Upload {
	
    var $uploaded = "";
    public $notUploaded;
    var $uploadCount = 0;
    var $uploadedFiles = "";
    var $uploadDir = "";
	var $badFileExt = array("php", "php2", "php3", "php4", "php5", "asp", "inc", "txt", "wma", "mov", "js", "exe", "jsp", 
								"map", "obj", " ", "", "html", "htm", "mpu", "wav", "cur", "ani", "fla", "swf");    
    function Upload($dir){
        $this->uploadDir = $dir;		
    }
    
    function doesFileExist($file){
		
        if(file_exists($this->uploadDir . $file)) {
		
            $file = $file . '_' . rand(100, 200) . $this->getExtension($file);
			
		}
        
		return $file;    
    }
    
    function getExtension($file){

		$fileInfo 	= pathinfo($file);
     	$fileExt 	= $fileInfo['extension'];

		if (!in_array($fileInfo['extension'], $this->badFileExt)){
		
			if ($fileExt == 'docx') { $fileExt = 'Word Doc'; }
			elseif ($fileExt == 'doc') { $fileExt = 'Word Doc'; }
			elseif ($fileExt == 'pdf' || $fileExt == 'PDF') { $fileExt = 'pdf'; }
			elseif ($fileExt == 'jpg') { $fileExt = 'Image'; }
			elseif ($fileExt == 'gif') { $fileExt = 'Image'; }
			elseif ($fileExt == 'bmp') { $fileExt = 'Image'; }
		
		return $fileExt;

		}
		
    }
    
    function uploadFiles($filename, $fileTemp){
        
//			$attachment_name = $this->doesFileExist($filename);
			move_uploaded_file($fileTemp, $this->uploadDir . '/' . $filename );
			chmod($this->uploadDir . $attachment_name, 0777);
			$this->uploadedFiles = $attachment_name;

	}
    
    function getUploaded(){
	
        return $this->uploaded;
    
	}
    
}

?>