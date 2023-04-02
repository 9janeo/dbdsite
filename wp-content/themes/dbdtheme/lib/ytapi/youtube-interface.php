<?php

// Register the options page for viewing video analytics
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

  // initialize YouTube key
  $yt_key = get_field('Youtube_API_key', 'options');
?>

  <div class="wrap">
    <h1>Settings</h1>
    <form role="presentation" action="yt_settings">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label for="channel_id">Channel ID</label></th>
            <td><input name="channelid" type="text" aria-describedby="youtube-channel-id" name="channel_id" id="channel_id" placeholder="YouTube channel id" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="channel_name">Channel Name</label></th>
            <td><input type="text" name="channel_name" id="channel_name" class="regular-text"></td>
          </tr>
          <tr>
            <td><input type="submit" name="submit" class="button button-primary" value="save">
              <input type="reset" name="reset" class="button button-danger" value="clear">
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
  <hr>
  <?php

  if ($_GET["page"] === 'video-analytics') {
    include_once('yt-service.php');
  }

  // $channel_details = load_channel_details($channel_id, $client, $service);
  // if ($channel_details) {
  //   var_dump($channel_details->items);
  // }

  $vids = load_videos($channel_id, $client, $service);
  if ($vids) {
    print_r("<br>================================<br>");
    $results = wp_remote_retrieve_body($vids);
    var_dump($results);
  }

  $playlists = get_playlists();
  if ($playlists) {
    print_r("<br>================================<br>");
    var_dump($playlists);
  }

  // Get the list of videos from the YouTube API
  // $video_list = get_videos_from_youtube_api($yt_key);

  $video_list =  (object) array(
    array(
      'title' => 'Sample Video 1',
      'views' => 1000,
      'analytics_url' => 'https://www.youtube.com/analytics/video/1',
    ),
    array(
      'title' => 'Sample Video 2',
      'views' => 500,
      'analytics_url' => 'https://www.youtube.com/analytics/video/2',
    ),
    array(
      'title' => 'Sample Video 3',
      'views' => 2000,
      'analytics_url' => 'https://www.youtube.com/analytics/video/3',
    ),
  );

  // print_r("video_list:- \n");
  // var_dump($video_list);

  ?>
  <div class="wrap">

    <h1>Video Analytics</h1>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th scope="col">Video Title</th>
          <th scope="col">Views</th>
          <th scope="col">Analytics</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // print_r("video_list: \n");
        // var_dump($video_list)
        ?>
        <?php if (!(property_exists($video_list, 'error'))) :
          foreach ($video_list as $video) : ?>
            <tr>
              <td><?php echo $video['title']; ?></td>
              <td><?php echo $video['views']; ?></td>
              <td><a href="<?php echo $video['analytics_url']; ?>">View Analytics</a></td>
            </tr>
          <?php endforeach;
        else : ?>
          <tr>
            <td><?php echo $video_list->error->message ?></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
<?php
}

// Retrieve the list of videos from the YouTube API
function get_videos_from_youtube_api($key)
{
  // Replace this with your own code to make a request to the YouTube API

  $req_url = "https://www.googleapis.com/youtube/v3/videos?key=" . $key . "&part=snippet,contentDetails,statistics&Id=UC_x5XG1OV2P6uZZ5FSM9Ttw";
  // $req_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=".$id."&key=".$yt_key;
  // $req_url = "https://www.googleapis.com/youtube/v3/videos?key=".$key;
  $response = wp_remote_get($req_url);
  $code = wp_remote_retrieve_response_code($response);
  var_dump($code);
  print_r("<br> === <br> \n");
  $body = wp_remote_retrieve_body($response);
  var_dump($body);
  print_r("<br> === <br> \n");
  $result = json_decode($body);
  print_r("<br> \n");
  // if ($code == 200){
  // 	$videos = $result;
  // 	// $vid_snippet = $result->items[0]->snippet;
  // 	// update_post_meta($post_ID, 'video_info', $vid_snippet);
  // 	// $vid_title = $vid_snippet->title;
  // 	// $vid_desc = $vid_snippet->description;
  // 	// $vid_published = $vid_snippet->publishedAt;
  //     print_r("get_videos_from_youtube > result <br> \n");
  //     var_dump($result);
  //     print_r("<br> \n");
  // }
  return $result;
  // and retrieve the list of videos, views, and analytics URL
  // GET https://www.googleapis.com/youtube/v3/videos

  // For example purposes, let's just return a dummy list of videos
  // return print_r($videos);
  // return array(
  //     array(
  //         'title' => 'Sample Video 1',
  //         'views' => 1000,
  //         'analytics_url' => 'https://www.youtube.com/analytics/video/1',
  //     ),
  //     array(
  //         'title' => 'Sample Video 2',
  //         'views' => 500,
  //         'analytics_url' => 'https://www.youtube.com/analytics/video/2',
  //     ),
  //     array(
  //         'title' => 'Sample Video 3',
  //         'views' => 2000,
  //         'analytics_url' => 'https://www.youtube.com/analytics/video/3',
  //     ),
  // );
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

function get_playlists()
{
  $api_key = 'AIzaSyAfiysBRyIIHIUsenOXURi2xRTRtWBn2A4';
  $request_url = "https://youtube.googleapis.com/youtube/v3/playlists?part=snippet%2CcontentDetails&channelId=UCglE7vDtPHuulBhLvn9Q-eg&maxResults=25&key=" . $api_key . " HTTP/1.1";
  $response = wp_remote_get($request_url);
  $body = wp_remote_retrieve_body($response);
  // $result = json_decode($body->result);
  return $response;
}
