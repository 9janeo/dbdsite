<?php

/**
 * Sample PHP code for youtube.videos.list
 * See instructions for running these code samples locally:
 * https://developers.google.com/explorer-help/code-samples#php
 */

// ToDo: Create a class wrapper for service with config functions for client

if (!file_exists(get_stylesheet_directory() . '/vendor/autoload.php')) {
  throw new Exception(sprintf('Please run "composer require google/apiclient:~2.0" in "%s"', (get_stylesheet_directory() . '/vendor/autoload.php')));
}
require_once(get_stylesheet_directory() . '/vendor/autoload.php');

// define('STDIN', fopen('php://stdin', 'r'));

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

// Define service object for making API requests.
$service = new Google\Service\YouTube($client);

$channel_id = 'UCglE7vDtPHuulBhLvn9Q-eg';
$username = 'DISBYDEM';

// $response = $service->videos->listVideos('snippet,contentDetails,statistics,status', $queryParams);
// $response = $service->channels->listChannels('snippet,contentDetails,statistics,status', $queryParams);
// $response = $client->videos->listVideos('id,snippet,contentDetails,statistics,status');
// var_dump($response);
// print_r($response);
