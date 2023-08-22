<?php

/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

  <header class="entry-header">
    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    <div class="entry-meta bg-secondary"><?php understrap_posted_on(); ?></div>
  </header>

  <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>

  <div class="entry-content">
    <?php
    the_content();
    understrap_link_pages();
    ?>
  </div>

  <footer class="entry-footer">
    <?php
    // print_r("\n\n<br><h3>Post Meta</h3><br>");
    // var_dump(get_post_meta($post->ID));
    // print_r("\n<br>");
    $url = get_field('video_link');
    $url = get_post_meta($post->ID, 'video_link', true);
    $link = get_post_meta($post->ID, 'video_link', true);
    $video_id = get_post_meta($post->ID, 'video_id', true);
    $vid_exists = (isset($link) && isset($video_id));
    if ($vid_exists) :
      $publishedAt = get_post_meta($post->ID, 'publishedAt', true);
      $thumb = get_post_meta($post->ID, 'video_thumb', true);
      $title = get_the_title();
      $desc = get_the_content();
      // move link building to save as youtube post function
      $url_pattern = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
      // preg_match_all($url_pattern, $desc, $matches);
      // var_dump($matches);
      // $desc = preg_replace($url_pattern, '<a href="$0" target="_blank">$0</a>', $desc);
    ?>
      <div class="yt-meta card">
        <!-- <a href="<?php // echo $url 
                      ?>" target="_blank"><img class="card-img-top" src="<?php // echo $thumb 
                                                                                            ?>" /></a> -->
        <div class="card-body">
          <h3 class="card-title"><a href="<?php echo $link ?>" target="_blank"><?php echo $title ?></a></h3>
          <!-- <p class="card-text"><?php // echo $desc 
                                    ?></p> -->
          <p class="small card-text pull-left">Published: <?php echo date("Y-m-d", strtotime($publishedAt)); ?></p>
        </div>
      </div>
    <?php endif; ?>

    <?php // understrap_entry_footer(); 
    ?>

  </footer><!-- .entry-footer -->

</article><!-- #post-## -->