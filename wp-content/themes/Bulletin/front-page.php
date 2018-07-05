<?php
/*
Template Name: Font Page
*/
?>

<?php get_header(); ?>
  <div class="container">
      <div class="starter-template">
        <div class="col-md-6 text-center">
          <img src="<?php echo home_url(); ?>/wp-content/themes/Bulletin/images/Shifting-lives-Logo-480.png" width="219" alt="the unlimited logo" onerror="this.onerror=null; this.src='<?php echo home_url(); ?>/wp-content/themes/Bulletin/images/the-unlimited-logo.png'">
          <img src="<?php echo home_url(); ?>/wp-content/themes/Bulletin/images/whos-hot-this-week.png">
          <p class="mini-slogan"><span>The World's</span> <strong>Greatest Opportunity</strong></p>
        </div>
        <div class="col-md-6">
		  <!--<img class="front-image" src="<?php echo home_url(); ?>/wp-content/uploads/2015/09/3057_Rally_2015_Website_Banner.jpg" alt="Global direct"/>-->
          <!--<div class="countdown">
                <div class="clock"></div>
          </div>-->
          <div class="slogan" id="footer-left">
            <?php get_sidebar('Left Footer');?>
          </div>
        </div>
      </div>
  </div><!-- /.container -->
  <?php get_footer(); ?>
