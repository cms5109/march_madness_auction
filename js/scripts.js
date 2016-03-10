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
            alert(json["msg"]);
            //data: return data from server
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            alert("ERROR: Your bid could not be placed.  Please try again.");
            //if fails      
        }
    });
    e.preventDefault(); //STOP default action
    //e.unbind(); //unbind. to stop multiple form submit.
});

// Make an AJAX call to advance to next auction team
function nextTeam() {
    $.get('php/next_team.php', {}, function(data){
            alert(data);
        }, 'html');
}

// Make an AJAX call to return to previous auction team
function previousTeam() {
    $.get('php/previous_team.php', {}, function(data){
            alert(data);
        }, 'html');
}

// Make an AJAX call to return to restart draft
function restartDraft() {
    $.get('php/restart_draft.php', {}, function(data){
            alert(data);
        }, 'html');
}

// Make an AJAX call to return to clear last bid
function clearLastBid() {
    $.get('php/clear_last_bid.php', {}, function(data){
            alert(data);
        }, 'html');
}

var lastData = "";
var sync_value = -1;
var current_team = "";
var deadline = new Date(2016, 2, 14, 20, 30);
// Update the page with the current state of the auction
function updatePage(data) {
   
	// Don't do unecessary work
    if (data == lastData || data == "") {
		if (initializeTimer('clockdiv', deadline)) {
			nextTeam();
		}
        return;
    } 

    lastData = data;

    json = JSON.parse(data);  	
	var t = json['bidtime'].split(/[- :$]/);
	
	// Update page graphics (teams)
    if (current_team != json['teamname']) {
        $("#main_team").fadeOut(0).fadeIn(1000);
        $("#content_bid").fadeOut(0).fadeIn(1000);
        $("#previous_team").fadeOut(0);
        document.getElementById('sound_buzzer').play();
		deadline = new Date(Date.parse(new Date(t[1], t[2]-1, t[3], t[4], t[5], t[6])) + 60 * 1000);
		initializeTimer('clockdiv', deadline);
    } else {
        $("#content_bid").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        document.getElementById('sound_cash').play();
		deadline = new Date(Date.parse(new Date(t[1], t[2]-1, t[3], t[4], t[5], t[6])) + 15 * 1000);
		initializeTimer('clockdiv', deadline);
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
		var timeout = true;
		return timeout;
	}	
}

// Calculate time left
function getTimeRemaining(endtime) {
	var t = Date.parse(endtime) - Date.parse(new Date(getServerTime()));
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

// On load, do an initial update then set a timer to update every X seconds
$( document ).ready(function() {
    $("#previous_team").fadeOut(100); 
    ajax_update({});
    setInterval(function () { ajax_update({sync_value: sync_value}); }, 1000);
});