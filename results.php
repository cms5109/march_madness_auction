<?php
require('ajax/header.php');
require('ajax/team_array.php');

db_connect();

// Get the list of teams that we bid on.
$bid_teams = db_get_bid_teams();
?>
<!DOCTYPE html>
<html>
<head>
	<title>March Madness Auction</title>
	<script src="js/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/madness.css">
</head>
<body>
<div class="section_header">
	Auction Results:
</div>
<div class="content">
	<table id="results_table">
	<thead>
		<th>Seed</th>
		<th>Region</th>
		<th>Team Name</th>
		<th>Owner</th>
		<th>Amount Paid</th>
		<tr>
	</thead>
	<tbody>
<?php
$total_pot = 0;
foreach ($bid_teams as $team_id) {
	$bid_winner = db_get_current_bid($team_id);
	echo "<tr>";
	echo "<td>".$teamInfo['seed'][$team_id]."</td>";
	echo "<td>".$teamInfo['team'][$team_id]."</td>";
	echo "<td>".$teamInfo['region'][$team_id]."</td>";
	echo "<td>".$bid_winner['name']."</td>";
	echo "<td>$".$bid_winner['amount']."</td>";
	echo "</tr>";

	$total_pot += $bid_winner['amount'];
}
?>
	</tbody>
	</table>

	<div id="pot_amount">
		Total Pot Amount: <b>$<?php echo $total_pot; ?></b>
	</div>
</div>
</body>
</html>

<?php
db_close();
?>