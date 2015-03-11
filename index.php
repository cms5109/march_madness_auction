<?php
require('ajax/header.php');
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
	Up for auction:
</div>
<div class="content" id="main_team">
	<div id="teamimage_div">
		<img src="http://www.cbsaltitudegroup.com/wp-content/uploads/2014/03/march_madness.jpg" id="teamimage"/>
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
	<form name="bidform" id="bidform" action="ajax/bid.php" method="POST">
	    <div>
	    	<span style="">You are bidding as </span>
		    <span style="font-weight:bold"><?php echo $_SESSION['user']; ?></span>
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
</body>
</html>
