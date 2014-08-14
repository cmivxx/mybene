<?php

function benefits_page() { 
	
   	global $wpdb;
	global $current_user;
	get_currentuserinfo();

// Preload TinyMCE Stuff  (03/04/13)

		add_action('admin_init', 'editor_admin_init');
		add_action('admin_head', 'editor_admin_head');

		function editor_admin_init() {
			wp_enqueue_script('word-count');
			wp_enqueue_script('post');
			wp_enqueue_script('editor');
			wp_enqueue_script('media-upload');
		}
		
		function editor_admin_head() {
			wp_tiny_mce();
		}


	$client = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id=".$_GET['id']);
	
// Post editor content
	if($_GET['editpage'] == 'true') {
		$pageInfo = $wpdb->get_row("SELECT * FROM ".PAGES_TABLE." WHERE pid=".$_GET['pid']);
	} 
	
	if($_POST['submit-editor-content']) {
	
		if($_GET['editpage'] == 'true') {	
			$pageName = esc_attr( $_POST['page_name']);
			$content = wpautop( $_POST['editor'], $br = 1 );
			
			$wpdb->query("UPDATE ".PAGES_TABLE." SET page_name='".$pageName."', html='".$content."' WHERE pid=".$_GET['pid']." ");
			echo mysql_error();

		$pageInfo = $wpdb->get_row("SELECT * FROM ".PAGES_TABLE." WHERE pid=".$_GET['pid']);
		
		?><div class="updated"><p><strong>Page was updated</strong></p></div> <?php

		} else {
		
			$pageName = esc_attr( $_POST['page_name']);
			$content = wpautop( $_POST['editor'], $br = 1 );
			$next = $wpdb->get_row("SELECT count(*) FROM ".PAGES_TABLE." WHERE client_id=".$client->id);
			$next += 1;
			
			$wpdb->query("INSERT INTO ".PAGES_TABLE." ( client_id, page_name, html, page_order) VALUES ( '".$_GET['id']."', '".$pageName."', '".$content."','".$next."' ) ");
		
		?><div class="updated"><p><strong>Page was created</strong></p></div> <?php
		}
	}

// File Delete Operation

		if($_POST['task'] == 'delgroupFiles'){
			if(isset($_POST['deleteFile']))
			
			$del_ids = $_POST['deleteFile'];
			if ( $del_ids == NULL ) {
				?><div class="updated"><p><strong>You must select a file to delete first</strong></p></div> <?php
			} else {
				foreach($del_ids as $del_id){
					$file = $wpdb->get_row("SELECT * FROM ".FILES_TABLE." WHERE id=".$del_id);
					$deleteFile = UPLOAD_DIRECTORY.$client->path.'/'.$file->file_name;
					unlink($deleteFile);
					$wpdb->query("DELETE FROM ".FILES_TABLE." WHERE id = $del_id LIMIT 1");
				}
				?><div class="updated"><p><strong>File was deleted</strong></p></div> <?php
			}
		}
// --------- End file delete operation ----------------

// Page Delete Operation

		if($_POST['task'] == 'delgroupPages'){
			if(isset($_POST['deletePage']))
			{	
				$del_ids = $_POST['deletePage'];
				if ( $del_ids == NULL ) {
					?><div class="updated"><p><strong>You must select a page to delete first</strong></p></div> <?php
				} else {
					foreach($del_ids as $del_id){
						$wpdb->query("DELETE FROM ".PAGES_TABLE." WHERE pid = $del_id LIMIT 1");
					}
				?><div class="updated"><p><strong>Page was deleted</strong></p></div> <?php
				}
			}
			if(isset($_POST['page_order']))
			{
				$updatePages = $_POST['page_order'];
				foreach($updatePages as $pid=>$value){
					$wpdb->query("UPDATE ".PAGES_TABLE." SET page_order='".$value."' WHERE pid = $pid ");
				}?><div class="updated"><p><strong>Page order updated.</strong></div> <?php

			}

		}
// --------- End page delete operation ----------------

if (isset($_POST['uploadFile'])){
	
	if ( $_FILES['uploaded_file']['name'] == NULL ) { 
	
	?><div class="updated"><p><strong>Please choose a file to upload.</strong></p></div> <?php
	
	} else { 
	    
	$filename       = $_FILES['uploaded_file']['name'];
    $fileTemp       = $_FILES['uploaded_file']['tmp_name'];
    $uploadfile	    = ereg_replace('[^A-Za-z0-9.]', '-', $filename);
	
	$docPath = UPLOAD_DIRECTORY.$client->path;		

	// Upload file to directory	
	$upload = new Upload($docPath);
	$fileExt = $upload->getExtension($filename);
	
//			if ($fileExt == 'pdf' || $fileExt == 'PDF') {
				
				$uploadResult = $upload->uploadFiles($uploadfile, $fileTemp);
				$fileExt = $upload->getExtension($filename);
				$uploads = $upload->getUploaded();
					
				$wpdb->query("INSERT INTO ".FILES_TABLE." ( client_id, file_name ) 
							  VALUES ( '".$_GET['id']."', '".$uploadfile."' ) ");
			
				?><div class="updated"><p><strong>File was uploaded.</strong></p></div> <?php
//			} else {
//			}
	}
}

	$files_list = $wpdb->get_results("SELECT * FROM ".FILES_TABLE." WHERE client_id=".$_GET['id']);
	$pages_list = $wpdb->get_results("SELECT * FROM ".PAGES_TABLE." WHERE client_id=".$_GET['id']." ORDER BY page_order ASC");

?>
	<script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery-1.4.1.js" ></script>
					<script type="text/javascript" src="<?php echo JS_DIRECTORY ?>mybene_functions.js" ></script>
				   	<script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery.validationEngine.js" ></script>
					<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>validationEngine.jquery.css" media="screen" title="no title" charset="utf-8" />
					<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>plugin_styles.css" media="screen" title="no title" charset="utf-8" />

	<div class="wrap">
    	<h2>Policy Briefcase Manager for <?php echo $client->company; ?> </h2>
	
   	  <div style="float:left; width:675px; margin-left:0px; padding:0px;">
   	    <form method="post" action="<?php echo $_SERVER["../".DIRECTORY_NAME."/REQUEST_URI"]; ?>">
    	<table width="650" cellspacing="4">
          <tr>
            <td><strong><?php if($_GET['editpage'] == 'true') {echo 'Update';} else {echo 'Create';} ?> myBenefits Page</strong></td>
          </tr>
          <tr>
            <td><label>
              <input name="page_name" type="text" class="validate[required]" style="height:30px; font-size:18px" id="page_name" value="<?php echo $pageInfo->page_name; ?>" size="60" />
            </label></td>
          </tr>
          <tr>
            <td>  
                <?php the_editor($pageInfo->html, $id = 'editor', $prev_id = 'title', $media_buttons = true, $tab_index = 2) ?>
 		<script type="text/javascript">
                $(document).ready(function() {
                        $("#editor").width("100%");
                });
        	</script>

                
                
          <br />
         <span class="submit">
         <input type="submit" class="button-secondary" name="submit-editor-content" value="Save"/>
        </span>

            </td>
          </tr>
        </table>
     </form>
    	</div>
        
    	<div style="float:left; width:275px; margin-left:15px; padding:5px;">
    	<h3> Pages <a class="button add-new-h2" href="?page=benefits_page&id=<?php echo $_GET['id'] ?>">New Page</a>        </h3>
        <form name="form1" method="post" action="<?php echo $_SERVER["../my-benefits/REQUEST_URI"]; ?>">
		<input type="hidden" value="delgroupPages" name="task"/>      
                <table width="100%" class="widefat">
                  <thead>
                    <tr class="thead">
                      <th width="6%" scope="col"></th>
                      <th width="82%" align="left" scope="col">Page Name</th>
                      <th width="12%" scope="col"></th>
                      <th width="12%" scope="col"></th>
                  </tr>
                </thead>
                  <tbody>
                    <?php
					if(isset($pages_list)){
						foreach($pages_list as $benPage){
					
					?>
					<tr class="alternate" id="group-<?php echo $benPage->pid; ?>">
					  <th class="check-column" scope="row"><?php if( $benPage->welcome == '1' ) { 
					  echo ''; } else { echo '<input name="deletePage[]" type="checkbox" id="deletePage[]" value="' . $benPage->pid . '"/>'; } ?>
                      </th>
					  <td><?php echo $benPage->page_name; ?></td>
					  <td><a href="?page=benefits_page&editpage=true&pid=<?php echo $benPage->pid; ?>&id=<?php echo $client->id; ?>"> 
					  <img src="<?php echo IMG_DIRECTORY.'edit.png' ?>" align="absmiddle" title="Edit this page" /></a></td>
					  <td><?php if( $benPage->welcome == '1' ) {
                                          echo ''; } else {
					echo '<select id="page_order['.$benPage->pid.']" name="page_order['.$benPage->pid.']">'; 
					      for($cnt=1;$cnt<count($pages_list);$cnt++)
					      {
						echo '<option value="'.$cnt.'"';						if($cnt == $benPage->page_order)
							echo ' selected="selected"';
						echo '>'.$cnt.'</option>';
					      }

					  echo '</select>'; } ?></td>
				  </tr>
                    <?php
						}
					}
					?>
                </tbody>
              </table>
         <input type="submit" class="button-secondary delete" name="deleteit" value="Delete Page(s)" id="deleteit" onclick="return confirm('Are you sure you want to delete the page(s) you selected?')"/>
         <input type="submit" class="button-secondary update" name="updateit" value="Update Page Order" id="updateit"/>
      
      </form>


<script type="text/javascript">
function beginUpload() {
	$("#progressbar").removeClass("updateprogress");
	alert(progress);
	return true;
}
</script>
                    
        
        <hr style="border:none; height:1px; background-color:#ccc;" />
        
        <h3>File Manager</h3>
        <form action="" method="post" enctype="multipart/form-data">
          	<input type="file" name="uploaded_file"  />
       	  <input name="uploadFile" type="submit" class="submit-btn" value="Upload" onClick="beginUpload()" />
          <span style="padding-left:10px;" class="updateprogress" id="progressbar"><img src="<?php echo IMG_DIRECTORY ?>loader.gif" /></span>
        </form><br />

        <form name="form1" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<input type="hidden" value="delgroupFiles" name="task"/>      
        <table width="100%" class="widefat">
          <thead>
            <tr class="thead">
              <th width="6%" scope="col"></th>
              <th colspan="2" align="left" scope="col">File Name</th>
            </tr>
          </thead>
          <tbody>
            <?php
                    if(isset($files_list)){
                        foreach($files_list as $file){                    
            ?>
                    <tr class="alternate" id="group-<?php echo $file->id; ?>">
                      <th class="check-column" scope="row"><input name="deleteFile[]" type="checkbox" value="<?php echo $file->id; ?>"/></th>
                      <td width="87%"><a href="<?php echo UPLOAD_DIRECTORY.$client->path.'/'.$file->file_name; ?>" target="_blank"><?php echo $file->file_name; ?></a></td>
                      <td width="7%"><a onClick="addHtmlLink('<?php echo LINK_UPLOAD_DIRECTORY.$client->path.'/'.$file->file_name; ?>', '<?php echo $file->file_name; ?>')" href="#"> 
					  <img src="<?php echo IMG_DIRECTORY.'plus.png' ?>" align="absmiddle" title="Add file to page" /></a></td>
                    </tr>
            <?php
                        }
                    }
                    ?>
          </tbody>
        </table>
        <br />
         <span class="submit">
         <input type="submit" class="button-secondary delete" name="deleteit" value="Delete File(s)" id="deleteit" onclick="return confirm('Are you sure you want to delete the file(s) you selected?')"/>
        </span>
        
        </form>
        </div>
        
    
</div>

<?php
}
?>
