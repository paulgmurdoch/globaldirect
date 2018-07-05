<!DOCTYPE html>
<html>
    <head>
	  <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/ico/favicon.ico">

      <title>

        <?php 

            wp_title( '-', true, 'right' );

            bloginfo( 'name' );
        ?>

      </title>
	   
	   <meta charset="utf-8"/>
	   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name="viewport" content="width=device-width, initial-scale=1.0"> 


    <?php wp_head(); ?>
	  
	  <link rel='stylesheet' href='<?php echo home_url(); ?>/wp-content/themes/Bulletin/style.css' type='text/css' media='screen' />
	  <!-- [if lt IE 10]><!-->
	  	<style type="text/css">
			/* IE9 fix */
			.home-left, 
			.home-right,
			.black-corner, 
			.black-right-corner {
			  display: none;
			}
		  	.nav > li > a { padding: 4px 4px 3px !mportant; }
		</style>
	  <!--<![endif]-->
	  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
        <script src="<?php echo home_url(); ?>/wp-content/themes/Bulletin/js/html5shiv.js"></script>
        <script src="<?php echo home_url(); ?>/wp-content/themes/Bulletin/js/respond.min.js"></script>
      <![endif]-->
    </head>
  <body>

  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <h1 class="title">The Leaders Ladder</h1>
        <div class="pull-right top-tab">
		  <?php 
        	$getdata = $wpdb->get_row('SELECT * FROM wp_datedata WHERE uid = 1');
        		echo $getdata->month . ' - WEEK ' . $getdata->week; 
        	?>
		</div>
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="home-left"></li>
            <li class="active"><a href="/">Home</a></li>
            <li class="home-right"></li>
            <?php

              $separator = '<li class="black-corner"></li>';

              $args = array(
                'menu' => 'main-menu',
                'echo' => false,
               'link_after' => $separator
              );


              echo strip_tags(wp_nav_menu( $args ), '<li> <a>');

            ?>
            <li class="black-right-corner"></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
