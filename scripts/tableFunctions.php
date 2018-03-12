<?php

// Build our access control list from CSV file.
function build_acl($filename) {
	$userInfo = Array();
	if (($handle = fopen($filename, "r")) !== FALSE) {
    	while (($data = fgetcsv($handle)) !== FALSE) {
    		$name = $data[0];
    		$email = $data[1];
    		$userInfo[$email] = $name;
    	}
    }
    return $userInfo;
}

// Extract team info from CSV file
function build_table($fileName) {
	
	// Allocate table arrays
	$team   = array();
	$seed   = array();
	$region = array();
	$image  = array();
	$color  = array();
	
	// Parse data from CSV
	$row = 1;
	if (($handle = fopen($fileName, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1024, ",", '"', '"')) !== FALSE) {
			$team[$row]   = $data[1];
			$seed[$row]   = $data[2];
			$region[$row] = $data[3];
			$image[$row]  = $data[4];
			$color[$row]  = $data[5];
			$row++;
		}		
		// Close file
		fclose($handle);
	}

	// Define keys
	$keys = range(1, count($team));
	
	// Allocate arrays
	$table = array( "team"    => array_combine($keys, $team),
					"region"  => array_combine($keys, $region),
					"seed"	  => array_combine($keys, $seed),
					"image"	  => array_combine($keys, $image),
					"color"	  => array_combine($keys, $color),
					"initBid" => array_combine($keys, array_fill(0, count($team), 0) ),
					"opp_key" => array_combine($keys, array_fill(0, count($team), 0) ),
						);
	// Fill initial bids based on scale
	foreach($table["seed"] as $key => $value) {
		if ($value == 1 || $value == 2){
			$table["initBid"][$key] = "\$20";
		} elseif ($value == 3 || $value == 4) {
			$table["initBid"][$key] = "\$10";
		} elseif ($value >= 5 && $value <= 8) {
			$table["initBid"][$key] = "\$5";
		} elseif ($value >= 9 && $value <= 12) {
			$table["initBid"][$key] = "\$3";
		} else {
			$table["initBid"][$key] = "\$1";
		} // end if-else
	} // end foreach

	// Fill opponent key
	foreach($table["seed"] as $key => $value) {
		$opp_seed = 17 - $value;
		$opp_seed_key_array = array_keys($table["seed"], $opp_seed);
		foreach($opp_seed_key_array as $opp_seed_key) {
			if ($table["region"][$key] == $table["region"][$opp_seed_key]) {
				$table["opp_key"][$key] = $opp_seed_key;
			}
		}
	}
	
	return $table;
} // end function buildTable

function get_rand_id($availIDs) {
	
	// Generate random index for team_id
	$randIdx = rand(1, count($availIDs));	
	// Retrieve random team_id
	$randID = $availIDs($randIdx);	
	
	return $randID;	
} // end function randTeam

?>
