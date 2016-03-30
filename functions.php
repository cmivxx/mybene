<?php

function redirectPage($location, $id) {
?>
	<script type="text/javascript"> window.location = "<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php'.$location.'&id='.$id; ?>" </script>
<?php
	exit;
}

function member_login_redirect($location, $id, $welcome_page_id) {
?>
	<script type="text/javascript"> window.location = "<?php echo get_bloginfo('wpurl').$location.'&pid='.$welcome_page_id.'&uid='.$id.'&mybene=1'; ?>" </script>
<?php
	exit;
}

function redirect($location) {
?>
	<script type="text/javascript"> window.location = "<?php echo get_bloginfo('wpurl').$location ?>" </script>
<?php
	exit;
}

function encode5t($str) {
	for($i=0; $i<5;$i++) {
		$str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
	}
	return $str;
}
		
//function to decrypt the Passord

function decode5t($str) {
	for($i=0; $i<5;$i++) {
		$str=base64_decode(strrev($str)); //apply base64 first and then reverse the string}
	}
	return $str;
}

function createPath($uploadPath) {
	
	global $wpdb;

// Create client file directory. If directory already exists, try a new random number. Die after 4000 attempts.
	do {
		$randNum = rand(100, 2000);
		$Path = $randNum;
		if ($tries == 4000)
			die ('ERROR 0x00A450 - Unable to create directories. Permissions Issue?');
			$tries = $tries + 1;
	} while (!mkdir('./'.$uploadPath.'/'. $wpdb->prefix.$Path, 0777));			
	
	chmod('./'.$uploadPath.'/'. $wpdb->prefix.$Path, 0777);
	return $wpdb->prefix.$Path;		
}

function deleteDir($dir){
   if (substr($dir, strlen($dir)-1, 1) != '/')
       $dir .= '/';
   if ($handle = opendir($dir)){  
       while ($obj = readdir($handle)){
           if ($obj != '.' && $obj != '..'){
               if (is_dir($dir.$obj)){
                   if (!deleteDir($dir.$obj))
                       return false;
					   }
               elseif (is_file($dir.$obj)){
                   if (!unlink($dir.$obj))
                       return false;
               }
           }
       }
	closedir($handle);
	   if (!@rmdir($dir))
           return false;
       return true;
   }
   return false;
} 
?>
