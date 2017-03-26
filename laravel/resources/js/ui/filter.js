const $ = require('wetfish-basic');

$(document).ready(function()
{
    $('.filter-days').on('change', function()
    {
        var date = $(this).value();

        if(date == "all")
        {
            // Show all the days
            $('.days .day').removeClass('hidden');
        }
        else
        {
            // Hide all the days
            $('.days .day').addClass('hidden');

            // Show this one
            $('.days .day[data-date="' + date + '"]').removeClass('hidden');
            $(window).trigger('resize');
        }
    });

    $('.filter-departments').on('change', function()
    {
        var department = $(this).value();

        if(department == "all")
        {
            // Show all the departments
            $('.department-wrap .department').removeClass('hidden');
        }
        else
        {
            // Hide all the departments
            $('.department-wrap .department').addClass('hidden');

            // Show this one
            $('.department-wrap .department[data-id="' + department + '"]').removeClass('hidden');
            $(window).trigger('resize');
        }
    });
});
