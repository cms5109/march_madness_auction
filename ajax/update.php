<?php
require('header.php');
include('tableFunctions.php');

$teamInfo = build_table("../teamInfo.csv");
$sync_value = get_sync_value();

// if (array_key_exists('sync_value',$_SESSION) && $_SESSION['sync_value'] == $sync_value) {
// 	echo "";
// 	exit;
// }

db_connect();

$team_id = db_get_current_team_id();
$opponent_seed = 17 - $teamInfo['seed'][$team_id];


if ($team_id == -1) {
	echo "";
} else {
	$bid = db_get_current_bid($team_id);
	echo '{	
			"teamimage":"teamImages/'.$teamInfo['image'][$team_id].'",
			"teamname":"'.$teamInfo['team'][$team_id].'",
			"teamregion":"'.$teamInfo['region'][$team_id].'",
			"teamseed":"'.$teamInfo['seed'][$team_id].'",
			"teamopponent":"'.$teamInfo['team'][$team_id].'",
			"teamopponentseed":"'.$teamInfo['seed'][$team_id].'",
			"teamcolor":"'.$teamInfo['color'][$team_id].'",
			"bidamount":"$'.$bid['amount'].'",
			"highestbidder":"'.$bid['name'].'"
		}';
}


db_close();

$_SESSION['sync_value'] = $sync_value;



?>