<?php

function myBenAdmin() { 

		global $wpdb;
		
	if (  LOGIN_PAGE == NULL ||  MEMBER_PAGE == NULL ) { 
		echo ERR_MSG_01;
	}		
		$admin = $wpdb->get_row("SELECT * FROM ".ADMIN_TABLE." ");

		if (isset($_POST['submit'])) {

					$page_login = esc_attr( $_POST['page_login'] );
					$page_benefits = esc_attr( $_POST['page_benefits'] );
					
				
			if ( $admin != NULL ) {
													
				$wpdb->query("UPDATE ".ADMIN_TABLE." SET page_login='".$page_login."', page_benefits='".$page_benefits."' WHERE aid=".$admin->aid."");								
				$admin = $wpdb->get_row("SELECT * FROM ".ADMIN_TABLE." ");
	
			} else {

				$wpdb->query("INSERT INTO ".ADMIN_TABLE."( page_login, page_benefits ) VALUES ('".$page_login."', '".$page_benefits."') ");
				$admin = $wpdb->get_row("SELECT * FROM ".ADMIN_TABLE." ");
				
				?>

<div class="updated">
  <p><strong>Updated.</strong></p></div> 
  
  <?php } 
  
}
  
  ?>
        <script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery-2.1.1.js" ></script>
        <script type="text/javascript" src="<?php echo JS_DIRECTORY ?>mybene_functions.js" ></script>
        <script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery.validationEngine.js" ></script>
        <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>validationEngine.jquery.css" media="screen" title="no title" charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>plugin_styles.css" media="screen" title="no title" charset="utf-8" />
	
            <div class="wrap">
                
            <h2>myBenefits Admin</h2>
            
                <form action="" method="post" >
                
                    <table width="741" cellspacing="4">
                        <tr>
                          <td colspan="2">After you have added the myBenefits Template pages (Members and Login Pages) you are going to assign the post / page numbers in the below boxes. This number is the Permalink that is located under the Page title of each page. And looks something like ?page_id=???</td>
                        </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="right">myBenefits Login Page:</td>
                          <td width="506"><input name="page_login" type="text" id="page_login" class="validate[required]" value="<?php echo $admin->page_login; ?>" size="10" /></td>
                        </tr>
                        <tr>
                            <td width="217" align="right">myBenefits Members Page:</td>
                            <td><input name="page_benefits" type="text" id="page_benefits" class="validate[required]" value="<?php echo $admin->page_benefits; ?>" size="10" /></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="right">&nbsp;</td>
                      </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td><p class="submit"> <input type="submit" class="" name="submit" value="Submit"/> </p>
            </td>
                      </tr>
                    </table>
                    
                </form>
               
            </div>

<?php
}

?>
