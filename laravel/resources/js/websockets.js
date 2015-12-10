var socket = require('socket.io-client')('http://voldb.local:6001');

socket.on('connect', function()
{
    console.log("Connected!");
});

socket.on('event-1:slot-changed', function(data)
{
    console.log("Slot changed!", data);
});
