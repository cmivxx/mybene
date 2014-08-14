<?php

/*
Template Name: myBenefits Login Page
*/
?>

<?php get_header(); ?>

	<div class="col-1">
        
        
        
        		<div style="padding:30px;">
					   <?php 
                        require_once( getcwd().'/wp-content/plugins/mybene/members/mb-login-form.php' );
                        ?>
             	</div>
		
        
        </div> <!-- end main -->
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>