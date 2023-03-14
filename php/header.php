<?php
//error_reporting (E_ALL);
ini_set('display_errors', 1);
include('static_arrays.php');

session_start();

// Mysql stuff
$admin_email = "acampbell.psu@gmail.com";
// $servername = "localhost";
// $sql_user = "march";
// $sql_pass = "madness";
// $sql_db = "marchmadness";
$sql_table_bid = "bids";
$sql_table_team = "current_team";
// $admin_pass = "haters";
// $link = "";

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;


//Trying platform SH stuff
//use Platformsh\ConfigReader\Config;
//$config = new Config();
$credentials = $config->credentials('database');
$platform_server = "a6iqkwgjbmcxwv3wzxerspvmoe.db.service._.us-2.platformsh.site"; //$credentials['hostname'];
$platform_port = "3306"; //$credentials['port'];
$platform_path = "main"; //$credentials['path'];
$platform_username = "user"; //$credentials['username'];
$platform_password = ""; //$credentials['password'];
$platform_db = "main";


// Syncronization stuff
$sync_file = "./sync_file"; // actually in 'php/'

// Bid specific stuff
$BID_INCREMENT = 1;

// What to display if the user fails authentication
function fail($msg) {
	echo '<body style="margin-top:25%;text-align:center;">';
	if ($msg != "") {
		echo '<div style="font-weight:bold;color:red;margin:2%">'.$msg.'</div>';
	}
	echo "Please enter your e-mail address below:";
	echo '<form><input name="user_email"><BR><input type="submit"></form>';
	echo '</body>';
	exit;
}

// Force users to login
if (!array_key_exists('user_name',$_SESSION)) {

	if (isset($_GET) && array_key_exists('user_email',$_GET)) {
		$user_email = $_GET['user_email'];
		$name = check_access($user_email);
		if ($name === false) {
			fail("Invalid E-mail.");
		} else {
			$_SESSION['user_name'] = $name;
			$_SESSION['user_email'] = $user_email;
		}
	} else {
		fail("");
	}
	// Check to see if this is an admin
	if ($_SESSION['user_email'] == $admin_email) {
		$_SESSION['ADMIN'] = true;
	}
}

// Check to see if this is an admin
//if ($_GET['admin'] == $admin_pass) {
   //$_SESSION['ADMIN'] = true;
//}

// Create connection
function db_connect() {
	// global $link, $cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db, $sql_table_bid;
	//  $link = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db)
	//     or die('Could not connect: ' . mysqli_error($cleardb_server));
	// mysqli_select_db($link, $cleardb_db) or die('Could not select database');

	global $link, $platform_server, $platform_username, $platform_password, $platform_db, $sql_table_bid;
	  $link = mysqli_connect($platform_server, $platform_username, $platform_password, $platform_db)
	     or die('Could not connect: ' . mysqli_error($platform_server));
	 mysqli_select_db($link, $platform_db) or die('Could not select database');
}

// Close our connection
function db_close() {
	global $link;
	// Unlock our tables
	mysqli_query($link, "unlock tables");
	mysqli_close($link);
}

// Lock our db tables
function db_lock() {
	global $link, $sql_table_bid, $sql_table_team;
	mysqli_query($link, "lock table $sql_table_bid WRITE, $sql_table_team WRITE");
}

// Return the current highest bid
function db_get_current_bid($team_id) {
	global $link, $sql_table_bid;

	$sql = "SELECT * from $sql_table_bid WHERE team_id = '$team_id' ORDER BY amount DESC LIMIT 1";

	// Figure out the current bid
	$result = mysqli_query($link, $sql) or die('{"status": 0, "msg":"Error getting current bid for team id: '.$team_id.'"}');

	// extract our results
	$row = mysqli_fetch_array($result);
	return Array('name'=>$row['name'],
				'amount'=>$row['amount'],
				'timestamp'=>$row['timestamp']);
}

