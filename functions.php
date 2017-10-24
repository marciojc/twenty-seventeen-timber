<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		$context['sidebar2'] = Timber::get_widgets('sidebar-2');
		$context['sidebar3'] = Timber::get_widgets('sidebar-3');
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		// wordpress
		$twig->addFunction( new Timber\Twig_Function( 'is_home', 'is_home' ) );
		$twig->addFunction( new Timber\Twig_Function( 'is_front_page', 'is_front_page' ) );
		$twig->addFunction( new Timber\Twig_Function( 'has_custom_header', 'has_custom_header' ) );
		$twig->addFunction( new Timber\Twig_Function( 'is_single', 'is_single' ) );
		$twig->addFunction( new Timber\Twig_Function( 'is_page', 'is_page' ) );
		$twig->addFunction( new Timber\Twig_Function( 'is_active_sidebar', 'is_active_sidebar' ) );
		$twig->addFunction( new Timber\Twig_Function( 'has_nav_menu', 'has_nav_menu' ) );
		$twig->addFunction( new Timber\Twig_Function( 'have_posts', 'have_posts' ) );
		$twig->addFunction( new Timber\Twig_Function( 'is_customize_preview', 'is_customize_preview' ) );
		$twig->addFunction( new Timber\Twig_Function( 'page_for_posts', 'page_for_posts' ) );
		$twig->addFunction( new Timber\Twig_Function( 'get_option', 'get_option' ) );
		$twig->addFunction( new Timber\Twig_Function( 'sprintf', 'sprintf' ) );
		$twig->addFunction( new Timber\Twig_Function( 'the_content', 'theme_the_content' ) );
		$twig->addFunction( new Timber\Twig_Function( 'the_post_thumbnail', 'theme_the_post_thumbnail' ) );
		$twig->addFunction( new Timber\Twig_Function( 'the_excerpt', 'theme_the_excerpt' ) );
		// theme
		$twig->addFunction( new Timber\Twig_Function( 'twentyseventeen_get_svg', 'twentyseventeen_get_svg' ) );
		$twig->addFunction( new Timber\Twig_Function( 'twentyseventeen_is_frontpage', 'twentyseventeen_is_frontpage' ) );
		$twig->addFunction( new Timber\Twig_Function( 'twentyseventeen_edit_link', 'twentyseventeen_edit_link' ) );
		$twig->addFunction( new Timber\Twig_Function( 'twentyseventeen_panel_count', 'twentyseventeen_panel_count' ) );
		$twig->addFunction( new Timber\Twig_Function( 'twentyseventeen_front_page_section', 'twentyseventeen_front_page_section' ) );
		$twig->addFunction( new Timber\Twig_Function( 'dd', 'dd' ) );
		//
		$twig->addFilter('ratio', new Twig_SimpleFilter( 'ratio', 'ratio' ) );

		return $twig;
	}
}

function theme_the_content($content, $id) {
	global $post;

	setup_postdata($id ? $id : $post->ID);
	return the_content($content);
}

function theme_the_post_thumbnail($size='') {
	global $post;

	setup_postdata($post->ID);
	return the_post_thumbnail($size);
}

function theme_the_excerpt($id) {
	global $post;

	setup_postdata($id);
	return the_excerpt();
}

function dd($values) {
	echo "<pre>";
	print_r($values);
	echo "</pre>";
	die();
}

function ratio($thumbnail){
	return esc_attr($thumbnail[2] / $thumbnail[1] * 100);
}

add_action( 'setup_postdata', 'my_setup_postdata' );

function my_setup_postdata( $post ) {
  // $context stores the template context in case you need to reference it

  // Outputs title of your post
  setup_postdata(get_post($post->ID));
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/theme-functions.php';
require get_template_directory() . '/inc/twig-functions.php';

new StarterSite();
