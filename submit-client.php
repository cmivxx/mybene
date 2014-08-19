<?php

function add_client() { 

	if (  LOGIN_PAGE == NULL ||  MEMBER_PAGE == NULL ) { 
		echo ERR_MSG_01;
	}		
		global $wpdb;
		
		if($_GET['id']) {
			$client = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id=".$_GET['id']);
		}
		
		if (isset($_POST['submit'])) {
			
			if($_GET['id']) {
				
				$first_name = esc_attr( $_POST['first_name'] );
				$last_name = esc_attr( $_POST['last_name'] );
				$email = esc_attr( $_POST['email'] );
				$company = esc_attr( $_POST['company'] );
				$street = esc_attr( $_POST['street'] );
				$city = esc_attr( $_POST['city'] );
				$state = esc_attr( $_POST['state'] );
				$zip = esc_attr( $_POST['zip'] );
				$phone = esc_attr( $_POST['phone'] );
				$username = esc_attr( $_POST['username'] );
				$password = encode5t( $_POST['password'] );
				
				$wpdb->query("UPDATE ".CLIENTS_TABLE." SET first_name='".$first_name."', last_name='".$last_name."', email='".$email."', company='".$company."', street='".$street."', city='".$city."', state='".$state."', zip='".$zip."', username='".$username."', password='".$password."', phone='".$phone."'  WHERE ID = ".$_GET['id']." ");
				
				$client = $wpdb->get_row("SELECT * FROM ".CLIENTS_TABLE." WHERE id=".$_GET['id']);
				
				?>

<div class="updated"><p><strong>Client was updated.</strong></p></div> <?php
									
			} else {
						
				$clientPath = createPath(UPLOAD_DIRECTORY);
				
				$first_name = esc_attr( $_POST['first_name'] );
				$last_name = esc_attr( $_POST['last_name'] );
				$email = esc_attr( $_POST['email'] );
				$company = esc_attr( $_POST['company'] );
				$street = esc_attr( $_POST['street'] );
				$city = esc_attr( $_POST['city'] );
				$state = esc_attr( $_POST['state'] );
				$zip = esc_attr( $_POST['zip'] );
				$phone = esc_attr( $_POST['phone'] );
				$username = esc_attr( $_POST['username'] );
				$password = encode5t( $_POST['password'] );

				$wpdb->query("INSERT INTO ".CLIENTS_TABLE." ( first_name, last_name, email, company, street, city, state, zip, phone, path, username, password ) 
							  VALUES ( '".$first_name."', '".$last_name."', '".$email."', '".$company."', '".$street."', '".$city."', '".$state."', '".$zip."', '".$phone."', '".$clientPath."', '".$username."', '".$password."' ) ");
				
				$client_id = $wpdb->insert_id;
				
				// Insert Welcome Page per client
				$pageName 	= 'Welcome Page';
				$content 	= 'This is the insureds welcome page. What you you like to tell the insured?';
				$welcome 	= '1';
				
				$wpdb->query("INSERT INTO ".PAGES_TABLE." ( welcome, client_id, page_name, html, page_order ) VALUES ( '".$welcome."', '".$client_id."', '".$pageName."', '".$content."','0' ) ");
								
				redirectPage('?page=benefits_page', $client_id );
										
			}
			
		}
	
?>
	<script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery-2.1.1.js" ></script>
					<script type="text/javascript" src="<?php echo JS_DIRECTORY ?>mybene_functions.js" ></script>
				   	<script type="text/javascript" src="<?php echo JS_DIRECTORY ?>jquery.validationEngine.js" ></script>
					<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>validationEngine.jquery.css" media="screen" title="no title" charset="utf-8" />
					<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIRECTORY ?>plugin_styles.css" media="screen" title="no title" charset="utf-8" />
                    
            <div class="wrap">
                
            <h2><?php if ($_GET['id']) { echo 'Update'; } else { echo 'Add'; } ?> Insured</h2>
            
                <form action="" method="post" >
                
                    <table width="741" cellspacing="4">
                        <tr>
                          <td align="right">Company Name:</td>
                          <td colspan="6"><input name="company" type="text" id="company" class="validate[required]" value="<?php echo $client->company; ?>" size="50" />
                          <span class="required">(required)</span></td>
                        </tr>
                        <tr>
                            <td width="146" align="right">First Name:</td>
                            <td width="144"><label>
                              <input type="text" name="first_name" id="first_name" value="<?php echo $client->first_name; ?>">
                          </label></td>
                            <td colspan="3" align="right">Last Name:</td>
                            <td width="223"><input type="text" name="last_name" id="last_name" value="<?php echo $client->last_name; ?>" /></td>
                            <td width="61">&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td colspan="3" align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                        <tr>
                          <td align="right">Street:</td>
                          <td colspan="6"><input name="street" type="text" id="street" value="<?php echo $client->street; ?>" size="50" /> 
                          <span class="required"></span></td>
                      </tr>
                        <tr>
                          <td align="right">City:</td>
                          <td><input type="text" name="city" id="city" value="<?php echo $client->city; ?>" /></td>
                          <td width="34" align="right">State:</td>
                          <td width="60" align="left"><input name="state" type="text" id="state" value="<?php echo $client->state; ?>" size="10" /></td>
                          <td width="25" align="right">Zip:</td>
                          <td><input name="zip" type="text" id="zip" value="<?php echo $client->zip; ?>" size="20" /> 
                          <span class="required"></span></td>
                          <td>&nbsp;</td>
                      </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td colspan="3" align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                        <tr>
                          <td align="right">Phone:</td>
                          <td><input type="text" name="phone" id="phone" value="<?php echo $client->phone; ?>" /></td>
                          <td colspan="3" align="left">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                        <tr>
                          <td colspan="7"><hr style="border:none; height:1px; background-color:#ccc;" /></td>
                      </tr>
                        <tr>
                          <td colspan="7"><h4>User Login Information</h4></td>
                      </tr>
                        <tr>
                          <td align="right">Username:</td>
                          <td><input type="text" name="email" class="validate[required]" id="email" value="<?php echo $client->email; ?>" /></td>
                          <td colspan="3" align="left"><span class="required">(required)</span></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                        <tr>
                          <td align="right">Password</td>
                          <td><input type="text" name="password" class="validate[required]" id="password" value="<?php echo decode5t( $client->password ); ?>"></td>
                          <td colspan="3" align="left"><span class="required">(required)</span></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td colspan="3" align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td><p class="submit"> <input type="submit" class="" name="submit" value="Submit"/> </p>
            </td>
                          <td colspan="3" align="right">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                    </table>
                    
                </form>
               
            </div>

<?php
}

?>
