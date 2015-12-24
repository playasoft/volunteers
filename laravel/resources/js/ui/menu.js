var $ = require('wetfish-basic');

$(document).ready(function()
{
    $('.hamburger').on('click', function()
    {
        if($('.navbar-collapse').hasClass('open'))
        {
            $('.navbar-collapse').removeClass('open');
        }
        else
        {
            $('.navbar-collapse').addClass('open');
        }
    });
});
