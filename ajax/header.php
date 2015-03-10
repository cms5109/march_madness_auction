<?php
error_reporting (E_ALL);
ini_set('display_errors', 1);

session_start();

// Mysql stuff
$servername = "localhost";
$sql_user = "march";
$sql_pass = "madness";
$sql_db = "marchmadness";
$sql_table_bid = "bids";
$sql_table_team = "current_team";

// Syncronization stuff
$sync_file = "/var/www/marchmadness/sync_file";
$sync_file = "/Users/Shortman/projects/march_madness_auction/sync_file";


// Bid specific stuff
$BID_INCREMENT = 1;

// Create connection
function db_connect() {
	global $sql_user, $sql_pass, $sql_db, $sql_table_bid;
	$link = @mysql_connect('localhost', $sql_user, $sql_pass)
	    or die('Could not connect: ' . mysql_error());
	mysql_select_db($sql_db) or die('Could not select database');
}

// Close our connection
function db_close() {
	// Unlock our tables
	mysql_query("unlock tables");
	mysql_close();
}

// Lock our db tables
function db_lock() {
	global $sql_table_bid;
	mysql_query("lock table $sql_table_bid");
}

// Return the current highest bid
function db_get_current_bid($team_id) {
	global $sql_table_bid;

	$sql = "SELECT * from $sql_table_bid WHERE team_id = '$team_id' ORDER BY amount DESC LIMIT 1";

	// Figure out the current bid
	$result = mysql_query($sql) or die('{"status": 0, "msg":"Error getting current bid for team id: '.$team_id.'"}');

	// extract our results
	$row = mysql_fetch_array($result);
	return Array('name'=>$row['name'],
				'amount'=>intval($row['amount']),
				'timestamp'=>$row['timestamp']);
}

// Return the current team id being bid on
function db_get_current_team_id() {
	global $sql_table_team;

	$sql = "SELECT team_id from $sql_table_team ORDER BY timestamp DESC";

	// Figure out the current bid
	$result = mysql_query($sql) or die('{"status": 0, "msg":"Error getting the current team id. ('.mysql_error().'"}');

	if (mysql_num_rows($result) == 0) {
		return -1;
	}
	// extract our results
	$row = mysql_fetch_array($result);
	return intval($row['team_id']);
}

// Update the current team that we are bidding on
function db_update_current_team($team_id) {
	global $sql_table_team; 

	$sql = "INSERT INTO $sql_table_team (team_id) VALUES ('$teamid')";
	mysql_query($sql) or die('Query failed: ' . mysql_error());
}

// Insert a bid into the table
function db_update_bid($team_id, $name, $amount) {
	global $sql_table_bid;

	$sql = "INSERT INTO $sql_table_bid (team_id, name, amount)
	VALUES ('$team_id', '$name', '$amount')";
	mysql_query($sql) or die('Query failed: ' . mysql_error());
}

// Update our sync_file
function update_sync() {
	global $sync_file;
	$current = file_get_contents($sync_file);
	$cur_val = intval($current);
	$f = fopen($sync_file, "w+");
	fwrite($f, $cur_val+1);
	fclose($f);
}

// Get the current synchronization file from our file.
function get_sync_value() {
	global $sync_file;
	$current = file_get_contents($sync_file);
	$cur_val = intval($current);
	return $cur_val;
}


?>