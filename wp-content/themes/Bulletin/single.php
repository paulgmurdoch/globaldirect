<?php
/*
Template Name: Posts
*/
?>

<?php get_header(); ?>
<div class="container">
  <section id="content" role="main">
     <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
      <header class="entry-header"> 

	<h1 class="entry-title">
          <?php the_title(); ?>
        </h1>
        <br>
        <?php get_template_part('content', 'meta'); ?>

        <?php if ( 'has_post_thumbnail' ) { ?>
        <!--<div class="entry-thumb">
          <?php the_post_thumbnail( 'post-single' ); ?>
        </div>-->
        <?php } ?>
      </header>
      
        
        
        <div class="entry-content">
          <?php the_content(); ?>
          <?php wp_link_pages( array( 'before' => '<span class="div-small"></span><div class="page-links">' . __( 'Pages:', 'framework' ), 'after' => '</div>' ) ); ?>
        </div>
        
        <?php if (is_single() && has_tag()) { ?>
        <div class="entry-meta-tags">
          <?php the_tags(); ?>
        </div>
        <?php } ?>
        

    </article>
    <?php	// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
			comments_template( '', true );	?>
    <?php endwhile; // end of the loop. ?>
    
    
  </section>
</div>
<?php get_footer(); ?>


  
  