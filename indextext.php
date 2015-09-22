<?php

/***********************************************************************
 * TODO:
 * ------------------------------------------
 * X - Stop using sort_table
 * Move to html output
 * Add front-end
 */


include_once(dirname(__FILE__).'/config.php');
include_once(dirname(__FILE__).'/lib/TwitterSentimentAnalysis.php');
include_once(dirname(__FILE__).'/TwitTemplate.php');

$TwitterSentimentAnalysis = new TwitterSentimentAnalysis(DATUMBOX_API_KEY,TWITTER_CONSUMER_KEY,TWITTER_CONSUMER_SECRET,TWITTER_ACCESS_KEY,TWITTER_ACCESS_SECRET);

//Search Tweets parameters as described at https://dev.twitter.com/docs/api/1.1/get/search/tweets
$twitterSearchParams=array(
    'q'=>'#MNTwins',
    'lang'=>'en',
    'count'=>10
);
$saveTop = 20;
$dumpAllTweets = FALSE;
$sentimentScore = 0;
$totalSentimentScore = 0;
$twitCount = 0;
$dates = array();
$users = array();

$TwitTemplate = new TwitTemplate();
$results=$TwitterSentimentAnalysis->sentimentAnalysis($twitterSearchParams);

$handle = fopen('twits.txt','w');

// Sort tweets by retweet count and retweet status.
$results = table_orderby($results, 'retweet_count', SORT_DESC, 'parent', SORT_ASC, 'is_retweet', SORT_ASC);

foreach($results as $index=>$tweet) {
	// Get a numeric sentiment score and track it.
	if($tweet['sentiment'] == 'positive') { $sentimentScore = 1; }
	elseif ($tweet['sentiment'] == 'negative') { $sentimentScore = -1; }
	else { $sentimentScore = 0; }
	$totalSentimentScore = $totalSentimentScore + $sentimentScore;
	$twitCount++;
	
	// Get dates for start and end.
	array_push($dates, $tweet['created_at']);
	
	// Keep track of users, their tweet count, and their total sentiment.
	if(isset($users[$tweet['user']])){
		$users[$tweet['user']]['tweetCount']++;
		$users[$tweet['user']]['sentiment'] = $users[$tweet['user']]['sentiment'] + $sentimentScore;
	}
	else {
		$users[$tweet['user']] = array('tweetCount'=>1,'sentiment'=>$sentimentScore);
	}
	
	// Write data to file.
	if($dumpAllTweets == TRUE) {
		fwrite($handle, 
			$tweet['user'] . "\t" . 
			date_format($tweet['created_at'],'Y-m-d H:i:s') . "\t" .
			'is retweet: ' . $tweet['is_retweet'] . "\t" .
			'retweets: ' . $tweet['retweet_count'] . "\t" .
			'favorites: ' . $tweet['favorite_count'] . "\t" .
			$sentimentScore . "\t" . 
			$tweet['url'] . "\t" . 
			$tweet['text'] . "\n" . 
			print_r($tweet['raw'],TRUE) . "\n"
			);
	}
	// Keep the top X tweets.
	if($twitCount > $saveTop){
		unset($results[$index]);
	}
	unset($tweet['raw']);
	
}

// Get start and end dates.
sort($dates);
$start = array_shift($dates);
$end = array_pop($dates);
$avgSentiment = $totalSentimentScore/$twitCount;

// Get date range, volume, and average sentiment.
fwrite($handle, "Date Range:\t" . date_format($start,'Y-m-d H:i:s') . ' to ' . date_format($end,'Y-m-d H:i:s') . "\n");
fwrite($handle, "Tweets:\t" . $twitCount . "\n");
fwrite($handle, "Average Sentiment Score:\t" . sprintf("%01.2f", $avgSentiment) . "\n\n");

// Sort users by number of tweets.
$users = table_orderby($users, 'tweetCount', SORT_DESC);

// Print list of users.
fwrite($handle, "Top Users\n");
fwrite($handle, "User\tTweets\tSentiment" . "\n");
foreach($users as $user=>$counts){
	//print_r($user);
	fwrite($handle, $user . "\t" . $counts['tweetCount'] . "\t" . $counts['sentiment'] . "\n");
}

// Print most-retweeted tweets.
fwrite($handle, "\nTop " . $saveTop . " Tweets\n");
fwrite($handle, "User\tDate\tRetweets\tFavorites\tSentiment\tURL\n");
//fwrite($handle, print_r($results, TRUE));
foreach($results as $index=>$tweet) {
	fwrite($handle,
		$tweet['user'] . "\t" .
		date_format($tweet['created_at'],'Y-m-d H:i:s') . "\t" .
		$tweet['retweet_count'] . "\t" .
		$tweet['favorite_count'] . "\t" .
		$sentimentScore . "\t" .
		$tweet['url'] . "\n" .
		$tweet['text'] . "\n"
	);
}

fclose($handle);

function table_orderby()
{
	$args = func_get_args();
	$data = array_shift($args);
	foreach ($args as $n => $field) {
		if (is_string($field)) {
			$tmp = array();
			foreach ($data as $key => $row)
				$tmp[$key] = $row[$field];
			$args[$n] = $tmp;
		}
	}
	$args[] = &$data;
	call_user_func_array('array_multisort', $args);
	return array_pop($args);
}

