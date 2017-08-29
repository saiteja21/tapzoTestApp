<?php
session_start();
require 'autoload.php';
require_once('TwitterAPIExchange.php');
require 'authenticationdb.php';
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

$extras_name = $user->name;
$extras_pro_pic = $user->profile_image_url;    

    //Set here the Twitter account from where getting latest tweets
$screen_name = $user->screen_name;

// Get timeline using TwitterAPIExchange
//$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
$getfield = "?screen_name={$screen_name}";
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$user_timeline = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();

$response = json_decode($user_timeline);
  //print_r($response);   
//------------------------------------------------------------------------ Data Inserting back ----------------------------------    
    $sql = "INSERT IGNORE INTO tapzo_main (m_twitter_id, m_twitter_url, m_twitter_ts, m_twitter_dt, m_twitter_hashtag, tapzo_user_id, tapzo_tweet_id) VALUES (:m_twitter_id, :m_twitter_url, :m_twitter_ts, :m_twitter_dt, :m_twitter_hashtag, :tapzo_user_id, :tapzo_tweet_id)";
    $stmt = $pdo->prepare($sql);
        
    
    $stmt->bindParam(':m_twitter_id', $m_twitter_id, PDO::PARAM_STR);
    $stmt->bindParam(':m_twitter_url', $m_twitter_url, PDO::PARAM_STR);
    $stmt->bindParam(':m_twitter_ts', $m_twitter_ts, PDO::PARAM_STR);
    $stmt->bindParam(':m_twitter_dt', $m_twitter_dt, PDO::PARAM_STR);
    $stmt->bindParam(':m_twitter_hashtag', $m_twitter_hashtag, PDO::PARAM_STR);
    $stmt->bindParam(':tapzo_user_id', $tapzo_user_id, PDO::PARAM_STR);   
    $stmt->bindParam(':tapzo_tweet_id', $tapzo_tweet_id, PDO::PARAM_STR);
    
    $check = "SELECT * FROM tapzo_main WHERE tapzo_user_id = :tapzo_user_id AND tapzo_tweet_id = :tapzo_tweet_id";
    $check_me = $pdo->prepare($check);
    $check_me->bindParam(':tapzo_user_id', $tapzo_user_id, PDO::PARAM_STR);   
    $check_me->bindParam(':tapzo_tweet_id', $tapzo_tweet_id, PDO::PARAM_STR);
        
    $tapzo_user_id = $screen_name;
    
    foreach($response as $tweet)
	{   
		if(isset($tweet->user->screen_name)){
            $m_twitter_id = $tweet->user->screen_name;
        }
        if(isset($tweet->text)){
            $m_twitter_hashtag = $tweet->text;
        }   
		if(isset($tweet->id_str)){
            $tapzo_tweet_id = $tweet->id_str;
        }	
		if(isset($tweet->entities->urls[0]->url)){
            $m_twitter_url = $tweet->entities->urls[0]->url;
        }
		if(isset($tweet->created_at)){
            $m_twitter_dt = $tweet->created_at;
            $i= $tweet->created_at;
            $str = substr($i,0,3) . ', ' . substr($i,8,3) . substr($i,4,4) . substr($i,26,4).' '. substr($i,11,14);
            $m_twitter_ts = gmdate('Y-m-d', strtotime($str));
        }
        $check_me->execute();
        $number = $check_me->rowCount();
        if($number == 0){
          $stmt->execute();	  
        }        
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="TapzoTest app created by sai teja nagamothu">
    <meta name="author" content="">

    <title> <?php echo "{$screen_name} welcome" ;?> </title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/scrolling-nav.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">TapzoTestApp User@ <?php echo $screen_name ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#about">About</a>
            </li>
            <li class="nav-item">
    <?php 
      echo '
      <div>
      <a class="nav-link js-scroll-trigger" href="/tapzo/logout.php">logout</a>
      <div>'; 
    ?>                
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><img src="<?php echo $extras_pro_pic; ?>" alt="Mountain View" style="width:40px;height:35px; border-radius:60%"></a>
            </li>
            
              
          </ul>
        </div>
      </div>
    </nav>

      <header class="bg-primary text-white">
      <div class="container text-center">
        <h1><?php echo $extras_name . '! '; ?>Welcome to TapzoTestApp</h1>
        <p class="lead">Get the links that you missed from your twitter feed.</p>
		
	<div class="row">
  <div class="col-sm-2"></div>
  <div class="col-sm-8">	
   
<!--  ---------------------------------  Search form starts from here ----------------      -->
<form class="form-inline" action="./dash.php" method="get">
  <div class="form-group">
   
    <input type="text" name="m_twitter_id" class="form-control" placeholder="User Screen Name" id="email">
  </div>
  <div class="form-group">
    
    <input type="text" name="m_twitter_hashtag" class="form-control" placeholder="#twitterHashtag" id="pwd">
  </div>
  <div class="form-group">
    
    <input type="date" name="m_twitter_ts" class="form-control" id="pwd">
  </div>
  <button type="submit" name="submit" class="btn btn-outline-warning">search!</button>
</form>      
<!--  ---------------------------------  Search form ends here ----------------      -->   
      
  </div>
  <div class="col-sm-2"></div>
</div>

		
	  </div>
    </header>
	
      
	<div class="row">
  <div class="col-sm-2"></div>
  <div class="col-sm-8">	<div class="container">
  
<!--  ---------------------------------  Search results getting data from DB ----------------      -->   
  
  <table class="table table-striped" style="table-layout: fixed;
    word-wrap: break-word;">
    <thead>
      <tr>
        <th>Twitter Id</th>
        <th>Tweet URL</th>
        <th>Tweeted date and time</th>
      </tr>
    </thead>
    
    <tbody>
     
<?php
    // require 'search.php';
    
    if(isset($_GET['submit'])){
        $sqll= 'SELECT * FROM tapzo_main where tapzo_user_id = :tapzo_user_id AND m_twitter_id LIKE :m_twitter_id AND m_twitter_hashtag LIKE :m_twitter_hashtag AND m_twitter_ts LIKE :m_twitter_ts';
        $stmt2 = $pdo->prepare($sqll); 
        
        if(isset($_GET['m_twitter_id']))
          $one = $_GET['m_twitter_id'];
        else
          $one = '';
        
        if(isset($_GET['m_twitter_hashtag']))
          $two = $_GET['m_twitter_hashtag'];
        else
          $two = '';
        
        if(isset($_GET['m_twitter_ts']))
          $three = $_GET['m_twitter_ts'];
        else
          $three = '';
    
    $tapzo_user_id = $screen_name;    
    $m_twitter_id = '%' . $one . '%'; 
    $m_twitter_hashtag = '%' . $two . '%';
    $m_twitter_ts = '%' . $three . '%';

    $stmt2->bindParam(':tapzo_user_id', $tapzo_user_id, PDO::PARAM_STR);        
    $stmt2->bindParam(':m_twitter_id', $m_twitter_id, PDO::PARAM_STR);    
    $stmt2->bindParam(':m_twitter_hashtag', $m_twitter_hashtag, PDO::PARAM_STR);
    $stmt2->bindParam(':m_twitter_ts', $m_twitter_ts, PDO::PARAM_STR);
    $stmt2->execute();

    $result = $stmt2->fetchAll();
    foreach($result as $row){
           echo "<tr>";
           echo "<td>{$row['m_twitter_id']}</td>";
           echo "<td>"."<a href='".$row['m_twitter_url']."' target=\"_blank\">Link, opens in new tab</a>"."</td>";
           echo "<td>{$row['m_twitter_dt']}</td>";
           echo "</tr>";
    }
        
  }else{
    $sqll= "SELECT * FROM tapzo_main where tapzo_user_id= '{$screen_name}'";
    $stmt2 = $pdo->prepare($sqll); 
    $stmt2->execute();
    $result = $stmt2->fetchAll();
    foreach($result as $row){
           echo "<tr>";
           echo "<td>{$row['m_twitter_id']}</td>";
           echo "<td>"."<a href='".$row['m_twitter_url']."' target=\"_blank\">Link, opens in new tab</a>"."</td>";
           echo "<td>{$row['m_twitter_dt']}</td>";
           echo "</tr>";
    }
}      
?>
   </tbody>
  </table>
</div></div>
  <div class="col-sm-2"></div>
</div>

      
    <!-- Footer -->
    <footer class="py-5 bg-dark" style="position: fixed; height: 10px; bottom: 0; width: 100%;">
      <div class="container">
        <p class="m-0 text-center text-white">A product of maddoubts &copy; maddoubts.com 2017. Created by <a href="https://www.linkedin.com/in/saiteja-n" target="_blank">Sai Teja Nagamothu,</a>Made in <a href="http://www.cuchd.in"><span style="color:red">Chandigarh University</span></a></p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom JavaScript for this theme -->
    <script src="js/scrolling-nav.js"></script>

  </body>

</html>
