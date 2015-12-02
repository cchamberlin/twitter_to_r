This is a report that uses the Twitter API, Datumbox, JQuery, PHP, and R to examine a sample of tweets. The top tweets and users are ranked, and the current sentiment is compared to historical data.

The scripts in /lib are (slightly edited) samples from Datumbox, and not my code.

This report uses a basic MVC architecture. All incoming traffic is sent to twitindex.php (the Controller), which attempts to gather all the necessary data into the TwitData object (the Model). The TwitData object then processes all the data into summary statistics (including an R graph), and twitindex.php sends that data model to the TwitTemplate object (the View) for display.

The twitsuggestions.php file responds to POST requests with JSON data showing the hashtags the database is already tracking.


