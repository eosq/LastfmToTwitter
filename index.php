<?php
require_once 'twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

$CONSUMER_KEY=''; // Consumer Key
$CONSUMER_SECRET=''; //	Consumer Secret
$oauth_tok=''; //	Access Token
$oauth_sec=''; //	Access Token Secret
$lastfm_user='username';
$lastfm_key=''; //lastfm API key

  $params = array(
      'method'  => 'user.getRecentTracks',
      'user'    => $lastfm_user,
      'limit'  => '1',
      'api_key' => $lastfm_key, // lastfm API key
  );

  $request = file_get_contents('http://ws.audioscrobbler.com/2.0/?' . http_build_query($params, '', '&'));
  $xml = new SimpleXMLElement($request);
  if(isset($xml->recenttracks->track[0]->attributes()->nowplaying) &&
  $xml->recenttracks->track[0]->attributes()->nowplaying==true )
  {
    $mbid=(string)$xml->recenttracks->track[0]->mbid;
  $params = array(
      'method'  => 'track.getInfo',
      'user'    => $lastfm_user,
      'api_key' => $lastfm_key,
      'mbid'    => $mbid
  );
$request = file_get_contents('http://ws.audioscrobbler.com/2.0/?' . http_build_query($params, '', '&'));
$xml = new SimpleXMLElement($request);

$connection = new TwitterOAuth($CONSUMER_KEY,$CONSUMER_SECRET,$oauth_tok,$oauth_sec);
$text=$xml->track->artist->name.' — '.$xml->track->name.' #nowplaying #np';
  echo '<p>'.$text.'</p>';

if(isset($xml->track->album->image[3])) {
  $path=$xml->track->album->image[3];
  echo $xml->track->album->image[3];
  $result=$connection->send($text,NULL,$path);
}
else {
    $result=$connection->send($text);
}
  }
?>
