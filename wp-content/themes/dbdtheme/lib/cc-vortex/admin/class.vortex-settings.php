<?php
defined('ABSPATH') or die('Cant access this file directly');

class Vortex_Settings
{
	// register plugin settings
	/*
		register_setting(
			string   $option_group,
			string   $option_name,
			callable $sanitize_callback
		);
	*/
	public static function vortex_register_settings()
	{

		register_setting(
			'vortex_options',
			'vortex_options',
			'vortex_callback_validate_options'
		);

		/*
			add_settings_section( string: $id, string: $title, callable: $callback, string: $page);
		*/
		add_settings_section(
			'vortex_section_admin',
			esc_html__('Customize Admin Area', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_section_admin'),
			'vortex_admin_menu'
		);
		add_settings_section(
			'vortex_section_channels',
			esc_html__('Customize Social Channels Page', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_section_channels'),
			'vortex_admin_menu'
		);

		/*
			add_settings_field(string:$id, string:$title, callable: $callback, string:$page, string:$section = 'default', array: $args = []);
		*/

		// Admin Section
		add_settings_field(
			'custom_footer',
			esc_html__('Custom Footer', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_text'),
			'vortex_admin_menu',
			'vortex_section_admin',
			['id' => 'custom_footer', 'label' => esc_html__('Custom footer text', 'cc-vortex')]
		);

		add_settings_field(
			'custom_toolbar',
			esc_html__('Custom Toolbar', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_checkbox'),
			'vortex_admin_menu',
			'vortex_section_admin',
			['id' => 'custom_toolbar', 'label' => esc_html__('Remove new post and comment links from the Toolbar', 'cc-vortex')]
		);

		add_settings_field(
			'custom_scheme',
			esc_html__('Custom Scheme', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_select'),
			'vortex_admin_menu',
			'vortex_section_admin',
			['id' => 'custom_scheme', 'label' => esc_html__('Default color scheme for new users', 'cc-vortex')]
		);

		// Channels Section
		add_settings_field(
			'custom_url',
			esc_html__('Custom URL', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_text'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_url', 'label' => esc_html__('Custom URL for the channels logo link', 'cc-vortex')]
		);

		add_settings_field(
			'custom_title',
			esc_html__('Custom Title', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_text'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_title', 'label' => esc_html__('Custom title attribute for the logo link', 'cc-vortex')]
		);

		add_settings_field(
			'custom_style',
			esc_html__('Custom Style', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_radio'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_style', 'label' => esc_html__('Custom CSS for the Login screen', 'cc-vortex')]
		);

		add_settings_field(
			'custom_message',
			esc_html__('Custom Message', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_textarea'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_message', 'label' => esc_html__('Custom text and/or markup', 'cc-vortex')]
		);

		add_settings_field(
			'custom_api_key',
			esc_html__('Custom API Key', 'cc-vortex'),
			array('Vortex_Settings', 'vortex_callback_field_sensitive'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_api_key', 'label' => esc_html__('Custom API Key', 'cc-vortex')]
		);
	}

	// callback: login section
	public static function vortex_callback_section_admin()
	{
		echo '<p>' . esc_html__('These settings enable you to configure the Vortex settings', 'cc-vortex') . '</p>';
	}

	// callback: admin section
	public static function vortex_callback_section_channels()
	{
		echo '<p>' . esc_html__('These settings enable you to customize the Vortex Channels', 'cc-vortex') . '</p>';
	}

	// callback: text field
	public static function vortex_callback_field_text($args)
	{
		$options = get_option('vortex_options', vortex_options_default());

		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';

		$value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

		echo '<input id="vortex_options_' . $id . '" name="vortex_options[' . $id . ']" type="text" size="40" value="' . $value . '"><br />';
		echo '<label for="vortex_options_' . $id . '">' . $label . '</label>';
	}

	// radio field options
	static function vortex_options_radio()
	{
		return array(
			'enable'  => esc_html__('Enable custom styles', 'cc-vortex'),
			'disable' => esc_html__('Disable custom styles', 'cc-vortex')
		);
	}

	// callback: radio field
	public static function vortex_callback_field_radio($args)
	{
		$options = get_option('vortex_options', vortex_options_default());

		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';

		$selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

		$radio_options = self::vortex_options_radio();

		foreach ($radio_options as $value => $label) {

			$checked = checked($selected_option === $value, true, false);

			echo '<label><input name="vortex_options[' . $id . ']" type="radio" value="' . $value . '"' . $checked . '> ';
			echo '<span>' . $label . '</span></label><br />';
		}
	}

	// callback: textarea field
	public static function vortex_callback_field_textarea($args)
	{
		$options = get_option('vortex_options', vortex_options_default());

		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';

		$allowed_tags = wp_kses_allowed_html('post');

		$value = isset($options[$id]) ? wp_kses(stripslashes_deep($options[$id]), $allowed_tags) : '';

		echo '<textarea id="vortex_options_' . $id . '" name="vortex_options[' . $id . ']" rows="5" cols="50">' . $value . '</textarea><br />';
		echo '<label for="vortex_options_' . $id . '">' . $label . '</label>';
	}

	// callback: checkbox field
	public static function vortex_callback_field_checkbox($args)
	{
		$options = get_option('vortex_options', vortex_options_default());

		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';

		$checked = isset($options[$id]) ? checked($options[$id], 1, false) : '';

		echo '<input id="vortex_options_' . $id . '" name="vortex_options[' . $id . ']" type="checkbox" value="1"' . $checked . '> ';
		echo '<label for="vortex_options_' . $id . '">' . $label . '</label>';
	}

	// callback: select field
	public static function vortex_callback_field_select($args)
	{
		$options = get_option('vortex_options', vortex_options_default());

		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';

		$selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

		$select_options = array(

			'default'   => esc_html__('Default',	'cc-vortex'),
			'light'     => esc_html__('Light',		'cc-vortex'),
			'blue'      => esc_html__('Blue',		'cc-vortex'),
			'coffee'    => esc_html__('Coffee',		'cc-vortex'),
			'ectoplasm' => esc_html__('Ectoplasm',	'cc-vortex'),
			'midnight'  => esc_html__('Midnight',	'cc-vortex'),
			'ocean'     => esc_html__('Ocean',		'cc-vortex'),
			'sunrise'   => esc_html__('Sunrise',	'cc-vortex'),

		);

		echo '<select id="vortex_options_' . $id . '" name="vortex_options[' . $id . ']">';

		foreach ($select_options as $value => $option) {

			$selected = selected($selected_option === $value, true, false);

			echo '<option value="' . $value . '"' . $selected . '>' . $option . '</option>';
		}

		echo '</select> <label for="vortex_options_' . $id . '">' . $label . '</label>';
	}

	// callback: sensitive field
	public static function vortex_callback_field_sensitive($args)
	{
		$options = get_option('vortex_options', vortex_options_default());

		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';

		$value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

		echo '<input id="vortex_options_' . $id . '" name="vortex_options[' . $id . ']" type="password" size="40" value="' . $value . '"><br />';
		echo '<label for="vortex_options_' . $id . '">' . $label . '</label>';
	}
}
