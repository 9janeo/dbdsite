<?php
if (!current_user_can('manage_options')) {
  wp_die('You do not have sufficient permissions to access this page.');
}

if ($_GET["page"] === 'video-analytics' || is_page('videos')) {
  wp_enqueue_script('custom-admin-script');
}

?>
<div class="wrap analytics">
  <h1>Channels</h1>
  <?php
  $channels = DBD_Channels::get_dbd_channels();
  foreach ($channels as $channel) { ?>
    <div class="accordion-item">
      <div class="accordion-header">
        <div class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#<?= $channel->channel_username ?>" aria-expanded="false" aria-controls="<?= $channel->channel_username ?>">
          <div class="col-6">
            <h4><?= $channel->channel_name ?></h4>
            <span class="small"><?= $channel->id . ' || ' . $channel->platform . ' || ' . $channel->channel_url ?></span>
          </div>
        </div>
      </div>

      <div id="<?= $channel->channel_username ?>" class="p-3 accordion-collapse collapse">
        <p class="mb-0">Display the videos for this channel</p>
        <?php
        if ($channel->platform == 'youtube') {
          $channel_id = $channel->channel_id;
        ?>
          <div>
            <?php
            $attrs = ['id' => "yt_sync", 'channel_id' => "$channel_id"];
            submit_button('Sync Channel', ' btn btn-danger', 'sync', true, $attrs);
            ?>
          </div>
          <h4>Playlists</h4>
        <?php
          // Display the video analytics page
          $channels = DBD_Channels::get_dbd_channels('youtube');
          $playlists = Dbd_Youtube::get_dbd_playlists($channels[0]->channel_id);
          // $playlists = Dbd_Youtube::get_playlists_from_yt_with_items($channel_id);
          // Dbd_Youtube::save_playlists($playlists);
          if (isset($playlists) && $playlists) :
            Dbd_Admin::display_playlists($playlists, true);
          endif;
        }
        ?>
      </div>
    </div>
  <?php
  }

  ?>

  <h1>Scheduled</h1>
  <?php dbd_get_cron_jobs(); ?>
</div>