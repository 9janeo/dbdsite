<?php

// ToDo: Create a class wrapper for service with config functions for client

use Google\Service\YouTube\Channel;

class Dbd_Youtube
{

  // initialize class constants
  const CHANNEL_ID = 'UCglE7vDtPHuulBhLvn9Q-eg';
  const CHANNEL_NAME = 'DISBYDEM';
  const API_KEY = 'AIzaSyAfiysBRyIIHIUsenOXURi2xRTRtWBn2A4';

  // initialize class variables
  private static $initiated = false;
  private static $client = false;
  private static $service = false;

  public static function init()
  {
    if (!self::$initiated) {
      self::init_hooks();
    }
  }

  /**
   * Initializes WordPress hooks
   */
  private static function init_hooks()
  {
    self::$initiated = true;

    // load client
    self::$client = self::load_client();
    self::$service = self::service();
    // set up client scopes
    // set client Access type
    // load client service account credentials from json
    // get channel Id
    // Get channel username
  }

  /**
   * Initializes YouTube client
   */
  public static function load_client()
  {
    // YouTube Client Setup
    $client = new Google\Client();
    $client->setApplicationName('DBD_WP_YouTube');

    $client->setScopes([
      'https://www.googleapis.com/auth/youtube.readonly',
    ]);
    // $client->addScope(Google\Service\YouTube::YOUTUBE_READONLY);
    // $client->addScope(Google\Service\YouTubeAnalytics::YOUTUBE);
    $client->setAccessType('offline');
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . "/../service-account.json");
    $client->useApplicationDefaultCredentials();

