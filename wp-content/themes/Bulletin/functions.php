<?php 

// Load the Theme CSS
function theme_styles() {

	//wp_enqueue_style( 'main', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css' );
    wp_enqueue_style( 'flipclock', get_template_directory_uri() . '/css/flipclock.css' );
}

// Load the Theme JS
function theme_js() {

	//wp_deregister_script('jquery');
	wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.js');
	//wp_register_script('jquery');
	wp_enqueue_script('jquery');

	wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery'), '', true );
	wp_enqueue_script('bootstrap');

	wp_register_script( 'base', get_template_directory_uri() . '/js/flipclock/libs/base.js', array( 'bootstrap'), '', true );
	wp_enqueue_script('base');

	wp_register_script( 'flipclock', get_template_directory_uri() . '/js/flipclock/flipclock.js', array( 'base' ), '', true );
	wp_enqueue_script('flipclock');

	wp_register_script( 'dailycounter', get_template_directory_uri() . '/js/flipclock/faces/dailycounter.js', array( 'flipclock' ), '', true );
	wp_enqueue_script('dailycounter');

	wp_register_script( 'lang', get_template_directory_uri() . '/js/flipclock/lang/en-us.js', array( 'dailycounter'), '', true );
	wp_enqueue_script('lang');

	wp_register_script( 'custom', get_template_directory_uri() . '/js/custom.js', array('lang'), '', true );
	wp_enqueue_script('custom');
}


add_action( 'wp_enqueue_scripts', 'theme_js' );

add_action( 'wp_enqueue_scripts', 'theme_styles' );

// Enable custom menus
add_theme_support( 'menus' );

// Enable post thumbnails
add_theme_support( 'post-thumbnails' ); 

/*function create_widget( $name, $id, $description ) {
	$args = array(
		'name'          => $name,
		'id'            => $id,
		'description'   => $description,
	    'class'         => '',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>' 
	);

	register_sidebar(  '$args' );
}*/

if ( function_exists('register_sidebar') )
register_sidebar(array(
		'name'          => 'Left Footer',
	    'class'         => '',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>', 
));

//register_sidebar(  '$args' );

//create_widget( 'Left Footer', 'footer_left', 'Displays in the bottom left of the footer' );
//create_widget( 'Middle Footer', 'middle', 'Displays in the middle of the footer' );
//create_widget( 'Right Footer', 'footer_right', 'Displays in the bottom right of the footer' );

?>