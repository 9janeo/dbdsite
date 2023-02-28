<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

require_once('lib/custom_taxonomies.php');
require_once('lib/youtube-interface.php');


/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @param string $current_mod The current value of the theme_mod.
 * @return string
 */
function understrap_default_bootstrap_version( $current_mod ) {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );

/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

// Error logs wp_remote calls
if( ! function_exists( 'debug_wp_remote_post_and_get_request' ) ) :
  function debug_wp_remote_post_and_get_request( $response, $context, $class, $r, $url ) {
    error_log( '------------------------------' );
    error_log( $url );
    error_log( json_encode( $response ) );
    error_log( $class );
    error_log( $context );
    error_log( json_encode( $r ) );
	}
	add_action( 'http_api_debug', 'debug_wp_remote_post_and_get_request', 10, 5 );
endif;

function dis_by_dem_video_info($post_ID, $post){
	$yt_key = get_field('Youtube_API_key', 'options');
	
	if(get_field('video_link')):
		$url = get_field('video_link');
		parse_str(parse_url($url, PHP_URL_QUERY), $arr_of_vars );
		$id = $arr_of_vars['v'];

		$req_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=".$id."&key=".$yt_key;
		$response = wp_remote_get($req_url);
		$code = wp_remote_retrieve_response_code($response);
		$result = json_decode(wp_remote_retrieve_body( $response ));
		if ($code == 200){
			$vid_snippet = json_decode($result->items[0]->snippet);
			update_post_meta($post_ID, 'video_info', $vid_snippet);
			// $vid_title = $vid_snippet->title;
			// $vid_desc = $vid_snippet->description;
			// $vid_published = $vid_snippet->publishedAt;
		}
	endif;
}
add_action( 'save_post','dis_by_dem_video_info', 10, 2);

if( function_exists('acf_add_options_page') ) {
    
	acf_add_options_page(array(
		'page_title'    => 'Theme Settings',
		'menu_title'    => 'Theme Settings',
		'menu_slug'     => 'theme-settings',
		'capability'    => 'edit_posts',
		'redirect'      => false
	));
	
	acf_add_options_sub_page(array(
		'page_title'    => 'Post Card Settings',
		'menu_title'    => 'Cover',
		'parent_slug'   => 'theme-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title'    => 'Footer Settings',
		'menu_title'    => 'Footer',
		'parent_slug'   => 'theme-settings',
	));
	
}
