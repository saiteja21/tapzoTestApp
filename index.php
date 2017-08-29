<?php
session_start();
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', 'sRPY21ebxatI5TE2NO3mYXc82'); 
define('CONSUMER_SECRET', 'Bjhkcg5CkdQOiJnxIU97gIuFeWsH9ALxa0r8eEdjOUKUCDPCas');
define('OAUTH_CALLBACK', 'https://maddoubts.com/tapzo/callback.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Sai Teja Nagamothu">
    <title>Tapzo Twitter app</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <!-- Custom styles for this template -->
    <link href="css/creative.min.css" rel="stylesheet">
  </head>
  <body id="page-top">
    <header class="masthead">
      <div class="header-content">
        <div class="header-content-inner">
          <h1 id="homeHeading">Tapzo Twitter Test App</h1>
            <h5><small>Created by <a href="https://www.linkedin.com/in/saiteja-n" target="_blank">Sai Teja Nagamothu,</a> Chandigarh University</small></h5>
          <hr>
          <p>Login with your Twitter account!</p>
		  <?php if (!isset($_SESSION['access_token'])) {
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
	echo '
      <div>
      <a class="btn btn-primary btn-xl" href="' . $url . '">Sigin with twitter</a>
      <div>';	
} else {
	header('Location: ./dash.php');
}
?>
		  
            
        </div>
      </div>
    </header>
    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
