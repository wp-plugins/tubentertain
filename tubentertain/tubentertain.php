<?php
 /*-----------------------------------------------------------------------------------*/
 /*
Plugin Name: TubEntertain 
Plugin URI: http://tubentertain .com/
Description: TubEntertain  is a plugin that enable you to easily take your  YouTube Live Stream and  Videos gallery, along side with your twitter Feed to your site.
TubEntertain, was originally build for a television station whose uses YouTube for their video marketing and on demand videos.
Please see readme txt for usage.
For further customization please contact goroye247@yahoo.co.uk.
Version: 1.0
Author: Adegoroye Owolabi
Author URI: http://tubentertain .com/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/*-----------------------------------------------------------------------------------*/
//============Work Start Here========TubEntertain=====
function tubentertain_activation() {}
if (!is_admin()) add_action("wp_enqueue_scripts", "jquery_enqueue", 11);
function jquery_enqueue() {
wp_deregister_script('jquery');
wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://code.jquery.com/jquery-1.11.0.min.js", false,"1.11.0", true);
wp_enqueue_script('jquery');
}
//=============
add_action('wp_enqueue_scripts', 'tubentertainScripts');
function tubentertainScripts() {
wp_register_script('tubentertainIscroll', plugins_url('js/tubscroll.js', __FILE__), false,  false, true);
wp_register_script('tubentertainVid', plugins_url('js/tubentertain.js', __FILE__),array("jquery"),  false,  true);
wp_register_script('tubentertainScroContent', plugins_url('js/scrollelemts.js', __FILE__), false,  false, true);
wp_enqueue_script('tubentertainIscroll');
wp_enqueue_script('tubentertainVid');
wp_enqueue_script('tubentertainScroContent');
  }
