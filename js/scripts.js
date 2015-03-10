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
 

function updatePage(data) {
    json = JSON.parse(data);

    $('#teamimage').attr("src",json['teamimage']);
    $('#teamname').html(json['teamname']);
    $('#teamregion').html(json['teamregion']);
    $('#teamseed').html(json['teamseed']);
    $('#teamopponent').html(json['teamopponent']);
    $('#bidamount').html(json['bidamount']);
    $('#highestbidder').html(json['highestbidder']);

    $('#main_team').css('background-color',json['teamcolor']);
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