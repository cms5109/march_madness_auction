<?php
require('ajax/header.php');
?>
<!DOCTYPE html>
<html>
<body>
<head>
	<title>March Madness Auction</title>
	<script src="js/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/madness.css">
</head>

<div class="section_header">
	Up for auction:
</div>
<div class="content" id="main_team">
	<div id="teamimage_div">
		<img src="http://alphasigmaphi.org/Websites/alphasigmaphihq/images/School_Logos/unc-chapel-hill-logo.jpg" id="teamimage"/>
	</div>
	<div id="teamname_div">
		<div> 
			<span id="teamseed">#1</span>
			<span id="teamname">The University of North Carolina at Chapel Hill</span>
		</div>
		<div>
			<span id="teamregion">South West</span>
		</div>
		<div>
			vs.
			<span id="teamopponentseed">#2</span>
			<span id="teamopponent">Penn State University</span>
		</div>
	</div>

</div>
<div id="content_bid">
	<div style="float: left;width:49%">
		Current Bid: <span id="bidamount">
			200
		</span>
	</div>
	<div style="float:right;width:49%">
		Highest Bidder: <span id="highestbidder">
			Chad Spensky
		</span>
	</div>
	<form name="bidform" id="bidform" action="ajax/bid.php" method="POST">
	    <div>
	    	<span style="">You are bidding as </span>
		    <span style="font-weight:bold"><?php echo $_SESSION['user']; ?></span>
		</div>
		<div>
		    <span style="">Your Bid: 
		    </span>
	    	<input type="text" name="amount" value ="" size="4" maxlength="4" /> <br/>
	    </div>
	    <input type="submit" value="Place your bid"/>
	</form>
</div>
<div class="section_header" style="margin-top:2%">
	Previous result:
</div>

<div class="content" id="previous_team">
	<div style="width:30%;float:left;">
		<img id="previousteamimage" src="http://alphasigmaphi.org/Websites/alphasigmaphihq/images/School_Logos/unc-chapel-hill-logo.jpg"/>
	</div>
	<div style="width:70%;float:left;margin-top:3%">
		<span id="previousteam">Blah U</span>
		<br/>
		Winning Bid: 
		<span id="previoushighestbidder">Chad</span> 
		for 
		<span id="previousbidamount">$500</span>
	</div>
	<div style="clear:both;padding:0;margin:0;"></div>
</div>

<script src="js/scripts.js"></script>
</body>
</html>