//===================//::::::::::::::::::
add_action('wp_enqueue_scripts', 'tubentertain_styles');
function tubentertain_styles() {
wp_register_style( 'tubentertainCss',plugins_url(  'css/tubentertain.css', __FILE__) );
wp_enqueue_style( 'tubentertainCss' );
}
//::::::::::::::::::::::::::::::::::::
//     Main ShortCode Start Here    ::
//                                  ::
//::::::::::::::::::::::::::::::::::::
if ( !function_exists('tubentertain') ){
function tubentertain_shortcode(){
//Configuration
$depoolList=file(plugins_url(  'channels/config.txt', __FILE__));
if($depoolList){
//for wordpress
$depool=explode("||", (string)$depoolList[0]);
$channel=$depool[0];
$TwitterUser=$depool[1];
}
else
{
?>
<div class="inError" >
<strong>The System Needs Re-Configuration, please check your configuration in the TubEntertain Admin panel make sure all data are valid. </strong>
</div>
<?php
}
//::::::::::::::::::::::::::::::::::::::::::::::::::::
//getTub Function start Here
function getTub($channel,$LiveVid,$TwitterUser){
if(isset($_REQUEST['i']))
{
$startIndex=$_REQUEST['i'] * 50 + 1;
$rangeIndex=$_REQUEST['i'] * 50 ;
}
else
{
$startIndex=1;
$rangeIndex=0;
}
//:::::::::::Grab Data::::::::::::
$youtube = "http://gdata.youtube.com/feeds/api/users/".$channel."/uploads?v=2&alt=jsonc&start-index=".$startIndex."&max-results=50";
 $curl = curl_init($youtube);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 $return = curl_exec($curl);
 curl_close($curl); 
$result = json_decode($return, true); 
$totalRes=$result['data']['totalItems'];
//::::::::::::
//:::::::::::Generate Video Id::::::::::::::::	
if(isset($_REQUEST['v']) && $_REQUEST['v']!=='undefined') { 
$videoId=$_REQUEST['v'];
//display css option
$kliKoff='none';
$kliKon='block';
}
else if(isset($_REQUEST['v']) && $_REQUEST['v']=='undefined') { 
$videoId=$result['data']['items'][0]['id'];
//display css option
$kliKoff='block';
$kliKon='none';
}
else{
$videoId=$result['data']['items'][0]['id'];   
//display css option
$kliKoff='block';
$kliKon='none';
}
//:::::::::::::::::::
if(isset($totalRes))
{
$totalVideo=number_format($totalRes);
}
else
{
$totalVideo=0;
}
//:::::::
if($totalRes > 50)
{
$sRange=$rangeIndex + 50;
if($sRange > $totalRes)
{
$starRange=$totalVideo;
}
else
{
$starRange=number_format($rangeIndex + 50);
}
}
//::
else
{
$starRange=number_format($totalVideo);
}
//:::::::::::Create Pagenation::::::::::::
$iCh=0;
$rangeArray=range($startIndex,$totalRes);
$prevLink ="?i=".($iCh-1);
$nextLink ="?i=".($iCh+1);
//:::::is v2
if(isset($_REQUEST['i']))
{
$iCh=$_REQUEST['i'];
$perPage = 50;
$maxPages = ceil($totalRes / $perPage);
$leftOver=$totalRes - $rangeIndex;
//:::
if($leftOver < 50){$rangeTo=$startIndex+$leftOver;}
else{$rangeTo=$startIndex+50;}
$rangeArray== range($startIndex,$rangeTo);
//:::
if($iCh > 1 || $iCh < $maxPages)
{
if(isset($_REQUEST['v']))
{
$Chv=$_REQUEST['v'];
$prevLink ="?i=".($iCh-1)."&v=".$Chv;
$nextLink ="?i=".($iCh+1)."&v=".$Chv;
}
else
{
$prevLink ="?i=".($iCh-1);
$nextLink ="?i=".($iCh+1);
}
}
if($iCh ===1 || $iCh < 1)
{
if(isset($_REQUEST['v']))
{
$Chv=$_REQUEST['v'];
$prevLink ="?i=0&v=".$Chv;
$nextLink ="?i=".($iCh+1)."&v=".$Chv;
}
else
{
$prevLink ="?i=0";
$nextLink ="?i=".($iCh+1);
}
}
if($iCh === $maxPages || $leftOver < $perPage )
{
if(isset($_REQUEST['v']))
{
$Chv=$_REQUEST['v'];
$prevLink ="?i=".($iCh-1)."&v=".$Chv;
$nextLink ="?i=".$iCh."&v=".$Chv;
}
else
{
$prevLink ="?i=".($iCh-1);
$nextLink ="?i=".$iCh;
}
}	
}
//::::::::::Ends Pagnation here::::::::::::::
//:::::::::Time Ago::::::::
function timeAgo($datetime, $full = false) {
$now = new DateTime;
$ago = new DateTime($datetime);
$diff = $now->diff($ago);
$diff->w = floor($diff->d / 7);
$diff->d -= $diff->w * 7;
$string = array(
'y' => 'year',
'm' => 'month',
'w' => 'week',
'd' => 'day',
'h' => 'hour',
'i' => 'minute',
's' => 'second',
);
foreach ($string as $k => &$v) {
       if ($diff->$k) {
$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
} 
else 
{
unset($string[$k]);
}
}
if (!$full) $string = array_slice($string, 0, 1);
return $string ? implode(', ', $string) . ' ago' : 'just now';
}
//:::::Twiter Feeds
require_once("TweetAuth/twitteroauth.php"); //Path to twitteroauth library
$twitteruser =$TwitterUser;//user_screen_name
$notweets = 10;
$consumerkey = "zBVq4E9GFhjHverzz7Ng";
$consumersecret = "zDKGZuF7n9imffcRhNEQkylgoxXKQLBvjMKQckCMLTY";
$accesstoken = "234673183-PElajvDVzHdnt6YlcTjzW7J8KwqckGphQESuC0EO";
$accesstokensecret = "IfDV7kWRV3m67yhP1BAN5B4CYDXJbD9BcIolYFc";
//:::::::::::::::::::::::: now make connection to twitter::::::::::::::::::::::::::::::::::
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
return $connection;
}
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
$get_tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
//:::::::::::::::::::::::: Get user profile Image :::::::::::::::::::::::::::::::::
$get_userImage = $connection->get("https://api.twitter.com/1.1/users/show.json?screen_name=".$twitteruser);
$userName = $get_userImage->name;
//:::::::::::::::::::::::: Get user profile Banner :::::::::::::::::::::::::::::::::
//$get_userBanner = $connection->get("https://api.twitter.com/1.1/users/profile_banner.json?screen_name=".$twitteruser);
//change this 
 if($videoId){ 
$ondPlayer = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$videoId."?v=2&alt=jsonc"));
$uploaded=$ondPlayer->data->uploaded;
$vidTitle=$ondPlayer->data->title;
$vidTitle=str_replace(array('»','»','¿',':',';','"', "'",'-', '.', '(', ')', '!', '@', '#', '$', '%', '^', '&', '*', '_', '=', '+'), '', $vidTitle);		   
$upd =explode("T", (string)$uploaded);
$upLdate = $upd[0];
$uplt= explode(".", (string)$upd[1]);
$upLtime=$uplt[0];
$uploader=$ondPlayer->data->uploader;
if($ondPlayer->data->description){
$description=$ondPlayer->data->description;
$description= preg_replace('/(https?:\/\/[^\s"<>]+)/','<a href="$1" target="_blank">  &raquo;&raquo; Go!</a>', $description);
//$description=str_replace(array('â€™','"',  '!', '@', '#', '$', '%', '^', '&', '*', '_', '=', '+'), '', $description);
}
else 
{
$description="No Description Available!";
}
}
?>
<div id="body" iCh="<?php echo $iCh ?>"  dChtitle="<?php echo  $vidTitle ?>" featureVid="<?php echo $videoId ?>">
<div class="content-box" id="toppy">
<a title="Live or Feature" id="live" style="color:#eee;">&sdotb;</a>
<a title="Playlist" id="list" style="color:#eee">&equiv;</a>
<div class="toptitile"><strong><?php echo  $vidTitle ?></strong></div>
</div>
<!-- The Player Start Here -->
<div  class="videoRes" id="videoHolder" style=""> 
<iframe type='text/html'  id="wopTubplayer" src="" webkitallowfullscreen ></iframe>
</div>
 <!-- The Player End Here -->
