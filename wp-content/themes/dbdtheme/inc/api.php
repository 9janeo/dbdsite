<?php

/**
 * Accepts a hook to return the latest scheduled job using that hook
 * @param [string] $hook [Filters results for only jobs using this hook]
 */
function dbd_get_latest_scheduled($hook)
{
  $cron_jobs = get_option('cron');
  $filtered_crons = array();
  foreach ($cron_jobs as $timestamp => $cron) {
    if ((gettype($cron) == 'array') && array_key_exists($hook, $cron)) :
      $filtered_crons[$timestamp] = $cron;
    endif;
  }
  // filter only elements with a timestamp key
  $stamped = array_filter(array_keys($filtered_crons), 'is_int');
  $latest = $filtered_crons ? max($stamped) : time();
  return $latest;
}

/**
 * Accepts and object with post_id and resource_id key value pairs to queue up for meta updates
 * @param [object] $queue [contains key/value pairs of post_id and resource_id and schedules each pair]
 */
function schedule_video_details_update($playlists)
{
  // Initialize counter for sequential scheduling
  $increment = 0;
  foreach ($playlists as $list) {
    if (isset($list->items)) {
      foreach ($list->items as $item) {
        $resource_id = $item->contentDetails->videoId;
        $post_id = $item->wp_id;
        // set as a cron jobs every 3 seconds intervals
        if (get_post_type($post_id) == 'youtube-post' && gettype($resource_id) == 'string' && get_post_status($post_id) != 'publish') {
          $latest = dbd_get_latest_scheduled('dbd_schedule_video_meta_and_tag_update') + (3 * $increment);
          $args = array($post_id, $resource_id);
          wp_schedule_single_event($latest, 'dbd_schedule_video_meta_and_tag_update', $args);
          $increment++;
        }
      }
    }
  }
}

/**
 * Add video_schema post meta to post on save or update based on schema changes
 * @param [int] $post_id [description]
 * @param [string] $videoID [Youtube Video ID]
 */
function dis_by_dem_video_info($post_id, $videoID)
{
  error_log("Running job for post(" . json_encode($post_id) . ") with vid_ID " . json_encode($videoID));
  if ((gettype($videoID) == 'object') || empty($videoID)) {
    $videoID = get_post_meta($post_id, 'video_id', true);
  }

  $yt_key = get_site_option('Youtube_API_key');
  $videoLink = "https://www.youtube.com/watch?v={$videoID}&ab_channel=DISBYDEM";
  $req_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" . $videoID . "&key=" . $yt_key;
  $response = wp_remote_get($req_url);
  $code = wp_remote_retrieve_response_code($response);
  $result = json_decode(wp_remote_retrieve_body($response));
  if ($code == 200) {
    if (isset($result->items) && $result->items) {
      error_log("===== hit dis_by_dem_video_info vid_snippet =====\n");
      $item = $result->items[0];
      $vid_snippet = $item->snippet;
      $thumb = 'https://i.ytimg.com/vi/' . $videoID . '/hqdefault.jpg';
      $description = $vid_snippet->description;
      $published = $vid_snippet->publishedAt;
      $name = $vid_snippet->title ? $vid_snippet->title : 'A blog post on DISBYDEM by Tale Adewole';

      //Pull tags from YT and add them to existing post tags
      if (isset($vid_snippet->tags) && $vid_snippet->tags) {
        $current_tags = get_the_tags($post_id);
        $post_tags = array();
        foreach ($current_tags as $term) {
          $post_tags[] = sanitize_title($term->name);
        }

        $new_tags = [];
        foreach ($vid_snippet->tags as $tag) {
          if (empty($post_tags) || !has_tag(sanitize_title($tag), $post_id)) {
            $new_tags[] = $tag;
          }
        }
        if (!empty($new_tags)) {
          error_log("Current post tags " . json_encode($post_tags) . " for post $post_id \n");
          wp_set_post_tags($post_id, $new_tags, true);
          error_log("Added " . json_encode($new_tags) . " tags to $post_id \n");
        }
      }

      // Schema specific variables || prepare schema values 
      $hostname = get_site_url();


      $upload_date = date("Y-m-d", strtotime($published));

      // build schema for video schema
      $vid_schema = array(
        '@type'         => 'VideoObject',
        '@id'           => $hostname . '#/schema/VideoObject/{{' . $videoID . '}}',
        'name'           => $name,
        'description'   => $description,
        'thumbnail'     => $thumb,
        'uploadDate'     => $upload_date
      );

      // Set video meta info:
      $metaValues = array(
        'video_info' => $vid_snippet->description,
        'etag' => $item->etag,
        'resourceId' => $item->id,
        'video_thumb' => $thumb,
        'video_link' => $videoLink,
        'publishedAt' => $vid_snippet->publishedAt,
        'post_video_schema' => $vid_schema,
      );
      foreach ($metaValues as $metaKey => $metaValue) {
        update_post_meta($post_id, $metaKey, $metaValue);
      }
    }

    // Go through publish checklist
    dbd_publish_checklist($post_id);

    // set featured image using YT thumbnail
    // - download image and save as attachement
    // - set attachment as post featured image
  }
}
add_action('dbd_schedule_video_meta_and_tag_update', 'dis_by_dem_video_info', 10, 2);


// add_action('save_post', 'dis_by_dem_strip_meta_info', 10, 2);
/**
 * Removes video_schema post meta to post on save or update based on schema changes
 * @param [int] $post_id [description]
 */
function dis_by_dem_strip_meta_info($post_id)
{
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

/**
 * Checks a list of critera and updates post status from draft to publish if met
 * @param [int] $post_id [description]
 */
function dbd_publish_checklist($post_id)
{
  // if meta has values for 
  $has_video_id = metadata_exists('post', $post_id, 'video_id');
  $has_video_schema = metadata_exists('post', $post_id, 'post_video_schema');
  $has_tags = has_tag('', $post_id);
  if ($has_video_id && $has_video_schema && $has_tags) {
    // check post status is not published
    if (get_post_status($post_id) != 'publish' && get_post_type($post_id) == 'youtube-post') {
      error_log("Checklist items confirmed, publishing... " . $post_id);
      $yt_post = array('ID' => $post_id, 'post_status' => 'publish');
      wp_update_post($yt_post);
    }
  }
}
