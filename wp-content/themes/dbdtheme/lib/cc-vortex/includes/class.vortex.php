<?php

class Vortex
{
	// initialize class constants
	const CLASS_MATES = 'Put a constant here!';
	const VORTEX_API_KEY = '9876543210';
	const API_PORT = 80;

	public static $limit_notices = array(
		10501 => 'FIRST_MONTH_OVER_LIMIT',
		10502 => 'SECOND_MONTH_OVER_LIMIT',
		10504 => 'THIRD_MONTH_APPROACHING_LIMIT',
		10508 => 'THIRD_MONTH_OVER_LIMIT',
		10516 => 'FOUR_PLUS_MONTHS_OVER_LIMIT',
	);

	// initialize class variables
	private static $initiated = false;

	public static function init()
	{
		if (!self::$initiated) {
			self::init_hooks();
		}
	}


	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks()
	{
		self::$initiated = true;

		// add_action( 'wp_insert_comment', array( 'Vortex', 'auto_check_update_meta' ), 10, 2 );
		// add_filter( 'preprocess_comment', array( 'Vortex', 'auto_check_comment' ), 1 );
		// add_filter( 'rest_pre_insert_comment', array( 'Vortex', 'rest_auto_check_comment' ), 1 );

		// add_action( 'comment_form', array( 'Vortex', 'load_form_js' ) );
		// add_action( 'do_shortcode_tag', array( 'Vortex', 'load_form_js_via_filter' ), 10, 4 );

		// add_action( 'vortex_scheduled_delete', array( 'Vortex', 'delete_old_comments' ) );
		// add_action( 'vortex_scheduled_delete', array( 'Vortex', 'delete_old_comments_meta' ) );
		// add_action( 'vortex_scheduled_delete', array( 'Vortex', 'delete_orphaned_commentmeta' ) );
		// add_action( 'vortex_schedule_cron_recheck', array( 'Vortex', 'cron_recheck' ) );

		// add_action( 'comment_form',  array( 'Vortex',  'add_comment_nonce' ), 1 );
		// add_action( 'comment_form', array( 'Vortex', 'output_custom_form_fields' ) );
		// add_filter( 'script_loader_tag', array( 'Vortex', 'set_form_js_async' ), 10, 3 );

		// add_filter( 'comment_moderation_recipients', array( 'Vortex', 'disable_moderation_emails_if_unreachable' ), 1000, 2 );
		// add_filter( 'pre_comment_approved', array( 'Vortex', 'last_comment_status' ), 10, 2 );

		// add_action( 'transition_comment_status', array( 'Vortex', 'transition_comment_status' ), 10, 3 );

		// Run this early in the pingback call, before doing a remote fetch of the source uri
		// add_action( 'xmlrpc_call', array( 'Vortex', 'pre_check_pingback' ) );

		// Jetpack compatibility
		// add_filter( 'jetpack_options_whitelist', array( 'Vortex', 'add_to_jetpack_options_whitelist' ) );
		// add_filter( 'jetpack_contact_form_html', array( 'Vortex', 'inject_custom_form_fields' ) );
		// add_filter( 'jetpack_contact_form_vortex_values', array( 'Vortex', 'prepare_custom_form_values' ) );

		// Gravity Forms
		// add_filter( 'gform_get_form_filter', array( 'Vortex', 'inject_custom_form_fields' ) );
		// add_filter( 'gform_vortex_fields', array( 'Vortex', 'prepare_custom_form_values' ) );

		// Contact Form 7
		// add_filter( 'wpcf7_form_elements', array( 'Vortex', 'append_custom_form_fields' ) );
		// add_filter( 'wpcf7_vortex_parameters', array( 'Vortex', 'prepare_custom_form_values' ) );

		// Formidable Forms
		// add_filter( 'frm_filter_final_form', array( 'Vortex', 'inject_custom_form_fields' ) );
		// add_filter( 'frm_vortex_values', array( 'Vortex', 'prepare_custom_form_values' ) );

		// Fluent Forms
		// add_filter( 'fluentform_form_element_start', array( 'Vortex', 'output_custom_form_fields' ) );
		// add_filter( 'fluentform_vortex_fields', array( 'Vortex', 'prepare_custom_form_values' ), 10, 2 );

		// add_action( 'update_option_wordpress_api_key', array( 'Vortex', 'updated_option' ), 10, 2 );
		// add_action( 'add_option_wordpress_api_key', array( 'Vortex', 'added_option' ), 10, 2 );

		// add_action( 'comment_form_after',  array( 'Vortex',  'display_comment_form_privacy_notice' ) );
	}

	public static function get_api_key()
	{
		return apply_filters('vortex_get_api_key', defined('VORTEX_API_KEY') ? constant('VORTEX_API_KEY') : get_option('wordpress_api_key'));
	}

	public static function view($name, array $args = array())
	{
		$args = apply_filters('vortex_view_arguments', $args, $name);

		foreach ($args as $key => $val) {
			$$key = $val;
		}

		load_plugin_textdomain('cc-vortex');

		$file = VORTEX__PLUGIN_DIR . 'admin/views/' . $name . '.php';

		include($file);
	}

	private static function bail_on_activation($message, $deactivate = true)
	{
?>
		<!doctype html>
		<html>

		<head>
			<meta charset="<?php bloginfo('charset'); ?>" />
			<style>
				* {
					text-align: center;
					margin: 0;
					padding: 0;
					font-family: "Lucida Grande", Verdana, Arial, "Bitstream Vera Sans", sans-serif;
				}

				p {
					margin-top: 1em;
					font-size: 18px;
				}
			</style>
		</head>

		<body>
			<p><?php echo esc_html($message); ?></p>
		</body>

		</html>
<?php
		if ($deactivate) {
			$plugins = get_option('active_plugins');
			$vortex = plugin_basename(VORTEX__PLUGIN_DIR . 'vortex.php');
			$update  = false;
			foreach ($plugins as $i => $plugin) {
				if ($plugin === $vortex) {
					$plugins[$i] = false;
					$update = true;
				}
			}

			if ($update) {
				update_option('active_plugins', array_filter($plugins));
			}
		}
		exit;
	}

	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation()
	{
		if (version_compare($GLOBALS['wp_version'], VORTEX__MINIMUM_WP_VERSION, '<')) {
			load_plugin_textdomain('cc-vortex');

			$message = '<strong>' . sprintf(
				esc_html__('Vortex %s requires WordPress %s or higher.', 'cc-vortex'),
				VORTEX_VERSION,
				VORTEX__MINIMUM_WP_VERSION
			) . '</strong> ' . sprintf(
				__('Please <a href="%s">upgrade WordPress</a> to a current version to use this plugin.', 'cc-vortex'),
				'https://codex.wordpress.org/Upgrading_WordPress'
			);

			Vortex::bail_on_activation($message);
		} elseif (!empty($_SERVER['SCRIPT_NAME']) && false !== strpos($_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php')) {
			add_option('Activated_Vortex', true);
		}
	}

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation()
	{
		// self::deactivate_key(self::get_api_key());

		// Remove any scheduled cron jobs.
		$vortex_cron_events = array(
			'vortex_schedule_cron_recheck',
			'vortex_scheduled_delete',
		);

		foreach ($vortex_cron_events as $vortex_cron_event) {
			$timestamp = wp_next_scheduled($vortex_cron_event);

			if ($timestamp) {
				wp_unschedule_event($timestamp, $vortex_cron_event);
			}
		}
	}

	public static function predefined_api_key()
	{
		if (defined('VORTEX_API_KEY')) {
			return true;
		}

		return apply_filters('vortex_predefined_api_key', false);
	}
}
