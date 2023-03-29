<?php
defined('ABSPATH') or die('Cant access this file directly');

class Vortex_Menu
{

	public static function load_menu()
	{
		$hook = add_menu_page(
			__('Vortex Admin Page', 'cc-vortex'),
			__('Vortex', 'cc-vortex'),
			'manage_options',
			'vortex_admin_menu',
			array('Vortex_Admin', 'display_page'),
			'dashicons-share-alt',
			76
		);

		if ($hook) {
			add_action("load-$hook", array('Vortex_Menu', 'admin_help'));
		}
	}

	public static function load_submenu()
	{
		$hook = add_submenu_page(
			'vortex_admin_menu',
			__('Channels', 'cc-vortex'),
			__('Vortex Socials', 'cc-vortex'),
			'manage_options',
			'vortex_channels',
			array('Vortex_Admin', 'display_configuration_page'),
		);

		if ($hook) {
			add_action("load-$hook", array('Vortex_Menu', 'admin_help'));
		}
	}

	/**
	 * Add help to the Vortex page
	 *
	 * @return false if not the Vortex page
	 */
	public static function admin_help()
	{
		$current_screen = get_current_screen();

		// Screen Content
		if (current_user_can('manage_options')) {
			if (!Vortex::get_api_key() || (isset($_GET['view']) && $_GET['view'] == 'start')) {
				//setup page
				$current_screen->add_help_tab(
					array(
						'id'		=> 'overview',
						'title'		=> __('Overview', 'cc-vortex'),
						'content'	=>
						'<p><strong>' . esc_html__('Vortex Setup', 'cc-vortex') . '</strong></p>' .
							'<p>' . esc_html__('Vortex filters out spam, so you can focus on more important things.', 'cc-vortex') . '</p>' .
							'<p>' . esc_html__('On this page, you are able to set up the Vortex plugin.', 'cc-vortex') . '</p>',
					)
				);

				$current_screen->add_help_tab(
					array(
						'id'		=> 'setup-signup',
						'title'		=> __('New to Vortex', 'cc-vortex'),
						'content'	=>
						'<p><strong>' . esc_html__('Vortex Setup', 'cc-vortex') . '</strong></p>' .
							'<p>' . esc_html__('You need to enter an API key to activate the Vortex service on your site.', 'cc-vortex') . '</p>' .
							'<p>' . sprintf(__('Sign up for an account on %s to get an API Key.', 'cc-vortex'), '<a href="https://vortex.com/plugin-signup/" target="_blank">Vortex.com</a>') . '</p>',
					)
				);

				$current_screen->add_help_tab(
					array(
						'id'		=> 'setup-manual',
						'title'		=> __('Enter an API Key', 'cc-vortex'),
						'content'	=>
						'<p><strong>' . esc_html__('Vortex Setup', 'cc-vortex') . '</strong></p>' .
							'<p>' . esc_html__('If you already have an API key', 'cc-vortex') . '</p>' .
							'<ol>' .
							'<li>' . esc_html__('Copy and paste the API key into the text field.', 'cc-vortex') . '</li>' .
							'<li>' . esc_html__('Click the Use this Key button.', 'cc-vortex') . '</li>' .
							'</ol>',
					)
				);
			} elseif (isset($_GET['view']) && $_GET['view'] == 'stats') {
				//stats page
				$current_screen->add_help_tab(
					array(
						'id'		=> 'overview',
						'title'		=> __('Overview', 'cc-vortex'),
						'content'	=>
						'<p><strong>' . esc_html__('Vortex Stats', 'cc-vortex') . '</strong></p>' .
							'<p>' . esc_html__('Vortex filters out spam, so you can focus on more important things.', 'cc-vortex') . '</p>' .
							'<p>' . esc_html__('On this page, you are able to view stats on spam filtered on your site.', 'cc-vortex') . '</p>',
					)
				);
			} else {
				//configuration page
				$current_screen->add_help_tab(
					array(
						'id'		=> 'overview',
						'title'		=> __('Overview', 'cc-vortex'),
						'content'	=>
						'<p><strong>' . esc_html__('Vortex Configuration', 'cc-vortex') . '</strong></p>' .
							'<p>' . esc_html__('Vortex filters out spam, so you can focus on more important things.', 'cc-vortex') . '</p>' .
							'<p>' . esc_html__('On this page, you are able to update your Vortex settings and view spam stats.', 'cc-vortex') . '</p>',
					)
				);

				$current_screen->add_help_tab(
					array(
						'id'		=> 'Vortex',
						'title'		=> __('Dashboard', 'cc-vortex'),
						'content'	=>
						'<p><strong>' . esc_html__('Vortex Configuration', 'cc-vortex') . '</strong></p>' .
							(Vortex::predefined_api_key() ? '' : '<p><strong>' . esc_html__('API Key', 'cc-vortex') . '</strong> - ' . esc_html__('Enter/remove an API key.', 'cc-vortex') . '</p>') .
							'<p><strong>' . esc_html__('Comments', 'cc-vortex') . '</strong> - ' . esc_html__('Show the number of approved comments beside each comment author in the comments list page.', 'cc-vortex') . '</p>' .
							'<p><strong>' . esc_html__('Strictness', 'cc-vortex') . '</strong> - ' . esc_html__('Choose to either discard the worst spam automatically or to always put all spam in spam folder.', 'cc-vortex') . '</p>',
					)
				);

				if (!Vortex::predefined_api_key()) {
					$current_screen->add_help_tab(
						array(
							'id'		=> 'account',
							'title'		=> __('Account', 'cc-vortex'),
							'content'	=>
							'<p><strong>' . esc_html__('Vortex Configuration', 'cc-vortex') . '</strong></p>' .
								'<p><strong>' . esc_html__('Subscription Type', 'cc-vortex') . '</strong> - ' . esc_html__('The Vortex subscription plan', 'cc-vortex') . '</p>' .
								'<p><strong>' . esc_html__('Status', 'cc-vortex') . '</strong> - ' . esc_html__('The subscription status - active, cancelled or suspended', 'cc-vortex') . '</p>',
						)
					);
				}
			}
		}

		// Help Sidebar
		$current_screen->set_help_sidebar(
			'<p><strong>' . esc_html__('For more information:', 'cc-vortex') . '</strong></p>' .
				'<p><a href="https://clearcutcomms.ca/vortex/faq/" target="_blank">'     . esc_html__('Vortex FAQ', 'cc-vortex') . '</a></p>' .
				'<p><a href="https://clearcutcomms.ca/vortex/support/" target="_blank">' . esc_html__('Vortex Support', 'cc-vortex') . '</a></p>'
		);
	}
}
