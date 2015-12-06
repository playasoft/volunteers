var $ = require('wetfish-basic');

$(window).on('resize', function()
{
    // If we're using a desktop resolution
    if($('.desktop').style('display') != "none")
    {
        
    }
});

$(document).ready(function()
{
    $(window).trigger('resize');
});
