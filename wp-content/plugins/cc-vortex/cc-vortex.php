<?php

/**
 * Plugin Name:     Clear Cut Vortex
 * Plugin URI:      https://clearcutcomms.ca/vortex
 * Description:     This is a WordPress management plugin provided by Clear Cut
 * Author:          ClearCut
 * Author URI:      https://clearcutcomms.ca
 * Text Domain:     cc-vortex
 * Domain Path:     /languages
 * Version:         0.1.0
 * Requires at least: 5.0
 * Requires PHP: 5.2
 *
 * @package         Clear_cut_Vortex
 */

// Your code starts here.

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// load text domain
function vortex_load_textdomain()
{
	load_plugin_textdomain('cc-vortex', false, plugin_dir_path(__FILE__) . 'languages/');
}
add_action('plugins_loaded', 'vortex_load_textdomain');

// setup

// Variables
define('VORTEX_VERSION', '5.0.2');
define('VORTEX__MINIMUM_WP_VERSION', '6.0');
define('VORTEX__PLUGIN_DIR', plugin_dir_path(__FILE__));

// Includes
$rootFiles = glob(VORTEX__PLUGIN_DIR . 'includes/*.php');
$subdirectoryFiles = glob(VORTEX__PLUGIN_DIR . 'includes/**/*.php');
$allFiles = array_merge($rootFiles, $subdirectoryFiles);

foreach ($allFiles as $filename) {
	include_once($filename);
}

// Hooks
register_activation_hook(__FILE__, array('Vortex', 'plugin_activation'));
register_deactivation_hook(__FILE__, array('Vortex', 'plugin_deactivation'));

add_action('init', array('Vortex', 'init'));
// add_action('rest_api_init', array('Vortex_REST_API', 'init'));
add_action('init', 'ccv_social_post_type');
add_action('init', 'ccv_register_blocks');

if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
	require_once(VORTEX__PLUGIN_DIR . 'admin/class.vortex-admin.php');
	add_action('init', array('Vortex_Admin', 'init'));
}
require_once(VORTEX__PLUGIN_DIR . 'admin/class.vortex-settings-validate.php');

// default plugin options. these are used until the user makes edits
function vortex_options_default()
{

	return array(
		'custom_url'     => 'https://disbydem.com/',
		'custom_title'   => esc_html__('What\'s your DBD\'ers scale?', 'cc-vortex'),
		'custom_style'   => 'disable',
		'custom_message' => '<p class="custom-message">' . esc_html__('My custom message', 'cc-vortex') . '</p>',
		'custom_footer'  => esc_html__('Special message for users', 'cc-vortex'),
		'custom_toolbar' => false,
		'custom_scheme'  => 'default',
		'custom_api_key'  => 'default',
	);
}
