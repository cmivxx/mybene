<?php


    if($_GET['mybene'] == '1')
    {		
	if (!$session->is_logged_in()) {
	 redirect(LOGIN_PAGE);
	} 

	if($_GET['pid']) {
	
			$clientInfo = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id=".$_GET['uid']);		
			$pageInfo = $wpdb->get_row("SELECT * FROM ".PAGES_TABLE." WHERE pid=".$_GET['pid']." AND client_id=".$_SESSION['uid'] );
			
			echo $pageInfo->html;
	
	} else {
			$clientInfo = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id=".$_GET['uid']);		
		
			$welcomePage = $wpdb->get_row("SELECT * FROM ".PAGES_TABLE." WHERE client_id=".$_SESSION['uid']." AND welcome=1");
			
			echo $welcomePage->html;
			
		 
	}
    }		
		
		
?>
