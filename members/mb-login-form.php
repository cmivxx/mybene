<?php

	require_once(getcwd().'/wp-content/plugins/mybene/functions.php');
	require_once(getcwd().'/wp-content/plugins/mybene/constant.php');
	require_once(getcwd().'/wp-content/plugins/mybene/session.php');
	
	global $wpdb;

	$session = new Session();
		
if(isset($_POST['logout-submit'])) {

	$session->logout();
	$url = bloginfo('wpurl');
	redirect($url);

}
	
if (!$session->is_logged_in()) {	
	
	if(isset($_POST['login-submit'])) {
		   
		   global $wpdb;

		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		$encodedPass = encode5t($password);
				
		$found_user = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE email='".$email."' AND password='".$encodedPass."' ");
		  if ($found_user) {  
				$welcomePage = $wpdb->get_row("SELECT * FROM ".PAGES_TABLE." WHERE client_id=".$found_user->id." AND welcome=1");
				$session->login($found_user);
				member_login_redirect( MEMBER_PAGE, $found_user->id,$welcomePage->pid);
				exit;
		  } else {  
			$msg = 'username and/or password doesn\'t exist. Please try again.';
		  }
	
	
	} 
				
?>
        
        <style>
		
		br { padding:0px; }
		#ab_container input[type=text], #ab_container input[type=password], #ab_container textarea {
			display:block;
			color:#333;
			margin: 0px;
			border: 1px solid #666;
			-moz-border-radius: 4px;
			-webkit-border-radius: 4px;
			padding: .5em;
			font-family:Verdana, Geneva, sans-serif;
			font-size:12px;
		}
			
		#message {
			 background:#F9F; border:#F36 2px solid; padding:8px; margin:0px; color:#333
		}
		</style>
  
 <div id="ab_container">       
      
<?php if($msg) { echo '<div id="message" >'.$msg.'</div>'; } ?>
              
       <form name="loginform" id="loginform" action="" method="post">

            <label for="log">Username</label> 
            <input name="email" type="text" id="" tabindex="10" value="" size="25" />
            
      <label for="pwd">Password</label> 
            <input name="password" type="password" id="" tabindex="20" value="" size="25" />
            
    <br />
              <input type="submit" name="login-submit" id="" value="&nbsp;" style="
    background: url(http://demo4.intellagentbenefits.com/wp-content/uploads/2014/08/Sign-In-Button.png) no-repeat;
    width:  67px;
    height: 28px;
    border:  none;" />
              
             <!-- <a href="#">Lost password?</a> -->
              
       </form>
	</div>
	<div style="clear:both"></div>
    
<?php } else { 

	$userinfo = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id='".$_SESSION['uid']."'");

?>
    
    <div style="margin:10px;">
    				
	  <h4>Benefits Navigation <!-- ?=$userinfo->company; ? --></h4>
    
    <?php
	
    if ( $_SESSION['uid'] != NULL ) {	
	

    $pages_list = $wpdb->get_results("SELECT * FROM ".PAGES_TABLE." WHERE client_id=".$_SESSION['uid']." ORDER BY page_order ASC");
	$userinfo = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id='".$_SESSION['uid']."'");
    
    if(!$pages_list == NULL ){
		
		echo '<ul>';
		
       foreach($pages_list as $benPage){
           echo '<li><a href="'.MEMBER_PAGE.'&pid='.$benPage->pid.'&uid='.$_SESSION['uid'].'&mybene=1">'.$benPage->page_name.'</a></li>';
        }
       
		   
		echo '</ul>';
		

		
	}


}

?>
	<br />
	<br />
	<br />

                    
	<form name="logout" id="logout" action="" method="post">
    	<input type="submit" name="logout-submit" id="" value="Sign Out" />
	</form>
    
    
    
    </div>
<?php	}  ?>
