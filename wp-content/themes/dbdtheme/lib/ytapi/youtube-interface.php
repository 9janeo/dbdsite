<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

// Register the options page for viewing video analytics
// begin wrapping in classes
require_once('class.dbd-admin.php');
add_action('init', array('Dbd_Admin', 'init'), 0); # Start initializationn of custom YT classes

// default plugin options. these are used until the user makes edits
function dbd_options_default()
{
  return array(
    'custom_url'     => 'https://disbydem.com/',
    'custom_title'   => esc_html__('What\'s your DBD\'ers scale?', 'disbydem'),
    'custom_style'   => 'disable',
    'custom_message' => '<p class="custom-message">' . esc_html__('My custom message', 'disbydem') . '</p>',
    'custom_footer'  => esc_html__('Special message for users', 'disbydem'),
    'custom_toolbar' => false,
    'custom_scheme'  => 'default',
    'custom_api_key'  => 'default',
  );
}

// Retrieve the list of videos from the YouTube API
function get_videos_from_youtube_api($key)
{
  // Replace this with your own code to make a request to the YouTube API
  $req_url = "https://www.googleapis.com/youtube/v3/videos?key=" . $key . "&part=snippet,contentDetails,statistics&Id=UC_x5XG1OV2P6uZZ5FSM9Ttw";
  $response = wp_remote_get($req_url);
  $code = wp_remote_retrieve_response_code($response);
  $body = wp_remote_retrieve_body($response);
  $result = json_decode($body);
  return $result;
}

// $response = $service->channels->listChannels('snippet,contentDetails,statistics,status', $queryParams);
function load_channel_details($channel_id, $client, $service)
{
  $massmutterer = (object) array(
    'user_id' => 'd7P5_8tEWJdB3twoTI3vYg',
    'channel_id' => 'UCd7P5_8tEWJdB3twoTI3vYg',
    'channel_name' => 'MassMutterer'
  );

  $client->setAuthConfig(__DIR__ . '/../client_secret.json');

  if ($channel_id) {
    $queryParams = [
      // 'forUsername' => 'DISBYDEM'
      'forUsername' => $massmutterer->channel_name
    ];
    // $service = new Google\Service\YouTube($client);
    $response = $service->channels->listChannels('snippet,contentOwnerDetails,statistics,status', $queryParams);
    print_r($response);
    return $response;
  } else {
    return 'Set Channel Name and ID to view details';
  }
}

function load_videos($channel_id, $client, $service)
{
  $queryParams = [
    'id' => 'Ks-_Mh1QhMc'
  ];
  $response = $service->videos->listVideos('snippet,contentDetails,statistics,status', $queryParams);
  return $response;
}

/**
 * Returns a url based on the source type provided
 * @param string  $resource_id  [The id of the resource]
 * @param string  $source_type  [The type of resource link to be generated]
 * @return string  $source_type  [The generated url for the resource]
 */
function build_link($id, $type = '')
{
  $base = 'https://www.youtube.com/watch?v=';
  $tail = "&ab_channel=DISBYDEM";
  if ($type == 'video') {
    $link = $base . $id . $tail;
  } else {
    $link = $base . $id;
  }
  return $link;
}


/**
 * Creates WP categories from playlist names if not already existing
 * @param string  $title  [The id of the resource]
 * @return string  $source_type  [The type of resource link to be generated]
 */
function add_to_playlist_category()
{
  // get $parent category term "playlist"
  // if playlists category does not exist, create it
  // else add $title as sub-category
  /* 
    wp_insert_term( 'My New Category', 'product_cat', array(
        'description' => 'Description for category', // optional
        'parent' => 0, // optional
        'slug' => 'my-new-category' // optional
    ) );
  */
}
