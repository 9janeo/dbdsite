<?php

/**
 * Add video_schema post meta to post on save or update based on schema changes
 * @param [type] $post_id [description]
 * @param [type] $videoID [Youtube Video ID]
 */
function dis_by_dem_video_info($post_id, $videoID)
{
  // get existing meta from post_id
  $post_etag = get_post_meta($post_id, 'etag', true);
  if ((gettype($videoID) == 'object') || empty($videoID)) {
    $videoID = get_post_meta($post_id, 'video_id', true);
  }
  $hostname = get_site_url();
  $yt_key = get_site_option('Youtube_API_key');
  $videoLink = "https://www.youtube.com/watch?v={$videoID}&ab_channel=DISBYDEM";

  $req_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" . $videoID . "&key=" . $yt_key;
  $response = wp_remote_get($req_url);
  $code = wp_remote_retrieve_response_code($response);
  $result = json_decode(wp_remote_retrieve_body($response));
  if ($code == 200) {
    $item = $result->items[0];
    $vid_snippet = $item->snippet;
    error_log("===== hit dis_by_dem_video_info vid_snippet =====\n");
    // error_log(json_encode($vid_snippet));
    $vid_tags = $vid_snippet->tags;
    // prepare schema values
    $thumb = 'https://i.ytimg.com/vi/' . $videoID . '/hqdefault.jpg';
    $name = 'Dis By Dem Video Link';
    $description = 'Created by Tale Adewole';
    $published = get_the_date();
    $upload_date = date("Y-m-d", strtotime($published));
    // build schema for video
    // $vid_schema = array(
    //   '@type'         => 'VideoObject',
    //   '@id'           => $hostname . '#/schema/VideoObject/{{' . $id . '}}',
    //   'name'           => $name,
    //   'description'   => $description,
    //   'thumbnail'     => $thumb,
    //   'uploadDate'     => $upload_date
    // );

    // Only do this if post etag is empty or different
    if (isset($post_etag) && $post_etag !== $item->etag) {
      // Set both video schema and info meta:
      $metaValues = array(
        // 'post_video_schema' => $vid_schema,
        'video_info' => $vid_snippet->description,
        'etag' => $item->etag,
        'resourceId' => $item->id,
        'video_thumb' => $thumb,
        'video_link' => $videoLink,
        'publishedAt' => $vid_snippet->publishedAt
      );
      foreach ($metaValues as $metaKey => $metaValue) {
        update_post_meta($post_id, $metaKey, $metaValue);
      }

      //Pull tags from YT and add them to existing post tags
      if (isset($vid_tags) && $vid_tags) {
        // $post_tags = get_the_tags($post_id);
        $post_tags = array();
        foreach (get_the_tags($post_id) as $term) {
          $post_tags[] = $term->name;
        }
        // $post_tags = get_tags($post_id);
        error_log("Current post tags " . json_encode($post_tags) . " for post $post_id \n");
        $new_tags = [];
        foreach ($vid_tags as $tag) {
          if (empty($post_tags) || !in_array($tag, $post_tags)) {
            error_log("Check if vid tag " . $tag . " in post tags for post $post_id " . (in_array($tag, $post_tags) ? '-yes' : '-no') . "\n");
            $new_tags[] = $tag;
          }
        }
        if (!empty($new_tags)) {
          wp_set_post_tags($post_id, $new_tags, true);
          error_log("Added " . json_encode($new_tags) . " tags to $post_id \n");
        }
      }
    }
  }

  // endif;
}
add_action('save_post', 'dis_by_dem_video_info', 10, 2);
add_action('dbd_schedule_video_meta_and_tag_update', 'dis_by_dem_video_info', 10, 2);


// add_action('save_post', 'dis_by_dem_strip_meta_info', 10, 2);
/**
 * Removes video_schema post meta to post on save or update based on schema changes
 * @param [type] $post_id [description]
 */
function dis_by_dem_strip_meta_info($post_id)
{

  // $vid_schema = array(
  //   '@type'         ,
  //   '@id'           ,
  //   'name'          ,
  //   'description'   ,
  //   'thumbnail'     ,
  //   'uploadDate'    ,
  // );
  // Set both video schema and info meta:
  $metaValues = array(
    'post_video_schema',
    'video_info',
    'etag',
    'resourceId',
    'video_thumb',
    'video_link',
    'publishedAt'
  );

  foreach ($metaValues as $metaKey) {
    delete_post_meta($post_id, $metaKey);
    error_log("cleared $metaKey from $post_id");
  }
}
