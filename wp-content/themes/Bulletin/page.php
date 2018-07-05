<?php
/*
Template Name: Page
*/
?>

<?php get_header(); ?>

<div class="container">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div class="row">
	  <div class="top-head">
		
		<div class="round-left"></div>
		
		   <span class="h3"><?php the_title() ;?></span>	

		<div class="round-right"></div>
		
	  </div>
    </div>
  		<div class="row">
	  <div class="col-md-9">
			<?php the_content(); ?>
	  </div>
	  <div class="col-md-3">
		<img class="pull-right" src="<?php echo home_url(); ?>/wp-content/themes/Bulletin/images/Shifting-lives-Logo-480.png" alt="The Unlimited logo" " alt="The Unlimited logo" />
	  </div>
    </div>

  	<div class="printfriendly">
  
  		<a href="http://gd.theunlimited.co.za/all-content?pfstyle=wp">PRINT</a>
	  
  	</div>

	<?php endwhile; else: ?>
		
		<p>There are no posts or pages here</p>

	<?php endif; ?>
</div>

<?php get_footer(); ?>