<?php 

/*

	List Clients 

*/

function list_clients() {
		
	global $wpdb;
	
	if (  LOGIN_PAGE == NULL ||  MEMBER_PAGE == NULL ) { 
		echo ERR_MSG_01;
	}		

	
		if(isset($_GET['page'])) {
				$cur_admin_page = $_GET['page'];
		}
		
		if($_POST['task'] == 'delgroup'){
			if(isset($_POST['delete']))
			
			$del_ids = $_POST['delete'];
						
			if ( $del_ids == NULL ) {
				?><div class="updated">
                
  <p><strong>You must first select an insured(s) to delete</strong></p></div> <?php
			} else {
				foreach($del_ids as $del_id){
					
					$client_path = $wpdb->get_row("SELECT path FROM ".CLIENTS_TABLE." WHERE id=".$del_id);
					deleteDir( UPLOAD_DIRECTORY.$client_path->path.'/' );
					
					$wpdb->query("DELETE FROM ".CLIENTS_TABLE." WHERE id = $del_id LIMIT 1");
					$wpdb->query("DELETE FROM ".PAGES_TABLE." WHERE client_id = $del_id");
					$wpdb->query("DELETE FROM ".FILES_TABLE." WHERE client_id = $del_id");
				}
					
				?><div class="updated">
    <p><strong>Insured(s) was deleted</strong></p></div> <?php
			}
		}
			
	
 	$clients_list = $wpdb->get_results("SELECT * FROM ".CLIENTS_TABLE." ORDER BY company ASC ");
	
				
	  echo '<div class="wrap">';
		echo "<h2>Policy Briefcase Insureds List</h2>";

?>

        <form name="form1" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<input type="hidden" value="delgroup" name="task"/>
        
        <p class="submit">
        <input type="submit" class="button-secondary delete" name="deleteit" value="Delete Client(s)" onclick="return confirm('WARNING: Are you sure you want to delete? This action will remove all related information for this client including all myBenefits files.')"/>
        </p>

    <table class="widefat">
                <thead>
                    <tr class="thead">
                        <th scope="col"></th>
                        <th scope="col">Company Name</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Address</th>
                        <th scope="col">City</th>
                        <th scope="col">State</th>
                        <th scope="col">Zip</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(isset($clients_list)){
                        foreach($clients_list as $clients){
                    
                    ?>
                    
                    <tr class="alternate" id="group-<?php echo $clients->id; ?>">
                    
                        <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $clients->id; ?>" name="delete[]"/></th>
                        
                        <td><?php echo $clients->company; ?></td>
                        <td><?php echo $clients->first_name; ?></td>
                        <td><?php echo $clients->last_name; ?></td>
                        <td><a href="mailto:<?php echo $clients->email; ?>"><?php echo $clients->email; ?></a></td>
                        <td><?php echo $clients->street; ?></td>
                        <td><?php echo $clients->city; ?></td>
                        <td><?php echo $clients->state; ?></td>
                        <td><?php echo $clients->zip; ?></td>
                        <td>
                        <a href="?page=client_file_check&id=<?php echo $clients->id; ?>">&check;</a>
                        <a href="?page=add_client&id=<?php echo $clients->id; ?>">
                            <img src="<?php echo IMG_DIRECTORY.'edit.png' ?>" align="absmiddle" title="Edit Client Information"></a>  
                        <a href="?page=benefits_page&id=<?php echo $clients->id; ?>">
                            <img src="<?php echo IMG_DIRECTORY.'active.png' ?>" align="absmiddle" title="Add/Edit Client Benefits"></a> 
                        </td>
                    </tr>
                        <?php
                        }
                    }
                    ?>
                </tbody>
            </table>        
                </form>
</div>

<?php }

?>