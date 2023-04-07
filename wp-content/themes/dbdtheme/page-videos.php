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

include_once(__DIR__ . '/lib/ytapi/yt-service.php');
?>

<div class="wrapper" id="videos-wrapper">
  <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">
    <div class="row">
      <h2 class="header" data-bs-toggle="collapse" data-bs-target="#transients" aria-expanded="true" aria-controls="collapseTransients">Transients</h2>
      <hr>
      <div id="transients" class="row collapse show">
        <?php
        // $transients = get_transient('channel_playlists');
        // PLgHIZMXekZton6SBh4A8aXs2-koNW9cgP -> Legenderay -> Item count 0
        // PLgHIZMXekZtogiiHWtMryQ_SCRk5z4PbC -> Shorts -> item count 42
        // PLgHIZMXekZtq9VRdfgIRSBIEBLegs2JW8 -> Book Reviews -> item count 2 || Videos not accessible, private videos

        $playlists = get_playlists($service);
        foreach ($playlists->items as $playlist) {
          $id = $playlist->id;
          $itemCount = $playlist->contentDetails->itemCount;
          if (!($itemCount > 0)) {
            continue;
          }
          $video_list = get_playlists_items($service, $id);
          get_template_part('lib/youtube-templates/playlists', 'playlists', array('playlist' => $playlist, 'videos' => $video_list));
        }
        ?>
      </div>
    </div>
    <div class="row">
      <?php
      if ($playlists) {
        if (!($playlists->error)) {
      ?>
          <h2 class="header" data-bs-toggle="collapse" data-bs-target="#video-section" aria-expanded="false" aria-controls="collapsePlayist">Playlists</h2>
          <hr>
          <div id="video-section" class="yt playlists row row-cols-2 collapse">
            <?php
            foreach ($playlists->items as $playlist) {
              $id = $playlist->id;
              // if (($playlist->status->privacyStatus == 'private') || ($playlist->contentDetails->itemCount < 1)) {
              //   unset($playlist->items[$key]);
              // }
              // $videos = get_playlists_items($service, $id);
              // get the playlist items
              // get_template_part('lib/youtube-templates/playlists', 'playlists', array('playlist' => $playlist, 'videos' => $videos));
            }
            ?>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
</div>
<?php
get_footer();
