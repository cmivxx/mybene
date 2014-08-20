<?php

function client_file_check() { 

  global $wpdb;

  // Check for blank admin fields and error out if not set yet or error retrieving.
  if ( LOGIN_PAGE == NULL ||  MEMBER_PAGE == NULL ) { 
    echo ERR_MSG_01;
  } 

  $client = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id=".$_GET['id']);
  $files_list = $wpdb->get_results("SELECT * FROM ".FILES_TABLE." WHERE client_id=".$_GET['id']);

?>
        <script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery-2.1.1.js" ></script>
        <script type="text/javascript" src="<?php echo JS_DIRECTORY ?>mybene_functions.js" ></script>
        <script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery.validationEngine.js" ></script>
        <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>validationEngine.jquery.css" media="screen" title="no title" charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>plugin_styles.css" media="screen" title="no title" charset="utf-8" />
	
            <div class="wrap">
                
            <h2>Client File Check</h2>
            
            <p>This page checks to see if all the files you have associated with this client's account actually exist on the server.  Files can be missing for numerous reasons, however if one is missing, simply proceed to that clients file manager, delete the listed file and re-upload a new copy.</p>

            <p><b>File List</b></p>

              <ul class="file_list">

<?php
  if(isset($files_list)){
    foreach($files_list as $file) {
      $fdir = UPLOAD_DIRECTORY.$client->path.'/'.$file->file_name;
      if (file_exists($fdir)) {
        $message = "<font style='color: green; font-weight: bold;'>&check;</font>";
      } else {
        $message = "<font style='color: red; font-weight: bold;'>&#10008;</font>";
      }
?>

                <li><?php echo $message; ?> : <?php echo $file->file_name; ?></li>

<?php
    }
  }
?>

              </ul>
               
            </div>

<?php
}

?>
