<?php

global $wpdb;

if ( $_SESSION['uid'] != NULL ) {	
	

    $pages_list = $wpdb->get_results("SELECT * FROM ".PAGES_TABLE." WHERE client_id='".$_SESSION['uid']"' AND status='0'");
	$userinfo = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id='".$_SESSION['uid']."'");
    
    if(!$pages_list == NULL ){
		
		echo '
				<h2>myBenefits</h2>
					<h4>Welcome '.$userinfo->company.'</h4>
					
				<ul>';
		
       foreach($pages_list as $benPage){
           echo '<li><a href="'.MEMBER_PAGE.'&pid='.$benPage->pid.'&uid='.$_SESSION['uid'].'">'.$benPage->page_name.'</a></li>';
        }
       
		   
		echo '</ul>';
		

		
	}


}
?>