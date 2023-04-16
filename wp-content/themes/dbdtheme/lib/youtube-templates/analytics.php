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
<div class="wrap">
  <h1>Youtube Analytics</h1>
  <?php
  $videos = Dbd_Youtube::get_all_videos();
  // var_dump($videos);

  ?>

  <h2>Playlists</h2>
  <?php
  // Display the video analytics page
  $playlists = Dbd_Youtube::get_playlists_with_items();

  // var_dump($playlists);
  if (isset($playlists) && $playlists) :
    // $pl_vids = $playlists->
    Dbd_Admin::display_playlists($playlists, false);
  endif;


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