// Return the current team id being bid on
function db_get_current_team_id() {
	global $link, $sql_table_team;

	$sql = "SELECT team_id from $sql_table_team ORDER BY timestamp DESC LIMIT 1";

	// Figure out the current bid
	$result = mysqli_query($link, $sql) or die('{"status": 0, "msg":"Error getting the current team id. ('.mysqli_error($link).'"}');

	if (mysqli_num_rows($result) == 0) {
		return -1;
	}
	// extract our results
	$row = mysqli_fetch_array($result);
	return intval($row['team_id']);
}

// Return the previous team id
function db_get_previous_team_id() {
	global $link, $sql_table_team;

	$sql = "SELECT team_id from $sql_table_team ORDER BY timestamp DESC LIMIT 1,1";

	// Figure out the current bid
	$result = mysqli_query($link, $sql) or die('{"status": 0, "msg":"Error getting the previous team id. ('.mysqli_error($link).'"}');

	if (mysqli_num_rows($result) == 0) {
		return -1;
	}
	// extract our results
	$row = mysqli_fetch_array($result);
	return intval($row['team_id']);
}

// Update the current team that we are bidding on
function db_update_current_team($team_id) {
	global $link, $sql_table_team; 

	$sql = "INSERT INTO $sql_table_team (team_id) VALUES ('$team_id')";
	mysqli_query($link, $sql) or die('db_update_current_team failed: ' . mysqli_error($link));
}

// Return used teams
function db_get_bid_teams() {
	global $link, $sql_table_team; 

	$sql = "SELECT DISTINCT(team_id),timestamp from $sql_table_team WHERE team_id != '-1' ORDER BY timestamp DESC";
	$result = mysqli_query($link, $sql) or die('db_get_bid_teams failed: ' . mysqli_error($link));

	// Add of the team ids to an array and return it
	$rtn_array = Array();
	while ($row = mysqli_fetch_array($result)) {
		array_push($rtn_array, intval($row['team_id']));
	}

	return $rtn_array;
}

// Insert a bid into the table
function db_update_bid($team_id, $name, $amount) {
	global $link, $sql_table_bid;

	$sql = "INSERT INTO $sql_table_bid (team_id, name, amount)
	VALUES ('$team_id', '$name', '$amount')";
	mysqli_query($link, $sql) or die('db_update_bid failed: ' . mysqli_error($link));
}

// Clear previous bid
function db_clear_previous_bid() {
	global $link, $sql_table_bid;
	
	$sql = "DELETE FROM $sql_table_bid ORDER BY timestamp DESC LIMIT 1";
	mysqli_query($link, $sql) or die('db_clear_previous_bid failed: ' . mysqli_error($link));
}

// Clear current team from database
function db_clear_current_team() {
	global $link, $sql_table_team;
	
	$sql = "DELETE FROM $sql_table_team ORDER BY timestamp DESC LIMIT 1";
	mysqli_query($link, $sql) or die('db_clear_current_team failed: ' . mysqli_error($link));
}

// Clear all teams from database
function db_clear_all_teams() {
	global $link, $sql_table_team;
	
	$sql = "TRUNCATE TABLE $sql_table_team";
	mysqli_query($link, $sql) or die('db_clear_all_teams failed: ' . mysqli_error($link));
}

// Clear all bids from database
function db_clear_all_bids() {
	global $link, $sql_table_bid;
	
	$sql = "TRUNCATE TABLE $sql_table_bid";
	mysqli_query($link, $sql) or die('db_clear_all_bids failed: ' . mysqli_error($link));
}

// Update our sync_file
function update_sync() {
	global $sync_file;
	$current = file_get_contents($sync_file);
	$cur_val = intval($current);
	$f = fopen($sync_file, "w+");
	fwrite($f, $cur_val+1);
	fclose($f);
}

// Get the current synchronization file from our file.
function get_sync_value() {
	global $sync_file;
	$current = file_get_contents($sync_file);
	$cur_val = intval($current);
	return $cur_val;
}


// Check to see if this user is in our access list
function check_access($email) {
	global $userInfo;

	if (array_key_exists($email, $userInfo)) {
		return $userInfo[$email];
	} else {
		return false;
	}
}

?>
