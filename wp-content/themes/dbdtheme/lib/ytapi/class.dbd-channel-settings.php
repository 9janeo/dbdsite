<?php

// Load dbd channel settings
defined('ABSPATH') or die('Cant access this file directly');

class DBD_Channels
{
  public static function ready_channels_table_into_db() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . 'channels';

    $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
      id int(11) NOT NULL Auto_INCREMENT,
      channel_name tinytext NOT NULL,
      platform tinytext NOT NULL,
      channel_username VARCHAR(20),
      channel_id VARCHAR(25),
      channel_url VARCHAR(25),
      PRIMARY KEY  (id),
      KEY channel_id (channel_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
    $is_error = empty($wpdb->last_error);

    return $is_error;
  }

  public static function get_dbd_channels(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'channels';
    $channels = $wpdb->get_results("SELECT * FROM {$table_name}");
    return $channels;
  }

  static function get_channel_counts()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'channels';
    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
    return isset($rowcount)? $rowcount : 0;
  }

  static function dbd_channel_defaults()
  {
    return array(
      'id'   => esc_html__('new channel id', 'disbydem'),
      'name'   => esc_html__('new channel', 'disbydem'),
      'platform'   => esc_html__('youtube', 'disbydem')
    );
  }

  public static function dbd_register_channel_settings()
  {
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
      ['label' => esc_html__('Channel Name', 'disbydem')]
    );

    add_settings_field(
      'channel_platform',
      esc_html__('Select channel platform', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_new_select'),
      'dbd_channels',
      'dbd_section_new_channel',
      ['label' => esc_html__('Channel platform', 'disbydem')]
    );
    
    add_settings_field(
      'Username',
      esc_html__('Input channel username', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_new_username'),
      'dbd_channels',
      'dbd_section_new_channel',
      ['label' => esc_html__('Username', 'disbydem')]
    );
    
    add_settings_field(
      'channel_id',
      esc_html__('Input channel ID', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_new_channel_id'),
      'dbd_channels',
      'dbd_section_new_channel',
      ['label' => esc_html__('Channel ID', 'disbydem')]
    );
    
    add_settings_field(
      'channel_url',
      esc_html__('Input channel URL', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_url'),
      'dbd_channels',
      'dbd_section_new_channel',
      ['label' => esc_html__('Channel URL', 'disbydem')]
    );

    // if (self::get_channel_counts() > 0) {
    //   add_settings_section(
    //     'dbd_section_channels',
    //     esc_html__('Update channel details', 'disbydem'),
    //     array('DBD_Channels', 'dbd_callback_display_channels'),
    //     'dbd_channels'
    //   );
    // }
  }

  // callback: channel section
  public static function dbd_callback_display_channels()
  {
    echo '<p>' . esc_html__('Configure the DBD channel settings', 'disbydem') . '</p>';
    // dbd_channels_callback_field_text();
    // $options = get_option('channel_settings');
    // var_dump($options);

    // foreach ($options as $key => $channel) {
    // add_settings_field(
    //   'channel_name',
    //   esc_html__('The channel Name', 'disbydem'),
    //   array('DBD_Channels', 'dbd_channels_callback_field_text'),
    //   'dbd_channels',
    //   'dbd_section_channels',
    //   ['channel' => $key, 'id' => 'channel_name', 'label' => esc_html__('Channel Name', 'disbydem')]
    // );

    // add_settings_field(
    //   'channel_platform',
    //   esc_html__('The channel platform', 'disbydem'),
    //   array('DBD_Channels', 'dbd_callback_platform_select'),
    //   'dbd_channels',
    //   'dbd_section_channels',
    //   ['channel' => $key, 'id' => 'channel_platform', 'label' => esc_html__('Channel platform', 'disbydem')]
    // );
    // }
  }

  // callback: channel section
  public static function dbd_callback_add_channel()
  {
    echo '<p>' . esc_html__('Add the details for this channel, load each channel in settings', 'disbydem') . '</p>';
  }

  // callback for select options
  public static function dbd_callback_select_options($options)
  {
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

  // callback: new Channel name
  static function dbd_channels_callback_new_channel($args)
  {
    $options = self::dbd_channel_defaults();
    $channel_defaults = self::dbd_channel_defaults();
    $label = isset($args['label']) ? $args['label'] : '';
    $select_options = self::dbd_callback_select_options('platform');
    echo '<input id="channel_name" name="channel_name" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }
  
  // callback: new Channel ID
  static function dbd_channels_callback_new_channel_id($args)
  {
    $options = self::dbd_channel_defaults();
    $channel_defaults = self::dbd_channel_defaults();
    $label = isset($args['label']) ? $args['label'] : '';
    echo '<input id="channel_id" name="channel_id" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }
  
  // callback: new Channel Username
  static function dbd_channels_callback_new_username($args)
  {
    $options = self::dbd_channel_defaults();
    $channel_defaults = self::dbd_channel_defaults();
    $label = isset($args['label']) ? $args['label'] : '';
    echo '<input id="channel_username" name="channel_username" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }
  
  // callback: new Channel URL
  static function dbd_channels_callback_url($args)
  {
    $options = self::dbd_channel_defaults();
    $channel_defaults = self::dbd_channel_defaults();
    $label = isset($args['label']) ? $args['label'] : '';
    echo '<input id="channel_url" name="channel_url" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }

  // callback: new channel platform
  public static function dbd_channels_callback_new_select($args)
  {
    $options = self::dbd_channel_defaults();
    $id    = '';
    $label = isset($args['label']) ? $args['label'] : '';
    $selected_option = isset($options['platform']) ? sanitize_text_field($options['platform']) : '';
    $select_options = self::dbd_callback_select_options('platform');
    echo '<select id="channel_platform" name="channel_platform">';
    foreach ($select_options as $value => $option) {
      $selected = selected($selected_option === $value, true, false);
      echo '<option value="' . $value . '"' . $selected . '>' . $option . '</option>';
    }
    echo '<label for="channel_platform' . '_' . $id . '">' . $label . '</label>';
  }

  // New channel form
  public static function new_channel_form() {
    if (isset($_POST['add_channel'])){
      DBD_Channels::add_new_dbd_channel();
      // global $wpdb;
      // $table_name = $wpdb->prefix . 'channels';

      // $wpdb->insert($table_name, array(
      //   'channel_name' => $_POST['channel_name'],
      //   'platform' => $_POST['channel_platform'],
      //   'username' => $_POST['username'],
      //   'channel_id' => $_POST['channel_id'],
      //   'channel_url'  => $_POST['channel_url'],
      // ), array('%s','%s','%s','%s','%s'));

    }
  ?>
    <form action="" role="presentation" method="post">
      <?php
      // output security fields
      // settings_fields('channel_settings');

      // output setting sections
      do_settings_sections('dbd_channels');
      // do_settings_sections('dbd_section_new_channel');

      // submit button
      submit_button('Add Channel', 'primary', 'add_channel');
      // submit_button('Add Channel');

      ?>
      <hr>
    </form>
    <?php
  }

  // Add new channel
  public static function add_new_dbd_channel(){
    if (isset($_POST['add_channel'])){
      // print_r($_POST);
      dbd_callback_validate_options($_POST['add_channel']);

      if(empty($_POST['errors'])){
        global $wpdb;
        $table_name = $wpdb->prefix.'channels';
  
        $wpdb->insert($table_name, array(
          'channel_name' => $_POST['channel_name'],
          'platform' => $_POST['channel_platform'],
          'channel_username' => $_POST['channel_username'],
          'channel_id' => $_POST['channel_id'],
          'channel_url'  => $_POST['channel_url'],
        ), array('%s','%s','%s','%s','%s'));

        echo "Channel added";
      } else {
        print_r($_POST['errors']);
      }
    }

    $is_error = empty($wpdb->last_error);
    return $is_error;

  }
}
