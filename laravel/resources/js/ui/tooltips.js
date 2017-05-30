var $ = require('wetfish-basic');

$(document).ready(function()
{
    $('.description').on('click', function()
    {
        if($(this).find('p').hasClass('hidden'))
        {
            $('.description p').addClass('hidden');
            $(this).find('p').removeClass('hidden');
        }
        else
        {
            $(this).find('p').addClass('hidden');
        }
    });
});
