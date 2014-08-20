<?php
/*
Plugin Name: myBenefits Plugin
Plugin URI: http://www.intellagentbenefits.com
Description: 
Version: 2.0.1a
Author: Intelagent Benefit Solutions
Author URI: http://www.intellagentbenefits.com
*/
	
	// This is the main file that is used when activating and deavtivatting this plugin.  
	// It is used to control the pages that are displayed when the user clicks a link in the admin menu.  
	// Uses the case switch at the bottom of this file to display the pages.  The functions for the other 
	// pages could be in this one file but are seperated for simpicity.

	//Load functions used throughout the plugin 
	require_once('functions.php');
	require_once('constant.php');
	require_once('upload.class.php');
	require_once('session.php');
	
	// Include other pages used in the plugin
	include('list-clients.php');
	include('submit-client.php');
	include('client-benefits.php');
	include('myben-admin.php');
	include('client_file_check.php');

	global $wpdb;
	global $blog_id;
//	
//	global $current_user;
//	get_currentuserinfo();
//	
	
	$db_version = "1.0";
	
	// Register activation hook by calling setup_plugin function.  This function is called when the user activates the plugin
	register_activation_hook( __FILE__, 'setup_plugin' );
	// Register unistall hook by calling unistall function. This function is called when the user unactivates the plogin
	register_deactivation_hook(__FILE__, 'uninstall');
	
	// This action is used to load the add_menu function. This is the admin menu and is loaded when the plugin is activated.
	add_action('admin_menu', 'add_menus');

// Sidebar Widget
	add_action('widgets_init', create_function('', 'return register_widget("LoginWidget");'));
/**
 * LoginWidget Class
 */
class LoginWidget extends WP_Widget {
    /** constructor */
    function LoginWidget() {
        parent::WP_Widget(false, $name = 'Policy Briefcase Login');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        
	    echo $before_widget; 
	    if ( $title )
                echo $before_title . $title . $after_title; 
			
	    include('members/mb-login-form.php');
			
	    echo $after_widget; 
			
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
        <?php 
    }

} // class LoginWidget


// Load MCE

function show_tinymce_load() {
	// conditions here
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
}
 
add_filter('admin_head', 'show_tinymce_load');



function setup_plugin() {
	
   global $wpdb;
   global $db_version;

	

   if($wpdb->get_var("show tables like ".CLIENTS_TABLE."" ) != CLIENTS_TABLE) {
      
      $sqlClients = "CREATE TABLE " . CLIENTS_TABLE . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  company VARCHAR(100) NOT NULL,
	  first_name VARCHAR(100) NOT NULL,
	  last_name VARCHAR(100) NOT NULL,
	  email VARCHAR(100) NOT NULL,
	  street VARCHAR(100) NOT NULL,
	  city VARCHAR(100) NOT NULL,
	  state VARCHAR(100) NOT NULL,
	  zip VARCHAR(100) NOT NULL,
	  phone VARCHAR(100) NOT NULL,
	  path VARCHAR(100) NOT NULL,
	  username VARCHAR(100) NOT NULL,
	  password VARCHAR(100) NOT NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sqlClients);
	  
      add_option("db_version", $db_version);
   }
      if($wpdb->get_var("show tables like ".FILES_TABLE."" ) != FILES_TABLE) {
      
      $sqlFiles = "CREATE TABLE " . FILES_TABLE . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  client_id INT(11) NOT NULL,
	  file_name VARCHAR(255) NOT NULL,
	  UNIQUE KEY id (id)
	);";

	dbDelta($sqlFiles);
   } 

      if($wpdb->get_var("show tables like ".PAGES_TABLE."" ) != PAGES_TABLE) {
      
      $sqlPages = "CREATE TABLE " . PAGES_TABLE . " (
	  pid mediumint(9) NOT NULL AUTO_INCREMENT,
	  client_id INT(11) NOT NULL,
	  welcome INT(11) NOT NULL,
	  page_name VARCHAR(255) NOT NULL,
	  html TEXT,
	  page_order INT(11),
	  UNIQUE KEY pid (pid)
	);";

	dbDelta($sqlPages);
   } 

      if($wpdb->get_var("show tables like ".ADMIN_TABLE."" ) != ADMIN_TABLE) {
      
      $sqlAdmin = "CREATE TABLE " . ADMIN_TABLE . " (
	  aid mediumint(9) NOT NULL AUTO_INCREMENT,
	  page_benefits INT(11) NOT NULL,
	  page_login INT(11) NOT NULL,
	  UNIQUE KEY aid (aid)
	);";

	dbDelta($sqlAdmin);
   } 


}

function uninstall() {
	
	global $wpdb;
	$wpdb->query( "DROP TABLE ".PAGES_TABLE  );
	$wpdb->query( "DROP TABLE ".FILES_TABLE  );
	$wpdb->query( "DROP TABLE ".CLIENTS_TABLE  );
	unregister_sidebar_widget( 'widget_mybene_login' );	
	unregister_sidebar_widget( 'pages_sidebar' );	
}


function add_menus() {

	// Add a new top-level menu 
   add_menu_page( 'Policy Briefcase Panel', 'Policy Briefcase', 'manage_options', 'list_clients', 'list_clients', IMG_DIRECTORY.'ico16.png', 3);
   add_submenu_page( 'list_clients', 'Briefcase Panel', 'List Clients', 'manage_options', 'list_clients', 'list_clients');
   add_submenu_page( 'options.php', 'Benefits Page', 'Benfits Page', 'manage_options', 'benefits_page', 'benefits_page');
   add_submenu_page( 'list_clients', 'New Insured', 'Add New Client', 'manage_options', 'add_client', 'add_client');
   add_submenu_page( 'list_clients', 'Admin', 'Admin', 'manage_options', 'myBenAdmin', 'myBenAdmin');
   add_submenu_page( 'options.php', 'Check For Missing Files', 'File Check', 'manage_options', 'client_file_check', 'client_file_check');

}

?>
