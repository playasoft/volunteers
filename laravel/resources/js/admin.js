var $ = require('wetfish-basic');

$(document).ready(function()
{
    // Force user role to the data value on load (fixes firefox form behavior)
    $('.user-role').value($('.user-role').data('role'));
    
    $('.user-role').on('change', function()
    {
        $('.buttons').style({visibility: 'visible', opacity: 1});
    });

    $('.save-role').on('click', function()
    {
        var role = $('.user-role').value();
        var csrf = $('.csrf-token').value();
        var data =
        {
            role: role,
            _token: csrf
        };

        var options =
        {
            method: 'post',
            credentials: 'include',
            headers:
            {
                'Content-Type': 'application/json'
            },

            body: JSON.stringify(data)
        }
        
        fetch(window.location, options);

        $('.user-role').value(role);
        $('.buttons').attr('style', false);
    });

    $('.cancel-role').on('click', function()
    {
        $('.user-role').value($('.user-role').data('role'));
        $('.buttons').attr('style', false);
    });
});
