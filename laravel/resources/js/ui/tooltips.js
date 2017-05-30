var $ = require('wetfish-basic');

$(document).ready(function()
{
    $('.description').on('click', function()
    {
        if($(this).find('.tip').hasClass('hidden'))
        {
            $('.tip').addClass('hidden');
            $(this).find('.tip').removeClass('hidden');
        }
        else
        {
            $(this).find('.tip').addClass('hidden');
        }
    });
});
