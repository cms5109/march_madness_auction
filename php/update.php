<?php
require('header.php');
include('static_arrays.php');
$sync_value = get_sync_value();

if (array_key_exists('sync_value',$_GET) && $_GET['sync_value'] == $sync_value) {
	echo "";
	exit;
}

// $teamInfo = build_table("../teamInfo.csv");
db_connect();

$team_id = db_get_current_team_id();
// $opponent_seed = 17 - $teamInfo['seed'][$team_id];
$opponent_id = $teamInfo['opp_key'][$team_id];

$previous_team_id = db_get_previous_team_id();

if ($team_id == -1) {
	echo "";
} else {
	$bid = db_get_current_bid($team_id);
	
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
			"highestbidder":"'.$bid['name'].'",';
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
			"previousteamcolor":"",'
			"previousbidamount":"",
			"previoushighestbidder":""';
	}

			
	echo '}';
}


db_close();

$_SESSION['sync_value'] = $sync_value;



?>