var $ = require('wetfish-basic');

$(document).ready(function()
{
    $('.shift-type').on('change', function()
    {
        if($(this).value() == 'all')
        {
            $('.shift.start-date, .shift.end-date').addClass('hidden');
        }
        else if($(this).value() == 'some')
        {
            $('.shift.start-date, .shift.end-date').removeClass('hidden');
        }
        else if($(this).value() == 'one')
        {
            $('.shift.start-date').removeClass('hidden');
            $('.shift.end-date').addClass('hidden');
        }
    });
});
