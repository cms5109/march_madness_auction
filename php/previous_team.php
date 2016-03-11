<?php

require('header.php');
include('static_arrays.php');

if (!isset($_SESSION['ADMIN']) || $_SESSION['ADMIN'] != true) {
//	echo "You don't have permission to advance the teams.";
	exit;
} 

db_connect();

// Remove current team from Database
db_clear_current_team();
db_clear_previous_bid();
// Here we're retaining the bid history for the team we're returning to
// But we're deleting the bid history for the team that we just cleared
// If we need to we can clear individual or all bids for the new (old) team manually

// Get previous team id 
$prev_team_id = db_get_current_team_id();

// Get previous bid info
$prev_bid_info = db_get_current_bid($prev_team_id);
$prev_bidder = $prev_bid_info['name'];
$prev_amount = $prev_bid_info['amount'];

// Refresh both tables with previous info to update timestamp
// Bids
db_clear_previous_bid();
db_update_bid($prev_team_id, $prev_bidder, $prev_amount);
// Teams
db_clear_current_team();
db_update_current_team($prev_team_id);

// Update our sync file to notify an update has occured
update_sync();

db_close();

echo $teamInfo['team'][$prev_team_id];

?>