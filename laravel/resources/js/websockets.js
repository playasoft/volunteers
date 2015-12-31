var config = require('./config');
var socket = require('socket.io-client')(config.websocket.server);
var $ = require('wetfish-basic');

$(document).ready(function()
{
    var event = $('.event').data('id');
    
    socket.on('connect', function()
    {
        console.log("Websocket connected!");
    });

    socket.on('event-'+event+':event-changed', function(data)
    {
        var reload = confirm('An administrator made changes to this event while you were viewing it.\nWould you like to refresh the page?');

        if(reload)
        {
            window.location.reload();
        }
    });

    socket.on('event-'+event+':slot-changed', function(data)
    {
        var slot = $('.slot[data-id="'+data.slot.id+'"]');

        if(data.change.status == 'taken')
        {
            slot.addClass('taken');
            slot.attr('href', '/slot/' + data.slot.id + '/release');
            slot.text(data.change.name);
        }
        else
        {
            slot.removeClass('taken');
            slot.attr('href', '/slot/' + data.slot.id + '/take');
            slot.text('');
        }
    });
});
