<?php 

/*
 * Template Name: All Content
 */

?>

<?php get_header(); ?>

<div class="container">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div class="col-md-12">
				
				<?php echo the_content(); ?>
								
			</div>
  
  			<?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
  
		<hr>

	<?php endwhile; else: ?>
		
		<p>There are no posts or pages here</p>

	<?php endif; ?>
</div>

<?php get_footer(); ?>


