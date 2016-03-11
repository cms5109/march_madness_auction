<?php

require('header.php');
include('static_arrays.php');

if (!isset($_SESSION['ADMIN']) || $_SESSION['ADMIN'] != true) {
//	echo "You don't have permission to clear bids;
	exit;
} 

db_connect();

// Ensure there is a bid to clear
// Get current team id 
$team_id = db_get_current_team_id();

// Get current bid info
$bid_info = db_get_current_bid($team_id);
$bidder = $bid_info['name'];
$amount = $prev_bid_info['amount'];

// Don't remove bids by "The House"
if ($bidder == "The House") {
	echo "No bid to clear";
	exit;
}

// Remove previous bid
db_clear_previous_bid();

// Get new current bid info
$prev_bid_info = db_get_current_bid($team_id);
$prev_bidder = $prev_bid_info['name'];
$prev_amount = $prev_bid_info['amount'];

// Refresh last entry in both tables with previous info to update timestamp
// Bids
db_clear_previous_bid();
db_update_bid($team_id, $prev_bidder, $prev_amount);
// Teams
db_clear_current_team();
db_update_current_team($team_id);

// Update our sync file to notify an update has occured
update_sync();

db_close();

echo sprintf("Cleared %s's bid of $%s for %s",
			 $bidder,
			 $amount,
			 $teamInfo['team'][$team_id]);
?>