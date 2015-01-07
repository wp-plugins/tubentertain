<?php error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);?>
<?php
session_start();
require_once("twitteroauth.php"); //Path to twitteroauth library
 
$twitteruser = "Dego247";
$notweets = 11;
$consumerkey = "zBVq4E9GFhjHverzz7Ng";
$consumersecret = "zDKGZuF7n9imffcRhNEQkylgoxXKQLBvjMKQckCMLTY";
$accesstoken = "234673183-PElajvDVzHdnt6YlcTjzW7J8KwqckGphQESuC0EO";
$accesstokensecret = "IfDV7kWRV3m67yhP1BAN5B4CYDXJbD9BcIolYFc";
 
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}
 
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
$get_tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);

$get_userImage = $connection->get("https://api.twitter.com/1.1/users/show.json?screen_name=".$twitteruser);


foreach($get_tweets as $tweet) { 

$tweet_desc = $tweet->text;
$tweetId = $tweet->id_str;
$userImg = $get_userImage->profile_image_url_https;
$userName = $get_userImage->name;

$isretwee=substr($tweet_desc, 0,2);

if($isretwee == 'RT'){
$isretwee='YEs';
$text = trim($tweet_desc, 'RT');
}
else{
$isretwee='NO';
$text=$tweet_desc;
}


// Add hyperlink html tags to any urls, twitter ids or hashtags in the tweet.
$text= preg_replace('/(https?:\/\/[^\s"<>]+)/','<a href="$1" target="_blank">$1</a>',$text);
$text = preg_replace('/(^|[\n\s])@([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/$2" target="_blank">@$2</a>', $text);
$text = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/search?q=%23$2" target="_blank">#$2</a>', $text);

$actions='<a href="https://twitter.com/intent/tweet?in_reply_to='.$tweetId.'" title="Reply" target="_blank">R</a>
<a href="https://twitter.com/intent/retweet?tweet_id='.$tweetId.'" title="Retweet" target="_blank">RT</a>
<a href="https://twitter.com/intent/favorite?tweet_id='.$tweetId.'" title="Favourite" target="_blank">Fav</a>';							
echo '<br/>'.'<img src="'.$userImg.'"/>'.'<br/>'.$text.'<br/>'.$actions.'<br/>'.'he'.$isretwee."  |".$userName;


}
?>