<div class="pmarqee" id="mfeed" >
<a style="color:#fff; font-size:100%;font-weight:bold;text-shadow: 2px 2px 3px #111;cursor:pointer;" id="tfeed" href="https://twitter.com/<?php echo $twitteruser ?>" > <?php echo $twitteruser ?></a>
<div  id="nextScroll">
<?php if(empty($userName)) {//If no response?>
<!--If no response from Twitter -->
<a id="noresponSe">Loading.........Tweets   from Twitter.Com | TimeOut Issues</a>
<!--If no response from Twitter -->
<?php 
}
//If no responseClosed Here
else
{
?>
<?php 
//:::::::::::::::::::::::: Display the tweets::::::::::::::::::::::::::::::::::
foreach($get_tweets as $tweet) { 
$tweet_desc = $tweet->text;
$tweetId = $tweet->id_str;
$ScreeNtweetId = $tweet->id;
$tweetTimenDate = $tweet->created_at;
$date = $tweetTimenDate;
$PostDate = timeAgo($date);
//end of date format
$userImg = $get_userImage->profile_image_url_https;
//$uerbanner=$get_userBanner->sizes->mobile->url;
$isretwee=substr($tweet_desc, 0,2);
if($isretwee == 'RT'){
$isretwee='<a href="#" class="isRt">isRt</a>';
$text = trim($tweet_desc, 'RT');
}
else
{
$isretwee='';
$text=$tweet_desc;
}
// Add hyperlink html tags to any urls, twitter ids or hashtags in the tweet.
$text= preg_replace('/(https?:\/\/[^\s"<>]+)/','<a style="color:#fff" href="$1" target="_blank">$1</a>',$text);
$text = preg_replace('/(^|[\n\s])@([^\s"\t\n\r<:]*)/is', '$1<a  style="color:#fff" href="http://twitter.com/$2" target="_blank">@$2</a>', $text);
$text = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a  style="color:#fff" href="http://twitter.com/search?q=%23$2" target="_blank">#$2</a>', $text);
?>
<?php echo $text; ?><a id="tim" style="color:#00ccFF;padding:0 10px 0 10px;"><?php echo $PostDate; ?> </a> **
<?php 
}
}
?>	
</div>  
</div> 
<!-- produce PlayList -->
<div class="listWrapper">
<div class="listBwrapper"> 
<div id="listHeader" ><?php echo "<span class='maga'>".number_format($startIndex) ."</span> To <span class='maga'>".$starRange."</span>  of  <span class='maga'>". $totalVideo."</span> Recent Videos" ?> </div>
<?php if($totalRes > 50){?>
<!-- morevideo -->
<div id="moreVid" class="listTop"> 
 <a  id="prev" href="<?php echo $prevLink ?>" title="Previous 50 Video" class="cntbutton maga" style="float:left;color:#fff;"><strong  style="font-size:200%;padding:0 10px 0 10px;">&laquo;</strong >50 Videos</a> 
 <a id="next" href="<?php echo $nextLink ?>" title="Next 50 Video" class="cntbutton maga" style="float:right;color:#fff;"><strong style="font-size:200%;padding:0 10px 0 10px;">&raquo;</strong >50 Videos</a>
 </div>
<?php 
} 
else 
{ 
} 
?>
<!-- PlayList -->
<div class="listDivHolder" id="listDivHolder"> 	  
<?php if(isset($result['data']['items'])) { ?>
<ul class="listItemsHolder" id="listItemsHolder" style="list-style: none; margin:0 auto;padding:0;display:block">	  
<?php foreach($result['data']['items'] as $key => $item){ 
$k=$key;
$dTitle=str_replace(array('»','»','¿',':',';','"', "'",'-', '.', '(', ')', '!', '@', '#', '$', '%', '^', '&', '*', '_', '=', '+'), '', $item['title']);	
if($item['description']){
$description=$item['description'];
$description= preg_replace('/(https?:\/\/[^\s"<>]+)/','<a href="$1" target="_blank">  &raquo;&raquo; Go!</a>', $description);
//$description=str_replace(array('â€™','"',  '!', '@', '#', '$', '%', '^', '&', '*', '_', '=', '+'), '', $description);
}
else 
{
$description="No Description Available!";
}
?> 
<li  id="<?php  echo $item['id']; ?>" title="<?php echo ucwords($dTitle) ?>" style="list-style: none; margin:0 auto;padding:1%;display:block">
<div  class="listFrontEnd">	
<!--listFrontEnd-->
<div class="youplay" ><a href="<?php echo "?i=".$iCh."&v=".$item['id']?>" title="PLay" class="iplay">play</a></div>	
<img class="spotImg" src="http://img.youtube.com/vi/<?php echo $item['id']?>/0.jpg" width="100"  alt="<?php echo $item['title']; ?>" /> 
<div class="vidTitle" >
<?php echo ucwords($dTitle) ?>
</div>
</div>	
<div  class="listBackEnd">	
<!-- listBackEnd-->
<!--The content to scroll-->
<div  class="detail">
<?php  echo $description ?>	
</div>	
</div>	
<div class="vidbase">	
<p>
<em class="timago" ><?php  echo timeAgo($item['uploaded']); ?></em>
<em class="imfo showFace frontFace" title="more details" >...More</em>
<em class="imfo hideFace backFace" title="less details" >...Less</em>
<em class="nowplayed" title="duration" ><?php echo date('H:i:s', $item['duration'])?></em>
</p>
<p>
<span style="float:left;margin-left:2%;color:#eee;"><?php echo number_format($rangeArray[$k])."  of  ".$totalVideo;?></span>  
<a href="https://twitter.com/intent/tweet?original_referer=<?php echo $_SERVER['SERVER_NAME'];?>&text=<?php echo rawurlencode(ucwords($dTitle)."     ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" class="sTwitter Share" target="_blank" title="Share On Twitter" >s1</a>
<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode(ucwords($dTitle).$_SERVER['REQUEST_URI']); ?>" class="sFaceBook Share" target="_blank" title="Share On FaceBook">s2</a>
<a href="https://plus.google.com/share?url=<?php echo rawurlencode(ucwords($dTitle).$_SERVER['REQUEST_URI']); ?>"  class="sGooglePlus Share" target="_blank" title="Share On GooglePlus">s3</a>
</p>
</div>	
</li>	 
<?php } //foreach ends?>
</ul><!-- End listItemsHolder here	 -->
<!-- PlayList Ends-->
<!--Error-->
<?php }
else
{
$rfresher="<meta http-equiv='refresh' content='5'>";
 //If no Video ID Print Error page
 ?>
<div class="inError"> <img src="css/images/timeout.png" title="Time Out Image" width="120"  /> 
<strong><span>Error Displaying PlayList !</span></strong> <br/>
<strong> 
What we are having here right now is a failure to load data  from YouTube.... 
</strong> 
<br/>
<em>This may be network or Api timeout. </em> <br/>
<em>The most likely culprit is a networking issue somewhere between the server and the YouTube API. </em> <br/>
<em>In the other hand the application might not have been configure  correctly </em> 
<br/>
<br/>
Trying to refresh the page...... 
<?php echo $rfresher; ?>
In this case if the system is not refreshing itself, then we need to refresh the system mannually. <br/>
          <br/>
Please [<a style="#ff0066" href="<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
Click Here</a>] 
to refresh the system. <em> We Might have to do this more than ones !</em> 
</div>
<!-- error Frame closed here -->
<?php	
}  //closed is no id
?>
</div><!-- End listDivHolder here	 -->	
</div><!-- listBwrapper -->
</div><!--  listWrapper -->
</div><!-- End Body frame here	 -->	
<?php
}// getTub end 
//:::::::Run The Show::::::::::
getTub($channel,$LiveVid,$TwitterUser);
}// Function Shortcode
}// Function Not Exist
//Let Go Live We Good To Go!
add_shortcode("tubentertain", "tubentertain_shortcode");
//::::::::::::::::::::::::::::::::::::
//      Main ShortCode ends Here    ::
//                                  ::
//::::::::::::::::::::::::::::::::::::								   
//::::::::Admin Only:::::::::
add_action( 'admin_init', 'TubEntertainAdminInit' );
add_action('admin_menu', 'TubEntertainAdminPage');
//::
function TubEntertainAdminInit() {       
       wp_register_style( 'TubEntertainAdminCss', plugins_url('css/tubadmin.css', __FILE__) );	 
       wp_register_script('tubentertainIscroll', plugins_url('js/tubscroll.js', __FILE__));
       wp_register_script('tubentertainVid', plugins_url('js/tubadminjs.js', __FILE__));	   
   }
