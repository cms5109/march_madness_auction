<?php

require('header.php');
include('static_arrays.php');

if (!isset($_SESSION['ADMIN']) || $_SESSION['ADMIN'] != true) {
//	echo "You don't have permission to restart the draft;
	exit;
} 

db_connect();

// Restart Draft
db_clear_all_teams();
db_clear_all_bids();
db_update_current_team(-1);
db_update_bid(-1, "The House", 999);

// Update our sync file to notify an update has occured
update_sync();

db_close();

echo "Restarting Draft";
?>