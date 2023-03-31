<?php
defined('ABSPATH') or die('Cant access this file directly');
class Vortex_Admin
{
	// const NONCE = 'vortex-update-key';

	private static $initiated = false;
	private static $notices   = array();
	private static $allowed   = array(
		'a' => array(
			'href' => true,
			'title' => true,
		),
		'b' => array(),
		'code' => array(),
		'del' => array(
			'datetime' => true,
		),
		'em' => array(),
		'i' => array(),
		'q' => array(
			'cite' => true,
		),
		'strike' => array(),
		'strong' => array(),
	);

	public static function init()
	{
		if (!self::$initiated) {
			self::init_hooks();
		}
	}

	public static function init_hooks()
	{
		// The standalone stats page was removed in 3.0 for an all-in-one config and stats page.
		// Redirect any links that might have been bookmarked or in browser history.
		if (isset($_GET['page']) && 'vortex-stats-display' == $_GET['page']) {
			wp_safe_redirect(esc_url_raw(self::get_page_url('stats')), 301);
			die;
		}

		self::$initiated = true;

		require_once(VORTEX__PLUGIN_DIR . 'admin/class.vortex-menu.php');
		require_once(VORTEX__PLUGIN_DIR . 'admin/class.vortex-settings.php');

		add_action('admin_init', array('Vortex_Admin', 'admin_init'));
		add_action('admin_menu', array('Vortex_Admin', 'admin_menu')); # Priority 5, so it's called before Jetpack's admin_menu.
		add_action('admin_init', array('Vortex_settings', 'vortex_register_settings'));
		add_action('admin_notices', array('Vortex_Admin', 'display_notice'));

		add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'admin.php'), array('Vortex_Admin', 'admin_plugin_settings_link'));
	}

	public static function admin_init()
	{
		if (get_option('Activated_Vortex')) {
			delete_option('Activated_Vortex');
			if (!headers_sent()) {
				$admin_url = self::get_page_url('init');
				wp_redirect($admin_url);
			}
		}

		load_plugin_textdomain('cc-vortex');
	}

	public static function admin_menu()
	{
		Vortex_Menu::load_menu();
		Vortex_Menu::load_submenu();
	}

	public static function admin_plugin_settings_link($links)
	{
		$settings_link = '<a href="' . esc_url(self::get_page_url('admin')) . '">' . __('Settings', 'cc-vortex') . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	public static function load_resources()
	{
		global $hook_suffix;

		if (in_array($hook_suffix, apply_filters('vortex_admin_page_hook_suffixes', array(
			'index.php', # dashboard
			'edit-comments.php',
			'comment.php',
			'post.php',
			'vortex-admin-menu',
			'plugins.php',
		)))) {
			wp_register_style('vortex.css', plugin_dir_url(__FILE__) . 'assets/css/admin_styles.css', array(), VORTEX_VERSION);
			wp_enqueue_style('vortex.css');

			wp_register_script('vortex.js', plugin_dir_url(__FILE__) . 'assets/js/vortex.js', array('jquery'), VORTEX_VERSION);
			wp_enqueue_script('vortex.js');

			$inline_js = array(
				'comment_author_url_nonce' => wp_create_nonce('comment_author_url_nonce'),
				'strings' => array(
					'Remove this URL' => __('Remove this URL', 'cc-vortex'),
					'Removing...'     => __('Removing...', 'cc-vortex'),
					'URL removed'     => __('URL removed', 'cc-vortex'),
					'(undo)'          => __('(undo)', 'cc-vortex'),
					'Re-adding...'    => __('Re-adding...', 'cc-vortex'),
				)
			);

			wp_localize_script('vortex.js', 'WPVortex', $inline_js);
		}
	}

	public static function plugin_action_links($links, $file)
	{
		if ($file == plugin_basename(plugin_dir_url(__FILE__) . '/vortex.php')) {
			$links[] = '<a href="' . esc_url(self::get_page_url('admin')) . '">' . esc_html__('Configure', 'cc-vortex') . '</a>';
			$links[] = '<a href="' . esc_url(self::get_page_url('vortex_channels')) . '">' . esc_html__('Socials', 'cc-vortex') . '</a>';
		}

		return $links;
	}

	public static function get_page_url($page = 'admin')
	{

		$args = array('page' => 'vortex_admin_menu');

		if ($page == 'admin') {
			$args = array('page' => 'vortex_admin_menu', 'view' => 'start');
		} elseif ($page === 'vortex_channels') {
			$args = array('page' => 'vortex_channels', 'view' => 'config');
		}

		return add_query_arg($args, menu_page_url('vortex_admin_menu', false));
	}

	public static function display_alert()
	{
		Vortex::view('notice', array(
			'type' => 'alert',
			'code' => (int) get_option('vortex_alert_code'),
			'msg'  => get_option('vortex_alert_msg')
		));
	}

	public static function display_page()
	{
		// ToDo: rewrite url for vortex admin settings page
		if (!Vortex::get_api_key() || (isset($_GET['view']) && $_GET['view'] == 'start')) :
			self::display_start_page();
		else :
			self::display_configuration_page();
		endif;
	}

	public static function display_start_page()
	{
		echo '<h3>Vortex Admin: Display start page</h3>';
		if (isset($_GET['action'])) {
			if ($_GET['action'] == 'delete-key') {
				if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], self::NONCE))
					delete_option('wordpress_api_key');
			}
		}

		if ($api_key = Vortex::get_api_key() && (empty(self::$notices['status']) || 'existing-key-invalid' != self::$notices['status'])) {
			self::display_configuration_page();
			return;
		}

		$vortex_user = false;

		Vortex::view('start', compact('vortex_user'));
	}

	public static function display_stats_page()
	{
		// Vortex::view('stats');
		echo '<h3>Vortex Admin: Display stats page</h3>';
	}

	public static function display_configuration_page()
	{
		$api_key      = Vortex::get_api_key();
		$akismet_user = 'Beta User';
		echo '<h3>Vortex Admin: Display configuration page</h3>';
		Vortex::view('config', compact('api_key', 'akismet_user'));
	}

	public static function set_intro_message()
	{
		Vortex::view('notice', array(
			'type' => 'intro_message',
			'notice_header' => 'This plugin is currently in development and this is a beta version',
			'notice_text'   => 'If you are a beta tester, you can contact the developer at clearcutmgr@gmail.com to provide feedback, request features or report a bug.'
		));
	}

	public static function display_notice()
	{
		global $hook_suffix;
		if (in_array($hook_suffix, array('toplevel_page_vortex-dashboard'))) {
			self::set_intro_message();
		}
	}

	public static function display_status()
	{

		if (!empty(self::$notices)) {
			foreach (self::$notices as $index => $type) {
				if (is_object($type)) {
					$notice_header = $notice_text = '';

					if (property_exists($type, 'notice_header')) {
						$notice_header = wp_kses($type->notice_header, self::$allowed);
					}

					if (property_exists($type, 'notice_text')) {
						$notice_text = wp_kses($type->notice_text, self::$allowed);
					}

					if (property_exists($type, 'status')) {
						$type = wp_kses($type->status, self::$allowed);
						Vortex::view('notice', compact('type', 'notice_header', 'notice_text'));

						unset(self::$notices[$index]);
					}
				} else {
					Vortex::view('notice', compact('type'));

					unset(self::$notices[$index]);
				}
			}
		}
	}
}
