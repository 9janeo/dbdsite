<?php
$first_tab = 'new_channel';
$channel_count = DBD_Channels::get_channel_counts();
if ($channel_count > 0) :
  $channel_settings = DBD_Channels::get_dbd_channels();
  $first_tab = sanitize_title($channel_settings[0]->channel_name);
endif;
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : $first_tab;
?>

<div class="wrap">
  <h1>Channel Settings</h1>

  <div class="dbd-box">
    <h2 class="nav-tab-wrapper channel-tabs">
      <?php if ($channel_count > 0) :  ?>
        <?php foreach ($channel_settings as $channel) {
          $name = $channel->channel_name;
        ?>
          <a href="?page=dbd_channels&tab=<?= sanitize_title($name) ?>" class="nav-tab <?php echo ($active_tab == sanitize_title($name)) ? "nav-tab-active notice-warning" : "" ?>"><?php echo $name ?></a>
        <?php } ?>
      <?php endif; ?>
      <a href=" ?page=dbd_channels&tab=<?= "new_channel" ?>" class="nav-tab"><?php echo "New" ?></a>
    </h2>

    <?php if ($channel_count > 0) :
      foreach ($channel_settings as $channel) {
        $channel_name = $channel->channel_name;
        if (($active_tab == sanitize_title($channel_name)) && ($active_tab != 'new_channel')) :
          DBD_Channels::edit_channel_form($channel);
        endif;
      }
    endif;
    // add new channel to channel_settings
    if ($active_tab == 'new_channel') :
      DBD_Channels::new_channel_form();
    endif;
    // DBD_Channels::dbd_callback_display_channel();
    ?>
  </div>

</div>