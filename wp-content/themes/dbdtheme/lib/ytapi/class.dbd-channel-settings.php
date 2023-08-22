<?php

// Load dbd channel settings
defined('ABSPATH') or die('Cant access this file directly');

class DBD_Channels
{
  public static function ready_channels_table_into_db()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . 'channels';

    $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
      id int(11) NOT NULL Auto_INCREMENT,
      channel_name tinytext NOT NULL,
      platform tinytext NOT NULL,
      channel_username VARCHAR(20),
      channel_id VARCHAR(50),
      channel_url VARCHAR(50),
      PRIMARY KEY  (id),
      KEY channel_id (channel_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
    $is_error = empty($wpdb->last_error);

    return $is_error;
  }

  /**
   * Returns registed channels
   * Accepts platform name as a filter
   * @param string $platform [filters the results to only channels on the provided platform]
   */
  public static function get_dbd_channels($platform = null)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'channels';
    if (isset($platform) && !empty($platform)) :
      $channels = $wpdb->get_results("SELECT * FROM {$table_name} WHERE platform = '{$platform}'");
    else :
      $channels = $wpdb->get_results("SELECT * FROM {$table_name}");
    endif;
    return $channels;
  }

  static function get_channel_counts()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'channels';
    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
    return isset($rowcount) ? $rowcount : 0;
  }

  public static function dbd_channel_fields()
  {
    /* 
      return name, label, type, size and default value for each channel setting input field
    */
    return array(
      'id' => array('id', null, 'hidden', 11, ''),
      'channel_name' => array('channel_name', 'Channel Name', 'text', 40, ''),
      'platform' => array('platform', 'Channel Platform', 'select', 20, ''),
      'channel_username' => array('channel_username', 'Channel Username', 'text', 20, ''),
      'channel_id' => array('channel_id', 'Channel ID', 'text', 50, ''),
      'channel_url' => array('channel_url', 'Channel URL', 'text', 50, '')
    );
  }

  static function dbd_add_channel_field($field_id, $title, $type, $size = 50, $value = '')
  {
    $page = 'dbd_channels';
    $section = 'dbd_channel_tab';
    // Add hidden class to <tr> if field $type is hidden
    $class = ($type == 'hidden') ? 'hidden' : '';
    // Exclude label for attribute for field types here
    $remove_for = array('hidden');
    $for = (in_array($type, $remove_for)) ? '' : esc_html__($field_id, 'disbydem');
    $args = array(
      'id' => esc_html__($field_id, 'disbydem'),
      'label' => esc_html__($title, 'disbydem'),
      'label_for' => $for,
      'type' => esc_html__($type, 'disbydem'),
      'size' => $size,
      'value' => $value,
      'class' => $class
    );
    // check type and dont render table row or <th> if hidden
    add_settings_field(
      $field_id,
      esc_html__($title, 'disbydem'),
      array('DBD_Channels', 'dbd_channel_render_input_field'),
      $page,
      $section,
      $args
    );
  }

  static function dbd_channel_defaults()
  {
    return array(
      'id'   => esc_html__('new channel id', 'disbydem'),
      'channel_name'   => esc_html__('new channel', 'disbydem'),
      'channel_platform'   => esc_html__('youtube', 'disbydem'),
      'channel_username'   => esc_html__('@Username', 'disbydem'),
      'channel_id'   => esc_html__('Channel ID', 'disbydem'),
      'channel_url'   => esc_html__('Channel URL', 'disbydem')
    );
  }

  public static function dbd_register_channel_settings()
  {
    // New channel
    add_settings_section(
      'dbd_section_channel',
      esc_html__('Channel Details', 'disbydem'),
      array('DBD_Channels', 'dbd_callback_display_channel'),
      'dbd_channels'
    );

    add_settings_field(
      'channel_name',
      esc_html__('Custom Channel Name', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_channel_name'),
      'dbd_channels',
      'dbd_section_channel',
      ['id' => esc_html__('channel_name', 'disbydem'), 'label' => esc_html__('Channel Name', 'disbydem'), 'channel' => ($channel ?? null)]
    );

    add_settings_field(
      'channel_platform',
      esc_html__('Select Channel Platform', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_select'),
      'dbd_channels',
      'dbd_section_channel',
      ['id' => esc_html__('channel_platform', 'disbydem'), 'label' => esc_html__('Channel platform', 'disbydem')]
    );

    add_settings_field(
      'Username',
      esc_html__('Channel Username', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_username'),
      'dbd_channels',
      'dbd_section_channel',
      ['id' => esc_html__('channel_username', 'disbydem'), 'label' => esc_html__('Username', 'disbydem')]
    );

    add_settings_field(
      'channel_id',
      esc_html__('Channel ID', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_channel_id'),
      'dbd_channels',
      'dbd_section_channel',
      ['id' => esc_html__('channel_id', 'disbydem'), 'label' => esc_html__('Channel ID', 'disbydem')]
    );

    add_settings_field(
      'channel_url',
      esc_html__('Channel URL', 'disbydem'),
      array('DBD_Channels', 'dbd_channels_callback_url'),
      'dbd_channels',
      'dbd_section_channel',
      ['id' => esc_html__('channel_url', 'disbydem'), 'label' => esc_html__('Channel URL', 'disbydem')]
    );
  }

  // callback: channel section
  public static function dbd_callback_display_channel($args)
  {
    if (!isset($args['channel']) || empty($args['channel'])) {
      echo '<p>' . esc_html__('New DBD channel form loads here', 'disbydem') . '</p>';
    } else {
      $channel = $args['channel'];
      echo '<p>' . esc_html__("Configure " . $channel->channel_name ?? null . " channel settings", 'disbydem') . '</p>';
    }
  }

  public static function dbd_channel_render_input_field($args)
  {
    $id = $args['id'];
    $label = $args['label'];
    $type = $args['type'];
    $size = $args['size'];
    $value = $args['value'];
    $platform_options = DBD_Settings::dbd_callback_select_options('platform');

    switch ($type) {
      case 'hidden':
        echo '<input type="' . $type . '" id="' . $id . '" name="' . $id . '" size="' . $size . '" placeholder="' . $label . '" value="' . $value . '">';
        break;
      case 'text':
        echo '<input id="' . $id . '" name="' . $id . '" type="' . $type . '" size="' . $size . '" placeholder="' . $label . '" value="' . $value . '"><br />';
        break;
      case 'select':
        $selected_option = $value;
        echo '<select id="' . $id . '" name="' . $id . '">';
        foreach ($platform_options as $value => $option) {
          $selected = selected($selected_option === $value, true, false);
          echo '<option value="' . $value . '"' . $selected . '>' . $option . '</option>';
        }
        echo '</select><br />';
        break;
      default:
        echo '<input disabled id="' . $id . '" name="' . $id . '" type="' . $type . '" size="' . $size . '" placeholder="' . $label . '" value="' . $value . '"><br />';
    }
  }

  // callback: channel section
  public static function dbd_callback_add_channel()
  {
    echo '<p>' . esc_html__('Add the details for this channel, load each channel in settings', 'disbydem') . '</p>';
  }

  // callback: new Channel name
  static function dbd_channels_callback_channel_name($args)
  {
    echo '<br>';
    $label = isset($args['label']) ? $args['label'] : '';
    echo '<input id="channel_name" name="channel_name" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }

  // callback: new Channel ID
  static function dbd_channels_callback_channel_id($args)
  {
    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';
    echo '<input id="' . $id . '" name="' . $id . '" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }

  // callback: new Channel Username
  static function dbd_channels_callback_username($args)
  {
    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';
    echo '<input id="' . $id . '" name="channel_username" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }

  // callback: new Channel URL
  static function dbd_channels_callback_url($args)
  {
    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';
    echo '<input id="' . $id . '" name="' . $id . '" type="text" size="40" placeholder="' . $label . '" value=""><br />';
  }

  // callback: new channel platform
  public static function dbd_channels_callback_select($args)
  {
    $options = self::dbd_channel_defaults();
    $id      = isset($args['id']) ? $args['id'] : '';
    $label   = isset($args['label']) ? $args['label'] : '';
    $selected_option = isset($options['platform']) ? sanitize_text_field($options['platform']) : '';
    $select_options = DBD_Settings::dbd_callback_select_options('platform');
    echo '<select id="' . $id . '" name="' . $id . '">';
    foreach ($select_options as $value => $option) {
      $selected = selected($selected_option === $value, true, false);
      echo '<option value="' . $value . '"' . $selected . '>' . $option . '</option>';
    }
  }

  // New channel form
  public static function new_channel_form()
  {
    if (isset($_POST['add_channel'])) {
      DBD_Channels::add_new_dbd_channel();
    }
?>
    <form action="" role="presentation" method="post" class="channel_add">
      <?php
      // output setting sections
      do_settings_sections('dbd_channels');

      // submit button
      submit_button('Add Channel', 'primary', 'add_channel');
      ?>
      <hr>
    </form>
  <?php
    return;
  }

  // Channel Details Form
  public static function edit_channel_form($channel)
  {
    // update channel details
    if (isset($_POST['edit_dbd_channel'])) {
      DBD_Channels::edit_dbd_channel();
    }
  ?>
    <form action="" role="presentation" method="post" class="edit_channel">
      <?php
      settings_fields('dbd_channels');
      echo '<table class="form-table">';

      $inputs = DBD_Channels::dbd_channel_fields();
      foreach ($channel as $key => $field_value) {
        $field_id = $inputs[$key][0];
        $title = $inputs[$key][1];
        $type = $inputs[$key][2];
        $size = $inputs[$key][3];
        $value = $field_value;
        DBD_Channels::dbd_add_channel_field($field_id, $title, $type, $size, $value);
      }

      // output edit channel setting sections
      do_settings_fields('dbd_channels', 'dbd_channel_tab');
      echo '</table>';

      // submit button
      submit_button('Edit Channel', 'secondary', 'edit_dbd_channel');
      write_log("Update form loaded for dbd_channel \n");
      ?>
      <hr>
    </form>
<?php
  }


  // Validate channel inputs
  public static function dbd_callback_validate_channel($input)
  {
    $error = array();
    $warning = array();

    $platfom_options = DBD_Settings::dbd_callback_select_options('platform');

    // channel name
    if (isset($input['channel_name'])) {
      $input['channel_name'] = sanitize_text_field($input['channel_name']);
    }
    // channel platform supported
    if (isset($input['channel_platform']) && !array_key_exists(sanitize_text_field($input['channel_platform']), $platfom_options)) {
      $error['channel_platform'] = 'Channel platform not supported';
      $input['channel_platform'] = null;
    }
    // channel username
    if (isset($input['channel_username'])) {
      $warning['channel_username'] = 'Channel username not set';
      $input['channel_username'] = sanitize_text_field($input['channel_username']);
    }
    // channel ID
    // ToDo: Check if channel id exists
    if (isset($input['channel_id'])) {
      $input['channel_id'] = sanitize_text_field($input['channel_id']);
    }
    // channel url
    if (isset($input['channel_url'])) {
      $input['channel_url'] = sanitize_url($input['channel_url']);
    }

    if (count($error) > 0) {
      $input['errors'] = $error;
    }

    if (count($warning) > 0) {
      $input['warnings'] = $warning;
    }

    return $input;
  }

  // Add new channel
  public static function add_new_dbd_channel()
  {
    if (isset($_POST['add_channel'])) {
      DBD_Channels::dbd_callback_validate_channel($_POST['add_channel']);

      if (!empty($_POST['errors'])) {
        print_r($_POST['errors']);
        wp_die('Channel fields not valid');
      } else {
        global $wpdb;
        $table_name = $wpdb->prefix . 'channels';

        $data_ = array(
          'channel_name' => $_POST['channel_name'],
          'platform' => $_POST['channel_platform'],
          'channel_username' => $_POST['channel_username'],
          'channel_id' => $_POST['channel_id'],
          'channel_url'  => $_POST['channel_url'],
        );

        $result = $wpdb->insert($table_name, $data_, array('%s', '%s', '%s', '%s', '%s'));

        if (false === $result) {
          print_r($wpdb->last_error);
          // wp_die('Failed to add channel');
        } else {
          echo "Channel added";
        }
        // return print_r($_POST['errors']);
      }
    }
  }

  // update channel
  public static function edit_dbd_channel()
  {
    if (isset($_POST['edit_dbd_channel'])) {
      DBD_Channels::dbd_callback_validate_channel($_POST['edit_dbd_channel']);

      if (!empty($_POST['errors'])) {
        write_log('Found field errors in validate');
        write_log($_POST['errors']);
        wp_die('Channel fields not valid');
      } else {
        global $wpdb;
        $table_name = $wpdb->prefix . 'channels';

        // Get corresponding id row from table| check against new data
        $id = $_POST['id'];
        $data_ = array(
          'channel_name' => $_POST['channel_name'],
          'platform' => $_POST['platform'],
          'channel_username' => $_POST['channel_username'],
          'channel_id' => $_POST['channel_id'],
          'channel_url'  => $_POST['channel_url'],
        );

        $where = array('id' => $id);

        $updated = $wpdb->update($table_name, $data_, $where, array('%s', '%s', '%s', '%s', '%s'), array('%d'));

        if (false === $updated) {
          write_log($wpdb->last_error);
          // wp_die('Failed to add channel');
        } else {
          echo "Channel updated";
          // reload form
        }
      }
    }
  }
}
