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
function updatePage(data) {
    json = JSON.parse(data);

    if (data == lastData) {
        return;
    }
    lastData = data;
    $('#teamimage').attr("src",json['teamimage']);
    $('#teamname').html(json['teamname']);
    $('#teamregion').html(json['teamregion']);
    $('#teamseed').html(json['teamseed']);
    $('#teamopponent').html(json['teamopponent']);
    $('#teamopponentseed').html(json['teamopponentseed']);
    $('#bidamount').html(json['bidamount']);
    $('#highestbidder').html(json['highestbidder']);

    $('#main_team').css('background-color',json['teamcolor']);

    $('#previousteamimage').attr("src",json['previousteamimage']);
    $('#previousteam').html(json['previousteam']);
    $('#previousbidamount').html(json['previousbidamount']);
    $('#previoushighestbidder').html(json['previoushighestbidder']);

    $('#previous_team').css('background-color',json['previousteamcolor']);

    $("#content_bid").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);

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
    ajax_update({});
    setInterval(function () { ajax_update({}); }, 1000);
});