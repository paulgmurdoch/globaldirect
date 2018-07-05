<?php
/*
Template Name: Category
*/
?>

<?php get_header(); ?>

<div class="container">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

<?php if(has_post_thumbnail()) { ?>
<div class="entry-thumb">
	<a href="<?php the_permalink(); ?>" rel="nofollow">
	<?php the_post_thumbnail( 'post-square' ); ?>
	</a>
</div>
<?php } ?>


<div class="entry-wrap">

<h2 class="entry-title"><a rel="bookmark" href="<?php the_permalink(); ?>">
    <?php the_title(); ?>
    </a></h2>

<?php get_template_part('content', 'meta'); ?> 

  <div class="entry-content">
    <?php the_excerpt(); ?>
  </div>
  <a href="<?php the_permalink(); ?>" class="readmore" rel="nofollow"><?php _e( 'Read More', 'framework' ); ?><span> &rarr;</span></a>
</div>
 

</article>

	<?php endwhile; else: ?>
		
		<p>There are no posts or pages here</p>

	<?php endif; ?>
</div>

<?php get_footer(); ?>