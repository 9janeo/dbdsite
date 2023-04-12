<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

// Register the options page for viewing video analytics
// begin wrapping in classes
require_once('class.dbd-admin.php');
add_action('admin_menu', array('Dbd_Admin', 'init'), 0); # Start initializationn of custom YT classes

add_action('admin_menu', 'register_video_analytics_page');
function register_video_analytics_page()
{
  add_options_page(
    'Video Analytics',
    'Video Analytics',
    'manage_options',
    'video-analytics',
    'display_video_analytics'
  );
}

// Display the video analytics page
function display_video_analytics()
{
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
  }

  if ($_GET["page"] === 'video-analytics' || is_page('videos')) {
    include_once('yt-service.php');
    wp_enqueue_script('custom-admin-script');
  }

  get_template_part('lib/youtube-templates/settings', 'settings');
  get_template_part('lib/youtube-templates/videos', 'videos');



  // Dbd_Menu::load_menu();

  // $playlists = get_playlists($service);
  if (isset($playlists) && $playlists) {
    if (!($playlists->error)) {
?>
      <div class="yt playlists row row-cols-2">
        <?php
        foreach ($playlists->items as $key => $playlist) {
          $id = $playlist->id;
          if (!($playlist->contentDetails->itemCount > 0)) {
            // skip playlist if no items in it
            // print_r("<br><h3>This playlist has no videos!!!</h3><br>");
            continue;
          }
          $videos = get_playlists_items($service, $id);
          // get the playlist items
          get_template_part('lib/youtube-templates/playlists', 'playlists', array('playlist' => $playlist, 'videos' => $videos));
        }
        ?>
      </div>
<?php
    }
  }
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
 * Returns the playlists for a channel
 * @param  mixed  $service      [Youtube service]
 * @return mixed  $args         [The query params for the call]
 */
function get_playlists($service)
{
  if (false === ($channel_playlists = get_transient('channel_playlists'))) {
    $queryParams = [
      'channelId' => 'UCglE7vDtPHuulBhLvn9Q-eg',
      'maxResults' => 25
    ];
    $channel_playlists = $service->playlists->listPlaylists('snippet,contentDetails,status', $queryParams);
    set_transient('channel_playlists', $channel_playlists, DAY_IN_SECONDS);
  }
  return $channel_playlists;
}

/**
 * Returns the videos for a playlist
 * @param  mixed  $service      [Youtube service]
 * @return mixed  $playlist_id  [The id of the playlist we want]
 */
function get_playlists_items($service, $playlist_id)
{
  $queryParams = [
    'maxResults' => 25,
    'playlistId' => $playlist_id
  ];
  if (false === ($playlist_items = get_transient('playlist_items'))) {
    $playlist_items = $service->playlistItems->listPlaylistItems('snippet,contentDetails,status', $queryParams);
    write_log('Setting Playlist Items transient for playlist - ' . $playlist_id);
    set_transient('playlist_items', $playlist_items, DAY_IN_SECONDS);
  } else {
    write_log('Retrieving Playlist Items transient for playlist - ' . $playlist_id);
  }
  return $playlist_items;
}


/**
 * Returns the videos for a playlist
 * @param  mixed  $service      [Youtube service]
 * @return mixed  $playlist_id  [The id of the playlist we want]
 */
function get_all_videos($service)
{
  $queryParams = [
    'chart' => 'mostPopular',
    'regionCode' => 'CA',
    'maxResults' => 3
    // 'id' => 'UCglE7vDtPHuulBhLvn9Q-eg'
  ];
  $response = $service->videos->listVideos('snippet,contentDetails,statistics', $queryParams);
  return $response;
}


/**
 * Returns a url based on the source type provided
 * @return string  $resource_id  [The id of the resource]
 * @return string  $source_type  [The type of resource link to be generated]
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
