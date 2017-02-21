var $ = require('wetfish-basic');

function timeToSeconds(time)
{
    var seconds = 0;
    time = time.split(':');

    seconds += time[0] * 60 * 60; // Hours
    seconds += time[1] * 60; // Minutes
    seconds += time[2]; // Seconds

    return seconds;
}

$(window).on('resize', function()
{
    // If we're using a desktop resolution
    if($('.desktop').style('display') != "none")
    {
        // Set the height of the shift based on the number of rows
        $('.shift.row').each(function()
        {
            var rows = parseInt($(this).data('rows'));
            var height = (2 * rows) + 'em';

            $(this).find('.title').style({'height': height});
        });

        // Set the position and size of the slots
        $('.slot-wrap').each(function()
        {
            var day = timeToSeconds('24:00:00');
            var start = timeToSeconds($(this).data('start')) * 1.009; // Magic number to make the slots align better with the bootstrap grid >_>
            var duration = timeToSeconds($(this).data('duration'));

            var startPercent = start / day * 100;
            var widthPercent = duration / day * 100;

            $(this).style({position: 'absolute', left: startPercent + '%', width: widthPercent + '%'});
        });

        // Set the height of the grid backgrounds
        $('.shift-wrap').each(function()
        {
            var height = $(this).find('.department-wrap').height();
            $(this).find('.timegrid .background').style({'height': height + 'px'});
        });
    }
    else
    {
        $('.slot-wrap').attr('style', false);
    }
});

$(document).ready(function()
{
    $(window).trigger('resize');

    $('.shift-wrap').each(function()
    {
        new Highlight(this);
    });
});

// Class to highlight the current time based on your mouse position
var Highlight = function(wrap)
{
    this.wrap = wrap;
    this.bind();
}

// Bind events
Highlight.prototype.bind = function()
{
    // Save the current scope
    var current = this;
    
    $(this.wrap).on('mouseenter', function(event)
    {
        // Save the position of the time grid
        current.position = $(current.wrap).find('.times').position();
        current.margin = parseInt($(current.wrap).find('.times').style('padding-left')) + 2;
        current.width = $(current.wrap).find('.time').eq(0).width();

        // Save the current mouse position of the user
        current.cursor = event.clientX;

        current.clear();
        current.check();
    });

    $(this.wrap).on('mousemove', function(event)
    {
        current.cursor = event.clientX;

        current.clear();
        current.check();
    });

    $(this.wrap).on('mouseleave', function(event)
    {
        current.clear();
    });
}

// Clear any previously active times
Highlight.prototype.clear = function()
{
    $(this.wrap).find('.time').removeClass('active');
}

// Figure out where the current position is based on the size of the time grid
Highlight.prototype.check = function()
{
    // Offset the cursor position based on the grid position
    var cursor = this.cursor - this.position.left - this.margin;

    // The index of the time element
    var index = Math.floor(cursor / this.width);

    $(this.wrap).find('.times .time').eq(index).addClass('active');
    $(this.wrap).find('.background .time').eq(index).addClass('active');
}
