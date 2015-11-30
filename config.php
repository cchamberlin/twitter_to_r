<?php

// Configure your Datumbox API Key. Get yours at http://www.datumbox.com/apikeys/view/
define('DATUMBOX_API_KEY', 'a10985d5b94f706659147118913522c9');

// Configure your OAuth settings from your application at https://dev.twitter.com/apps

define('TWITTER_CONSUMER_KEY', 'i3LYr8dDeZscO81iV8jWc3Qd4');
define('TWITTER_CONSUMER_SECRET', 'EukwfKaFJYRBRlqXteTGKR23YSkYL0SaHtoHVqn6IV6L6f6HVV');


// Configure authentication credentials.
// you can generate your own access key from the link above

define('TWITTER_ACCESS_KEY', '3300917882-rq39MG3dGWJh4DV8W6KKJArQnAqJxbnhOgtcmr6');
define('TWITTER_ACCESS_SECRET', 'PD4qfQMaYlsg991RgFRrq0MDEDQjCndorELTPbT66JHGb'); 

// How many tweets to keep for the top tweets report
define('SAVE_TOP', 10);

// Database info
define('DB_DRIVER','mysql');
define('DB_DATABASE','TwitterSentiment');
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASSWORD',NULL);

// Save debugging info in twits.txt?
define('DUMP_ALL_TWEETS', TRUE);
