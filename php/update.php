<?php
require('header.php');
include('static_arrays.php');

// Has anything new happened?
$sync_value = get_sync_value();
if (array_key_exists('sync_value',$_GET) && ($_GET['sync_value'] == $sync_value)) {
	echo "";
	exit;
}

// $teamInfo = build_table("../teamInfo.csv");
db_connect();

// Get our team id
$team_id = db_get_current_team_id();
if ($team_id == -1) {
	echo '{	
		"sync_value":"'.$sync_value.'",
		"teamimage":"teamImages/filler.jpg",
		"teamname":"The Most March Madness(est)",
		"teamregion":"",
		"teamseed":"",
		"teamopponent":"You... Are You Ready?",
		"teamopponentseed":"",
		"teamcolor":"#000033",
		"bidamount":"$999",
		"bidtime":"'.$bid['timestamp'].'",
		"highestbidder":"The House",
		"previousteamimage":"teamImages/prevYearWinner.jpg",
		"previousteam":"Duke University",
		"previousteamcolor":"",
		"previousbidamount":"$75",
		"previoushighestbidder":"Bill Powell"}';
	exit;
}
// Get the id of their opponent
$opponent_id = $teamInfo['opp_key'][$team_id];
// Get the current bid info
$bid = db_get_current_bid($team_id);

// Return a JSON object
echo '{	
		"sync_value":"'.$sync_value.'",
		"teamimage":"teamImages/'.$teamInfo['image'][$team_id].'",
		"teamname":"'.$teamInfo['team'][$team_id].'",
		"teamregion":"'.$teamInfo['region'][$team_id].' Region",
		"teamseed":"#'.$teamInfo['seed'][$team_id].'",
		"teamopponent":"'.$teamInfo['team'][$opponent_id].'",
		"teamopponentseed":"#'.$teamInfo['seed'][$opponent_id].'",
		"teamcolor":"'.$teamInfo['color'][$team_id].'",
		"bidamount":"$'.$bid['amount'].'",
		"bidtime":"'.$bid['timestamp'].'",
		"highestbidder":"'.$bid['name'].'",';

// Was there a previous team that was bid on?
$previous_team_id = db_get_previous_team_id();
if ($previous_team_id != -1) {
	$previous_bid = db_get_current_bid($previous_team_id);
	echo '
		"previousteamimage":"teamImages/'.$teamInfo['image'][$previous_team_id].'",
		"previousteam":"'.$teamInfo['team'][$previous_team_id].'",
		"previousteamcolor":"'.$teamInfo['color'][$previous_team_id].'",
		"previousbidamount":"$'.$previous_bid['amount'].'",
		"previoushighestbidder":"'.$previous_bid['name'].'"';
} else {
	echo '
		"previousteamimage":"",
		"previousteam":"",
		"previousteamcolor":"",
		"previousbidamount":"",
		"previoushighestbidder":""';
}

// End JSON object		
echo '}';

// Wrap it up
db_close();
?>