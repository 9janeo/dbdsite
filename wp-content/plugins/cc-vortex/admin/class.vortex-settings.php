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
			'Customize Admin Area',
			array('Vortex_Settings', 'vortex_callback_section_admin'),
			'vortex_admin_menu'
		);
		add_settings_section(
			'vortex_section_channels',
			'Customize Social Channels Page',
			array('Vortex_Settings', 'vortex_callback_section_channels'),
			'vortex_admin_menu'
		);

		/*
			add_settings_field(string:$id, string:$title, callable: $callback, string:$page, string:$section = 'default', array: $args = []);
		*/

		// Admin Section
		add_settings_field(
			'custom_footer',
			'Custom Footer',
			array('Vortex_Settings', 'vortex_callback_field_text'),
			'vortex_admin_menu',
			'vortex_section_admin',
			['id' => 'custom_footer', 'label' => 'Custom footer text']
		);

		add_settings_field(
			'custom_toolbar',
			'Custom Toolbar',
			array('Vortex_Settings', 'vortex_callback_field_checkbox'),
			'vortex_admin_menu',
			'vortex_section_admin',
			['id' => 'custom_toolbar', 'label' => 'Remove new post and comment links from the Toolbar']
		);

		add_settings_field(
			'custom_scheme',
			'Custom Scheme',
			array('Vortex_Settings', 'vortex_callback_field_select'),
			'vortex_admin_menu',
			'vortex_section_admin',
			['id' => 'custom_scheme', 'label' => 'Default color scheme for new users']
		);

		// Channels Section
		add_settings_field(
			'custom_url',
			'Custom URL',
			array('Vortex_Settings', 'vortex_callback_field_text'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_url', 'label' => 'Custom URL for the channels logo link']
		);

		add_settings_field(
			'custom_title',
			'Custom Title',
			array('Vortex_Settings', 'vortex_callback_field_text'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_title', 'label' => 'Custom title attribute for the logo link']
		);

		add_settings_field(
			'custom_style',
			'Custom Style',
			array('Vortex_Settings', 'vortex_callback_field_radio'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_style', 'label' => 'Custom CSS for the Login screen']
		);

		add_settings_field(
			'custom_message',
			'Custom Message',
			array('Vortex_Settings', 'vortex_callback_field_textarea'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_message', 'label' => 'Custom text and/or markup']
		);

		add_settings_field(
			'custom_api_key',
			'Custom API Key',
			array('Vortex_Settings', 'vortex_callback_field_sensitive'),
			'vortex_admin_menu',
			'vortex_section_channels',
			['id' => 'custom_api_key', 'label' => 'Custom API Key']
		);
	}

	// callback: login section
	public static function vortex_callback_section_admin()
	{
		echo '<p>These settings enable you to configure the Vortex settings.</p>';
	}

	// callback: admin section
	public static function vortex_callback_section_channels()
	{
		echo '<p>These settings enable you to customize the Vortex Channels.</p>';
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

	// callback: radio field
	public static function vortex_callback_field_radio($args)
	{
		$options = get_option('vortex_options', vortex_options_default());

		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';

		$selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

		$radio_options = array(

			'enable'  => 'Enable custom styles',
			'disable' => 'Disable custom styles'

		);

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

			'default'   => 'Default',
			'light'     => 'Light',
			'blue'      => 'Blue',
			'coffee'    => 'Coffee',
			'ectoplasm' => 'Ectoplasm',
			'midnight'  => 'Midnight',
			'ocean'     => 'Ocean',
			'sunrise'   => 'Sunrise',

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
