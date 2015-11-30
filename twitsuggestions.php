<?php
require('config.php');

try {
	// Get all the unique hashtags that the database is currently tracking
	$results = array();
	
	$db = new PDO(DB_DRIVER . ":dbname=" . DB_DATABASE . ";host=" . DB_SERVER . ";charset=utf8", DB_USER);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $db->prepare("SELECT DISTINCT Hashtag FROM TestRuns;");
	
	$isQueryOk = $stmt->execute();
	
	if ($isQueryOk) {
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN);
	} else {
		trigger_error('Error executing statement.', E_USER_ERROR);
	}
	
	$db = null;
	
	echo json_encode($results);

} catch (Exception $e) {
	// Write error to the error log
	$handle = fopen('error.log', 'a');
	fwrite($handle, $e->getFile() . " Line " . $e->getLine() . "\t" . $e->getMessage() . "\n");
	fclose($handle);
	echo json_encode(array('There','was','an','error'));
}