<?php

// Load dbd channel settings
defined('ABSPATH') or die('Cant access this file directly');

class DBD_Channels{
	static function get_channel_counts(){
		$channel_details = get_option('channel_settings');
		$count = is_iterable($channel_details)? count($channel_details) : 0;
		return $count;
	}

	public static function dbd_register_channel_settings()
	{
		// ToDo: Seperate channels from Channel Settings
		register_setting(
			'channel_settings',
			'channel_settings',
			'dbd_callback_validate_options'
		);

		// New channel

		add_settings_section(
			'dbd_section_new_channel',
			esc_html__('Add a new channel', 'disbydem'),
			array('DBD_Channels', 'dbd_callback_add_channel'),
			'dbd_channels'
		);

		add_settings_field(
			'channel_name',
			esc_html__('New channel Name', 'disbydem'),
			array('DBD_Channels', 'dbd_channels_callback_new_channel'),
			'dbd_channels',
			'dbd_section_new_channel',
			['id' => 'channel_name', 'label' => esc_html__('Channel Name', 'disbydem')]
		);

		add_settings_field(
			'channel_platform',
			esc_html__('Select channel platform', 'disbydem'),
			array('DBD_Channels', 'dbd_channels_callback_new_select'),
			'dbd_channels',
			'dbd_section_new_channel',
			['id' => 'channel_platform', 'label' => esc_html__('Channel platform', 'disbydem')]
		);

		if (self::get_channel_counts() > 0){
			add_settings_section(
				'dbd_section_channels',
				esc_html__('Update channel details', 'disbydem'),
				array('DBD_Channels', 'dbd_callback_channel_settings'),
				'dbd_channels'
			);
		}

		add_settings_field(
			'channel_name',
			esc_html__('The channel Name', 'disbydem'),
			array('DBD_Channels', 'dbd_channels_callback_field_text'),
			'dbd_channels',
			'dbd_section_channels',
			['id' => 'channel_name', 'label' => esc_html__('Channel Name', 'disbydem')]
		);

		add_settings_field(
			'channel_platform',
			esc_html__('The channel platform', 'disbydem'),
			array('DBD_Channels', 'dbd_callback_platform_select'),
			'dbd_channels',
			'dbd_section_channels',
			['id' => 'channel_platform', 'label' => esc_html__('Channel platform', 'disbydem')]
		);
	}

	static function dbd_channel_defaults()
	{
		return array(
			'id'   => esc_html__('new channel id', 'disbydem'),
			'name'   => esc_html__('new channel', 'disbydem'),
			'platform'   => esc_html__('youtube', 'disbydem')
		);
	}

	// callback: channel section
  public static function dbd_callback_channel_settings()
  {
    echo '<p>' . esc_html__('Configure the DBD channel settings', 'disbydem') . '</p>';
  }
	
	// callback: channel section
  public static function dbd_callback_add_channel()
  {
    echo '<p>' . esc_html__('Add the details for this channel, load each channel in settings', 'disbydem') . '</p>';
		// dbd_channels_callback_field_text();
  }

	// callback: new Channel name
  public static function dbd_channels_callback_new_channel($args)
  {
		$key = self::get_channel_counts();
		$options = get_option('channel_settings', self::dbd_channel_defaults());
		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';
		$value = isset($options[$key][$id]) ? sanitize_text_field($options[$key][$id]) : '';
		echo '<input id="channel_settings_'.$key .'_' . $id . '" name="channel_settings['.$key .'][' . $id . ']" type="text" size="40" value="' . $value . '"><br />';
		echo '<label for="channel_settings_'.$key .'_' . $id . '">' . $label . '</label>';
	}

	// callback: new channel platform
	public static function dbd_channels_callback_new_select($args){
		$key = self::get_channel_counts();
		$options = get_option('channel_settings', self::dbd_channel_defaults());
		
		// foreach($options as $key => $channel){
			$id    = isset($args['id'])    ? $args['id']    : '';
			$label = isset($args['label']) ? $args['label'] : '';
	
			$selected_option = isset($options[$key][$id]) ? sanitize_text_field($options[$key][$id]) : '';
	
			$select_options = self::dbd_callback_select_options('platform');
	
			echo '<select id="channel_settings_'.$key .'_' . $id . '" name="channel_settings['.$key .'][' . $id . ']">';
	
			foreach ($select_options as $value => $option) {
	
				$selected = selected($selected_option === $value, true, false);
	
				echo '<option value="' . $value . '"' . $selected . '>' . $option . '</option>';
			}
	
			echo '</select><br /> <label for="channel_settings_'.$key .'_' . $id . '">' . $label . '</label>';
		// }
	}

	// callback: text field
  public static function dbd_channels_callback_field_text($args)
  {
		$key = self::get_channel_counts();
    $options = get_option('channel_settings', self::dbd_channel_defaults());
		$id    = isset($args['id'])    ? $args['id']    : '';
		$label = isset($args['label']) ? $args['label'] : '';
		// if(isset($options) && !empty($option)):
		foreach($options as $key => $channel){
	
			$value = isset($options[$key][$id]) ? sanitize_text_field($options[$key][$id]) : '';
	
			echo '<input id="channel_settings_'.$key .'_' . $id . '" name="channel_settings['.$key .'][' . $id . ']" type="text" size="40" value="' . $value . '"><br />';
			echo '<label for="channel_settings_'.$key .'_' . $id . '">' . $label . '</label>';	
		}
  }

	// callback: select channel platform field
  public static function dbd_callback_platform_select($args)
  {
		// if(self::get_channel_counts() > 0):
    $options = get_option('channel_settings', self::dbd_channel_defaults());
		var_dump($options);
		// $i = $options? count($options) : 1;
		
		foreach($options as $key => $channel){
			$id    = isset($args['id'])    ? $args['id']    : '';
			$label = isset($args['label']) ? $args['label'] : '';
	
			$selected_option = isset($options[$key][$id]) ? sanitize_text_field($options[$key][$id]) : '';
	
			$select_options = self::dbd_callback_select_options('platform');
	
			echo '<select id="channel_settings_'.$key .'_' . $id . '" name="channel_settings['.$key .'][' . $id . ']">';
	
			foreach ($select_options as $value => $option) {
	
				$selected = selected($selected_option === $value, true, false);
	
				echo '<option value="' . $value . '"' . $selected . '>' . $option . '</option>';
			}
	
			echo '</select><br /> <label for="channel_settings_'.$key .'_' . $id . '">' . $label . '</label>';
		}
		// endif;
  }

	// callback for select options
  public static function dbd_callback_select_options($options)
  {
    $platfom_options = array(
      'default'   => esc_html__('YouTube',  'disbydem'),
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
}