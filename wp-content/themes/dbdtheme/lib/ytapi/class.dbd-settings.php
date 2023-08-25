<?php

// Load dbd channel settings
defined('ABSPATH') or die('Cant access this file directly');

class DBD_Settings
{
  public static function dbd_register_settings()
  {
    register_setting(
      'dbd_options',
      'dbd_options',
      'dbd_callback_validate_options'
    );

    /*
			add_settings_section( string: $id, string: $title, callable: $callback, string: $page);
		*/
    add_settings_section(
      'dbd_section_admin',
      esc_html__('Customize Admin Area', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_section_admin'),
      'dbd_admin_menu'
    );

    /*
			add_settings_field(string:$id, string:$title, callable: $callback, string:$page, string:$section = 'default', array: $args = []);
		*/

    // Admin Section
    add_settings_field(
      'custom_footer',
      esc_html__('Custom Footer', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_text'),
      'dbd_admin_menu',
      'dbd_section_admin',
      ['id' => 'custom_footer', 'label' => esc_html__('Custom footer text', 'disbydem')]
    );

    add_settings_field(
      'custom_toolbar',
      esc_html__('Custom Toolbar', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_checkbox'),
      'dbd_admin_menu',
      'dbd_section_admin',
      ['id' => 'custom_toolbar', 'label' => esc_html__('Remove new post and comment links from the Toolbar', 'disbydem')]
    );

    add_settings_field(
      'custom_scheme',
      esc_html__('Custom Scheme', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_select'),
      'dbd_admin_menu',
      'dbd_section_admin',
      ['id' => 'custom_scheme', 'label' => esc_html__('Default color scheme for new users', 'disbydem')]
    );

    // Channels Section
    add_settings_field(
      'custom_url',
      esc_html__('Custom URL', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_text'),
      'dbd_admin_menu',
      'dbd_section_channels',
      ['id' => 'custom_url', 'label' => esc_html__('Custom URL for the channels logo link', 'disbydem')]
    );

    add_settings_field(
      'custom_title',
      esc_html__('Custom Title', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_text'),
      'dbd_admin_menu',
      'dbd_section_channels',
      ['id' => 'custom_title', 'label' => esc_html__('Custom title attribute for the logo link', 'disbydem')]
    );

    add_settings_field(
      'custom_style',
      esc_html__('Custom Style', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_radio'),
      'dbd_admin_menu',
      'dbd_section_channels',
      ['id' => 'custom_style', 'label' => esc_html__('Custom CSS for the Login screen', 'disbydem')]
    );

    add_settings_field(
      'custom_message',
      esc_html__('Custom Message', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_textarea'),
      'dbd_admin_menu',
      'dbd_section_channels',
      ['id' => 'custom_message', 'label' => esc_html__('Custom text and/or markup', 'disbydem')]
    );

    add_settings_field(
      'custom_api_key',
      esc_html__('Custom API Key', 'disbydem'),
      array('DBD_Settings', 'dbd_callback_field_sensitive'),
      'dbd_admin_menu',
      'dbd_section_channels',
      ['id' => 'custom_api_key', 'label' => esc_html__('Custom API Key', 'disbydem')]
    );
  }

  // callback: login section
  public static function dbd_callback_section_admin()
  {
    echo '<p>' . esc_html__('These settings enable you to configure the DBD settings', 'disbydem') . '</p>';
  }

  // callback: text field
  public static function dbd_callback_field_text($args)
  {
    $options = get_option('dbd_options', dbd_options_default());

    $id    = isset($args['id'])    ? $args['id']    : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

    echo '<input id="dbd_options_' . $id . '" name="dbd_options[' . $id . ']" type="text" size="40" value="' . $value . '"><br />';
    echo '<label for="dbd_options_' . $id . '">' . $label . '</label>';
  }

  // radio field options
  static function dbd_options_radio()
  {
    return array(
      'enable'  => esc_html__('Enable custom styles', 'disbydem'),
      'disable' => esc_html__('Disable custom styles', 'disbydem')
    );
  }

  // callback: radio field
  public static function dbd_callback_field_radio($args)
  {
    $options = get_option('dbd_options', dbd_options_default());

    $id    = isset($args['id'])    ? $args['id']    : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

    $radio_options = self::dbd_options_radio();

    foreach ($radio_options as $value => $label) {

      $checked = checked($selected_option === $value, true, false);

      echo '<label><input name="dbd_options[' . $id . ']" type="radio" value="' . $value . '"' . $checked . '> ';
      echo '<span>' . $label . '</span></label><br />';
    }
  }

  // callback: textarea field
  public static function dbd_callback_field_textarea($args)
  {
    $options = get_option('dbd_options', dbd_options_default());

    $id    = isset($args['id'])    ? $args['id']    : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $allowed_tags = wp_kses_allowed_html('post');

    $value = isset($options[$id]) ? wp_kses(stripslashes_deep($options[$id]), $allowed_tags) : '';

    echo '<textarea id="dbd_options_' . $id . '" name="dbd_options[' . $id . ']" rows="5" cols="50">' . $value . '</textarea><br />';
    echo '<label for="dbd_options_' . $id . '">' . $label . '</label>';
  }

  // callback: checkbox field
  public static function dbd_callback_field_checkbox($args)
  {
    $options = get_option('dbd_options', dbd_options_default());

    $id    = isset($args['id'])    ? $args['id']    : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $checked = isset($options[$id]) ? checked($options[$id], 1, false) : '';

    echo '<input id="dbd_options_' . $id . '" name="dbd_options[' . $id . ']" type="checkbox" value="1"' . $checked . '> ';
    echo '<label for="dbd_options_' . $id . '">' . $label . '</label>';
  }

  // callback for select options
  public static function dbd_callback_select_options($options)
  {
    $style_options = array(
      'default'   => esc_html__('Default',  'disbydem'),
      'light'     => esc_html__('Light',    'disbydem'),
      'blue'      => esc_html__('Blue',    'disbydem'),
      'coffee'    => esc_html__('Coffee',    'disbydem'),
      'ectoplasm' => esc_html__('Ectoplasm',  'disbydem'),
      'midnight'  => esc_html__('Midnight',  'disbydem'),
      'ocean'     => esc_html__('Ocean',    'disbydem'),
      'sunrise'   => esc_html__('Sunrise',  'disbydem'),
    );

    $platfom_options = array(
      'youtube'   => esc_html__('YouTube',  'disbydem'),
      'tiktok'      => esc_html__('TikTok',    'disbydem'),
      'instagram'    => esc_html__('Instagram',    'disbydem'),
      'facebook' => esc_html__('Facebook',  'disbydem'),
    );

    if ($options == 'custom_styles') {
      return $style_options;
    } else {
      return $platfom_options;
    }
  }

  // callback: select field
  public static function dbd_callback_field_select($args)
  {
    $options = get_option('dbd_options', dbd_options_default());

    $id    = isset($args['id'])    ? $args['id']    : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

    $select_options = self::dbd_callback_select_options('custom_styles');

    echo '<select id="dbd_options_' . $id . '" name="dbd_options[' . $id . ']">';

    foreach ($select_options as $value => $option) {

      $selected = selected($selected_option === $value, true, false);

      echo '<option value="' . $value . '"' . $selected . '>' . $option . '</option>';
    }

    echo '</select> <label for="dbd_options_' . $id . '">' . $label . '</label>';
  }

  // callback: sensitive field
  public static function dbd_callback_field_sensitive($args)
  {
    $options = get_option('dbd_options', dbd_options_default());

    $id    = isset($args['id'])    ? $args['id']    : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

    echo '<input id="dbd_options_' . $id . '" name="dbd_options[' . $id . ']" type="password" size="40" value="' . $value . '"><br />';
    echo '<label for="dbd_options_' . $id . '">' . $label . '</label>';
  }
}
