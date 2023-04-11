<?php

/**
 * Sample PHP code for youtube.videos.list
 * See instructions for running these code samples locally:
 * https://developers.google.com/explorer-help/code-samples#php
 */

if (!file_exists(get_stylesheet_directory().'/vendor/autoload.php')) {
  throw new Exception(sprintf('Please run "composer require google/apiclient:~2.0" in "%s"', (get_stylesheet_directory().'/vendor/autoload.php')));
}
require_once(get_stylesheet_directory().'/vendor/autoload.php');

// define('STDIN', fopen('php://stdin', 'r'));

$client = new Google\Client();
$client->setApplicationName('DBD_WP_YouTube');

$client->setScopes([
  'https://www.googleapis.com/auth/youtube.readonly',
]);

$client->setAccessType('offline');

// TODO: For this request to work, you must replace
//       "YOUR_CLIENT_SECRET_FILE.json" with a pointer to your
//       client_secret.json file. For more information, see
//       https://cloud.google.com/iam/docs/creating-managing-service-account-keys
// $client->setAuthConfig(__DIR__ . '/../client_secret.json');

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . "/../service-account.json");
$client->useApplicationDefaultCredentials();


// $client->addScope('https://www.googleapis.com/auth/youtube.readonly');

// $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
// $client->setRedirectUri($redirect_uri);



// Request authorization from the user.
// $authUrl = $client->createAuthUrl();

// printf("Open this link in your browser:\n%s\n", $authUrl);
// print('Enter verification code: ');

// $authCode = trim(fgets(STDIN));
// $authCode = $_GET['code'];
// echo "\nThanks, " . $name . ", it's really nice to meet you.\n\n";

// Exchange authorization code for an access token.
// $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
// $client->setAccessToken($accessToken);


// $user_to_impersonate = 'bamitalebt@gmail.com';
// $client->setSubject($user_to_impersonate);


// Define service object for making API requests.
$service = new Google\Service\YouTube($client);

// $client->addScope(Google\Service\YouTube::YOUTUBE_READONLY);
// $client->addScope(Google\Service\YouTubeAnalytics::YOUTUBE);
$channel_id = 'UCglE7vDtPHuulBhLvn9Q-eg';
$username = 'DISBYDEM';

// $queryParams = [
//   'id' => 'Ks-_Mh1QhMc'
// ];

$queryParams = [
  'forUsername' => 'DISBYDEM'
];

// $response = $service->videos->listVideos('snippet,contentDetails,statistics,status', $queryParams);
// $response = $service->channels->listChannels('snippet,contentDetails,statistics,status', $queryParams);
// $response = $client->videos->listVideos('id,snippet,contentDetails,statistics,status');
// var_dump($response);
// print_r($response);
