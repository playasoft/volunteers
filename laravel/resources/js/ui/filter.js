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

        localStorage.setItem('filter-date', date);
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

        localStorage.setItem('filter-department', department);
    });

    // Fetch values from localstorage
    var filterDate = localStorage.getItem('filter-date');
    var filterDepartment = localStorage.getItem('filter-department');

    if(filterDate)
    {
        $('.filter-days').value(filterDate).trigger('change');
    }

    if(filterDepartment)
    {
        $('.filter-departments').value(filterDepartment).trigger('change');
    }
});
