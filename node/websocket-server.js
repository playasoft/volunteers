var fs = require('fs');
var config = require('./config');
var app;

function handler(req, res)
{
    res.writeHead(200);
    res.end('');
}

if(config.ssl.enabled)
{
    var ssl =
    {
        key: fs.readFileSync(config.ssl.key),
        cert: fs.readFileSync(config.ssl.cert)
    };
    
    app = require('https').createServer(ssl, handler);
}
else
{
    app = require('http').createServer(handler);
}

var io = require('socket.io')(app);
var Redis = require('ioredis');
var redis = new Redis();

app.listen(6001, function()
{
    console.log('Server is running!');
});

io.on('connection', function(socket)
{
    console.log('User connected!');
});

redis.psubscribe('*', function(err, count)
{
    //
});

redis.on('pmessage', function(subscribed, channel, message)
{
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});
