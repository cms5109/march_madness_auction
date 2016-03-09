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

// Update our sync file to notify an update has occured
update_sync();

db_close();

echo "Restarting Draft";
?>