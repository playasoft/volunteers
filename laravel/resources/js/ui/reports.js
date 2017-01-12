var $ = require('wetfish-basic');

$(document).ready(function()
{
    // Show report options when the report type is changed 
    $('.report-type').on('change', function()
    {
        var type = $(this).value();
        $('.report-options').addClass('hidden');
        $('.report-options[data-type="'+type+'"]').removeClass('hidden');
    });

    $('.user-options').on('change', function()
    {
        if($(this).value() == 'specific')
        {
            $('.user-search').removeClass('hidden');
        }
        else
        {
            $('.user-search').addClass('hidden');
        }
    });

    $('.department-options').on('change', function()
    {
        if($(this).value() == 'specific')
        {
            $('.departments-wrap .loading').removeClass('hidden');
        }
        else
        {
            $('.departments-wrap .loading').addClass('hidden');
        }
    });

    $('.day-options').on('change', function()
    {
        if($(this).value() == 'specific')
        {
            $('.days-wrap .loading').removeClass('hidden');
        }
        else
        {
            $('.days-wrap .loading').addClass('hidden');
        }
    });

    // Make sure all dropdowns are empty on page load
    $('.report-generator select').each(function()
    {
        $(this).find('option').el[0].selected = true;
        $(this).trigger('change');
    });
});
