<?php

// ToDo: Create a class wrapper for service with config functions for client
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
      // 'id' => 'UCglE7vDtPHuulBhLvn9Q-eg'
    ];
    $response = $service->videos->listVideos('snippet,contentDetails,statistics', $queryParams);
    return $response;
  }

  /**
   * Returns the playlists for a channel
   * @param  mixed  $service      [Youtube service]
   * @return mixed  $args         [The query params for the call]
   */
  public static function get_playlists()
  {
    // if (false === ($channel_playlists = get_transient('channel_playlists'))) {
    $queryParams = [
      'channelId' => 'UCglE7vDtPHuulBhLvn9Q-eg',
      'maxResults' => 25
    ];
    $channel_playlists = self::$service->playlists->listPlaylists('snippet,contentDetails,status', $queryParams);
    // find a way to filter the response for empty or private playlists
    // set_transient('channel_playlists', $channel_playlists, DAY_IN_SECONDS);
    // }
    return $channel_playlists;
  }

  /**
   * Accepts a playlist id and returns the videos for a playlist
   * @param  mixed  $service      [Youtube service]
   * @return mixed  $playlist_id  [The id of the playlist we want]
   */
  public static function get_playlist_items($playlist_id)
  {
    $queryParams = [
      'maxResults' => 25,
      'playlistId' => $playlist_id
    ];
    // if (false === ($playlist_items = get_transient('playlist_items'))) {
    $playlist_items = self::$service->playlistItems->listPlaylistItems('snippet,contentDetails,status', $queryParams);
    write_log('Attempt setting Playlist Items transient for playlist - ' . $playlist_id . " as - {$playlist_id}videos");
    // set_transient("{$playlist_name}videos", $playlist_videos, DAY_IN_SECONDS);
    // } else {
    //   write_log('Retrieving Playlist Items transient for playlist - ' . $playlist_id);
    // }
    return $playlist_items;
  }

  /**
   * Returns the playlists for a channel with it's corresponding videos
   */
  public static function get_playlists_with_items()
  {
    if (false === ($channel_playlists = get_transient('channel_playlists'))) {
      $queryParams = [
        'channelId' => 'UCglE7vDtPHuulBhLvn9Q-eg',
        'maxResults' => 25
      ];
      $channel_playlists = self::$service->playlists->listPlaylists('snippet,contentDetails,status', $queryParams);
      set_transient('channel_playlists', $channel_playlists, DAY_IN_SECONDS);
      foreach ($channel_playlists->items as $index => $playlist) {
        $id = $playlist->id;
        if (!($playlist->contentDetails->itemCount > 0)) {
          // skip playlist if no items in it
          continue;
        }
      }
    }
    return $channel_playlists;
  }
}
