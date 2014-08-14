<?php 
/*
Template Name: myBenefits Members Page
*/
?>

<?php get_header(); ?>

	<div class="col-1">
        
        
        
        		<div style="padding:30px;">
				<?php
					include( getcwd().'/wp-content/plugins/mybene/members/mb-page-content.php' );
								
				 ?>
				
                </div>
	        
       </div> <!-- end main -->
                
    
	<?php get_sidebar(); ?>
    
	<?php get_footer(); ?>

