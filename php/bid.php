<?php
require('header.php');

// Sanitize input
if (!isset($_POST) || 
	!array_key_exists('amount', $_POST)) {
	echo '{"status": 0, "msg":"Invalid form submission.  Amount is required."}';
	exit;
}

//
//	Start our bid code
//

// Open and lock or SQL connection
db_connect();
db_lock();

// Get our data to write to the table
$name = $_SESSION['user_name'];
$amount = @mysql_real_escape_string($_POST['amount']);
$team_id = db_get_current_team_id();

// Get the current bid
$current_bid = db_get_current_bid($team_id);

// Make sure this is a valid bid
if ($current_bid['name'] == $name) {
	// Does this person already hold the highest bid?
	echo '{"status": 0, "msg":"You ('.$name.') already have the highest bid at $'.$current_bid['amount'].'.  Save your money."}';

} else if ($amount < $current_bid['amount']+$BID_INCREMENT) {

	// Did they bid enough to beat the maximum bid?
	echo '{"status": 0, "msg":"Sorry, '.$current_bid['name'].' currently has the high bid at $'.$current_bid['amount'].'."}';

} else {
	db_update_bid($team_id, $name, $amount);
	echo '{"status": 1, "msg":"Your bid has been placed for $'.$amount.'."}';

	update_sync();
}

db_close();

?>
