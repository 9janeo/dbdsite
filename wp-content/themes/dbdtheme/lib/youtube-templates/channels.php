<div class="wrap">
  <h1>Channel Settings</h1>
  <div class="dbd-box">
    <!-- <h3>Pull Channel Settings </h3> -->
    <?php

    // var_dump($channel_settings);
    ?>
    <?php // add new channel to channel_settings 
    ?>
    <form role="presentation" action="options.php" method="post">
      <?php
      // output security fields
      settings_fields('channel_settings');

      // output setting sections
      // print_r('Doing settings section new channel');
      do_settings_sections('dbd_channels');
      // do_settings_sections('dbd_section_new_channel');

      // submit button
      submit_button('Add Channel');
      // submit_button('Add Channel');

      ?>
      <hr>
    </form>
    <?php
    if (DBD_Channels::get_channel_counts() > 0) :
      $channel_settings = get_option('channel_settings');
    ?>
      <hr>
      <!-- Create a header in the default WordPress 'wrap' container -->
      <div class="wrap">

        <div id="icon-themes" class="icon32"></div>
        <h2>Channel Details</h2>
        <?php
        settings_errors();

        var_dump($channel_settings);
        ?>

        <h2 class="nav-tab-wrapper">
          <?php foreach ($channel_settings as $key => $channel) {
            $name = $channel_settings[$key]['channel_name'] ? $channel_settings[$key]['channel_name'] : ($key . '_blank');
          ?>
            <a href="?page=dbd_channels&tab=<?= $name ?>" class="nav-tab"><?php echo 'Channel ' . ($key) . ' (' . $name . ')' ?></a>
          <?php } ?>
        </h2>

        <!-- <form method="post" action=""> -->
        <?php
        /* foreach ($channel_settings as $key => $channel) {
            add_settings_field(
              'channel_name',
              esc_html__('The channel Name', 'disbydem'),
              array('DBD_Channels', 'dbd_channels_callback_field_text'),
              'dbd_channels',
              'dbd_section_channels',
              ['channel' => $key, 'id' => 'channel_name', 'label' => esc_html__('Channel Name', 'disbydem')]
            );

            add_settings_field(
              'channel_platform',
              esc_html__('The channel platform', 'disbydem'),
              array('DBD_Channels', 'dbd_callback_platform_select'),
              'dbd_channels',
              'dbd_section_channels',
              ['channel' => $key, 'id' => 'channel_platform', 'label' => esc_html__('Channel platform', 'disbydem')]
            );
            submit_button('Save');
            }  */
        ?>
        <!-- </form> -->

      </div><!-- /.wrap -->
    <?php endif; ?>
  </div>