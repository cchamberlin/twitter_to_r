<?php
class TwitTemplate
{
	
/////////////////////////////
	public function header(){
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" class="client-nojs">
<head>
<meta charset="UTF-8" />
<title>Twitter Analysis</title>
<style>
caption{font-weight:bold;border:1px solid black;border-collapse:collapse;padding:2px 10px;background-color:lightgrey;}
th{cursor:n-resize;background-color:lightgrey;border:1px solid black;border-collapse:collapse;padding:2px 10px;}

p.message{color:red;}
.big{padding:none;border:none;border-collapse:collapse;}
.separator{background-color:darkgreen;}
.bignum{color:white;background-color:darkgreen;text-align:center;font-size:200%;}
.bolder{font-weight:bold;}

#rundata td {font-weight:bold;border:1px solid black;border-collapse:collapse;padding:2px 10px;}
#topusers, #topusers td, #history, #history td, #toptweets, #toptweets td {
border:1px solid black;
border-collapse:collapse;
padding:2px 10px;
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
$(document).ready(function(){

	$('th').click(function(){
		var table = $(this).parents('table').eq(0)
		var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
		this.asc = !this.asc
		if (!this.asc){rows = rows.reverse()}
		for (var i = 0; i < rows.length; i++){table.append(rows[i])}
	})
	function comparer(index) {
		return function(a, b) {
			var valA = getCellValue(a, index), valB = getCellValue(b, index)
			return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
		}
	}
	function getCellValue(row, index){ return $(row).children('td').eq(index).html() }
});
</script>
</head>
<body>
<h1>Twitter Sentiment Analysis</h1>
<?php
	}
	
//////////////////////////////////////////////////////////////
    public function twitReport($TwitData, $results) {
    	$this->header();
    	?>
<p><a href="twitindex.php">Return to entry form</a></p>
<table id="rundata">
<tbody>
<tr><td>Date Range:</td><td><?php print(date_format($TwitData->start,'Y-m-d H:i:s') . ' to ' .
    			date_format($TwitData->end,'Y-m-d H:i:s'))?></td></tr>
<tr><td>Tweets:</td><td><?php print($TwitData->twitCount)?></td></tr>
<tr><td>Average Sentiment Score:</td><td><?php printf("%01.2f", $TwitData->avgSentiment)?></td></tr>
</tbody>
</table>

<br />

<table class="big"><tr class="big" valign="top"><td class="big">
<table id="topusers">
<thead>
<caption><b>Top Users</b></caption>
<tr><th>User</th><th>Tweets</th><th>Sentiment</th></tr>
</thead><tbody>
<?php
		foreach ($TwitData->users as $user => $counts ){
?>
<tr><td style="color:white;background-color:darkgreen;"><?php print($user)?></td>
<td><?php print($counts['tweetCount'])?></td>
<td><?php print($counts['sentiment'])?></td>
</tr>
<?php 
		}
?>
</tbody>
</table>

</td><td class="big">

<table id="history">
<thead>
<caption><b>Sentiment History</b></caption>
</thead>
<tr><td><img src="sentiment.png" alt="Graph of sentiment history"></td></tr>
</table></td></tr></table>

<br />

<table id="toptweets">
<thead>
<caption><b><?php print("Top " . $TwitData->saveTop . " Tweets")?></b></caption>
<tr><th></th><th>User</th><th>Date</th><th>Retweets</th><th>Favorites</th><th>Sentiment</th><th>URL</th></tr>
</thead><tbody>
<?php
		foreach ( $results as $index => $tweet ) {
			if(isset($rownum)){	$rownum++; }
			else{				$rownum = 1; }
?> 
<tr>
	<td rowspan="2" class="bignum"><?php print($rownum); ?>
	<td class="bolder"><?php print($tweet['user'])?></td>
	<td><?php print(date_format( $tweet['created_at'], 'Y-m-d H:i:s' ))?></td>
	<td><?php print($tweet['retweet_count'])?></td>
	<td><?php print($tweet['favorite_count'])?></td>
	<td><?php print($tweet['sentiment'])?></td>
	<td><?php print('<a href=' . $tweet['url'] . '>' . $tweet['url'] . '</a>')?></td>
</tr>
<tr>
	<td colspan="6"><?php print($tweet['text'])?></td></tr>
<tr class="separator"><td colspan="7"></td></tr>
	<?php 
		}
	?>
</tbody>
</table>

<p><a href="twitindex.php">Return to entry form</a></p>

<?php		

		$this->footer();
    }
    
    
////////////////////////////////
    public function twitForm($message) {
    	$this->header();
?>
<p>Type your keyword below to perform Sentiment Analysis on Twitter results:</p>
<form method="GET">
    <label>Keyword: </label> <input type="text" name="q" /> 
    <label>Sample: </label>  
    	<select name="n">
  			<option>10</option>
  			<option>20</option>
  			<option>50</option>
  			<option>100</option>
  			<option>200</option>
  			<option>300</option>
		</select>
    <input type="submit" />
</form>
<p class="message"><?php print($message); ?></p>
<?php
		$this->footer();
    }
    
    
/////////////////////////////
    public function footer(){
    	?>
</body>
</html>
    <?php
        }
}