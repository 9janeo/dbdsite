<div class="wrap">
  <h1>Channel Settings</h1>
  <div class="dbd-box">
    <!-- <h3>Pull Channel Settings </h3> -->
    <?php

    // var_dump($channel_settings);
    $channel_count = DBD_Channels::get_channel_counts() ;
    ?>
    <?php // add new channel to channel_settings 
     DBD_Channels::new_channel_form();
    ?>
    
    <?php
    if ($channel_count > 0) :
      // $channel_settings = get_option('channel_settings');
      $channel_settings = DBD_Channels::get_dbd_channels();
      var_dump($channel_settings);
    ?>
      <hr>
    <?php endif; ?>
  </div>