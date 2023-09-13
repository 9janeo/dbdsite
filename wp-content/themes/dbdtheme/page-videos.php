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
      <h2 class="header" data-bs-toggle="collapse" data-bs-target="#videos" aria-expanded="false" aria-controls="collapseVideos">Videos</h2>
      <hr>
      <div id="videos" class="collapse show">
        <?php
        $channels = DBD_Channels::get_dbd_channels('youtube');
        $video_list = Dbd_Youtube::get_dbd_videos_list($channels[0]->channel_id);
        // $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
          'post_type' => 'youtube-post',
          'posts_per_page' => 12,
          'paged' => $paged,
          'meta_query' => array(
            'key' => 'video_id',
            'value' => $video_list,
            'compare' => 'IN',
          ),
        );
        $vid_query = new WP_Query($args); ?>

        <main class="site-main" id="main">
          <?php if ($vid_query->have_posts()) : ?>
            <div class="post-list card-deck row">
              <?php
              while ($vid_query->have_posts()) : ?>
                <div class="list-item col-lg-4 col-md-6">
                  <?php
                  $vid_query->the_post();
                  get_template_part('loop-templates/content', 'card');
                  ?>
                </div>
              <?php
              endwhile; ?>
            </div>
          <?php else : ?>
            <p><?php esc_html_e('Sorry, no YouTube posts matching your criteria found.'); ?></p>
          <?php endif; ?>
        </main>
        <?php
        understrap_pagination(array('total' => $vid_query->max_num_pages));
        wp_reset_postdata();
        ?>
      </div>
    </div>
    <div class="row">
      <h2 class="header" data-bs-toggle="collapse" data-bs-target="#playlist-section" aria-expanded="false" aria-controls="collapsePlayist">Playlists</h2>
      <hr>
      <div id="playlist-section" class="yt playlists collapse">
        <?php
        $channels = DBD_Channels::get_dbd_channels('youtube');
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
