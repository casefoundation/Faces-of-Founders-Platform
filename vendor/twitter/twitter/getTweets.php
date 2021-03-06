<?php

require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "",
    'oauth_access_token_secret' => "",
    'consumer_key' => "",
    'consumer_secret' => ""
);

$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';
$getfield = '?q=#DefeatMalaria&result_type=recent&count=10';

// Perform the request
$twitter = new TwitterAPIExchange($settings);
$tweets = json_decode($twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest());

$tweets_arr = array();
$tweets_ids = array();

foreach($tweets->statuses as $i=>$tweet_info){
    $tweets_arr[$tweet_info->id_str] = array(
        "created_at"        =>  $tweet_info->created_at,
        "id"                =>  $tweet_info->id_str,
        "text"              =>  $tweet_info->text,
        "user_name"         =>  $tweet_info->user->name,
        "user_displayname"  =>  $tweet_info->user->screen_name,
        "user_image"        =>  $tweet_info->user->profile_image_url,
    );
    $tweets_ids[] = $tweet_info->id_str;
}

include_once '../parse/parse.php';
$parseQuery = new parseQuery('tweet');
$parseQuery->whereContainedIn('tweet_id', $tweets_ids);
$queryResult = $parseQuery->find();


    try {
     $queryResult = $parseQuery->find();
    } catch (Exception $ex) {
        print_r($ex);
    }
    $ff = array();
    foreach($queryResult->results as $index=>$tweet){
            if($tweet->status=="suppressed"){
                unset($tweets_arr[$tweet->tweet_id]);
            }
    }
    $tweets_arrary = array();
    foreach($tweets_arr as $id=>$tweet){
        $tweets_arrary[] = $tweet;
    }

    echo json_encode($tweets_arrary);
