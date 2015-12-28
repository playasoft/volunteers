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
    // Force data values on load (fixes firefox form behavior)
    $('.user-role').value($('.user-role').data('role'));
    $('.upload-status').each(function()
    {
        $(this).value($(this).data('status'));
    });

    // Display save / cancel buttons when changing user roles
    $('.user-role').on('change', function()
    {
        $('.buttons').style({visibility: 'visible', opacity: 1});
    });

    // Display save / cancel buttons when changing upload status
    $('.upload-status').on('change', function()
    {
        $(this).parents('.upload').find('.buttons').style({visibility: 'visible', opacity: 1});
    });

    $('.save-role').on('click', function()
    {
        var user = $('.user-id').value();
        var role = $('.user-role').value();
        var csrf = $('.csrf-token').value();
        var data =
        {
            role: role,
            _token: csrf
        };

        ajaxOptions.body = JSON.stringify(data);
        fetch('/user/' + user + '/edit', ajaxOptions);

        $('.user-role').data('role', role);
        $('.buttons').attr('style', false);
    });

    $('.cancel-role').on('click', function()
    {
        $('.user-role').value($('.user-role').data('role'));
        $('.buttons').attr('style', false);
    });

    $('.save-upload').on('click', function()
    {
        var upload = $(this).parents('.upload').data('id');
        var status = $(this).parents('.upload').find('.upload-status').value();
        var csrf = $('.csrf-token').value();
        var data =
        {
            status: status,
            _token: csrf
        };

        ajaxOptions.body = JSON.stringify(data);
        fetch('/upload/' + upload + '/edit', ajaxOptions);

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
