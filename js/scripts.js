var lastData = "";
var sync_value = -1;
var current_team = "";
var deadline = new Date(Date.parse("Mon, 13 Mar 2016 20:45:00 EDT"));

//callback handler for form submit
$("#bidform").submit(function(e)
{
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR) 
        {
            json = JSON.parse(data);
            showPopup(json["msg"]);
            //data: return data from server
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            showPopup("ERROR: Your bid could not be placed.  Please try again.");
            //if fails      
        }
    });
    e.preventDefault(); //STOP default action
    //e.unbind(); //unbind. to stop multiple form submit.
});

// Make an AJAX call to advance to next auction team
function nextTeam() {
    $.get('php/next_team.php', {}, function(data){
            //showPopup(data);
        }, 'html');
}

// Make an AJAX call to return to previous auction team
function previousTeam() {
    $.get('php/previous_team.php', {}, function(data){
            //showPopup(data);
        }, 'html');
    $.get('php/update.php', {}, function(data){
            updatePage(data);
        }, 'html');
}

// Make an AJAX call to return to restart draft
function restartDraft() {
   if (!confirm("Are you sure you want to do this?  This will erase "
               + "everyone's bids, and they cannot be recovered!'")) {
      return;
    }
    $.get('php/restart_draft.php', {}, function(data){
            showPopup(data); ajax_update({});
        }, 'html');
    $.get('php/update.php', {}, function(data){
            updatePage(data);
        }, 'html');
}

// Make an AJAX call to return to clear last bid
function clearLastBid() {
    $.get('php/clear_last_bid.php', {}, function(data){
            showPopup(data); ajax_update({});
        }, 'html');
    $.get('php/update.php', {}, function(data){
            updatePage(data);
        }, 'html');
}

// Update the page with the current state of the auction
function updatePage(data) {
   
	// Don't do unecessary work
    if (data == lastData || data == "") {

      // Did our timer timeout?  
		if (initializeTimer('clockdiv', deadline)) {
			nextTeam();
		}
      return;
    } 

    lastData = data;

    json = JSON.parse(data);  	
	var t = json['bidtime'].split(/[- :]/);
	
	// Update page graphics (teams)	
	if (json['bidamount'] == "$999") {	
		// Restart
		// Reset timer for restart
		deadline = new Date(Date.parse(new Date()) + 5 * 60 * 1000);
		initializeTimer('clockdiv', deadline);
	} else if (json['teamname'] == "") {
		// Pre-draft
		initializeTimer('clockdiv', deadline);
	} else if (current_team != json['teamname']) { 
		// New Team
		// Reset timer for new team
		deadline = new Date(new Date().getTime() + 60 * 1000);
		initializeTimer('clockdiv', deadline);
		
		// Refresh page components
        $("#main_team").fadeOut(0).fadeIn(1000);
        $("#content_bid").fadeOut(0).fadeIn(1000);
        $("#previous_team").fadeOut(0);
        document.getElementById('sound_buzzer').play();
    } else { 
		// New Bid
		// Reset timer for new bid
		deadline = new Date(new Date().getTime() + 24 * 1000);
		initializeTimer('clockdiv', deadline);
		
		// Refresh bid component
        $("#content_bid").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        document.getElementById('sound_cash').play();
    }
    current_team = json['teamname'];
	
    $('#teamimage').attr("src",json['teamimage']);
    $('#teamname').html(json['teamname']);
    $('#teamregion').html(json['teamregion']);
    $('#teamseed').html(json['teamseed']);
    $('#teamopponent').html(json['teamopponent']);
    $('#teamopponentseed').html(json['teamopponentseed']);
    $('#bidamount').html(json['bidamount']);
    $('#highestbidder').html(json['highestbidder']);

    $('#main_team').css('background-color',json['teamcolor']);

    if (json['previousteam'] != "") {
        $("#previous_team").fadeIn(1000);
    }
    $('#previousteamimage').attr("src",json['previousteamimage']);
    $('#previousteam').html(json['previousteam']);
    $('#previousbidamount').html(json['previousbidamount']);
    $('#previoushighestbidder').html(json['previoushighestbidder']);

    $('#previous_team').css('background-color',json['previousteamcolor']);

    sync_value = json['sync_value'];
}

// Do an AJAX update of the page
function ajax_update(params) {
    $.get('php/update.php', params, function(data){
            updatePage(data);
        }, 'html');
}

// Initialize Timer
function initializeTimer(id, endtime) {
	var clock = document.getElementById(id);
	var daysSpan = clock.querySelector('.days');
	var hoursSpan = clock.querySelector('.hours');
	var minutesSpan = clock.querySelector('.minutes');
	var secondsSpan = clock.querySelector('.seconds');

   var t = getTimeRemaining(endtime);
		
	daysSpan.innerHTML = ('0' + t.days).slice(-2);
	hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
	minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
	secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
		
	if (t.total <= 0) {
		daysSpan.innerHTML = ('0' + 0).slice(-2);
		hoursSpan.innerHTML = ('0' + 0).slice(-2);
		minutesSpan.innerHTML = ('0' + 0).slice(-2);
		secondsSpan.innerHTML = ('0' + 0).slice(-2);

		return true;
	}
	return false;
}

// Calculate time left
function getTimeRemaining(endtime) {

   // Get the difference of our two times
	var t = endtime.getTime() - new Date().getTime();

	var seconds = Math.floor((t / 1000) % 60);
	var minutes = Math.floor((t / 1000 / 60) % 60);
	var hours = Math.floor((t / 1000 / 60 / 60) % 24);
	var days  = Math.floor((t / 1000 / 60 / 60 / 24 ) % 365);
	return {
		'total'   : t,
		'days'    : days,
		'hours'   : hours,
		'minutes' : minutes,
		'seconds' : seconds
	};
}

// Get server time
var xmlHttp;
function getServerTime() {
	try { // For FF, Opera, Safari, Chrome
		xmlHttp = new XMLHttpRequest();
	}
	catch (err1) {
		try { // IE
			xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
		}
		catch (err2) {
			try {
				xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
			}
			catch (err3) {
				// AJAX not supported, use CPU time
				alert("AJAX not supported");
			}
		}
	}
	xmlHttp.open('HEAD', window.location.href.toString(), false);
	xmlHttp.setRequestHeader("Content-Type", "text/html");
	xmlHttp.send('');
	return xmlHttp.getResponseHeader("Date");
}

// Hide our CSS popup window
function hidePopup() {
   $("#popup").fadeOut(500);
}

// Display a CSS popup window
function showPopup(message) {
   $("#popup_text").text(message);
   $("#popup").fadeIn(500);

   setTimeout(function() { hidePopup() }, 5000);
}

// On load, do an initial update then set a timer to update every X seconds
$( document ).ready(function() {
    $("#previous_team").fadeOut(100); 
    ajax_update({});
    setInterval(function () { ajax_update({sync_value: sync_value}); }, 1000);
});
