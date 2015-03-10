<?php
function build_table($fileName) {
	
	// Allocate table arrays
	$team   = array();
	$seed   = array();
	$region = array();
	$image  = array();
	$color  = array();
	
	// Parse data from CSV
	$row = 0;
	if (($handle = fopen($fileName, "r")) !== FALSE) {	
		while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
			$team[$row]   = $data[0];
			$seed[$row]   = $data[1];
			$region[$row] = $data[2];
			$image[$row]  = $data[3];
			$color[$row]  = $data[4];
			$row++;
		}		
		// Close file
		fclose($handle);
	}

	// Define keys
	$keys = range(0, count($team)-1);
	
	// Allocate arrays
	$table = array( "team"    => array_combine($keys, $team),
					"region"  => array_combine($keys, $region),
					"seed"	  => array_combine($keys, $seed),
					"image"	  => array_combine($keys, $image),
					"color"	  => array_combine($keys, $color),
					"initBid" => array_combine($keys, array_fill(1, count($team),0)),
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