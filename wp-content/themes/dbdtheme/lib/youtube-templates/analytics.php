<?php
if (!current_user_can('manage_options')) {
  wp_die('You do not have sufficient permissions to access this page.');
}

if ($_GET["page"] === 'video-analytics' || is_page('videos')) {
  // include_once('yt-service.php');
  wp_enqueue_script('custom-admin-script');
}
// $service = Dbd_Youtube::service();

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
            <span class="small"><?= $channel->id . ' || ' . $channel->platform .' || '. $channel->channel_url ?></span>
          </div>
        </div>
      </div>
      
      <div id="<?= $channel->channel_username ?>" class="p-3 accordion-collapse collapse">
        <p class="mb-0">Display the videos for this channel</p>
        <?php
        if ($channel->platform == 'youtube') {
          $channel_id = $channel->channel_id;
          // $channel_vids = Dbd_Youtube::get_channel_videos($channel);
          // print_r($channel_vids);
        ?>
        <!-- <h1>Youtube Analytics</h1> -->
        <?php
          // $videos = Dbd_Youtube::get_all_videos();
          // var_dump($videos);
        ?>
        <h4>Playlists</h4>
        <?php
          // Display the video analytics page
          $playlists = Dbd_Youtube::get_playlists_with_items($channel_id);

          // foreach($playlists as $key => $playlist){
          //   echo '<h3> Playlist ['. $key .'] '. $playlist->na
          // }


          // print_r($playlist);
          // var_dump($playlists);
          // if (isset($playlists->error)) :
          if (isset($playlists) && $playlists) :
            Dbd_Admin::display_playlists($playlists, true);
            // print_r($playlist);
          endif;
        }
        ?>
      </div>
    </div>
  <?php
  }

  // xdebug_info();

  // Dbd_Youtube::display_playlists($playlists);

  /*
  if (isset($playlists) && $playlists) {
    if (!($playlists->error)) { ?>
      <div class="yt playlists row row-cols-2">
        <?php foreach ($playlists->items as $key => $playlist) {
          $id = $playlist->id;
          if (!($playlist->contentDetails->itemCount > 0)) {
            // skip playlist if no items in it
            continue;
          }
          $videos = DBD_Youtube::get_playlist_items($id);
          // get the playlist items
          get_template_part('lib/youtube-templates/playlists', 'playlists', array('playlist' => $playlist, 'videos' => $videos));
        } ?>
      </div>
  <?php
    }
  } */ ?>
</div>