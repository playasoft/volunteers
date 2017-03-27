var $ = require('wetfish-basic');
const Highlight = require('./highlight');

function timeToSeconds(time)
{
    var seconds = 0;
    time = time.split(':');

    seconds += time[0] * 60 * 60; // Hours
    seconds += time[1] * 60; // Minutes
    seconds += time[2]; // Seconds

    return seconds;
}

$(window).on('resize', calculateSlotSizes);

$(document).ready(function()
{
    calculateSlotSizes();

    $('.shift-wrap').each(function()
    {
        new Highlight(this);
    });
});

function calculateSlotSizes()
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
            var row = parseInt($(this).data('row'));

            var startPercent = start / day * 100;
            var widthPercent = duration / day * 100;
            var offsetTop = 2 * (row - 1);

            $(this).style({position: 'absolute', left: startPercent + '%', top: offsetTop + 'em', width: widthPercent + '%'});
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
}

module.exports = {
    calculateSlotSizes
}
