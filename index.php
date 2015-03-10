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
			<span id="teamname">The University of North Carolina at Chapel Hill</span>
		</div>
		<div>
			<span id="teamseed">#1</span>
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

	<div style="clear:both"></div>
	<form name="bidform" id="bidform" action="ajax/bid.php" method="POST">
	    <div>
	    	<span style="width:50%;text-align:right;">Your Name: 
		    </span>
		    <input type="text" name="name" value =""/> <br/>
		</div>
		<div>
		    <span style="width:50%;text-align:right;">Your Bid: 
		    </span>
	    	<input type="text" name="amount" value ="" /> <br/>
	    </div>
	    <input type="hidden" name="team_id" value="1"/>
	    <input type="submit" value="Place your bid"/>
	</form>
</div>
<div class="section_header">
	Previous result:
</div>
<div class="content">
	<img id="lastteamimage" src="http://alphasigmaphi.org/Websites/alphasigmaphihq/images/School_Logos/unc-chapel-hill-logo.jpg"/>
	<div style="margin-top:5%">
		<span id="lastteamname">Blah U</span>
		<br/>
		Winning bidder: 
		<span id="lastbidder">Chad</span> 
		for 
		<span id="lastbidamount">$500</span>
	</div>
	<div style="clear:both"></div>
</div>

<script src="js/scripts.js"></script>
</body>
</html>
