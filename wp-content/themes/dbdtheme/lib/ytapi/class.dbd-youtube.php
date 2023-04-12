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

  public static function init()
  {
    if (!file_exists(get_stylesheet_directory() . '/vendor/autoload.php')) {
      throw new Exception(sprintf('This site does not have the required package for YouTube functions. Please run "composer require google/apiclient:~2.0" in "%s"', (get_stylesheet_directory() . '/vendor/autoload.php')));
    }
    require_once(get_stylesheet_directory() . '/vendor/autoload.php');

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
    // set up client scopes
    // set client Access type
    // load client service account credentials from json
    // get channel Id
    // Get channel username
  }

  /**
   * Initializes YouTube client
   */
  public static function client()
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
  }

  /**
   * Initializes YouTube service
   */
  public static function service()
  {
    // Define service object for making API requests.
    new Google\Service\YouTube(Dbd_Youtube::client());
  }
}


// define('STDIN', fopen('php://stdin', 'r'));

// $response = $service->videos->listVideos('snippet,contentDetails,statistics,status', $queryParams);
// $response = $service->channels->listChannels('snippet,contentDetails,statistics,status', $queryParams);
// $response = $client->videos->listVideos('id,snippet,contentDetails,statistics,status');
// var_dump($response);
// print_r($response);
