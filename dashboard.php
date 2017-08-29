<?php
session_start();
require 'autoload.php';
require_once('TwitterAPIExchange.php');
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'sRPY21ebxatI5TE2NO3mYXc82'); 
define('CONSUMER_SECRET', 'Bjhkcg5CkdQOiJnxIU97gIuFeWsH9ALxa0r8eEdjOUKUCDPCas');
define('OAUTH_CALLBACK', 'https://maddoubts.com/tapzo/callback.php'); 
if (!isset($_SESSION['access_token'])) {
	header('Location: ./');
} else {	
    $access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], 
    $access_token['oauth_token_secret']);
	$user = $connection->get("account/verify_credentials");

// Set here your twitter application tokens
$settings = array(
    'consumer_key' => 'sRPY21ebxatI5TE2NO3mYXc82',
    'consumer_secret' => 'Bjhkcg5CkdQOiJnxIU97gIuFeWsH9ALxa0r8eEdjOUKUCDPCas',
  // These two can be left empty since we'll only read from the Twitter's 
  // timeline
           'oauth_access_token' => $access_token['oauth_token'],
            'oauth_access_token_secret' => $access_token['oauth_token_secret']
);

// Set here the Twitter account from where getting latest tweets
$screen_name = $user->screen_name;

// Get timeline using TwitterAPIExchange
//$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
$getfield = "?screen_name={$screen_name}";
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$user_timeline = $twitter
  ->setGetfield($getfield)
  ->buildOauth($url, $requestMethod)
  ->performRequest();
   //print_r($user_timeline);
    echo "working";
    echo '
      <div>
      <a href="/tapzo/logout.php">logout</a>
      <div>';
    
}
?>