function TubEntertainAdminPage() {
$tubAdminPage=add_menu_page( 'TubEntertain', 'TubEntertain', 'manage_options', 'TubEn', 'TubEntertainSettings', plugins_url( 'tubentertain/logo.jpg' ), 13 );  
add_action( 'admin_print_styles-' . $tubAdminPage, 'tubAdminStylesNScripts' );
}
//::
function tubAdminStylesNScripts() {
 wp_enqueue_style( 'TubEntertainAdminCss' );
 wp_enqueue_script( 'tubentertainIscroll' );
 wp_enqueue_script( 'tubentertainVid' );
   }   
//:::   
function TubEntertainSettings() {
function filesystem_init($form_url, $method, $context, $fields = null) {
global $wp_filesystem;
    /* first attempt to get credentials */
if (false === ($creds = request_filesystem_credentials($form_url, $method, false, $context, $fields))) {
        /**
         * if we comes here - we don't have credentials
         * so the request for them is displaying
         * no need for further processing
         **/
return false;
    }
    /* now we got some credentials - try to use them*/ 
    if (!WP_Filesystem($creds)) {
        /* incorrect connection data - ask for credentials again, now with error message */
        request_filesystem_credentials($form_url, $method, true, $context);
    return false;
    }
    return true; //filesystem object successfully initiated
}
function TubEntWriter($form_url){
global $wp_filesystem;
check_admin_referer('TubEntertainSettings');
$TubUser = sanitize_text_field($_POST['TubUser']); //sanitize the input
$TwtUser = sanitize_text_field($_POST['TwtUser']); 
$form_fields = array('TubUser','TwtUser'); //fields that should be preserved across sc
$method = ''; //leave this empty to perform test for 'direct' writing
$context = WP_PLUGIN_DIR . '/tubentertain/channels'; //target folder
$form_url = wp_nonce_url($form_url, 'TubEntertainSettings'); //page url with nonce value
if(!filesystem_init($form_url, $method, $context, $form_fields))
return false; //stop further processign when request form is displaying
//Validate Data
if($TubUser==""){return new WP_Error('reading_error', 'Enter valid  YouTube UserName'); }
else if($TwtUser==""){return new WP_Error('reading_error', 'Enter valid Twitter UserName'); }
else{
$target_dir = $wp_filesystem->find_folder($context);
$target_file = trailingslashit($target_dir).'config.txt';
}	 
/* write into file */	
if(!$wp_filesystem->put_contents($target_file,  $TubUser."||".$TwtUser, FS_CHMOD_FILE)){ 
return new WP_Error('writing_error', 'Error when writing file');}//return error object		
return "Successful !";
}
// end Writer here
//::::::::::::Monitor Start Here::::::::::
$output = $error = '';
if(isset($_POST['createTub'])){//new submission
if(false === ($output = TubEntWriter($form_url))){        
return; 
} elseif(is_wp_error($output)){
$error = $output->get_error_message();
$output = '';
}
} 
$output = $output; 
//::::::::::::Monitor Ends Here::::::::::
?>
<div id="adPanel">
<center><a href="http://tubentertain.com"><img src="<?php echo plugins_url(  'css/images/adminbanner.png', __FILE__)?>" title="Create TubEntertain"   width="200"/></a></center>
<form name="TubEntertain"  method="post">
<?php wp_nonce_field('TubEntertainSettings'); ?>
<!-- Geting Started ! -->
<div id="forIntro">
<div id="pitems">  
<legend>Geting Started !</legend>
<p>1&diams;  Upload or Download <em>TubEntertain&trade;</em> to the <strong>/wp-content/plugins/</strong> directory.</p>
<p>2&diams;  Activate the plugin through the <strong>[Plugins]</strong> menu in WordPress.</p>
<p>3&diams; Go to the plugin admin, (<em>you are here now</em>).
<p><span style="color:red">Caution ! </span>You are responsible to make sure you that valid datas are entered, otherwise your TubEntertain will not work as expected.</p>
<p>4&diams; In the Configuration box below, type in your <strong>YouTube Username</strong>, <strong>Twitter username</strong> and make sure you add your YouTube feature or live stream <strong>video Id</strong>.</p>
<p>5&diams; Your YouTube feature or live stream video Id, will be the first video to be play when user first navigate to your TubEntertain Player.</p>
<p>6&diams; There also a button which enables switching from <em>Feature or Live video</em> to <em>Playlist</em> and vice versa.</p>
<p>7&diams; If you don't have youtube user name, then you need to login to your youtube account,</p>
<p>8&diams; Follow the Advance option in your user page, you should see option which says create new url or custom url</p>
<p>9&diams; Onecs you are able to create url you have automatically generate youtube user name</p>
<p>10&diams; You will have a similar url <strong>[https://www.youtube.com/user/your_new_usename]</strong></p>
<p>11&diams; All you need there is the username not the full url, copy the user name and paste it into the "YouTube UserName" Input on TubEntertain admin section</p>
<p>12&diams; Finally, copy and paste <strong>[tubentertain]</strong> in your templates or page you will like your player to appear.</p>
<p>13&diams; For further customsation and additional functionalities contact <em>entertainer@tubentertain.com</em> In all communication please refrence #TubEntertain.</p>  
<p>Visit us at  <em><a href="http://tubentertain.com">TubEntertain.Com</a></em></p>  
</div>
</div>
<!-- Configure Your TubEntertain -->
<fieldset id="configBox">
<legend>Configure Your TubEntertain</legend> 
<?php 
if(isset($_POST['createTub'])){
if(!empty($error)) { 
?>
<div id="showError">
<p id="error"><?php echo $error; ?></p>
</div>
<?php 
} 
else
{
?>
<div id="showDone"  >
<p id="success" ><?php echo $output ?></p>
</div>
<?php 
}
}//end error detector if isset
$depoolList=file(plugins_url(  'channels/config.txt', __FILE__));
//for wordpress
$channel=$LiveVid=$TwitterUser="";
if($depoolList > 0){
$depool=explode("||", (string)$depoolList[0]);
$channel=$depool[0];
$TwitterUser=$depool[1];
}
?>    
<p class="shelConfig">
<label for="TubUser">YouTube Username</label>
<img src="<?php echo plugins_url(  'css/images/YouTube-icon.png', __FILE__)?>" title="Image" width="32"  />
<input type="text" class="Lput" name="TubUser" id="TubUser" value="<?php echo $channel ?>" />
</p>
<p class="shelConfig">
<label for="TwtUser">Twitter UserName</label>
<img src="<?php echo plugins_url(  'css/images/twitter.png', __FILE__)?>" title="Image" width="32"  />
<input type="text" class="Lput" name="TwtUser" id="TwtUser" value="<?php echo $TwitterUser ?>" />
</p>
<!--<p class="shelConfig">
<label for="fVideoId">Feature or YouTube Live Stream Video Id</label>
<img src="<?php echo plugins_url(  'css/images/Videoicon.png', __FILE__)?>" title="Image" width="32"  />
<input type="text" class="Lput" name="fVideoId" id="fVideoId" value="<?php echo $LiveVid ?>" />
</p>  --> 
<p>  <?php submit_button('Done !', 'primary', 'createTub', true);?>  </p>
</fieldset>
</form>
</div>
<?php
}
//::::::::::Admin Page End Here:::::::::::::::::::
?>