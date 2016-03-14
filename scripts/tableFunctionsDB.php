<?php

// Build our access control list from CalcuttaInfo DB.
function build_acl_db() {
	
	// Connect to DB
	global $sql_user, $sql_pass, $sql_db, $sql_table_player, $sql_year;
	$link = @mysql_connect('localhost', $sql_user, $sql_pass)
		or die('Could not connect: ' . mysql_error());
	mysql_select_db($sql_db)
		or die('Could not select database');
		
	// Query DB for player information
	$query_players = sprintf("SELECT player_id, player_name, email_addr FROM player_info
							  WHERE in_%s='1'",
							  $sql_year);	
	$result = mysql_query($query_players);
	
	// Check result
	if (!$result) {
		$message = 'Invalid query: ' . mysql_error() . "\n";
		$message = 'Whole query: ' . $query_players;
		die($message);
	}
	
	// Use result
	$userInfo = Array();
	while($row = mysql_fetch_assoc($result)) {
		$p_id  = $row['player_id'];
		$name  = $row['player_name'];
		$email = $row['email_addr'];
		
		$userInfo[$email] = $name;
	}
		
	// Free result	
	mysql_free_result($result);
	
	// Close connect
	mysql_query("unlock tables");
	mysql_close();
	
	return $userInfo;
}

// Extract team info from CSV file
function build_table_db() {
	
	// Connect to Define
	global $sql_user, $sql_pass, $sql_db, $sql_year;
	$link = @mysql_connect('localhost', $sql_user, $sql_pass)
		or die('Could not connect: ' . mysql_error());
	mysql_select_db($sql_db)
		or die('Could not select database');
		
	// Query DB for team information	
	$query_teams = sprintf("SELECT year.team_name, year.tourn_id, year.opp_id, year.team_seed, year.team_region, info.team_logo, info.team_color 
							FROM team_%s year 
							INNER JOIN team_info info 
							ON info.team_id = year.team_id",
							$sql_year);
	$result = mysql_query($query_teams);
	
	// Check result
	if (!$result) {
		$message = 'Invalid query: ' . mysql_error() . "\n";
		$message = 'Whole query: ' . $query_teams;
		die($message); 
	}
	
	// Use result
	while($row = mysql_fetch_assoc($result)) {
		$team   = $row['team_name'];
		$key    = $row['tourn_id'];
		$o_id   = $row['opp_id'];
		$seed   = $row['team_seed'];
		$region = $row['team_region'];
		$image  = $row['team_logo'];
		$color  = $row['team_color'];
		
		// Form arrays
		$team_array[$key]   = $team;
		$o_id_array[$key]   = $o_id;
		$seed_array[$key]   = $seed;
		$region_array[$key] = $region;
		$image_array[$key]  = $image;
		$color_array[$key]  = $color;
	}
	// Free result	
	mysql_free_result($result);
	
	// Fill initial bids based on seed
	foreach ($seed_array as $key => $value) {
		if ($value == 1 || $value == 2) {
			$ibid_array[$key] = "20";
		} elseif ($value == 3 || $value == 4) {
			$ibid_array[$key] = "10";
		} elseif ($value >= 5 && $value <= 8) {
			$ibid_array[$key] = "5";
		} elseif ($value >= 9 && $value <= 12) {
			$ibid_array[$key] = "3";
		} else {
			$ibid_array[$key] = "1";
		} // end if-else
	} // end foreach

	// Assemble teamInfo array
	$teamInfo = array("team"    => $team_array,
					  "region"  => $region_array,
					  "seed"    => $seed_array,
					  "image"   => $image_array,
					  "color"   => $color_array,
					  "initBid" => $ibid_array,
					  "opp_key" => $o_id_array);
					  
	
	// Close connect
	mysql_query("unlock tables");
	mysql_close();
	
	return $teamInfo;
} // end function buildTable

function get_rand_id($availIDs) {
	
	// Generate random index for team_id
	$randIdx = rand(1, count($availIDs));	
	// Retrieve random team_id
	$randID = $availIDs($randIdx);	
	
	return $randID;	
} // end function randTeam

?>