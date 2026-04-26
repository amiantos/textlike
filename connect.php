<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

$db_host		= getenv('DB_HOST') ?: 'db';
$db_user		= getenv('DB_USER') ?: 'textlike';
$db_pass		= getenv('DB_PASS') ?: 'textlike';
$db_database	= getenv('DB_NAME') ?: 'textlike';

/* End config */

$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_select_db($db_database,$link);
mysql_query("SET names UTF8");

date_default_timezone_set('America/Los_Angeles');
$date = date("Y-m-d H:i:s");

function post_tweet($tweet_text) {

  // Use Matt Harris' OAuth library to make the connection
  // This lives at: https://github.com/themattharris/tmhOAuth
  require_once('tmhoauth/tmhOAuth.php');

  // Set the authorization values
  // To enable Twitter posting, replace these with real credentials
  $connection = new tmhOAuth(array(
    'consumer_key' => 'consumer_key',
    'consumer_secret' => 'consumer_secret',
    'user_token' => 'user_token',
    'user_secret' => 'user_secret',
	'curl_ssl_verifypeer'   => false
  ));

  // Make the API call
  $connection->request('POST',
    $connection->url('1.1/statuses/update'),
    array('status' => $tweet_text));

  return $connection->response['code'];
}


?>
