<?php

/**
 * Template Name: Videos Template
 *
 * The videos page template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');

include_once(__DIR__ . '/lib/ytapi/class.dbd-admin.php');
?>

<div class="wrapper" id="videos-wrapper">
  <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">
    <div class="row">
      <h2 class="header" data-bs-toggle="collapse" data-bs-target="#transients" aria-expanded="true" aria-controls="collapseTransients">Transients</h2>
      <hr>
      <div id="transients" class="row collapse">
        <?php
        $channel_playlists = get_transient('channel_playlists');
        if (isset($channel_playlists) && $channel_playlists) :
          Dbd_Admin::display_playlists($channel_playlists, true);
        else :
          echo "<h4>There are no current transients for Channel Playlists</h4>";
        endif;
        ?>
      </div>
    </div>
    <div class="row">
      <h2 class="header" data-bs-toggle="collapse" data-bs-target="#videos" aria-expanded="false" aria-controls="collapseVideos">Videos</h2>
      <hr>
      <div id="videos" class="row collapse">
        <?php
        $channels = DBD_Channels::get_dbd_channels('youtube');
        $video_list = Dbd_Youtube::get_dbd_videos_list($channels[0]->channel_id);
        ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Title</th>
              <th scope="col">Views</th>
              <th scope="col">Analytics</th>
            </tr>
          </thead>
          <tbody class="table">
            <?php get_template_part('lib/youtube-templates/video_list', 'video_list', array_keys($video_list)); ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <h2 class="header" data-bs-toggle="collapse" data-bs-target="#playlist-section" aria-expanded="false" aria-controls="collapsePlayist">Playlists</h2>
      <hr>
      <div id="playlist-section" class="yt playlists collapse show">
        <?php
        $channels = DBD_Channels::get_dbd_channels('youtube');
        // $playlists = Dbd_Youtube::get_playlists_with_items($channels[0]->channel_id);
        $playlists = Dbd_Youtube::get_dbd_playlists($channels[0]->channel_id);
        if (isset($playlists) && $playlists) :
          Dbd_Admin::display_playlists($playlists, true);
        endif;
        ?>
      </div>
    </div>
  </div>
</div>
<?php
get_footer();
