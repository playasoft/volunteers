var $ = require('wetfish-basic');
var ajaxOptions =
{
    method: 'post',
    credentials: 'include',
    headers:
    {
        'Content-Type': 'application/json'
    }
};

$(document).ready(function()
{
    $('.upload-status').each(function()
    {
        $(this).value($(this).data('status'));
        console.log($(this).value());
    });

    // Display save / cancel buttons when changing user roles
    $('.user-roles input').on('change', function()
    {
        $('.buttons').style({visibility: 'visible', opacity: 1});
    });

    // Display save / cancel buttons when changing upload status
    $('.upload-status').on('change', function()
    {
        $(this).parents('.upload').find('.buttons').style({visibility: 'visible', opacity: 1});
    });

    $('.save-roles').on('click', function()
    {
        var user = $('.user-id').value();
        var role = $('.user-role').value();
        var csrf = $('.csrf-token').value();
        var data =
        {
            roles: [],
            _token: csrf
        };

        $('.user-roles input').each(function()
        {
            if($(this).prop('checked'))
            {
                data.roles.push($(this).value());
            }
        });

        ajaxOptions.body = JSON.stringify(data);
        fetch('/user/' + user + '/edit', ajaxOptions);

        $('.user-role').data('role', role);
        $('.buttons').attr('style', false);
    });

    $('.cancel-roles').on('click', function()
    {
        $('.user-role').value($('.user-role').data('role'));
        $('.buttons').attr('style', false);
    });

    $('.save-upload').on('click', function()
    {
        var upload = $(this).parents('.upload').data('id');
        var status = $(this).parents('.upload').find('.upload-status').value();
        var csrf = $('.csrf-token').value();
        var slot = $('.slot-number').value(); //store var here
        var data =
        {
            status: status,
            _token: csrf
        };

        ajaxOptions.body = JSON.stringify(data);
        
        console.log(upload,status);

        // if there is nothing to upload, then check for volunteers who flaked
        if (!upload) {
            console.log('slot number: '+ slot);
            console.log(fetch('/slot/'+ slot +'/edit',ajaxOptions)); 

            fetch('/slot/'+ slot +'/edit',ajaxOptions);

            console.log(fetch('/slot/'+ slot +'/edit',ajaxOptions)); 
            $(this).parents('.upload').find('.upload-status').data('status', status);
            $(this).parents('.upload').find('.buttons').attr('style', false);
            return;
        }

        console.log(fetch('/upload/' + upload + '/edit', ajaxOptions));
        fetch('/upload/' + upload + '/edit', ajaxOptions);
        console.log(fetch('/upload/' + upload + '/edit', ajaxOptions));
        $(this).parents('.upload').find('.upload-status').data('status', status);
        $(this).parents('.upload').find('.buttons').attr('style', false);
    });

    $('.cancel-upload').on('click', function()
    {
        var status = $(this).parents('.upload').find('.upload-status').data('status');
        $(this).parents('.upload').find('.upload-status').value(status);
        $(this).parents('.upload').find('.buttons').attr('style', false);
    });
});
