<div class="wrap">
  <h1>Channel Settings</h1>
  <div class="dbd-box">
    <!-- <h3>Pull Channel Settings </h3> -->
    <?php
      $channel_settings = get_option('channel_settings');
      // var_dump($channel_settings);
    ?>
    <?php // add new channel to channel_settings ?>
    <form role="presentation" action="options.php" method="post">
      <?php
      // output security fields
      settings_fields('channel_settings');

      // output setting sections
      // print_r('Doing settings section new channel');
      do_settings_sections('dbd_channels');
      // do_settings_sections('dbd_section_new_channel');

      // submit button
      submit_button('Update Channels');
      // submit_button('Add Channel');

      ?>
      <hr>
      <?php

      // for each channel in settings => Iterate
      // if(isset($channel_settings) && $channel_settings):?>
      <!-- <div class="card"> -->
        <?php
          // foreach($channel_settings as $channel){
          //   do_settings_sections('dbd_channels');
          //   // submit button
          //   submit_button('Update Channel');
          // }
        ?>
      <!-- </div> -->
      <?php
      // else:
      //   echo "<h3>cant find any channels</h3>";
      //   do_settings_sections('dbd_channels');
      //   submit_button('Add Channel');
      // endif;
      ?>

    </form>
  </div>