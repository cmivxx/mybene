<?php

global $wpdb;
		   

define('DIRECTORY_NAME'				,'mybene');
define('PAGE_LOCATION'				,'?page_id=');
define('BASE_URL'				,'../wp-content/plugins/'.DIRECTORY_NAME.'/');
define('MCE_PLUGIN_URL'				,'../wp-content/plugins/'.DIRECTORY_NAME.'/js/');
define('IMG_DIRECTORY'				,'../wp-content/plugins/'.DIRECTORY_NAME.'/img/');
define('JS_DIRECTORY'				,'../wp-content/plugins/'.DIRECTORY_NAME.'/js/');
define('TINYMCE_PLUGIN_DIRECTORY'	,'../wp-content/plugins/'.DIRECTORY_NAME.'/js/');
define('CSS_DIRECTORY'				,'../wp-content/plugins/'.DIRECTORY_NAME.'/css/');
define('UPLOAD_DIRECTORY'			,'../wp-content/plugins/'.DIRECTORY_NAME.'/client-files/');
define('LINK_UPLOAD_DIRECTORY'		,get_bloginfo('wpurl').'/wp-content/plugins/'.DIRECTORY_NAME.'/client-files/');

## Define Tables
define('CLIENTS_TABLE'				,$wpdb->prefix.'mybene_clients');
define('FILES_TABLE'				,$wpdb->prefix.'mybene_files');
define('PAGES_TABLE'				,$wpdb->prefix.'mybene_pages');
define('ADMIN_TABLE'				,$wpdb->prefix.'mybene_admin');


	$page_num = $wpdb->get_row("SELECT * FROM ".ADMIN_TABLE." ");
	
## Define Login and Member page id's.
define('LOGIN_PAGE'					,PAGE_LOCATION.$page_num->page_login);
define('MEMBER_PAGE'				,PAGE_LOCATION.$page_num->page_benefits);

define('ERR_MSG_01'					,'<div style="background:#F9F; border:#F36 2px solid; padding:8px; margin:5px; color:#333"> myBenefits Error:  Please assign page numbers in the myBenefits Admin section for the Members and Login pages once you have created them.  Click on the Admin link in the myBenfits panel for more information.  </div>');



?>
