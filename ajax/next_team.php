<?php

require('header.php');
include('team_array.php');

db_connect();

// Get the list of teams that we bid on.
$bid_teams = db_get_bid_teams();

// Remove them from the list of all possible teams
$remaing_teams = array_diff(range(0,count($teamInfo['team'])-1), $bid_teams);
$remaing_teams = array_values($remaing_teams);

// Get a random index in our array of remaining teams
$next_team_idx = rand(0,count($remaing_teams)-1);
// Extract the team id from the index
$next_team_id = $remaing_teams[$next_team_idx];

// Update the database
db_update_current_team($next_team_id);
db_update_bid($next_team_id, "The House", $teamInfo['initBid'][$next_team_id]);

// Update our sync file to notify an update has occured
update_sync();

db_close();

?>