    return $client;
  }

  /**
   * Initializes YouTube service
   */
  public static function service()
  {
    // Define service object for making API requests.
    return new Google\Service\YouTube(self::$client);
  }

  /**
   * Returns the videos for a channel
   * @param  mixed  $service      [Youtube service]
   * @return mixed  $channel  [The channel object we want]
   * @return int  $maxResults  [The maximum number of results to return]
   */
  public static function get_channel_videos($channel, $maxResults = 25)
  {
    if (!isset($channel)) {
      return "Channel Info not set, incorrect or incomplete";
    }
    $id = $channel->channel_id;
    $max = $maxResults;

    $req_url = "https://www.googleapis.com/youtube/v3/search?channelId=" . $id . "&order=date&part=snippet&type=video&maxResults=" . $max . "&key=" . self::API_KEY;
    $response = wp_remote_get($req_url);
    // $code = wp_remote_retrieve_response_code($response);
    $result = json_decode(wp_remote_retrieve_body($response));
    return $result;
  }

  /**
   * Returns the videos for a playlist
   * @param  mixed  $service      [Youtube service]
   * @return mixed  $playlist_id  [The id of the playlist we want]
   */
  public static function get_all_videos()
  {
    $service = self::$service;
    $queryParams = [
      'chart' => 'mostPopular',
      'regionCode' => 'CA',
      'maxResults' => 3
    ];
    $response = $service->videos->listVideos('snippet,contentDetails,statistics', $queryParams);
    return $response;
  }

  /**
   * Returns the playlists for a channel
   * @param  mixed  $service      [Youtube service]
   * @return mixed  $channel_id  [The id of the channel to retrieve playlists from]
   */
  public static function get_playlists($channel_id = '')
  {
    // if (false === ($channel_playlists = get_transient('channel_playlists'))) {
    $queryParams = [
      'channelId' => $channel_id,
      'maxResults' => 25
    ];

    try {
      $channel_playlists = self::$service->playlists->listPlaylists('snippet,contentDetails,status', $queryParams);
      $playlists = array();
      // set_transient('channel_playlists', $channel_playlists, DAY_IN_SECONDS);
      foreach ($channel_playlists->items as $index => $playlist) {
        $id = $playlist->id;
        $url = $playlist->snippet->thumbnails->high->url;
        if (!($playlist->contentDetails->itemCount > 0)) {
          // skip playlist if no items in it
          continue;
        }
        $arr_of_vars = explode("/", parse_url($url, PHP_URL_PATH));
        $indexVid = $arr_of_vars[2];
        $url = 'https://www.youtube.com/watch?v=' . $indexVid . '&list=' . $id;
        $playlist->url = $url;
        array_push($playlists, json_encode($playlist));
      }
      return $channel_playlists;
    } catch (Exception $e) {
      echo 'Message: ' . $e->getMessage();
    }
  }

  /**
   * Accepts a playlist id and returns the items in the playlist
   * @param  mixed  $service      [Youtube service]
   * @return mixed  $playlist_id  [The id of the playlist we want]
   */
  public static function get_playlist_items($playlist_id)
  {
    $queryParams = [
      'playlistId' => $playlist_id,
      'maxResults' => 50
    ];
    // write_log('Attempt setting Playlist Items transient for playlist - ' . $playlist_id . " as - {$playlist_id}_videos");
    $playlist_items = array();
    try {
      $playlist_items = self::$service->playlistItems->listPlaylistItems('snippet,contentDetails,status', $queryParams);
      return $playlist_items;
    } catch (Exception $e) {
      write_log('Error getting Playlist Items for playlist - ' . $playlist_id);
      return false;
    }
  }

  /**
   * Returns the playlists for a channel with it's corresponding videos
   * return mixed  $channel_id  [The id of the channel to retrieve playlists from]
   */
  public static function get_playlists_with_items($channel_id = '')
  {
    $queryParams = [
      'channelId' => $channel_id,
      'maxResults' => 25
    ];

    try {
      $channel_playlists = self::$service->playlists->listPlaylists('snippet,contentDetails,status', $queryParams);
      $playlists = array();
      // set_transient('channel_playlists', $channel_playlists, DAY_IN_SECONDS);
      foreach ($channel_playlists->items as $index => $playlist) {
        $id = $playlist->id;
        // get the corresponding playlist items
        $items = self::get_playlist_items($playlist->id)->items;
        if (!($playlist->contentDetails->itemCount > 0)) {
          // skip playlist if no items in it
          continue;
        }
        $public = array();
        // count playlist items that are public
        foreach ($items as $item) {
          if (($item->status->privacyStatus == 'private')) {
            continue;
          }
          array_push($public, $item);
        }
        if (!(count($public) > 0)) {
          // skip playlist if there are no public videos
          continue;
        }
        $playlist->items = $public;
        $url = self::get_resource_url($playlist, 'playlist', $id);
        $playlist->url = $url;
        array_push($playlists, $playlist);
        // $filtered_items = array_map('Dbd_Youtube::pull_item_ids', $playlist->items);
        // error_log("Inside [get_playlists_with_items] " . wp_json_encode((object) $filtered_items));
      }
      return $playlists;
    } catch (Exception $e) {
      echo 'Message: ' . $e->getMessage();
    }
  }

  /**
   * Builds a url for the YouTube resource
   * @param object $resource [accepts the resource]
   * @param string $type [accepts the resource type]
   * @param string $id  [accepts the resource id]
   */
  public static function get_resource_url($resource, $type, $id)
  {
    if ($type == 'playlist') :
      if (isset($resource->items[0])) {
        $indexVid = $resource->items[0]->snippet->resourceId->videoId;
        $playlistUrl = 'https://www.youtube.com/watch?v=' . $indexVid . '&list=' . $id;
      }
      return $playlistUrl;
    endif;
  }

  static function pull_item_ids($item)
  {
    return $item->id;
  }

  // create playlists table
  /**
   * Creates a playlists table in the database
   * returns a success or error message
   */
  public static function create_channel_playlists()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . 'playlists';

    $sql = "CREATE TABLE `{$table_name}` (
      ID int NOT NULL Auto_INCREMENT,
      PlaylistId VARCHAR(50),
      PlaylistName VARCHAR(50),
      VideoCount INT,
      VideoList VARCHAR (4000),
      PlaylistUrl VARCHAR (100),
      channel_id VARCHAR(50),
      Published DATETIME,
      Created DATETIME,
      Updated DATETIME,
      PRIMARY KEY (id),
      UNIQUE KEY (PlaylistId),
      FOREIGN KEY (channel_id) REFERENCES wp_channels(channel_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
    $is_error = empty($wpdb->last_error);

    return $is_error;
  }

  // Save playlists
  /**
   * Accept a playlist and save them to database
   * @param object $playlists [Playlists for the channel to add to the database]
   * returns a success or error message
   */
  public static function save_playlists($playlists)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'playlists';

    if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) != $table_name) {
      error_log('Table does not exist' . ': ' . print_r($table_name, true));
      error_log('Create ' . print_r($table_name, true) . ' table');
      self::create_channel_playlists();
    }
    error_log('Table exists' . ': ' . print_r($table_name, true));

    foreach ($playlists as $pl) {
      // $allowed_item_key = ['id'];
      $filtered_items = array_map('Dbd_Youtube::pull_item_ids', $pl->items);
      $data_ = array(
        'PlaylistId' => $pl->id,
        'PlaylistName' => $pl->snippet->title,
        'VideoCount' => $pl->contentDetails->itemCount,
        'VideoList' => wp_json_encode((object) $filtered_items),
        'PlaylistUrl' => $pl->url,
        'channel_id' => $pl->snippet->channelId,
        'Published' => date("Y-m-d H:i:s", strtotime($pl->snippet->publishedAt)),
        'Created' => current_time('Y-m-d\TH:i:s.u\Z', 1), // current datetime,
        'Updated' => null
      );

      $exists = $wpdb->get_var("SELECT `ID` FROM {$table_name} WHERE `playlistId` = '{$pl->id}'");

      if ($exists == NULL) {
        $result = $wpdb->insert($table_name, $data_, array('%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s'));
        if (false === $result) {
          print_r($wpdb->last_error);
          // wp_die('Failed to add playlist');
        } else {
          echo "Playlist " . $pl->id . " added to database";
        }
      } else {
        // update with new values in $data_
        self::update_playlist($exists, $data_);
      }
    }
  }

  /**
   * Update a playlist
   * @param int $id [The id (primary key) of the existing playlist record in the database]
   * @param array $playlist [The new playlist information to compare with existing record]
   */
  public static function update_playlist($id, $playlist)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'playlists';
    // Set playlist updated to current datetime
    $playlist['Updated'] = current_time('Y-m-d H:i:s', 1);
    // Fetch existing playlist db row usint Id
    $check = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE `ID`='%d'", $id));

    $where = array('id' => $id);
    $updateCols = array();
    // Compare db entry and current $playlist
    foreach ($playlist as $col => $newVal) {
      // if the same exit
      if (($check->{$col} != $newVal) && ($col != "Created")) {
        // if different, add column to update fields
        $updateCols[$col] = $newVal;
      }
    }

    $update = $wpdb->update($table_name, $updateCols, $where, array('%s', '%s', '%s', '%s', '%s'), array('%d'));
    if (false === $update) {
      write_log($wpdb->last_error);
      print_r($wpdb->last_error);
      // wp_die('Failed to add channel');
    } else {
      $affected = json_encode(array_keys($updateCols));
      write_log("Channel " . $check->PlaylistName . " $affected " . " fields updated <br>\n");
    }
  }

  //Pull tags from YT and add them to existing post tags
  /**
   * Adds tags to a post
   * @param $post_id Post to update
   * @param $video Youtube video from API call
   * @return bool
   */
  public static function update_post_tags($post_id, $video)
  {
    $vid_tags = $video->snippet->tags;
    $post_tags = get_the_tags($post_id);
    $new_tags = [];
    foreach ($vid_tags as $key => $tag) {
      if (!($post_tags) || !in_array($tag, $post_tags)) {
        $new_tags[] = $tag;
      }
    }
    if (!empty($new_tags)) {
      wp_set_post_tags($post_id, $new_tags, true);
    }
  }
}
