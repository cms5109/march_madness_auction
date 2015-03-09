<?php
require('header.php');

$sync_value = get_sync_value();

db_connect();

// if (isset($_GET) && array_key_exists('wait', $_GET)) {
// 	while (get_sync_value() == $sync_value) {
// 		usleep(10);
// 	}
// }

$team_id = db_get_current_team_id();

if ($team_id == -1) {
	echo "";
} else {
	$bid = db_get_current_bid($team_id);
	echo '{	
			"teamimage":"http://acc.blogs.starnewsonline.com/files/2013/01/unc-logo1.gif",
			"teamname":"Test",
			"teamregion":"Test",
			"teamseed":"#0",
			"teamopponent":"Someone",
			"bidamount":"$'.$bid['amount'].'",
			"highestbidder":"'.$bid['name'].'"
		}';
}


db_close();




?>