<div class="wrap">
  <h1>Channel Settings</h1>
  <div class="dbd-box">
    <form role="presentation" action="options.php" method="post">
      <?php
      // output security fields
      settings_fields('channel_settings');

      // output setting sections
      do_settings_sections('dbd_channels');

      // submit button
      submit_button('Add Channel');

      ?>

    </form>
    <form role="presentation" action="options.php" method="post">
      <?php
      // output security fields
      settings_fields('dbd_options'); 

      // output setting sections
      do_settings_sections('dbd_admin_menu');

      // submit button
      submit_button();

      ?>

    </form>
  </div>