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
    e.unbind(); //unbind. to stop multiple form submit.
});
 

var lastData = "";
var sync_value = -1;
var current_team = "";
function updatePage(data) {

    // Don't do unecessary work
    if (data == lastData || data == "") {
        return;
    }
    lastData = data;

    json = JSON.parse(data);
    

    if (current_team != json['teamname']) {
        $("#main_team").fadeOut(0).fadeIn(1000);
        $("#content_bid").fadeOut(0).fadeIn(1000);
        $("#previous_team").fadeOut(0);
        document.getElementById('sound_buzzer').play();
    } else {
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

function ajax_update(params) {
    $.get('ajax/update.php', params, function(data){
            updatePage(data);
        }, 'html');
}

function update_forever() {
    while (1) {
        ajax_update({wait: 1});
    }
}

$( document ).ready(function() {
    $("#previous_team").fadeOut(100);
    ajax_update({});
    setInterval(function () { ajax_update({sync_value: sync_value}); }, 1000);
});