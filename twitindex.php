<?php

/***********************************************************************
 * TODO:
 * ------------------------------------------
 * 
 * jQuery .post() existing Hashtags 
 * (<div id="suggestion-box"></div>)?
 */

include_once (dirname ( __FILE__ ) . '/config.php');
include_once (dirname ( __FILE__ ) . '/lib/TwitterSentimentAnalysis.php');
include_once (dirname ( __FILE__ ) . '/TwitTemplate.php');
include_once (dirname ( __FILE__ ) . '/TwitData.php');

try {
	$TwitTemplate = new TwitTemplate();
	$TwitData = new TwitData();
	
	if(isset($_GET['q']) && $_GET['q']!='') {
		
		// Initialize the Sentiment Analysis.
		$TwitterSentimentAnalysis = new TwitterSentimentAnalysis(DATUMBOX_API_KEY, TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_KEY, TWITTER_ACCESS_SECRET);
		
		// Clean inputs
		$query = $TwitData->cleanHashtag($_GET['q']);
		$count = $TwitData->cleanResultsNum($_GET['n']);
		
		// Search Tweets parameters as described at https://dev.twitter.com/docs/api/1.1/get/search/tweets
		$twitterSearchParams = array (
				'q' => $query,
				'lang' => 'en',
				'count' => $count
		);
			
		// Get the results from Twitter and DatumBox.
		$results = ($TwitterSentimentAnalysis->sentimentAnalysis( $twitterSearchParams )) or die($TwitTemplate->twitForm("Caught a cURL error!"));
	
		// Write data to file if debugging.
/* 		if (DUMP_ALL_TWEETS == TRUE) {
			$handle = fopen('twits.txt', 'w');
			fwrite($handle, $query);
			fwrite($handle, print_r($results,TRUE));
			fclose($handle);
		} */
		
		// If there were no results, send them back to the submission form. Otherwise, process and deliver the report.
		if($results == Array()){
			$TwitTemplate->twitForm("Sorry, no results found! Please try again");
		}
		else {
			$TwitData->process($results, SAVE_TOP, $query);
			$TwitTemplate->twitReport($TwitData, $TwitData->results);	
		}
	}
	else {
		// Deliver blank submission form.
		$TwitTemplate->twitForm("");
	}
 } catch (Exception $e) {
 	// Is it returnable to the user?
 	if($e->getCode() == 9999){
 		$TwitTemplate->twitForm($e->getMessage());
 	}
 	else{
	 	// Return warning on submission form.
	 	$TwitTemplate->twitForm("Sorry, there was an error! Helpdesk has been notified. Please try again later.");
	 	
	 	// Write error to the error log
	 	$handle = fopen('error.log', 'a');
	 	fwrite($handle, $e->getFile() . " Line " . $e->getLine() . "\t" . $e->getMessage() . "\n");
	 	fclose($handle);
 	}
 }