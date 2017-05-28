var $ = require('wetfish-basic');

$(document).ready(function()
{
    $('.description').on('click', function()
    {
        $(this).find('p').toggle('hidden');
    });
});
