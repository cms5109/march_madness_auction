<?php
require('php/header.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>March Madness Auction</title>
	<script src="js/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/madness.css">
</head>
<body>
<audio id="sound_buzzer" src="sounds/buzzer_x.wav" preload="auto"></audio>
<audio id="sound_cash" src="sounds/cash_register_x.wav" preload="auto"></audio>
<div class="section_header">
	Up for auction:
</div>
<div class="content" id="main_team" style="background-color:#fff">
	<div id="teamimage_div">
		<img src="teamImages/filler.jpg" id="teamimage"/>
	</div>
	<div id="teamname_div">
		<div> 
			<span id="teamseed"></span>
			<span id="teamname"></span>
		</div>
		<div>
			<span id="teamregion"></span>
		</div>
		<div>
			vs.
			<span id="teamopponentseed"></span>
			<span id="teamopponent"></span>
		</div>
	</div>

</div>
<div id="content_bid">
	<div style="float: left;width:49%">
		Current Bid: <span id="bidamount"></span>
	</div>
	<div style="float:right;width:49%">
		Highest Bidder: <span id="highestbidder"></span>
	</div>
	<form name="bidform" id="bidform" action="php/bid.php" method="POST">
	    <div>
	    	<span style="">You are bidding as </span>
		    <span style="font-weight:bold"><?php echo $_SESSION['user_name']; ?></span>
		</div>
		<div>
		    <span style="font-size:18pt;">$ 
		    </span>
	    	<input type="text" name="amount" value ="" size="3" maxlength="3" /> <br/>
	    </div>
	    <input type="submit" value="Place your bid" id="bidbutton"/>
	</form>
</div>
<div class="section_header" style="margin-top:2%">
	Previous result:
</div>

<div class="content" id="previous_team">
	<div style="width:30%;float:left;">
		<img id="previousteamimage" src="http://www.cbsaltitudegroup.com/wp-content/uploads/2014/03/march_madness.jpg" alt="noimage"/>
	</div>
	<div style="width:70%;float:left;margin-top:3%">
		<span id="previousteam"></span>
		<br/>
		Winning Bid: 
		<span id="previoushighestbidder"></span> 
		for 
		<span id="previousbidamount"></span>
	</div>
	<div style="clear:both;padding:0;margin:0;"></div>
</div>

<script src="js/scripts.js"></script>


<div id="footer">
<?php
if (isset($_SESSION['ADMIN']) && $_SESSION['ADMIN'] == true) {
	echo "<button style='margin:1%;font-size:18pt;font-weight:bold;' onclick='nextTeam();'>Next Team</button><BR>";
}
?>
	Created by <a href="https://github.com/cspensky/march_madness_auction">Chad</a><BR>
</div>
</body>
</html>
