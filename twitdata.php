<?php
class TwitData
{
	public $results;
	public $twitCount = 0;
	public $saveTop;
	public $totalSentimentScore = 0;
	public $users = array ();
	public $avgSentiment = 0;
	public $start;
	public $end;
	
	public function cleanHashtag($hashtag) {
		// Lower case.
		$hashtag = strtolower($hashtag);
		
		// Does it start with #? If not, add the #.
		if(strpos($hashtag, '#')===false){
			$hashtag = '#' . $hashtag;
		}
		
		// Is it too long?
		if(strlen($hashtag) > 140){
			throw new Exception("Hashtags must be shorter than the tweet!",9999);
		}
		
		// Is there something beside letters and numbers in the rest of the hashtag?
		if(preg_match('/[^a-z0-9]/', $hashtag, $matches, 0, 1)<>0){
			throw new Exception("Hashtags can't contain anything but letters and numbers after the # symbol!",9999);
		}
		
		return $hashtag;
	}
	
	public function cleanResultsNum($resultsNum){
		if(($resultsNum < 10) || ($resultsNum > 300) ){
			throw new Exception("Number of results out of bound. Suspicious.");
		}
		elseif (preg_match('/[^0-9]/',$resultsNum)<>0){
			throw new Exception("Number of results is not a number. Suspicious.");
		}
		else {
			return $resultsNum;
		}
	}
	
    public function process($results, $saveTop) {
    	
    	$sentimentScore = 0;
    	$dates = array();
    	$this->saveTop = $saveTop;
    	$this->twitCount = 0;
    	
    	// Sort tweets by retweet count and retweet status.
    	$results = $this->table_orderby( $results, 'retweet_count', SORT_DESC, 'parent', SORT_ASC, 'is_retweet', SORT_ASC );
    	$users = array();
    	
    	// Process sorted tweets
    	foreach ( $results as $index => $tweet ) {
			$this->twitCount++;
    		
    		// Get a numeric sentiment score and track it.
    		if ($tweet['sentiment'] == 'positive') {
    			$sentimentScore = 1;
    		} elseif ($tweet['sentiment'] == 'negative') {
    			$sentimentScore = - 1;
    		} else {
    			$sentimentScore = 0;
    		}
    		$this->totalSentimentScore = $this->totalSentimentScore + $sentimentScore;

    	
    		// Get dates for start and end.
    		array_push( $dates, $tweet['created_at'] );
    	
    		// Keep track of users, their tweet count, and their total sentiment.
    		if (isset( $users[$tweet['user']] )) {
    			$users[$tweet['user']]['tweetCount']++;
    			$users[$tweet['user']]['sentiment'] = $users[$tweet['user']]['sentiment'] + $sentimentScore;
    		} else {
    			$users[$tweet['user']] = array (
    					'tweetCount' => 1,
    					'sentiment' => $sentimentScore
    			);
    		}
    	 		
    		
    		// Keep the top X tweets.
    		if ($this->twitCount > $saveTop) {
    			unset ( $results[$index] );
    		}
    		unset ( $tweet ['raw'] );
    	}
    	
    	// Get start and end dates.
    	sort( $dates );
    	$this->start = array_shift( $dates );
    	$this->end = array_pop( $dates );
    	$this->avgSentiment = $this->totalSentimentScore / $this->twitCount;
    	
    	// Sort users by number of tweets.
    	$this->users = $this->table_orderby( $users, 'tweetCount', SORT_DESC );
    	
    	// Keep sorted table.
    	$this->results = $results;
    }
    
    protected function table_orderby() {
		$args = func_get_args ();
		$data = array_shift ( $args );
		foreach ( $args as $n => $field ) {
			if (is_string ( $field )) {
				$tmp = array ();
				foreach ( $data as $key => $row )
					$tmp [$key] = $row [$field];
				$args [$n] = $tmp;
			}
		}
		$args [] = &$data;
		call_user_func_array ( 'array_multisort', $args );
		return array_pop ( $args );
	}
}